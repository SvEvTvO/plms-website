<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PLMS Source — Temukan & Simpan Referensi Website Terbaik</title>
        <meta name="description" content="Simpan, kategorikan, dan bagikan referensi website keren bersama komunitas developer dan kreator.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .bg-grid-pattern {
                background-image: radial-gradient(circle at 1px 1px, rgb(148 163 184 / 0.35) 1px, transparent 0);
                background-size: 28px 28px;
                mask-image: radial-gradient(ellipse 85% 65% at 50% 0%, black 40%, transparent 100%);
                -webkit-mask-image: radial-gradient(ellipse 85% 65% at 50% 0%, black 40%, transparent 100%);
            }
            @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-14px); } }
            @keyframes float-delayed { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(12px); } }
            @keyframes blob {
                0%, 100% { transform: translate(0, 0) scale(1); }
                33% { transform: translate(30px, -40px) scale(1.08); }
                66% { transform: translate(-25px, 25px) scale(0.94); }
            }
            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-float-delayed { animation: float-delayed 7s ease-in-out infinite; }
            .animate-blob { animation: blob 14s ease-in-out infinite; }
            .nav-scrolled .glass {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(12px);
                border-color: rgba(226, 232, 240, 0.8);
                box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
            }
            details summary { list-style: none; cursor: pointer; }
            details summary::-webkit-details-marker { display: none; }
            .accordion-wrapper { display: grid; grid-template-rows: 0fr; transition: grid-template-rows 0.3s ease-in-out; }
            .accordion-wrapper.is-open { grid-template-rows: 1fr; }
            .accordion-inner { overflow: hidden; }
        </style>
    </head>
    <body class="antialiased bg-white text-slate-800 selection:bg-teal-500 selection:text-white flex flex-col min-h-screen">

        <!-- NAVBAR -->
        <header class="fixed inset-x-0 top-0 z-50 transition-all duration-300" id="navbar">
            <nav class="mx-auto mt-3 max-w-7xl px-4 sm:px-6 lg:px-8 relative">
                <div class="glass flex h-16 items-center justify-between rounded-2xl border border-transparent bg-white/70 backdrop-blur-md px-4 shadow-sm sm:px-6 transition-all duration-300">
                    <a href="#" class="flex items-center gap-2.5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-700 text-white shadow-lg shadow-teal-700/20">
                            <i class="ti ti-bookmarks text-xl"></i>
                        </div>
                        <div>
                            <div class="text-[15px] font-extrabold leading-none tracking-tight text-slate-900">
                                PLMS<span class="text-teal-600">Source</span>
                            </div>
                            <div class="mt-1 hidden text-[9px] font-bold uppercase tracking-[.18em] text-slate-400 sm:block">
                                Resource Directory
                            </div>
                        </div>
                    </a>

                    <div class="hidden items-center gap-7 md:flex">
                        <a href="{{ route('explore') }}" class="text-sm font-semibold text-slate-500 transition hover:text-teal-700">Explore</a>
                        <a href="#keunggulan" class="text-sm font-semibold text-slate-500 transition hover:text-teal-700">Fitur</a>
                        <a href="#cara-kerja" class="text-sm font-semibold text-slate-500 transition hover:text-teal-700">Cara Kerja</a>
                        <a href="#faq" class="text-sm font-semibold text-slate-500 transition hover:text-teal-700">FAQ</a>
                    </div>

                    <div class="flex items-center gap-2.5">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800 hidden sm:block">
                                    Masuk Dasbor
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="hidden sm:block px-3 py-2 text-sm font-bold text-slate-600 transition hover:text-teal-700">
                                    Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-teal-700/20 transition hover:-translate-y-0.5 hover:bg-teal-800 hidden sm:block">
                                        Mulai Gratis
                                    </a>
                                @endif
                            @endauth
                        @endif

                        <button id="menu-btn" class="md:hidden flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-600 hover:bg-slate-100 transition">
                            <i id="menu-icon" class="ti ti-menu-2 text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden absolute top-20 left-4 right-4 rounded-2xl bg-white border border-slate-100 p-4 shadow-xl shadow-slate-900/5 md:hidden">
                    <div class="flex flex-col space-y-1 pb-4 border-b border-slate-100">
                        <a href="{{ route('explore') }}" class="mobile-link rounded-xl px-4 py-3 text-sm font-bold text-slate-600 hover:bg-teal-50 hover:text-teal-700">Explore</a>
                        <a href="#keunggulan" class="mobile-link rounded-xl px-4 py-3 text-sm font-bold text-slate-600 hover:bg-teal-50 hover:text-teal-700">Fitur</a>
                        <a href="#faq" class="mobile-link rounded-xl px-4 py-3 text-sm font-bold text-slate-600 hover:bg-teal-50 hover:text-teal-700">FAQ</a>
                    </div>
                    <div class="pt-4 flex flex-col gap-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="w-full rounded-xl bg-slate-900 px-4 py-3 text-center text-sm font-bold text-white">Dasbor</a>
                        @else
                            <a href="{{ route('login') }}" class="w-full rounded-xl bg-slate-50 px-4 py-3 text-center text-sm font-bold text-slate-700">Masuk</a>
                            <a href="{{ route('register') }}" class="w-full rounded-xl bg-teal-700 px-4 py-3 text-center text-sm font-bold text-white">Mulai Gratis</a>
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        <!-- HERO SECTION -->
        <section class="relative pt-36 pb-20 lg:pt-44 lg:pb-28 overflow-hidden bg-slate-50">
            <div class="absolute inset-0 bg-grid-pattern"></div>
            <div class="absolute top-0 right-0 -mr-24 -mt-24 w-[28rem] h-[28rem] rounded-full bg-teal-400/25 blur-3xl animate-blob pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -ml-24 -mb-24 w-96 h-96 rounded-full bg-emerald-400/20 blur-3xl animate-blob pointer-events-none" style="animation-delay: -6s;"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-14 lg:gap-12 items-center">

                    <div class="text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-50 border border-teal-100 text-teal-700 text-xs font-bold mb-7">
                            <i class="ti ti-world-search text-sm"></i> Bookmark Manager Era Baru
                        </div>

                        <h1 class="text-4xl md:text-5xl lg:text-[3.4rem] font-extrabold text-slate-900 tracking-tight mb-6 leading-[1.12]">
                            Simpan Web Keren,<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600">Berbagi Bersama Komunitas.</span>
                        </h1>

                        <p class="mt-2 max-w-xl mx-auto lg:mx-0 text-lg text-slate-500 font-medium leading-relaxed mb-9">
                            Jangan biarkan referensi penting tenggelam di riwayat browsermu. Paste URL-nya, biarkan sistem merapikan informasinya, dan temukan ratusan *tools* ajaib dari *user* lainnya.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white text-base font-bold rounded-2xl shadow-xl shadow-teal-600/30 hover:shadow-2xl transition-all duration-300 flex items-center justify-center gap-2 hover:-translate-y-0.5">
                                    <i class="ti ti-rocket text-xl"></i> Mulai Arsipkan URL
                                </a>
                            @endif
                            <a href="{{ route('explore') }}" class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-slate-50 text-slate-700 text-base font-bold rounded-2xl shadow-sm border border-slate-200 hover:border-teal-200 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="ti ti-compass text-xl text-teal-600"></i> Jelajahi Komunitas
                            </a>
                        </div>
                    </div>

                    <!-- Kanan: Mockup Bookmark Card -->
                    <div class="relative flex justify-center lg:justify-end">
                        <div class="absolute inset-0 -z-10 bg-gradient-to-tr from-teal-400/25 to-emerald-400/25 blur-3xl rounded-full scale-90"></div>

                        <div class="relative w-full max-w-md">
                            <!-- Main App Mockup -->
                            <div class="relative bg-white rounded-3xl shadow-2xl shadow-slate-900/10 border border-slate-100 p-6 animate-float">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center"><i class="ti ti-layout-grid"></i></div>
                                        <p class="font-bold text-slate-800">Library Tersimpan</p>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400">Baru Saja</span>
                                </div>

                                <div class="space-y-4">
                                    <!-- Card 1 -->
                                    <div class="p-4 border border-slate-100 rounded-2xl bg-slate-50/50 hover:border-teal-300 transition cursor-default">
                                        <div class="flex gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center shrink-0">
                                                <img src="https://www.google.com/s2/favicons?domain=chatgpt.com&sz=64" class="w-6 h-6 rounded" alt="Logo">
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 text-sm mb-1">ChatGPT - AI Assistant</h4>
                                                <p class="text-[11px] text-slate-500 leading-relaxed mb-3 line-clamp-2">Sistem kecerdasan buatan dari OpenAI yang membantu menulis, coding, dan berpikir kreatif.</p>
                                                <div class="flex gap-2">
                                                    <span class="text-[9px] font-bold text-teal-600 bg-teal-100 px-2 py-1 rounded-md">#AI Tools</span>
                                                    <span class="text-[9px] font-bold text-indigo-600 bg-indigo-100 px-2 py-1 rounded-md">#Productivity</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 2 -->
                                    <div class="p-4 border border-slate-100 rounded-2xl bg-slate-50/50 hover:border-emerald-300 transition cursor-default">
                                        <div class="flex gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center shrink-0">
                                                <i class="ti ti-brand-figma text-2xl text-pink-500"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 text-sm mb-1">Figma Design</h4>
                                                <p class="text-[11px] text-slate-500 leading-relaxed mb-3 line-clamp-2">Platform desain kolaboratif untuk tim digital merancang UI/UX yang memukau.</p>
                                                <div class="flex gap-2">
                                                    <span class="text-[9px] font-bold text-pink-600 bg-pink-100 px-2 py-1 rounded-md">#UI/UX</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Element -->
                            <div class="absolute -left-6 sm:-left-12 top-10 bg-white rounded-2xl shadow-xl shadow-slate-900/10 border border-slate-100 p-4 animate-float-delayed flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center"><i class="ti ti-users text-lg"></i></div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Crowdsourced</p>
                                    <p class="text-xs font-extrabold text-slate-800">Disimpan 1.250 user 🔥</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- VALUE STRIP -->
        <section class="border-y border-slate-200 bg-white">
            <div class="mx-auto grid max-w-7xl grid-cols-2 divide-x divide-slate-200 px-4 sm:px-6 lg:grid-cols-4 lg:px-8">
                <div class="group px-4 py-7 text-center lg:py-8 hover:bg-teal-600 transition-colors duration-300 cursor-default">
                    <i class="ti ti-wand text-2xl text-teal-600 group-hover:text-white transition-colors duration-300"></i>
                    <p class="mt-2 text-sm font-bold text-slate-800 group-hover:text-white transition-colors duration-300">Auto-Fetch Meta</p>
                    <p class="mt-1 text-xs text-slate-400 group-hover:text-teal-100 transition-colors duration-300">Paste URL, kami urus sisanya</p>
                </div>
                <div class="group px-4 py-7 text-center lg:py-8 hover:bg-teal-600 transition-colors duration-300 cursor-default">
                    <i class="ti ti-tags text-2xl text-teal-600 group-hover:text-white transition-colors duration-300"></i>
                    <p class="mt-2 text-sm font-bold text-slate-800 group-hover:text-white transition-colors duration-300">Smart Tagging</p>
                    <p class="mt-1 text-xs text-slate-400 group-hover:text-teal-100 transition-colors duration-300">Pencarian super spesifik</p>
                </div>
                <div class="group border-t px-4 py-7 text-center sm:border-t-0 lg:py-8 hover:bg-teal-600 transition-colors duration-300 cursor-default">
                    <i class="ti ti-world text-2xl text-teal-600 group-hover:text-white transition-colors duration-300"></i>
                    <p class="mt-2 text-sm font-bold text-slate-800 group-hover:text-white transition-colors duration-300">Community Driven</p>
                    <p class="mt-1 text-xs text-slate-400 group-hover:text-teal-100 transition-colors duration-300">Temukan web populer terbaru</p>
                </div>
                <div class="group border-t px-4 py-7 text-center sm:border-t-0 lg:py-8 hover:bg-teal-600 transition-colors duration-300 cursor-default">
                    <i class="ti ti-lock text-2xl text-teal-600 group-hover:text-white transition-colors duration-300"></i>
                    <p class="mt-2 text-sm font-bold text-slate-800 group-hover:text-white transition-colors duration-300">Kontrol Privasi</p>
                    <p class="mt-1 text-xs text-slate-400 group-hover:text-teal-100 transition-colors duration-300">Pilih tampil publik/privat</p>
                </div>
            </div>
        </section>

        <!-- FITUR KEUNGGULAN -->
        <section id="keunggulan" class="py-20 lg:py-28 bg-white relative overflow-hidden">
            <div class="absolute top-1/3 -right-32 w-96 h-96 bg-teal-100/40 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14 lg:mb-20">
                    <span class="inline-block px-3.5 py-1.5 rounded-full bg-teal-50 border border-teal-100 text-teal-700 text-xs font-bold tracking-widest uppercase mb-5">Manajemen Cerdas</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Lebih Dari Sekadar Folder Bookmark.</h2>
                    <p class="text-slate-500 text-lg">PLMS Source menyortir, merapikan, dan menghubungkan referensi website yang kamu butuhkan dalam ekosistem yang terstruktur.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <div class="group p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:bg-white hover:border-teal-200 hover:shadow-2xl hover:shadow-teal-600/10 transition-all duration-300 hover:-translate-y-1.5">
                        <div class="w-14 h-14 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-teal-600 group-hover:text-white transition-all duration-300">
                            <i class="ti ti-wand text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Sihir Auto-Fetch</h3>
                        <p class="text-slate-500 leading-relaxed text-sm">Cukup tempelkan URL. Sistem akan mengunduh Logo (Favicon), Judul Asli, dan Deskripsi secara otomatis dalam 2 detik.</p>
                    </div>
                    <div class="group p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:bg-white hover:border-indigo-200 hover:shadow-2xl hover:shadow-indigo-600/10 transition-all duration-300 hover:-translate-y-1.5">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                            <i class="ti ti-chart-bar text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Sistem Anti-Duplikat</h3>
                        <p class="text-slate-500 leading-relaxed text-sm">URL dinormalisasi. Jika ada 100 orang menyimpan web yang sama, datanya akan terhimpun menjadi satu kekuatan di Explore.</p>
                    </div>
                    <div class="group p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:bg-white hover:border-amber-200 hover:shadow-2xl hover:shadow-amber-600/10 transition-all duration-300 hover:-translate-y-1.5">
                        <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">
                            <i class="ti ti-tags text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Demokrasi Tags</h3>
                        <p class="text-slate-500 leading-relaxed text-sm">Sistem menghitung tag apa yang paling sering diberikan user pada sebuah web, membuatnya relevan dan sangat mudah dicari.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CARA KERJA (ACCORDION) -->
        <section id="cara-kerja" class="py-20 lg:py-28 bg-slate-50 border-t border-slate-100 relative overflow-hidden">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-14">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Menyimpan Tanpa Ribet</h2>
                    <p class="text-slate-500 text-lg">Hanya butuh tiga langkah agar sebuah website tersimpan selamanya.</p>
                </div>

                <div class="space-y-4">
                    <div class="demo-item bg-white border border-teal-200 shadow-md rounded-2xl overflow-hidden transition-all duration-300">
                        <button class="w-full text-left px-5 py-4 font-bold text-slate-800 flex justify-between items-center focus:outline-none">
                            <span class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-xs">1</span> Tempel (Paste) URL Website</span>
                            <i class="ti ti-chevron-down transform transition-transform rotate-180 text-teal-600 icon-arrow"></i>
                        </button>
                        <div class="demo-content accordion-wrapper is-open">
                            <div class="accordion-inner">
                                <div class="px-5 lg:px-14 pb-5 text-sm text-slate-500 leading-relaxed">
                                    <p>Masuk ke menu <strong>Tambah Website</strong>, letakkan link ke dalam kotak form. Seketika itu juga, robot kami (Auto-Fetch) akan menarik logo asli, judul meta, dan deskripsi dari website tujuan. Tidak perlu ngetik manual!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="demo-item bg-slate-50 border border-slate-100 hover:border-teal-200 rounded-2xl overflow-hidden transition-all duration-300">
                        <button class="w-full text-left px-5 py-4 font-bold text-slate-800 flex justify-between items-center focus:outline-none">
                            <span class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs">2</span> Ketik Tag / Kata Kunci</span>
                            <i class="ti ti-chevron-down transform transition-transform text-slate-400 icon-arrow"></i>
                        </button>
                        <div class="demo-content accordion-wrapper">
                            <div class="accordion-inner">
                                <div class="px-5 lg:px-14 pb-5 text-sm text-slate-500 leading-relaxed">
                                    <p>Berikan label. Cukup ketik seperti `#design` atau `#ai`, dan sistem akan merekomendasikan tag yang sudah disetujui (Approved). Ini akan membuat bookmark-mu sangat mudah dicari berbulan-bulan kemudian.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="demo-item bg-slate-50 border border-slate-100 hover:border-teal-200 rounded-2xl overflow-hidden transition-all duration-300">
                        <button class="w-full text-left px-5 py-4 font-bold text-slate-800 flex justify-between items-center focus:outline-none">
                            <span class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs">3</span> Atur Privasi & Simpan</span>
                            <i class="ti ti-chevron-down transform transition-transform text-slate-400 icon-arrow"></i>
                        </button>
                        <div class="demo-content accordion-wrapper">
                            <div class="accordion-inner">
                                <div class="px-5 lg:px-14 pb-5 text-sm text-slate-500 leading-relaxed">
                                    <p>Pilih apakah link ini <strong>Publik</strong> (bisa ditemukan user lain di halaman Explore) atau <strong>Privat</strong> (hanya ada di dashboard-mu sendiri). Selesai! Koleksi berhargamu sudah aman tersimpan di *cloud*.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="py-20 lg:py-28 bg-white border-t border-slate-100">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-14">
                    <span class="inline-block px-3.5 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold tracking-widest uppercase mb-5">FAQ</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Paling Sering Ditanyain</h2>
                </div>

                <div class="space-y-4" id="faq-container">
                    <div class="faq-item bg-white border border-slate-200 hover:border-teal-300 rounded-2xl transition-all shadow-sm">
                        <button class="w-full text-left flex items-center justify-between gap-4 p-5 lg:p-6 font-bold text-slate-800 text-[15px] focus:outline-none">
                            <span>Beneran gratis nih aplikasinya?</span>
                            <i class="ti ti-plus faq-icon text-teal-600 text-xl shrink-0 transition-transform duration-300"></i>
                        </button>
                        <div class="accordion-wrapper">
                            <div class="accordion-inner">
                                <p class="px-5 lg:px-6 pb-6 -mt-1 text-slate-500 text-sm leading-relaxed">Iya dong! Fitur dasbor penyimpanan, ekstrak meta otomatis, dan jelajah komunitas 100% gratis. Kamu bisa menyimpan referensi web sebanyak-banyaknya.</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item bg-white border border-slate-200 hover:border-teal-300 rounded-2xl transition-all shadow-sm">
                        <button class="w-full text-left flex items-center justify-between gap-4 p-5 lg:p-6 font-bold text-slate-800 text-[15px] focus:outline-none">
                            <span>Maksud dari "Crowdsourced Tags" itu apa?</span>
                            <i class="ti ti-plus faq-icon text-teal-600 text-xl shrink-0 transition-transform duration-300"></i>
                        </button>
                        <div class="accordion-wrapper">
                            <div class="accordion-inner">
                                <p class="px-5 lg:px-6 pb-6 -mt-1 text-slate-500 text-sm leading-relaxed">Jika ada 10 orang yang menyimpan web "ChatGPT" ke dalam sistem, algoritma kami akan menghitung tag apa yang paling banyak mereka sematkan. Jadi, pencarian akan sangat akurat karena diisi oleh manusia, bukan bot.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-white border-t border-slate-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <a href="#" class="inline-flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-teal-700 rounded-xl flex items-center justify-center text-white shadow-lg shadow-teal-700/20">
                            <i class="ti ti-bookmarks text-xl"></i>
                        </div>
                        <span class="font-extrabold text-xl tracking-tight text-slate-900">PLMS<span class="text-teal-600">Source</span></span>
                    </a>
                    <p class="text-slate-400 text-sm text-center md:text-left font-medium">
                        &copy; {{ date('Y') }} PLMS Source. Hak Cipta Dilindungi. <br>Dikembangkan oleh <b class="text-teal-600">Moch Miftahul Khoironi</b>.
                    </p>
                </div>
            </div>
        </footer>

        <!-- SCRIPTS -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Navbar scroll
                const navbar = document.getElementById('navbar');
                const onScroll = () => { if (window.scrollY > 20) { navbar.classList.add('nav-scrolled'); } else { navbar.classList.remove('nav-scrolled'); } };
                window.addEventListener('scroll', onScroll, { passive: true });
                onScroll();

                // Menu Mobile
                const menuBtn = document.getElementById('menu-btn'), menu = document.getElementById('mobile-menu'), menuIcon = document.getElementById('menu-icon');
                const closeMenu = () => { menu.classList.add('hidden'); menuIcon.className = 'ti ti-menu-2 text-xl'; };
                menuBtn.addEventListener('click', () => { menu.classList.toggle('hidden'); menuIcon.className = menu.classList.contains('hidden') ? 'ti ti-menu-2 text-xl' : 'ti ti-x text-xl'; });
                menu.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));

                // Animasi Accordion biasa (FAQ)
                const faqItems = document.querySelectorAll('.faq-item');
                faqItems.forEach(item => {
                    const btn = item.querySelector('button'), wrapper = item.querySelector('.accordion-wrapper'), icon = item.querySelector('.faq-icon');
                    btn.addEventListener('click', () => {
                        const isOpen = wrapper.classList.contains('is-open');
                        faqItems.forEach(otherItem => {
                            otherItem.querySelector('.accordion-wrapper').classList.remove('is-open');
                            if (otherItem.querySelector('.faq-icon')) otherItem.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
                        });
                        if (!isOpen) { wrapper.classList.add('is-open'); if(icon) icon.style.transform = 'rotate(45deg)'; }
                    });
                });

                // Animasi Accordion Khusus (Cara Kerja)
                const demoItems = document.querySelectorAll('.demo-item');
                demoItems.forEach(item => {
                    const btn = item.querySelector('button');
                    btn.addEventListener('click', () => {
                        if(item.classList.contains('bg-white')) return; // Already open

                        demoItems.forEach(el => {
                            el.classList.remove('bg-white', 'border-teal-200', 'shadow-md');
                            el.classList.add('bg-slate-50', 'border-slate-100');
                            el.querySelector('.demo-content').classList.remove('is-open');
                            const icon = el.querySelector('.icon-arrow');
                            icon.classList.remove('rotate-180', 'text-teal-600');
                            icon.classList.add('text-slate-400');
                        });

                        item.classList.remove('bg-slate-50', 'border-slate-100');
                        item.classList.add('bg-white', 'border-teal-200', 'shadow-md');
                        item.querySelector('.demo-content').classList.add('is-open');
                        const icon = item.querySelector('.icon-arrow');
                        icon.classList.remove('text-slate-400');
                        icon.classList.add('rotate-180', 'text-teal-600');
                    });
                });
            });
        </script>
    </body>
</html>
