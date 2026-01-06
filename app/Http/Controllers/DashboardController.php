<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Movie;
use App\Models\Screening;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Időszakok
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        // Alapstatisztikák
        $stats = [
            'total_revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
            'monthly_revenue' => Booking::where('status', 'confirmed')
                ->where('created_at', '>=', $thisMonth)
                ->sum('total_price'),
            'today_revenue' => Booking::where('status', 'confirmed')
                ->whereDate('created_at', $today)
                ->sum('total_price'),
            
            'total_bookings' => Booking::count(),
            'monthly_bookings' => Booking::where('created_at', '>=', $thisMonth)->count(),
            'today_bookings' => Booking::whereDate('created_at', $today)->count(),
            
            'total_tickets' => Ticket::count(),
            'monthly_tickets' => Ticket::whereHas('booking', function($q) use ($thisMonth) {
                $q->where('created_at', '>=', $thisMonth);
            })->count(),
            
            'total_users' => User::where('role_id', 1)->count(),
            'total_movies' => Movie::where('is_active', true)->count(),
            'total_screenings' => Screening::where('start_time', '>', now())->count(),
        ];

        // Havi bevétel grafikon (utolsó 12 hónap)
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Booking::where('status', 'confirmed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_price');
            
            $monthlyRevenue[] = [
                'month' => $date->format('Y-m'),
                'label' => $date->isoFormat('MMM'),
                'revenue' => $revenue,
            ];
        }

        // Napi bevétel (utolsó 30 nap)
        $dailyRevenue = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Booking::where('status', 'confirmed')
                ->whereDate('created_at', $date)
                ->sum('total_price');
            
            $dailyRevenue[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('m-d'),
                'revenue' => $revenue,
            ];
        }

        // Legnépszerűbb filmek (eladott jegyek alapján)
        $popularMovies = Movie::withCount(['screenings as tickets_sold' => function($q) {
            $q->join('tickets', 'screenings.id', '=', 'tickets.screening_id');
        }])
        ->orderBy('tickets_sold', 'desc')
        ->limit(10)
        ->get();

        // Terem kihasználtság
        $cinemaOccupancy = DB::table('cinemas')
            ->leftJoin('screenings', 'cinemas.id', '=', 'screenings.cinema_id')
            ->leftJoin('tickets', 'screenings.id', '=', 'tickets.screening_id')
            ->select(
                'cinemas.name',
                'cinemas.capacity',
                DB::raw('COUNT(DISTINCT screenings.id) as total_screenings'),
                DB::raw('COUNT(tickets.id) as tickets_sold')
            )
            ->where('screenings.start_time', '>=', $thisMonth)
            ->groupBy('cinemas.id', 'cinemas.name', 'cinemas.capacity')
            ->get()
            ->map(function($item) {
                $item->occupancy_rate = $item->total_screenings > 0 
                    ? round(($item->tickets_sold / ($item->capacity * $item->total_screenings)) * 100, 1)
                    : 0;
                return $item;
            });

        // Legutóbbi foglalások
        $recentBookings = Booking::with(['screening.movie', 'screening.cinema', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyRevenue',
            'dailyRevenue',
            'popularMovies',
            'cinemaOccupancy',
            'recentBookings'
        ));
    }
}