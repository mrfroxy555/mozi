@extends('layouts.app')

@section('title', 'Jegyvásárlás')

@section('content')
@verbatim
<style>
    .booking-header {
        background: #1a1a1a;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }
    .cinema-layout {
        background: #1a1a1a;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }
    .screen {
        background: linear-gradient(to bottom, #4b5563 0%, #1f2937 100%);
        height: 20px;
        border-radius: 50% 50% 0 0;
        margin-bottom: 3rem;
        position: relative;
    }
    .screen::after {
        content: 'VÁSZON';
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        color: #6b7280;
        font-size: 0.8rem;
    }
    .seats-grid {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: center;
    }
    .seat-row {
        display: flex;
        gap: 0.5rem;
    }
    .seat {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        border: 2px solid;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.75rem;
        transition: transform 0.2s;
    }
    .seat:hover:not(.booked) {
        transform: scale(1.1);
    }
    .seat.available {
        background: #1f2937;
        border-color: #4b5563;
    }
    .seat.selected {
        background: #3b82f6;
        border-color: #2563eb;
    }
    .seat.booked {
        background: #4b5563;
        border-color: #6b7280;
        cursor: not-allowed;
        opacity: 0.5;
    }
    .legend {
        display: flex;
        gap: 2rem;
        justify-content: center;
        margin: 2rem 0;
        flex-wrap: wrap;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .legend-box {
        width: 30px;
        height: 30px;
        border-radius: 4px;
        border: 2px solid;
    }
    .summary {
        background: #1a1a1a;
        padding: 2rem;
        border-radius: 8px;
        position: sticky;
        top: 20px;
    }
    .summary h3 {
        margin-bottom: 1rem;
        color: #3b82f6;
    }
    .selected-seats {
        margin: 1rem 0;
        max-height: 200px;
        overflow-y: auto;
    }
    .selected-seat-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem;
        background: #0f0f0f;
        border-radius: 4px;
        margin-bottom: 0.5rem;
    }
    .total-price {
        font-size: 1.5rem;
        font-weight: bold;
        color: #f59e0b;
        margin: 1rem 0;
    }
</style>
@endverbatim


<div class="booking-header">
    <h1>{{ $screening->movie->title }}</h1>
    <p style="color: #9ca3af;">{{ $screening->cinema->name }} • {{ $screening->start_time->format('Y.m.d H:i') }}</p>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <div>
        <div class="cinema-layout">
            <div class="screen"></div>
            
            <div class="seats-grid" id="seatsContainer"></div>
        </div>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-box" style="background: #1f2937; border-color: #4b5563;"></div>
                <span>Szabad</span>
            </div>
            <div class="legend-item">
                <div class="legend-box" style="background: #3b82f6; border-color: #2563eb;"></div>
                <span>Kiválasztott</span>
            </div>
            <div class="legend-item">
                <div class="legend-box" style="background: #4b5563; border-color: #6b7280; opacity: 0.5;"></div>
                <span>Foglalt</span>
            </div>
        </div>
    </div>

    <div class="summary">
        <h3>📋 Összesítő</h3>
        <div id="selectedSeatsContainer" class="selected-seats">
            <p style="color: #9ca3af;">Válassz helyet a térképről!</p>
        </div>
        <div class="total-price">Összesen: <span id="totalPrice">0</span> Ft</div>

        <form method="POST" action="{{ route('bookings.store', $screening) }}" id="bookingForm">
            @csrf
            <div id="seatsInputContainer"></div>
            
            @guest
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Név *</label>
                    <input type="text" name="guest_name" value="{{ old('guest_name') }}" required style="width: 100%; padding: 0.75rem; background: #0f0f0f; border: 1px solid #2a2a2a; border-radius: 4px; color: #e5e5e5;">
                    @error('guest_name')
                        <span style="color: #fca5a5; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Email *</label>
                    <input type="email" name="guest_email" value="{{ old('guest_email') }}" required style="width: 100%; padding: 0.75rem; background: #0f0f0f; border: 1px solid #2a2a2a; border-radius: 4px; color: #e5e5e5;">
                    @error('guest_email')
                        <span style="color: #fca5a5; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
            @endguest

            <button type="submit" class="btn" style="width: 100%;" id="submitBtn" disabled>Foglalás véglegesítése</button>
        </form>

        @if ($errors->any())
            <div style="background: #7f1d1d; padding: 1rem; border-radius: 4px; margin-top: 1rem;">
                @foreach ($errors->all() as $error)
                    <p style="color: #fca5a5; margin: 0.25rem 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const seats = @json($seats);
    let selectedSeats = [];

    function renderSeats() {
        const container = document.getElementById('seatsContainer');
        const rows = {};

        seats.forEach(seat => {
            if (!rows[seat.row]) rows[seat.row] = [];
            rows[seat.row].push(seat);
        });

        Object.keys(rows).sort((a, b) => a - b).forEach(rowNum => {
            const rowDiv = document.createElement('div');
            rowDiv.className = 'seat-row';

            rows[rowNum].sort((a, b) => a.seat - b.seat).forEach(seat => {
                const seatDiv = document.createElement('div');
                seatDiv.className = 'seat ' + (seat.isBooked ? 'booked' : 'available');
                seatDiv.textContent = seat.label;
                seatDiv.dataset.id = seat.id;
                seatDiv.dataset.label = seat.label;
                seatDiv.dataset.price = seat.price;
                seatDiv.style.borderColor = seat.color;

                if (!seat.isBooked) {
                    seatDiv.addEventListener('click', () => toggleSeat(seat));
                }

                rowDiv.appendChild(seatDiv);
            });

            container.appendChild(rowDiv);
        });
    }

    function toggleSeat(seat) {
        const index = selectedSeats.findIndex(s => s.id === seat.id);
        
        if (index > -1) {
            selectedSeats.splice(index, 1);
            document.querySelector(`[data-id="${seat.id}"]`).classList.remove('selected');
        } else {
            selectedSeats.push(seat);
            document.querySelector(`[data-id="${seat.id}"]`).classList.add('selected');
        }

        updateSummary();
    }

    function updateSummary() {
        const container = document.getElementById('selectedSeatsContainer');
        const totalPrice = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);

        if (selectedSeats.length === 0) {
            container.innerHTML = '<p style="color: #9ca3af;">Válassz helyet a térképről!</p>';
            document.getElementById('submitBtn').disabled = true;
        } else {
            container.innerHTML = selectedSeats.map(seat => `
                <div class="selected-seat-item">
                    <span>${seat.label}</span>
                    <span>${seat.price.toLocaleString()} Ft</span>
                </div>
            `).join('');
            document.getElementById('submitBtn').disabled = false;
        }

        document.getElementById('totalPrice').textContent = totalPrice.toLocaleString();
        
        // JAVÍTÁS: Tömbként küldjük el a seat ID-kat
        const inputContainer = document.getElementById('seatsInputContainer');
        inputContainer.innerHTML = '';
        
        selectedSeats.forEach(seat => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'seats[]';  // Fontos: seats[] a tömb jelzésére
            input.value = seat.id;
            inputContainer.appendChild(input);
        });
    }

    // Form submit validáció
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        if (selectedSeats.length === 0) {
            e.preventDefault();
            alert('Kérlek válassz ki legalább egy helyet!');
            return false;
        }
    });

    renderSeats();
</script>
@endpush
@endsection