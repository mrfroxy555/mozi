@extends('layouts.app')

@section('title', 'Új vetítés hozzáadása')

@section('content')
<style>
    .form-container {
        background: #1a1a1a;
        border-radius: 8px;
        padding: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #e5e5e5;
        font-weight: 500;
    }
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        background: #0f0f0f;
        border: 1px solid #2a2a2a;
        border-radius: 6px;
        color: #e5e5e5;
        font-size: 1rem;
    }
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .checkbox-group input[type="checkbox"] {
        width: auto;
    }
</style>

<div class="form-container">
    <h1 style="margin-bottom: 2rem;">📅 Új vetítés hozzáadása</h1>

    <form method="POST" action="{{ route('admin.screenings.store') }}">
        @csrf

        <div class="form-group">
            <label for="movie_id">Film *</label>
            <select id="movie_id" name="movie_id" required>
                <option value="">Válassz filmet...</option>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}" {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                        {{ $movie->title }} ({{ $movie->duration }} perc)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="cinema_id">Terem *</label>
            <select id="cinema_id" name="cinema_id" required>
                <option value="">Válassz termet...</option>
                @foreach($cinemas as $cinema)
                    <option value="{{ $cinema->id }}" {{ old('cinema_id') == $cinema->id ? 'selected' : '' }}>
                        {{ $cinema->name }} ({{ $cinema->capacity }} fő)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="start_time">Kezdési időpont *</label>
            <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
            <small style="color: #9ca3af;">A rendszer automatikusan ellenőrzi az ütközéseket</small>
        </div>

        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" id="is_visible" name="is_visible" value="1" checked>
                <label for="is_visible" style="margin: 0;">Látható a műsorrendben</label>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Mentés</button>
            <a href="{{ route('admin.screenings.index') }}" class="btn btn-secondary">Mégse</a>
        </div>
    </form>
</div>
@endsection