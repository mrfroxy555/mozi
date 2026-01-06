@extends('layouts.app')

@section('title', 'Foglalások kezelése')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .stat-box {
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
    }
    .stat-box-label {
        color: #9ca3af;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .stat-box-value {
        font-size: 1.8rem;
        font-weight: bold;
        color: #3b82f6;
    }
    .filters {
        background: #1a1a1a;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    .filter-group label {
        margin-bottom: 0.5rem;
        color: #9ca3af;
        font-size: 0.9rem;
    }
    .filter-group input,
    .filter-group select {
        padding: 0.5rem;
        background: #0f0f0f;
        border: 1px solid #2a2a2a;
        border-radius: 4px;
        color: #e5e5e5;
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
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.875rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-confirmed {
        background: #10b981;
        color: #ffffff;
    }
    .badge-pending {
        background: #f59e0b;
        color: #ffffff;
    }
    .badge-cancelled {
        background: #ef4444;
        color: #ffffff;
    }
    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .btn-danger {
        background: #dc2626;
    }
    .btn-danger:hover {
        background: #b91c1c;
    }
</style>

<div class="page-header">
    <h1>📋 Foglalások kezelése</h1>
</div>

<!-- Statisztikák -->
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-box-label">Összes foglalás</div>
        <div class="stat-box-value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-label">Visszaigazolt</div>
        <div class="stat-box-value">{{ $stats['confirmed'] }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-label">Függőben</div>
        <div class="stat-box-value">{{ $stats['pending'] }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-label">Törölve</div>
        <div class="stat-box-value">{{ $stats['cancelled'] }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-label">Összes bevétel</div>
        <div class="stat-box-value" style="font-size: 1.4rem;">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} Ft</div>
    </div>
</div>

<!-- Szűrők -->
<form method="GET" class="filters">
    <div class="filter-group">
        <label>Keresés (kód/email)</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Foglalási kód vagy email">
    </div>
    
    <div class="filter-group">
        <label>Státusz</label>
        <select name="status">
            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Összes</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Visszaigazolt</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Függőben</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Törölve</option>
        </select>
    </div>
    
    <div class="filter-group">
        <label>Dátum</label>
        <input type="date" name="date" value="{{ request('date') }}">
    </div>
    
    <div class="filter-group" style="justify-content: flex-end;">
        <label>&nbsp;</label>
        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-small">Szűrés</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-small btn-secondary">Törlés</a>
        </div>
    </div>
</form>

<!-- Foglalások táblázat -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Foglalási kód</th>
                <th>Film / Vetítés</th>
                <th>Vásárló</th>
                <th>Jegyek</th>
                <th>Összeg</th>
                <th>Státusz</th>
                <th>Időpont</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td><strong>{{ $booking->booking_code }}</strong></td>
                    <td>
                        <div style="font-weight: 500;">{{ $booking->screening->movie->title }}</div>
                        <div style="color: #9ca3af; font-size: 0.85rem;">
                            {{ $booking->screening->cinema->name }} • 
                            {{ $booking->screening->start_time->format('Y.m.d H:i') }}
                        </div>
                    </td>
                    <td>
                        <div>{{ $booking->getCustomerName() }}</div>
                        <div style="color: #9ca3af; font-size: 0.85rem;">{{ $booking->getCustomerEmail() }}</div>
                    </td>
                    <td>{{ $booking->tickets->count() }} db</td>
                    <td style="color: #f59e0b; font-weight: 600;">{{ number_format($booking->total_price, 0, ',', ' ') }} Ft</td>
                    <td>
                        <span class="badge badge-{{ $booking->status }}">
                            @if($booking->status === 'confirmed') 
                                ✓ Visszaigazolt
                            @elseif($booking->status === 'pending') 
                                ⏳ Függőben
                            @else 
                                ✗ Törölve
                            @endif
                        </span>
                    </td>
                    <td>{{ $booking->created_at->format('Y.m.d H:i') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-small btn-secondary">Részletek</a>
                            
                            @if($booking->status !== 'cancelled')
                                <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Biztosan törölöd?')">
                                        Törlés
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #6b7280; padding: 2rem;">
                        Nincs megjeleníthető foglalás
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem;">
    {{ $bookings->withQueryString()->links() }}
</div>
@endsection