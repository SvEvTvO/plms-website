<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PLMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- CSS Background Pattern -->
        <style>
            .bg-dots {
                background-color: #fafafa;
                background-image: radial-gradient(#d1d5db 1.5px, transparent 1.5px);
                background-size: 24px 24px;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-dots min-h-screen flex flex-col items-center justify-center p-4 sm:p-6">
        
        <!-- Logo Header -->
        <div class="mb-8 flex items-center gap-2.5">
            <div class="w-10 h-10 bg-primary text-white rounded-[12px] flex items-center justify-center shadow-lg shadow-primary/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z"></path></svg>
            </div>
            <span class="text-2xl font-extrabold tracking-tight text-gray-900">PLMS</span>
        </div>

        <!-- Card Container -->
        <div class="w-full sm:max-w-md bg-white px-6 py-10 sm:px-10 shadow-[0_8px_40px_rgba(0,0,0,0.04)] border border-gray-100 rounded-[28px]">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="mt-10 text-center text-xs text-gray-400 font-medium">
            &copy; {{ date('Y') }} PLMS Library. All rights reserved.
        </div>

    </body>
</html>
