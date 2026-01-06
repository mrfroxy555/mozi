<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function index(): View
    {
        $movies = Movie::where('is_active', true)
            ->withCount(['screenings' => function($q) {
                $q->where('is_visible', true)
                  ->where('start_time', '>', now());
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('movies.index', compact('movies'));
    }

    public function show(Movie $movie): View
    {
        $screenings = $movie->activeScreenings()->with('cinema')->get();
        
        return view('movies.show', compact('movie', 'screenings'));
    }

    // Admin funkciók
    public function adminIndex(): View
    {
        $movies = Movie::withCount('screenings')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.movies.index', compact('movies'));
    }

    public function create(): View
    {
        return view('admin.movies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'genre' => 'nullable|string|max:100',
            'director' => 'nullable|string|max:255',
            'age_rating' => 'required|integer|min:0',
            'poster_url' => 'nullable|url',
            'trailer_url' => 'nullable|url',
        ]);

        Movie::create($validated);

        return redirect()->route('admin.movies.index')
            ->with('success', 'Film sikeresen létrehozva!');
    }

    public function edit(Movie $movie): View
    {
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'genre' => 'nullable|string|max:100',
            'director' => 'nullable|string|max:255',
            'age_rating' => 'required|integer|min:0',
            'poster_url' => 'nullable|url',
            'trailer_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $movie->update($validated);

        return redirect()->route('admin.movies.index')
            ->with('success', 'Film sikeresen frissítve!');
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        $movie->delete();

        return redirect()->route('admin.movies.index')
            ->with('success', 'Film sikeresen törölve!');
    }
}