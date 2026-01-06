<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    /**
     * Felhasználó összes jegyének megjelenítése
     */
    public function myTickets(): View
    {
        $bookings = auth()->user()->bookings()
            ->with(['screening.movie', 'screening.cinema', 'tickets.seat.seatCategory'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tickets.my-tickets', compact('bookings'));
    }

    /**
     * PDF letöltése
     */
    public function downloadPdf(Booking $booking): Response
    {
        // Ellenőrizzük, hogy a felhasználó jogosult-e
        if (auth()->check() && $booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Nincs jogosultságod ehhez a foglaláshoz.');
        }

        $booking->load(['screening.movie', 'screening.cinema', 'tickets.seat.seatCategory']);

        $pdf = Pdf::loadView('tickets.pdf', compact('booking'));

        return $pdf->download('jegy-' . $booking->booking_code . '.pdf');
    }

    /**
     * QR kód generálása
     */
    public function generateQrCode(string $qrCode)
    {
        return response(QrCode::size(200)->format('png')->generate($qrCode))
            ->header('Content-Type', 'image/png');
    }
}