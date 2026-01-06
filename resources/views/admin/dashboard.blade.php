@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .dashboard-header {
        margin-bottom: 2rem;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    .stat-card {
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 8px;
        padding: 1.5rem;
        transition: transform 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        border-color: #3b82f6;
    }
    .stat-label {
        color: #9ca3af;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #3b82f6;
    }
    .stat-subvalue {
        color: #6b7280;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
    .chart-container {
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .chart-title {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: #f3f4f6;
    }
    canvas {
        max-height: 300px;
    }
    .table-container {
        background: #1a1a1a;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 2rem;
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
    .progress-bar {
        background: #2a2a2a;
        border-radius: 4px;
        height: 8px;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    .progress-fill {
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        height: 100%;
        transition: width 0.3s;
    }
</style>

<div class="dashboard-header">
    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">📊 Dashboard</h1>
    <p style="color: #9ca3af;">Mozi statisztikák és áttekintés</p>
</div>

<!-- Gyors statisztikák -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">💰 Összes bevétel</div>
        <div class="stat-value">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} Ft</div>
        <div class="stat-subvalue">
            Ma: {{ number_format($stats['today_revenue'], 0, ',', ' ') }} Ft
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">📅 Havi bevétel</div>
        <div class="stat-value">{{ number_format($stats['monthly_revenue'], 0, ',', ' ') }} Ft</div>
        <div class="stat-subvalue">
            Aktuális hónap
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">🎫 Eladott jegyek</div>
        <div class="stat-value">{{ number_format($stats['total_tickets']) }}</div>
        <div class="stat-subvalue">
            Ezen a hónapon: {{ number_format($stats['monthly_tickets']) }}
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">📋 Foglalások</div>
        <div class="stat-value">{{ number_format($stats['total_bookings']) }}</div>
        <div class="stat-subvalue">
            Ma: {{ $stats['today_bookings'] }} db
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">👥 Regisztrált felhasználók</div>
        <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">🎬 Aktív filmek</div>
        <div class="stat-value">{{ $stats['total_movies'] }}</div>
        <div class="stat-subvalue">
            Vetítések: {{ $stats['total_screenings'] }} db
        </div>
    </div>
</div>

<!-- Havi bevétel grafikon -->
<div class="chart-container">
    <h2 class="chart-title">📈 Havi bevétel (utolsó 12 hónap)</h2>
    <canvas id="monthlyRevenueChart"></canvas>
</div>

<!-- Napi bevétel grafikon -->
<div class="chart-container">
    <h2 class="chart-title">📊 Napi bevétel (utolsó 30 nap)</h2>
    <canvas id="dailyRevenueChart"></canvas>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <!-- Legnépszerűbb filmek -->
    <div class="table-container">
        <h2 class="chart-title" style="padding: 1.5rem 1.5rem 0;">🏆 Legnépszerűbb filmek</h2>
        <table>
            <thead>
                <tr>
                    <th>Film</th>
                    <th>Eladott jegyek</th>
                </tr>
            </thead>
            <tbody>
                @forelse($popularMovies as $movie)
                    <tr>
                        <td><strong>{{ $movie->title }}</strong></td>
                        <td>{{ $movie->tickets_sold }} db</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align: center; color: #6b7280;">Nincs adat</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Terem kihasználtság -->
    <div class="table-container">
        <h2 class="chart-title" style="padding: 1.5rem 1.5rem 0;">🎭 Terem kihasználtság (havi)</h2>
        <div style="padding: 1.5rem;">
            @foreach($cinemaOccupancy as $cinema)
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <strong>{{ $cinema->name }}</strong>
                        <span style="color: #3b82f6;">{{ $cinema->occupancy_rate }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $cinema->occupancy_rate }}%"></div>
                    </div>
                    <div style="color: #6b7280; font-size: 0.85rem; margin-top: 0.25rem;">
                        {{ $cinema->tickets_sold }} jegy / {{ $cinema->total_screenings }} vetítés
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Legutóbbi foglalások -->
<div class="table-container">
    <h2 class="chart-title" style="padding: 1.5rem;">📋 Legutóbbi foglalások</h2>
    <table>
        <thead>
            <tr>
                <th>Foglalási kód</th>
                <th>Film</th>
                <th>Vásárló</th>
                <th>Jegyek</th>
                <th>Összeg</th>
                <th>Dátum</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBookings as $booking)
                <tr>
                    <td><strong>{{ $booking->booking_code }}</strong></td>
                    <td>{{ $booking->screening->movie->title }}</td>
                    <td>{{ $booking->getCustomerName() }}</td>
                    <td>{{ $booking->tickets->count() }} db</td>
                    <td style="color: #f59e0b;">{{ number_format($booking->total_price, 0, ',', ' ') }} Ft</td>
                    <td>{{ $booking->created_at->format('Y.m.d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #6b7280;">Nincs foglalás</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding: 1rem; text-align: center;">
        <a href="{{ route('admin.bookings.index') }}" class="btn">Összes foglalás megtekintése</a>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Havi bevétel grafikon
    const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: @json(array_column($monthlyRevenue, 'label')),
            datasets: [{
                label: 'Bevétel (Ft)',
                data: @json(array_column($monthlyRevenue, 'revenue')),
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' Ft';
                        }
                    }
                }
            }
        }
    });

    // Napi bevétel grafikon
    const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: @json(array_column($dailyRevenue, 'label')),
            datasets: [{
                label: 'Bevétel (Ft)',
                data: @json(array_column($dailyRevenue, 'revenue')),
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                borderColor: 'rgba(139, 92, 246, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' Ft';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection