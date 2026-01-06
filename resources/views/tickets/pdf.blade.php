<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jegyek - {{ $booking->booking_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1f2937;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .booking-info {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .booking-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .booking-info td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .booking-info td:first-child {
            font-weight: bold;
            color: #6b7280;
            width: 150px;
        }
        .tickets-section {
            margin-top: 30px;
        }
        .ticket {
            page-break-inside: avoid;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #ffffff;
        }
        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .ticket-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .detail-value {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }
        .qr-section {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }
        .qr-code {
            max-width: 150px;
            height: auto;
            display: inline-block;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .price {
            color: #f59e0b;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎬 GépészMozi Jegyek</h1>
            <p style="color: #6b7280; font-size: 18px;">Foglalási kód: <strong>{{ $booking->booking_code }}</strong></p>
        </div>

        <div class="booking-info">
            <table>
                <tr>
                    <td>Film:</td>
                    <td>{{ $booking->screening->movie->title }}</td>
                </tr>
                <tr>
                    <td>Terem:</td>
                    <td>{{ $booking->screening->cinema->name }}</td>
                </tr>
                <tr>
                    <td>Időpont:</td>
                    <td>{{ $booking->screening->start_time->format('Y.m.d H:i') }}</td>
                </tr>
                <tr>
                    <td>Vásárló:</td>
                    <td>{{ $booking->getCustomerName() }}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{ $booking->getCustomerEmail() }}</td>
                </tr>
                <tr>
                    <td>Végösszeg:</td>
                    <td class="price">{{ number_format($booking->total_price, 0, ',', ' ') }} Ft</td>
                </tr>
            </table>
        </div>

        <div class="tickets-section">
            <h2 style="margin-bottom: 20px; color: #1f2937;">Jegyek ({{ $booking->tickets->count() }} db)</h2>
            
            @foreach($booking->tickets as $ticket)
                <div class="ticket">
                    <div class="ticket-header">
                        <div>
                            <h3 style="color: #3b82f6; font-size: 24px;">{{ $ticket->seat->seat_label }}</h3>
                            <p style="color: #6b7280;">{{ $ticket->seat->seatCategory->name }}</p>
                        </div>
                        <div class="price">
                            {{ number_format($ticket->price, 0, ',', ' ') }} Ft
                        </div>
                    </div>

                    <div class="ticket-details">
                        <div class="detail-item">
                            <span class="detail-label">Film</span>
                            <span class="detail-value">{{ $booking->screening->movie->title }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Időpont</span>
                            <span class="detail-value">{{ $booking->screening->start_time->format('Y.m.d H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Terem</span>
                            <span class="detail-value">{{ $booking->screening->cinema->name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Ülőhely</span>
                            <span class="detail-value">{{ $ticket->seat->seat_label }}</span>
                        </div>
                    </div>

                    <div class="qr-section">
                        {{-- SVG QR kód közvetlenül beágyazva --}}
                        <div style="display: inline-block; background: white; padding: 10px;">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($ticket->qr_code) !!}
                        </div>
                        <p style="margin-top: 10px; font-size: 12px; color: #6b7280;">{{ $ticket->qr_code }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="footer">
            <p>Jegyét kérjük a moziba érkezéskor bemutatni.</p>
            <p>A QR kódot megmutatva történik a beléptetés.</p>
        </div>
    </div>
</body>
</html>