<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $currentScreenings = Screening::with(['movie', 'cinema'])
            ->where('is_visible', true)
            ->where('start_time', '<=', now()->addHours(3))
            ->where('end_time', '>=', now())
            ->orderBy('start_time')
            ->get();

        $upcomingScreenings = Screening::with(['movie', 'cinema'])
            ->where('is_visible', true)
            ->where('start_time', '>', now()->addHours(3))
            ->orderBy('start_time')
            ->limit(6)
            ->get();

        return view('home', compact('currentScreenings', 'upcomingScreenings'));
    }
}