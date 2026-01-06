<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Screening;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScreeningController extends Controller
{
    public function index(): View
    {
        $screenings = Screening::with(['movie', 'cinema'])
            ->where('is_visible', true)
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->paginate(20);

        return view('screenings.index', compact('screenings'));
    }

    // Admin funkciók
    public function adminIndex(): View
    {
        $screenings = Screening::with(['movie', 'cinema'])
            ->orderBy('start_time', 'desc')
            ->paginate(30);

        return view('admin.screenings.index', compact('screenings'));
    }

    public function create(): View
    {
        $movies = Movie::where('is_active', true)->get();
        $cinemas = Cinema::all();

        return view('admin.screenings.create', compact('movies', 'cinemas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'cinema_id' => 'required|exists:cinemas,id',
            'start_time' => 'required|date|after:now',
            'is_visible' => 'boolean',
        ]);

        $movie = Movie::find($validated['movie_id']);
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addMinutes($movie->duration + 15); // 15 perc takarítás

        // Ellenőrizzük, hogy a terem szabad-e
        $conflict = Screening::where('cinema_id', $validated['cinema_id'])
            ->where(function($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function($q2) use ($startTime, $endTime) {
                      $q2->where('start_time', '<=', $startTime)
                         ->where('end_time', '>=', $endTime);
                  });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'start_time' => 'A terem ebben az időpontban már foglalt!'
            ])->withInput();
        }

        Screening::create([
            'movie_id' => $validated['movie_id'],
            'cinema_id' => $validated['cinema_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_visible' => $validated['is_visible'] ?? true,
        ]);

        return redirect()->route('admin.screenings.index')
            ->with('success', 'Vetítés sikeresen létrehozva!');
    }

    public function edit(Screening $screening): View
    {
        $movies = Movie::where('is_active', true)->get();
        $cinemas = Cinema::all();

        return view('admin.screenings.edit', compact('screening', 'movies', 'cinemas'));
    }

    public function update(Request $request, Screening $screening): RedirectResponse
{
    $validated = $request->validate([
        'start_time' => 'required|date',
        'is_visible' => 'required|boolean',
    ]);

    $startTime = Carbon::parse($validated['start_time']);
    $movie = $screening->movie;
    $endTime = $startTime->copy()->addMinutes($movie->duration + 15);

    // Teremfoglaltság-ellenőrzés
    $conflict = Screening::where('cinema_id', $screening->cinema_id)
        ->where('id', '!=', $screening->id)
        ->where(function($q) use ($startTime, $endTime) {
            $q->whereBetween('start_time', [$startTime, $endTime])
              ->orWhereBetween('end_time', [$startTime, $endTime])
              ->orWhere(function($q2) use ($startTime, $endTime) {
                  $q2->where('start_time', '<=', $startTime)
                     ->where('end_time', '>=', $endTime);
              });
        })
        ->exists();

    if ($conflict) {
        return back()->withErrors([
            'start_time' => 'A terem ebben az időpontban már foglalt!'
        ])->withInput();
    }

    // Mentés
    $screening->update([
        'start_time' => $startTime,
        'end_time' => $endTime,
        'is_visible' => $validated['is_visible'],
    ]);

    return redirect()->route('admin.screenings.index')
        ->with('success', 'Vetítés frissítve!');
}


    public function destroy(Screening $screening): RedirectResponse
    {
        if ($screening->tickets()->exists()) {
            return back()->withErrors([
                'delete' => 'Nem törölhető! Már vannak foglalások erre a vetítésre.'
            ]);
        }

        $screening->delete();

        return redirect()->route('admin.screenings.index')
            ->with('success', 'Vetítés sikeresen törölve!');
    }
}