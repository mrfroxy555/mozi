@extends('layouts.app')

@section('title', 'Műsorrend')

@section('content')
<style>
    .page-header {
        margin-bottom: 3rem;
    }
    .screening-card {
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: border-color 0.3s;
    }
    .screening-card:hover {
        border-color: #3b82f6;
    }
    .screening-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .movie-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #f3f4f6;
    }
    .screening-time {
        font-size: 1.3rem;
        font-weight: bold;
        color: #3b82f6;
    }
    .screening-details {
        display: flex;
        gap: 2rem;
        color: #9ca3af;
        margin-bottom: 1rem;
    }
</style>

<div class="page-header">
    <h1>📅 Műsorrend</h1>
    <p style="color: #9ca3af;">Összes elérhető vetítés</p>
</div>

@if($screenings->isEmpty())
    <div style="text-align: center; padding: 4rem 0;">
        <p style="font-size: 1.5rem; color: #6b7280;">Jelenleg nincs elérhető vetítés.</p>
    </div>
@else
    @foreach($screenings->groupBy(fn($s) => $s->start_time->format('Y-m-d')) as $date => $dayScreenings)
        <h2 style="color: #f59e0b; margin: 2rem 0 1rem 0; font-size: 1.8rem;">
            {{ \Carbon\Carbon::parse($date)->isoFormat('YYYY. MMMM DD. (dddd)') }}
        </h2>

        @foreach($dayScreenings as $screening)
            <div class="screening-card">
                <div class="screening-header">
                    <div>
                        <div class="movie-title">{{ $screening->movie->title }}</div>
                        <div class="screening-details">
                            <span>📍 {{ $screening->cinema->name }}</span>
                            <span>⏱️ {{ $screening->movie->duration }} perc</span>
                            <span>🔞 {{ $screening->movie->age_rating }}+</span>
                        </div>
                        <div style="color: #9ca3af;">
                            💺 {{ $screening->availableSeatsCount() }} szabad hely / {{ $screening->cinema->capacity }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div class="screening-time">{{ $screening->start_time->format('H:i') }}</div>
                        <a href="{{ route('bookings.create', $screening) }}" class="btn" style="margin-top: 1rem; display: inline-block;">
                            Jegyvásárlás
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    <div style="margin-top: 2rem;">
        {{ $screenings->links() }}
    </div>
@endif
@endsection