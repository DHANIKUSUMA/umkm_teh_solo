<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UMKM Teh Solo') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-stone-800 antialiased bg-[#f7f5f0] min-h-screen relative overflow-x-hidden selection:bg-emerald-600 selection:text-white">
        <!-- Ambient Background Glows (Green Daun Teh & Brown Seduhan Teh) -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-300/30 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -right-32 w-96 h-96 bg-amber-400/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/4 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>

        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 sm:px-6 lg:px-8">
            <!-- Brand Header -->
            <div class="w-full max-w-md sm:max-w-lg text-center mb-6">
                <a href="/" class="inline-flex flex-col items-center group transition transform duration-200 hover:scale-105">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 p-2 bg-white rounded-2xl shadow-md border border-stone-200/80 mb-3 group-hover:border-emerald-500/60 transition">
                        <x-application-logo class="w-full h-full" />
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100/90 text-amber-900 border border-amber-300/70 mb-1">
                        <svg class="w-3.5 h-3.5 text-emerald-700" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" style="display:none"></path>
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 4a1 1 0 012 0v4a1 1 0 01-2 0V6zm1 8a1.25 1.25 0 110-2.5 1.25 1.25 0 010 2.5z" style="display:none"></path>
                            <!-- Leaf icon -->
                            <path d="M10 2C6 2 2 6 2 11c0 4 3 7 7 7 1 0 2-.2 3-.5 5-1.5 6-6.5 6-11.5 0-2.5-4-4-8-4zm0 2c3.5 0 6 1.3 6 3 0 4.2-1 7.8-4.8 9-3.2.4-5.2-1.9-5.2-5 0-3.9 3.1-7 4-7z"/>
                        </svg>
                        Otentik Tradisi Surakarta
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-emerald-950 tracking-tight">
                        UMKM TEH SOLO
                    </h1>
                    <p class="text-xs sm:text-sm text-stone-600 mt-1 font-medium">
                        Portal Kasir & Administrasi Penjualan Teh
                    </p>
                </a>
            </div>

            <!-- Card Content -->
            <div class="w-full max-w-md sm:max-w-lg bg-white/95 backdrop-blur-md px-6 py-8 sm:px-10 sm:py-9 rounded-2xl shadow-xl shadow-stone-300/40 border border-stone-200/90 relative overflow-hidden">
                <!-- Top Accent Gradient Line (Teh Hijau & Coklat Seduhan) -->
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-600 via-amber-600 to-emerald-800"></div>

                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-xs text-stone-500 space-y-1">
                <p class="font-medium">© {{ date('Y') }} UMKM Teh Solo. All rights reserved.</p>
                <p class="text-stone-400">Menjaga Cita Rasa Teh Khas Nusantara Sejak Dini</p>
            </div>
        </div>
    </body>
</html>
