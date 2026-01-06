@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">👤 Profilom</h1>
            <p class="text-gray-400">Személyes adatok kezelése</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-500/20 border border-green-500/50 text-green-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            
            <!-- Profil információk frissítése -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-6 border-b border-gray-700">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <span class="mr-3">📝</span>
                        Profil információk
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">Frissítsd a neved, email címed és telefonszámod.</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <!-- Név -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Név <span class="text-red-400">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name', $user->name) }}"
                                required
                                class="w-full px-4 py-2 bg-gray-200 border border-gray-400 rounded-lg text-black placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            />
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email cím <span class="text-red-400">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email', $user->email) }}"
                                required
                                class="w-full px-4 py-2 bg-gray-200 border border-gray-400 rounded-lg text-black placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            />
                            @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            
                            @if($user->email_verified_at === null)
                                <p class="mt-2 text-sm text-yellow-400">
                                    ⚠️ Az email címed még nincs megerősítve.
                                </p>
                            @endif
                        </div>

                        <!-- Telefonszám -->
                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Telefonszám
                            </label>
                            <input 
                                type="text" 
                                name="phone" 
                                id="phone" 
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="+36 20 123 4567"
                                class="w-full px-4 py-2 bg-gray-200 border border-gray-400 rounded-lg text-black placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            />
                            @error('phone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mentés gomb -->
                        <div class="flex items-center justify-end">
                            <button 
                                type="submit"
                                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-lg shadow-blue-500/20"
                            >
                                Mentés
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Jelszó módosítás -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-6 border-b border-gray-700">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <span class="mr-3">🔒</span>
                        Jelszó módosítása
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">Győződj meg róla, hogy erős jelszót használsz.</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Jelenlegi jelszó -->
                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Jelenlegi jelszó <span class="text-red-400">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="current_password" 
                                id="current_password"
                                required
                                class="w-full px-4 py-2 bg-gray-200 border border-gray-400 rounded-lg text-black placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            />
                            @error('current_password', 'updatePassword')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Új jelszó -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Új jelszó <span class="text-red-400">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                required
                                class="w-full px-4 py-2 bg-gray-200 border border-gray-400 rounded-lg text-black placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            />
                            @error('password', 'updatePassword')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Új jelszó megerősítése -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Új jelszó megerősítése <span class="text-red-400">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation"
                                required
                                class="w-full px-4 py-2 bg-gray-200 border border-gray-400 rounded-lg text-black placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            />
                            @error('password_confirmation', 'updatePassword')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mentés gomb -->
                        <div class="flex items-center justify-end">
                            <button 
                                type="submit"
                                class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-lg shadow-green-500/20"
                            >
                                Jelszó frissítése
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Fiók törlése -->
            <div class="bg-red-900/20 backdrop-blur-sm rounded-2xl border border-red-700/50 overflow-hidden shadow-xl">
                <div class="p-6 border-b border-red-700/50">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <span class="mr-3">⚠️</span>
                        Fiók törlése
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">A fiók törlése végleges és nem visszafordítható.</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Biztosan törölni szeretnéd a fiókodat? Ez a művelet nem visszafordítható!');">
                        @csrf
                        @method('DELETE')

                        <p class="text-sm text-gray-300 mb-4">
                            Ha törlöd a fiókodat, minden adat véglegesen elvész. Kérlek, add meg a jelszavadat a megerősítéshez.
                        </p>

                        <!-- Jelszó megerősítés -->
                        <div class="mb-6">
                            <label for="password_delete" class="block text-sm font-medium text-gray-700 mb-2">
                                Jelszó <span class="text-red-400">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password_delete"
                                required
                                class="w-full px-4 py-2 bg-gray-200 border border-red-400 rounded-lg text-black placeholder-gray-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                            />
                            @error('password', 'userDeletion')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Törlés gomb -->
                        <div class="flex items-center justify-end">
                            <button 
                                type="submit"
                                class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-lg shadow-red-500/20"
                            >
                                Fiók törlése
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
