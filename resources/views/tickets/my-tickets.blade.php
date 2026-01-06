@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">🎫 Jegyeim</h1>
            <p class="text-gray-400">Az összes mozijegyed egy helyen</p>
        </div>

        @if($bookings->isEmpty())
            <!-- Nincs jegy üzenet -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 p-12 text-center">
                <div class="text-6xl mb-4">🎬</div>
                <h2 class="text-2xl font-bold text-white mb-2">Még nincs jegyed</h2>
                <p class="text-gray-400 mb-6">Böngészd a filmkínálatunkat és foglalj jegyet!</p>
                <a href="{{ route('movies.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200">
                    Filmek megtekintése
                </a>
            </div>
        @else
            <!-- Jegyek listája -->
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl hover:border-blue-500/50 transition-all duration-300">
                        <div class="p-6">
                            <div class="flex gap-6">
                                
                                <!-- Bal oldal - Film poszter -->
                                <div class="w-20 lg:w-24 flex-shrink-0">
                                    @php $movie = $booking->screening->movie; @endphp
                                    
                                    @if($movie->poster_url || $movie->poster)
                                        <img 
                                            src="{{ $movie->poster_url ?? asset('storage/' . $movie->poster) }}"
                                            alt="{{ $movie->title }}"
                                            class="w-full h-28 lg:h-32 object-cover rounded-lg shadow-lg"
                                        />
                                    @else
                                        <div class="w-full h-28 lg:h-32 bg-gray-700 rounded-lg flex items-center justify-center text-gray-400 text-xs">
                                            🎬
                                        </div>
                                    @endif
                                </div>

                                <!-- Jobb oldal - Foglalás részletei -->
                                <div class="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    
                                    <!-- Bal oszlop -->
                                    <div class="space-y-3">
                                        <!-- Film címe és foglalási kód -->
                                        <div>
                                            <h2 class="text-xl font-bold text-white mb-1">{{ $movie->title }}</h2>
                                            <p class="text-xs text-gray-400">
                                                Foglalási kód: 
                                                <span class="font-mono text-blue-400">{{ $booking->booking_code }}</span>
                                            </p>
                                        </div>

                                        <!-- Időpont -->
                                        <div class="flex items-center gap-2">
                                            <div class="p-2 bg-blue-500/20 rounded-lg">
                                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400">Időpont</p>
                                                <p class="text-sm font-semibold text-white">
                                                    {{ $booking->screening->start_time->format('Y.m.d. H:i') }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Terem -->
                                        <div class="flex items-center gap-2">
                                            <div class="p-2 bg-purple-500/20 rounded-lg">
                                                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400">Terem</p>
                                                <p class="text-sm font-semibold text-white">
                                                    {{ $booking->screening->cinema->name }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Jobb oszlop -->
                                    <div class="space-y-3">
                                        <!-- Végösszeg -->
                                        <div class="flex items-center gap-2">
                                            <div class="p-2 bg-green-500/20 rounded-lg">
                                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400">Végösszeg</p>
                                                <p class="text-sm font-bold text-green-400">
                                                    {{ number_format($booking->total_price) }} Ft
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Jegyek száma -->
                                        <div class="flex items-center gap-2">
                                            <div class="p-2 bg-yellow-500/20 rounded-lg">
                                                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400">Jegyek száma</p>
                                                <p class="text-sm font-semibold text-white">
                                                    {{ $booking->tickets->count() }} db
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Ülőhelyek -->
                                        @if($booking->tickets->count())
                                            <div>
                                                <p class="text-xs text-gray-400 mb-1">Ülőhelyek:</p>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($booking->tickets as $ticket)
                                                        <span class="px-2 py-0.5 bg-blue-500/20 border border-blue-500/50 text-blue-300 text-xs font-semibold rounded">
                                                            {{ $ticket->seat->seat_label }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Műveletek - teljes szélességben alul -->
                                    <div class="lg:col-span-2 flex flex-wrap gap-2 pt-2">
                                        <a href="{{ route('bookings.show', $booking) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors duration-200">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Részletek
                                        </a>

                                        <a href="{{ route('tickets.pdf', $booking) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors duration-200">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            PDF letöltése
                                        </a>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- Foglalás időpontja -->
                        <div class="px-6 py-2.5 bg-gray-900/50 border-t border-gray-700">
                            <p class="text-xs text-gray-500">
                                Foglalva: {{ $booking->created_at->format('Y.m.d H:i') }}
                            </p>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection