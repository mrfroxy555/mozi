@extends('layouts.app')

@section('title', 'Film szerkesztése')

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
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        background: #0f0f0f;
        border: 1px solid #2a2a2a;
        border-radius: 6px;
        color: #e5e5e5;
        font-size: 1rem;
    }
    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
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
    <h1 style="margin-bottom: 2rem;">🎬 Film szerkesztése</h1>

    <form method="POST" action="{{ route('admin.movies.update', $movie) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Cím *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $movie->title) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Leírás</label>
            <textarea id="description" name="description">{{ old('description', $movie->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="duration">Időtartam (perc) *</label>
            <input type="number" id="duration" name="duration" value="{{ old('duration', $movie->duration) }}" required>
        </div>

        <div class="form-group">
            <label for="genre">Műfaj</label>
            <input type="text" id="genre" name="genre" value="{{ old('genre', $movie->genre) }}" placeholder="pl. Akció, Vígjáték">
        </div>

        <div class="form-group">
            <label for="director">Rendező</label>
            <input type="text" id="director" name="director" value="{{ old('director', $movie->director) }}">
        </div>

        <div class="form-group">
            <label for="age_rating">Korhatár *</label>
            <select id="age_rating" name="age_rating" required>
                <option value="6" {{ old('age_rating', $movie->age_rating) == 6 ? 'selected' : '' }}>6+</option>
                <option value="12" {{ old('age_rating', $movie->age_rating) == 12 ? 'selected' : '' }}>12+</option>
                <option value="16" {{ old('age_rating', $movie->age_rating) == 16 ? 'selected' : '' }}>16+</option>
                <option value="18" {{ old('age_rating', $movie->age_rating) == 18 ? 'selected' : '' }}>18+</option>
            </select>
        </div>

        <div class="form-group">
            <label for="poster_url">Poszter URL</label>
            <input type="url" id="poster_url" name="poster_url" value="{{ old('poster_url', $movie->poster_url) }}">
        </div>

        <div class="form-group">
            <label for="trailer_url">Előzetes URL</label>
            <input type="url" id="trailer_url" name="trailer_url" value="{{ old('trailer_url', $movie->trailer_url) }}">
        </div>

        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $movie->is_active) ? 'checked' : '' }}>
                <label for="is_active" style="margin: 0;">Aktív</label>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Mentés</button>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Mégse</a>
        </div>
    </form>
</div>
@endsection