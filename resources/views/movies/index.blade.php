@extends('layouts.app')

@section('title', 'Filmek')

@section('content')
<style>
    .page-header {
        margin-bottom: 3rem;
    }
    .page-header h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .movies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    .movie-card {
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s, border-color 0.3s;
    }
    .movie-card:hover {
        transform: translateY(-8px);
        border-color: #3b82f6;
    }
    .movie-poster {
        width: 100%;
        height: 400px;
        object-fit: cover;
        background: #0f0f0f;
    }
    .movie-content {
        padding: 1.5rem;
    }
    .movie-title {
        font-size: 1.3rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: #f3f4f6;
    }
    .movie-meta {
        color: #9ca3af;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    .movie-meta span {
        margin-right: 1rem;
    }
    .movie-description {
        color: #d1d5db;
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .screenings-count {
        color: #3b82f6;
        font-weight: bold;
        margin-bottom: 1rem;
    }
</style>

<div class="page-header">
    <h1>🎬 Filmek</h1>
    <p style="color: #9ca3af;">Válassz a moziban jelenleg játszott filmek közül</p>
</div>

@if($movies->isEmpty())
    <div style="text-align: center; padding: 4rem 0;">
        <p style="font-size: 1.5rem; color: #6b7280;">Jelenleg nincs elérhető film.</p>
    </div>
@else
    <div class="movies-grid">
        @foreach($movies as $movie)
            <div class="movie-card">
                @if($movie->poster_url)
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="movie-poster">
                @else
                    <div class="movie-poster" style="display: flex; align-items: center; justify-content: center; font-size: 4rem;">
                        🎬
                    </div>
                @endif
                
                <div class="movie-content">
                    <h2 class="movie-title">{{ $movie->title }}</h2>
                    
                    <div class="movie-meta">
                        @if($movie->genre)
                            <span>🎭 {{ $movie->genre }}</span>
                        @endif
                        <span>⏱️ {{ $movie->duration }} perc</span>
                        <span>🔞 {{ $movie->age_rating }}+</span>
                    </div>

                    @if($movie->director)
                        <div class="movie-meta">
                            <span>🎬 {{ $movie->director }}</span>
                        </div>
                    @endif

                    @if($movie->description)
                        <p class="movie-description">{{ $movie->description }}</p>
                    @endif

                    @if($movie->screenings_count > 0)
                        <p class="screenings-count">
                            📅 {{ $movie->screenings_count }} vetítés elérhető
                        </p>
                    @endif

                    <a href="{{ route('movies.show', $movie) }}" class="btn" style="width: 100%; text-align: center; display: block;">
                        Részletek és jegyvásárlás
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 2rem;">
        {{ $movies->links() }}
    </div>
@endif
@endsection