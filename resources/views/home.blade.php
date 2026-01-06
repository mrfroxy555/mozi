@extends('layouts.app')

@section('title', 'Főoldal')

@section('content')
<style>
    .hero {
        background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 100%);
        padding: 4rem 2rem;
        border-radius: 12px;
        margin-bottom: 3rem;
        text-align: center;
    }
    .hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    .section-title {
        font-size: 2rem;
        margin-bottom: 2rem;
        color: #f3f4f6;
    }
    .screening-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    .screening-card {
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 8px;
        padding: 1.5rem;
        transition: transform 0.3s, border-color 0.3s;
    }
    .screening-card:hover {
        transform: translateY(-4px);
        border-color: #3b82f6;
    }
    .screening-card h3 {
        color: #3b82f6;
        margin-bottom: 0.5rem;
    }
    .screening-info {
        color: #9ca3af;
        font-size: 0.9rem;
        margin: 0.5rem 0;
    }
    .screening-time {
        color: #f59e0b;
        font-weight: bold;
        font-size: 1.1rem;
    }
</style>

<div class="hero">
    <h1>🎬 Üdvözöl a GépészMozi!</h1>
    <p style="font-size: 1.2rem;">Válaszd ki a kedvenc filmed és foglald le a helyedet online</p>
</div>

<section>
    <h2 class="section-title">🎥 Aktuális Vetítések</h2>
    @if($currentScreenings->isEmpty())
        <p style="color: #9ca3af;">Jelenleg nincs folyamatban vetítés.</p>
    @else
        <div class="screening-grid">
            @foreach($currentScreenings as $screening)
                <div class="screening-card">
                    <h3>{{ $screening->movie->title }}</h3>
                    <p class="screening-info">📍 {{ $screening->cinema->name }}</p>
                    <p class="screening-time">⏰ {{ $screening->start_time->format('H:i') }}</p>
                    <p class="screening-info">💺 Szabad helyek: {{ $screening->availableSeatsCount() }}</p>
                    <a href="{{ route('bookings.create', $screening) }}" class="btn" style="margin-top: 1rem; display: block; text-align: center;">Jegyvásárlás</a>
                </div>
            @endforeach
        </div>
    @endif
</section>

<section>
    <h2 class="section-title">📅 Következő Vetítések</h2>
    @if($upcomingScreenings->isEmpty())
        <p style="color: #9ca3af;">Nincs közelgő vetítés.</p>
    @else
        <div class="screening-grid">
            @foreach($upcomingScreenings as $screening)
                <div class="screening-card">
                    <h3>{{ $screening->movie->title }}</h3>
                    <p class="screening-info">📍 {{ $screening->cinema->name }}</p>
                    <p class="screening-time">📅 {{ $screening->start_time->format('Y.m.d H:i') }}</p>
                    <p class="screening-info">⏱️ {{ $screening->movie->duration }} perc</p>
                    <a href="{{ route('movies.show', $screening->movie) }}" class="btn btn-secondary" style="margin-top: 1rem; display: block; text-align: center;">Részletek</a>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection