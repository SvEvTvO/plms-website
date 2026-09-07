<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PLMS') }} - Digital Library Pribadimu</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts (Tailwind via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- CSS Dot Pattern -->
    <style>
        .bg-dots {
            background-color: #fafafa;
            background-image: radial-gradient(#d1d5db 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-dots selection:bg-primary selection:text-white flex flex-col min-h-screen">

    <!-- ======================================= -->
    <!-- FLOATING NAVBAR                         -->
    <!-- ======================================= -->
    <header class="fixed w-full top-0 z-50 p-4 sm:p-6 transition-all">
        <nav class="max-w-5xl mx-auto bg-white/80 backdrop-blur-md border border-white/50 rounded-[24px] px-5 py-3.5 shadow-[0_8px_30px_rgba(0,0,0,0.04)] flex items-center justify-between">

            <!-- Logo -->
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark text-white rounded-[12px] flex items-center justify-center shadow-md shadow-primary/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z"></path></svg>
                </div>
                <span class="text-xl font-heading font-extrabold tracking-tight text-gray-900">PLMS</span>
            </div>

            <!-- Nav Links / Auth -->
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900 px-4 py-2 transition hidden sm:block">Beranda Dasbor</a>
                        <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-[14px] hover:bg-primary-dark shadow-sm shadow-primary/30 transition">
                            Masuk Library
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l14 0"></path><path d="M13 18l6 -6"></path><path d="M13 6l6 6"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900 px-4 py-2 transition">Masuk</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-[14px] hover:bg-black shadow-sm transition">
                                Daftar Gratis
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>
    </header>

    <!-- ======================================= -->
    <!-- HERO SECTION                            -->
    <!-- ======================================= -->
    <main class="flex-grow flex flex-col items-center pt-32 pb-20 px-4">

        <!-- Decorative Glow Background -->
        <div class="absolute top-20 left-1/2 -translate-x-1/2 w-full max-w-3xl h-[400px] bg-gradient-to-tr from-primary/20 via-blue-400/10 to-transparent rounded-full blur-[80px] -z-10 pointer-events-none"></div>

        <div class="max-w-4xl mx-auto text-center mt-10 sm:mt-16 relative z-10">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-gray-200 text-gray-600 text-xs font-bold tracking-wider uppercase mb-6 shadow-sm">
                <span class="flex w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Rilis Versi 1.0 (2026)
            </div>

            <!-- Big Heading -->
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-heading font-extrabold text-gray-900 tracking-tight leading-[1.1] mb-6">
                Organisasikan Referensimu, <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-blue-600">Simpan Tanpa Batas.</span>
            </h1>

            <!-- Subheading -->
            <p class="text-lg sm:text-xl text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                PLMS (Personal Library Management System) adalah tempat terbaik untuk menyimpan, mengelompokkan, dan menemukan kembali *tools*, artikel, serta inspirasi digital favoritmu.
            </p>

            <!-- Call to Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-4 bg-primary text-white text-base font-bold rounded-[16px] hover:bg-primary-dark hover:scale-105 shadow-lg shadow-primary/30 transition-all duration-300">
                        Buka Dasbor Saya
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l14 0"></path><path d="M13 18l6 -6"></path><path d="M13 6l6 6"></path></svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-4 bg-primary text-white text-base font-bold rounded-[16px] hover:bg-primary-dark hover:scale-105 shadow-lg shadow-primary/30 transition-all duration-300">
                        Mulai Bangun Library
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l14 0"></path><path d="M13 18l6 -6"></path><path d="M13 6l6 6"></path></svg>
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-gray-700 text-base font-bold rounded-[16px] border border-gray-200 hover:bg-gray-50 hover:shadow-sm transition-all duration-300">
                        Masuk ke Akun
                    </a>
                @endauth
            </div>
        </div>

        <!-- ======================================= -->
        <!-- FEATURES SECTION                        -->
        <!-- ======================================= -->
        <div class="max-w-6xl mx-auto mt-24 grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10 w-full px-4">

            <!-- Feature 1 -->
            <div class="bg-white rounded-[28px] p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-[16px] flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 21v-6.5a3.5 3.5 0 0 0 -7 0v6.5h18v-6l-4.5 -4.5l-2.5 2.5l-4 -4l-4 4"></path></svg>
                </div>
                <h3 class="text-xl font-heading font-bold text-gray-900 mb-2">Auto-Fetch Meta</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Cukup masukkan URL, sistem akan menarik Nama Website dan Deskripsi secara otomatis. Hemat waktumu!
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-[28px] p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-[16px] flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>
                </div>
                <h3 class="text-xl font-heading font-bold text-gray-900 mb-2">Multi-Kategori Kustom</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Buat Folder Utama (Master) dan tambahkan Sub-Kategori tak terbatas. Beri warna kustom agar mudah dikenali.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-[28px] p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="w-14 h-14 bg-purple-50 text-purple-500 rounded-[16px] flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                </div>
                <h3 class="text-xl font-heading font-bold text-gray-900 mb-2">Pencarian Unified Cepat</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Cari berdasarkan nama, tag, atau saring berdasarkan kategori dan tipe harga (Free/Paid) dalam sepersekian detik.
                </p>
            </div>

        </div>
    </main>

    <!-- ======================================= -->
    <!-- FOOTER                                  -->
    <!-- ======================================= -->
    <footer class="py-8 text-center text-sm text-gray-400 font-medium">
        <p>&copy; 2026 PLMS (Personal Library Management System). All rights reserved.</p>
        <p class="mt-1 text-xs">Crafted with ❤️ and Tailwind CSS.</p>
    </footer>

</body>
</html>
