@extends('layouts.app')


@section('title', 'Foglalás részletei')

@section('content')
<style>
    .success-message {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        padding: 2rem;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 2rem;
    }
    .success-message h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .booking-details {
        background: #1a1a1a;
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #2a2a2a;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .tickets-list {
        background: #1a1a1a;
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .ticket-item {
        background: #0f0f0f;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .qr-codes {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }
    .qr-card {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
    }

    .qr-card svg {
        display: inline-block;
        margin: 0 auto;
        width: 200px !important;
        height: 200px !important;
    }

    .qr-card p {
        color: #1a1a1a;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }
</style>

<div class="success-message">
    <h1>✅ Sikeres Foglalás!</h1>
    <p style="font-size: 1.2rem;">Foglalási kód: <strong>{{ $booking->booking_code }}</strong></p>
</div>

<div class="booking-details">
    <h2 style="margin-bottom: 1.5rem; color: #3b82f6;">📋 Foglalás részletei</h2>
    
    <div class="detail-row">
        <span style="color: #9ca3af;">Film:</span>
        <strong>{{ $booking->screening->movie->title }}</strong>
    </div>
    
    <div class="detail-row">
        <span style="color: #9ca3af;">Terem:</span>
        <strong>{{ $booking->screening->cinema->name }}</strong>
    </div>
    
    <div class="detail-row">
        <span style="color: #9ca3af;">Időpont:</span>
        <strong>{{ $booking->screening->start_time->format('Y.m.d H:i') }}</strong>
    </div>
    
    <div class="detail-row">
        <span style="color: #9ca3af;">Vásárló:</span>
        <strong>{{ $booking->getCustomerName() }}</strong>
    </div>
    
    <div class="detail-row">
        <span style="color: #9ca3af;">Email:</span>
        <strong>{{ $booking->getCustomerEmail() }}</strong>
    </div>
    
    <div class="detail-row">
        <span style="color: #9ca3af;">Jegyek száma:</span>
        <strong>{{ $booking->tickets->count() }} db</strong>
    </div>
    
    <div class="detail-row" style="font-size: 1.3rem;">
        <span style="color: #9ca3af;">Végösszeg:</span>
        <strong style="color: #f59e0b;">{{ number_format($booking->total_price, 0, ',', ' ') }} Ft</strong>
    </div>
</div>

<div class="tickets-list">
    <h2 style="margin-bottom: 1.5rem; color: #3b82f6;">🎫 Jegyek</h2>
    
    @foreach($booking->tickets as $ticket)
        <div class="ticket-item">
            <div>
                <strong>{{ $ticket->seat->seat_label }}</strong>
                <span style="color: #9ca3af; margin-left: 1rem;">{{ $ticket->seat->seatCategory->name }}</span>
            </div>
            <span style="color: #f59e0b;">{{ number_format($ticket->price, 0, ',', ' ') }} Ft</span>
        </div>
    @endforeach
</div>

<div class="qr-codes">
    @foreach($booking->tickets as $ticket)
        <div class="qr-card">
            {!! QrCode::size(200)->generate($ticket->qr_code) !!}

            <p><strong>{{ $ticket->seat->seat_label }}</strong></p>
            <p style="font-size: 0.8rem; color: #6b7280;">{{ $ticket->qr_code }}</p>
        </div>
    @endforeach
</div>

<div style="text-align: center; margin-top: 2rem;">
    <a href="{{ route('tickets.pdf', $booking) }}" class="btn" style="margin-right: 1rem;">
        📄 Jegyek letöltése PDF-ben
    </a>
    <a href="{{ route('home') }}" class="btn btn-secondary">
        🏠 Vissza a főoldalra
    </a>
</div>
@endsection