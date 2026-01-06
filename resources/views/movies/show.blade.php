@extends('layouts.app')

@section('title', $movie->title)

@section('content')
<style>
    .movie-detail {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    .movie-poster-large {
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }
    .movie-poster-large img {
        width: 100%;
        height: auto;
    }
    .movie-info h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    .movie-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin: 1.5rem 0;
        padding: 1.5rem;
        background: #1a1a1a;
        border-radius: 8px;
    }
    .movie-meta-item {
        display: flex;
        flex-direction: column;
    }
    .movie-meta-label {
        color: #9ca3af;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }
    .movie-meta-value {
        font-size: 1.1rem;
        font-weight: bold;
        color: #f3f4f6;
    }
    .movie-description {
        line-height: 1.8;
        color: #d1d5db;
        margin: 2rem 0;
    }
    .screenings-section {
        margin-top: 3rem;
    }
    .screenings-section h2 {
        font-size: 2rem;
        margin-bottom: 2rem;
    }
    .screening-item {
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: border-color 0.3s;
    }
    .screening-item:hover {
        border-color: #3b82f6;
    }
    .screening-info {
        display: flex;
        gap: 2rem;
        align-items: center;
    }
    .screening-time {
        font-size: 1.5rem;
        font-weight: bold;
        color: #3b82f6;
    }
    .screening-cinema {
        color: #9ca3af;
    }
    @media (max-width: 768px) {
        .movie-detail {
            grid-template-columns: 1fr;
        }
    }
</style>

<div style="margin-bottom: 2rem;">
    <a href="{{ route('movies.index') }}" style="color: #3b82f6; text-decoration: none;">
        ← Vissza a filmekhez
    </a>
</div>

<div class="movie-detail">
    <div class="movie-poster-large">
        @if($movie->poster_url)
            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}">
        @else
            <div style="background: #1a1a1a; padding: 4rem; text-align: center; font-size: 6rem; border-radius: 8px;">
                🎬
            </div>
        @endif
    </div>

    <div class="movie-info">
        <h1>{{ $movie->title }}</h1>

        <div class="movie-meta">
            @if($movie->genre)
                <div class="movie-meta-item">
                    <span class="movie-meta-label">Műfaj</span>
                    <span class="movie-meta-value">🎭 {{ $movie->genre }}</span>
                </div>
            @endif

            <div class="movie-meta-item">
                <span class="movie-meta-label">Időtartam</span>
                <span class="movie-meta-value">⏱️ {{ $movie->duration }} perc</span>
            </div>

            <div class="movie-meta-item">
                <span class="movie-meta-label">Korhatár</span>
                <span class="movie-meta-value">🔞 {{ $movie->age_rating }}+</span>
            </div>

            @if($movie->director)
                <div class="movie-meta-item">
                    <span class="movie-meta-label">Rendező</span>
                    <span class="movie-meta-value">🎬 {{ $movie->director }}</span>
                </div>
            @endif
        </div>

        @if($movie->description)
            <div class="movie-description">
                <h3 style="margin-bottom: 1rem;">Történet</h3>
                <p>{{ $movie->description }}</p>
            </div>
        @endif

        @if($movie->trailer_url)
            <div style="margin-top: 2rem;">
                <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-secondary">
                    ▶️ Előzetes megtekintése
                </a>
            </div>
        @endif
    </div>
</div>

<div class="screenings-section">
    <h2>📅 Elérhető vetítések</h2>

    @if($screenings->isEmpty())
        <p style="color: #9ca3af; padding: 2rem; background: #1a1a1a; border-radius: 8px; text-align: center;">
            Jelenleg nincs elérhető vetítés ehhez a filmhez.
        </p>
    @else
        @foreach($screenings->groupBy(fn($s) => $s->start_time->format('Y-m-d')) as $date => $dayScreenings)
            <h3 style="color: #f59e0b; margin: 2rem 0 1rem 0;">
                {{ \Carbon\Carbon::parse($date)->isoFormat('YYYY. MMMM DD. (dddd)') }}
            </h3>

            @foreach($dayScreenings as $screening)
                <div class="screening-item">
                    <div class="screening-info">
                        <div class="screening-time">
                            {{ $screening->start_time->format('H:i') }}
                        </div>
                        <div>
                            <div style="font-size: 1.1rem; margin-bottom: 0.25rem;">
                                📍 {{ $screening->cinema->name }}
                            </div>
                            <div class="screening-cinema">
                                💺 {{ $screening->availableSeatsCount() }} szabad hely
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('bookings.create', $screening) }}" class="btn">
                        Jegyvásárlás
                    </a>
                </div>
            @endforeach
        @endforeach
    @endif
</div>
@endsection