@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">🎫 Foglalás részletei</h1>
                    <p class="text-gray-400">
                        Foglalási kód: 
                        <span class="font-mono text-blue-400">{{ $booking->booking_code ?? $booking->confirmation_code }}</span>
                    </p>
                </div>
                <a href="{{ route('admin.bookings.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Vissza
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Bal oldal -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Film információk -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-2xl">
    <div class="p-6 border-b border-gray-700">
        <h2 class="text-xl font-bold text-white flex items-center">
            <span class="mr-3">🎬</span>
            Film információk
        </h2>
    </div>

    <div class="p-6">

        {{-- DEFINIÁLJUK A VÁLTOZÓT --}}
        @php $movie = $booking->screening->movie; @endphp

        <div class="flex gap-4 items-start">

            {{-- POSZTER --}}
            <div class="w-[180px] flex-shrink-0">
                @if($movie->poster_url || $movie->poster)
                    <img 
                        src="{{ $movie->poster_url ?? asset('storage/' . $movie->poster) }}"
                        alt="{{ $movie->title }}"
                        class="rounded-lg object-cover"
                        style="width: 180px; height: 260px;"
                    />
                @else
                    <div class="w-[180px] h-[260px] bg-gray-700 rounded-lg flex items-center justify-center text-gray-400">
                        Nincs kép
                    </div>
                @endif
            </div>

            {{-- JOBB OLDAL - Film részletek --}}
            <div class="flex-1 min-w-0 space-y-3">
                <h3 class="text-2xl font-bold text-white">{{ $movie->title }}</h3>

                <p class="text-gray-300 text-sm">
                    <span class="font-semibold text-gray-400">Műfaj:</span> {{ $movie->genre }}
                </p>

                <p class="text-gray-300 text-sm">
                    <span class="font-semibold text-gray-400">Hossz:</span> {{ $movie->duration }} perc
                </p>

                <p class="text-gray-300 text-sm">
                    <span class="font-semibold text-gray-400">Rendező:</span> {{ $movie->director }}
                </p>

                <span class="inline-block px-3 py-1 bg-yellow-500/20 border border-yellow-500/50 text-yellow-300 text-xs font-semibold rounded-full">
                    {{ $movie->age_rating }}+
                </span>
            </div>

        </div>




                <!-- Vetítés információk -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                    <div class="p-6 border-b border-gray-700">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <span class="mr-3">📅</span>
                            Vetítés információk
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">

                        <div class="flex items-start">
                            <div class="p-3 bg-blue-500/20 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Vetítés időpontja</p>
                                <p class="text-lg font-semibold text-white">
                                    {{ $booking->screening->start_time->format('Y.m.d. H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="p-3 bg-purple-500/20 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Terem</p>
                                <p class="text-lg font-semibold text-white">{{ $booking->screening->cinema->name }}</p>
                            </div>
                        </div>

                    </div>
                </div>


                <!-- Ülések -->
                @if($booking->seats?->count())
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 shadow-xl">
                    <div class="p-6 border-b border-gray-700">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <span class="mr-3">💺</span>
                            Foglalt ülések
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach($booking->seats as $seat)
                                <div class="px-4 py-2 bg-blue-500/20 border border-blue-500/50 text-blue-300 font-semibold rounded-lg">
                                    Sor: {{ $seat->row }} / Szék: {{ $seat->seat_number }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>


            <!-- Jobb oldal -->
            <div class="space-y-6">

                <!-- Felhasználó -->
                <div class="bg-gray-800/50 rounded-2xl border border-gray-700/50 shadow-xl">
                    <div class="p-6 border-b border-gray-700">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <span class="mr-3">👤</span>
                            Felhasználó
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-sm text-gray-400">Név</p>
                            <p class="text-white font-semibold">{{ $booking->user->name ?? $booking->guest_name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-400">Email</p>
                            <p class="text-white font-semibold">{{ $booking->user->email ?? $booking->guest_email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Foglalás részletei -->
                <div class="bg-gradient-to-br from-green-500/20 to-green-600/20 rounded-2xl border border-green-500/50 shadow-xl">
                    <div class="p-6 border-b border-green-500/30">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <span class="mr-3">💳</span>
                            Foglalás részletei
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">

                        @php
                            $ticketCount = $booking->tickets->count();
                            $pricePerTicket = $ticketCount > 0 ? $booking->total_price / $ticketCount : 0;
                        @endphp

                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Jegyek száma</span>
                            <span class="text-white font-bold text-lg">{{ $ticketCount }} db</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Jegy ár</span>
                            <span class="text-white font-semibold">{{ number_format($pricePerTicket) }} Ft</span>
                        </div>

                        <div class="pt-4 border-t border-green-500/30">
                            <div class="flex justify-between items-center">
                                <span class="text-green-300 font-semibold">Végösszeg</span>
                                <span class="text-3xl font-bold text-green-400">{{ number_format($booking->total_price) }} Ft</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-green-500/30">
                            <p class="text-sm text-gray-400">Foglalás időpontja</p>
                            <p class="text-white font-semibold">{{ $booking->created_at->format('Y.m.d H:i') }}</p>
                        </div>

                    </div>
                </div>


                <!-- Műveletek -->
                <div class="bg-gray-800/50 rounded-2xl border border-gray-700/50 shadow-xl">
                    <div class="p-6 space-y-3">

                        <a href="{{ route('tickets.pdf', $booking) }}" 
                           target="_blank"
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200">
                            PDF letöltése
                        </a>

                        <!-- FOGALÁS TÖRLÉSE (EGY DARAB FORM!) -->
                        <form action="{{ route('admin.bookings.cancel', $booking) }}" 
                              method="POST"
                              onsubmit="return confirm('Biztosan törlöd ezt a foglalást?');">
                            @csrf
                            @method('PATCH')

                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200">
                                Foglalás törlése
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
