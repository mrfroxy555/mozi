<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['screening.movie', 'screening.cinema', 'user', 'tickets']);

        // Szűrés státusz szerint
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Szűrés dátum szerint
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        // Keresés foglalási kód vagy email szerint
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('email', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statisztikák
        $stats = [
            'total' => Booking::count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'total_revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['screening.movie', 'screening.cinema', 'user', 'tickets.seat.seatCategory']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        if ($booking->status === 'cancelled') {
            return back()->withErrors(['error' => 'Ez a foglalás már törölve van.']);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Foglalás sikeresen törölve!');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Foglalás véglegesen törölve!');
    }
}