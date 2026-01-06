@extends('layouts.app')

@section('title', 'Filmek kezelése')

@section('content')
<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .table-container {
        background: #1a1a1a;
        border-radius: 8px;
        overflow: hidden;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #2a2a2a;
    }
    th {
        background: #0f0f0f;
        color: #3b82f6;
        font-weight: 600;
    }
    tr:hover {
        background: #0f0f0f;
    }
    .btn-small {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    .btn-danger {
        background: #dc2626;
    }
    .btn-danger:hover {
        background: #b91c1c;
    }
</style>

<div class="admin-header">
    <h1>🎬 Filmek kezelése</h1>
    <a href="{{ route('admin.movies.create') }}" class="btn">+ Új film hozzáadása</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Cím</th>
                <th>Rendező</th>
                <th>Időtartam</th>
                <th>Korhatár</th>
                <th>Vetítések</th>
                <th>Státusz</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movies as $movie)
                <tr>
                    <td><strong>{{ $movie->title }}</strong></td>
                    <td>{{ $movie->director ?? '-' }}</td>
                    <td>{{ $movie->duration }} perc</td>
                    <td>{{ $movie->age_rating }}+</td>
                    <td>{{ $movie->screenings_count }}</td>
                    <td>
                        @if($movie->is_active)
                            <span style="color: #10b981;">● Aktív</span>
                        @else
                            <span style="color: #6b7280;">● Inaktív</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.movies.edit', $movie) }}" class="btn btn-small btn-secondary">Szerkesztés</a>
                        <form method="POST" action="{{ route('admin.movies.destroy', $movie) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Biztosan törlöd?')">Törlés</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #9ca3af;">Még nincsenek filmek</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem;">
    {{ $movies->links() }}
</div>
@endsection