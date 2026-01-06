<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Screening;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(Screening $screening): View
    {
        $screening->load(['movie', 'cinema', 'cinema.seats.seatCategory']);

        $bookedSeatIds = $screening->tickets()->pluck('seat_id')->toArray();

        $seats = $screening->cinema->seats->map(function($seat) use ($bookedSeatIds) {
            return [
                'id' => $seat->id,
                'row' => $seat->row_number,
                'seat' => $seat->seat_number,
                'label' => $seat->seat_label,
                'category' => $seat->seatCategory->name,
                'price' => $seat->seatCategory->price,
                'color' => $seat->seatCategory->color,
                'isBooked' => in_array($seat->id, $bookedSeatIds),
            ];
        });

        return view('bookings.create', compact('screening', 'seats'));
    }

    public function store(Request $request, Screening $screening): RedirectResponse
{
    $validated = $request->validate([
        'seats' => 'required|array|min:1',
        'seats.*' => 'exists:seats,id',
        'guest_name' => 'nullable|string|max:255',
        'guest_email' => 'nullable|email|max:255',
    ]);

    // Ha nincs user_id, akkor kötelező a guest adatok
    if (!auth()->check() && (empty($validated['guest_name']) || empty($validated['guest_email']))) {
        return back()->withErrors([
            'guest_name' => 'A név megadása kötelező vendég foglaláshoz.',
            'guest_email' => 'Az email megadása kötelező vendég foglaláshoz.'
        ])->withInput();
    }

    // Ellenőrizzük, hogy a székek még szabadok-e
    $alreadyBooked = Ticket::where('screening_id', $screening->id)
        ->whereIn('seat_id', $validated['seats'])
        ->exists();

    if ($alreadyBooked) {
        return back()->withErrors([
            'seats' => 'Egy vagy több kiválasztott szék már foglalt!'
        ])->withInput();
    }

    DB::beginTransaction();

    try {
        // Árak kiszámolása
        $seats = Seat::with('seatCategory')->whereIn('id', $validated['seats'])->get();
        $totalPrice = $seats->sum(fn($seat) => $seat->seatCategory->price);

        // Foglalás létrehozása
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'screening_id' => $screening->id,
            'guest_name' => $validated['guest_name'] ?? null,
            'guest_email' => $validated['guest_email'] ?? null,
            'total_price' => $totalPrice,
            'status' => 'confirmed',
        ]);

        // Jegyek generálása
        foreach ($seats as $seat) {
            Ticket::create([
                'booking_id' => $booking->id,
                'seat_id' => $seat->id,
                'screening_id' => $screening->id,
                'price' => $seat->seatCategory->price,
            ]);
        }

        DB::commit();

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Foglalás sikeresen létrehozva!');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()->withErrors([
            'error' => 'Hiba történt a foglalás során: ' . $e->getMessage()
        ])->withInput();
    }
}

    public function show(Booking $booking): View
    {
        $booking->load(['screening.movie', 'screening.cinema', 'tickets.seat.seatCategory']);

        return view('bookings.show', compact('booking'));
    }

// Ezeket add hozzá a BookingController-hez:
public function adminIndex(Request $request): View
{
    $query = Booking::with(['user', 'screening.movie'])
        ->orderBy('created_at', 'desc');

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('booking_code', 'like', "%{$search}%")
              ->orWhereHas('user', function($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%");
              })
              ->orWhereHas('screening.movie', function($q2) use ($search) {
                  $q2->where('title', 'like', "%{$search}%");
              });
        });
    }

    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    if ($request->filled('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    $bookings = $query->paginate(20);

    $stats = [
        'total' => Booking::count(),
        'confirmed' => Booking::where('status', 'confirmed')->count(),
        'pending' => Booking::where('status', 'pending')->count(),
        'cancelled' => Booking::where('status', 'cancelled')->count(),
        'total_revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
    ];

    return view('admin.bookings.index', compact('bookings', 'stats'));
}


    public function adminShow(Booking $booking): View
    {
        $booking->load([
            'user',
            'screening.movie',
            'tickets.seat.seatCategory'
        ]);

        return view('admin.bookings.show', compact('booking'));
    }



    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();
        
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Foglalás sikeresen törölve!');
    }
}
