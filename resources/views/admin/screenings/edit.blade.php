@extends('layouts.app')

@section('title', 'Vetítés szerkesztése')

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
    .info-box {
        background: #0f0f0f;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        color: #9ca3af;
    }
</style>

<div class="form-container">
    <h1 style="margin-bottom: 2rem;">📅 Vetítés szerkesztése</h1>

    <div class="info-box">
        <strong>{{ $screening->movie->title }}</strong><br>
        {{ $screening->cinema->name }}<br>
        {{ $screening->start_time->format('Y. m. d. H:i') }}
    </div>

    <form method="POST" action="{{ route('admin.screenings.update', $screening) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="start_time">Vetítés kezdete *</label>
            <input type="datetime-local" id="start_time" name="start_time"
                value="{{ old('start_time', $screening->start_time->format('Y-m-d\TH:i')) }}"
                required>
        </div>


        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" id="is_visible" name="is_visible" value="1" {{ old('is_visible', $screening->is_visible) ? 'checked' : '' }}>
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