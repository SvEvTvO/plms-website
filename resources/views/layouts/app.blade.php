<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PLMS Source') }}</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="antialiased bg-slate-50 text-slate-700 selection:bg-primary selection:text-white">

        <!-- WRAPPER UTAMA -->
        <div class="flex h-screen overflow-hidden bg-slate-50">

            <!-- Overlay Mobile -->
            <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-30 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

            <!-- SIDEBAR -->
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 flex flex-col transform -translate-x-full transition-transform duration-300 lg:relative lg:translate-x-0 shrink-0">

                <div class="h-16 flex items-center px-6 border-b border-slate-100 shrink-0 z-10 relative">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-bold shadow-md shadow-primary/20 group-hover:scale-105 transition-transform">
                            <i class="ti ti-bookmarks text-xl"></i>
                        </div>
                        <span class="font-bold text-lg tracking-tight text-slate-900">PLMS<span class="text-primary">Source</span></span>
                    </a>
                </div>

                <nav class="flex-1 overflow-y-auto p-4 space-y-5 z-10 relative custom-scrollbar">
                    <!-- NAVIGASI UTAMA -->
                    <div>
                        <h4 class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Eksplorasi</h4>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('explore') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('explore') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-primary-50 hover:text-primary' }}">
                                    <i class="ti ti-world text-lg mr-3"></i> Komunitas Publik
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-primary-50 hover:text-primary' }}">
                                    <i class="ti ti-layout-dashboard text-lg mr-3"></i> Dasbor Pribadi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard.published') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('dashboard.published') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-primary-50 hover:text-primary' }}">
                                    <i class="ti ti-rocket text-lg mr-3"></i> Publikasi Saya
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- TINDAKAN -->
                    <div>
                        <h4 class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tindakan</h4>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('bookmarks.create') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('bookmarks.create') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-primary-50 hover:text-primary' }}">
                                    <i class="ti ti-square-plus text-lg mr-3"></i> Tambah Website
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- PENGATURAN -->
                    <div class="pt-2">
                        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Pengaturan</p>

                        <!-- Link Notifikasi untuk Sidebar -->
                        <a href="{{ route('notifications.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('notifications.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
                            <div class="flex items-center gap-3">
                                <i class="ti ti-bell-ringing text-lg"></i>
                                <span>Notifikasi</span>
                            </div>

                            <!-- Badge Angka Unread (Hanya muncul jika ada pesan belum dibaca) -->
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] font-bold shadow-sm">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('taxonomy.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all mb-1 {{ request()->routeIs('taxonomy.index') ? 'bg-primary text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
                            <i class="ti ti-tags text-lg"></i>
                            <span>Label & Kategori</span>
                        </a>

                        <a href="{{ route('taxonomy.requests.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all mb-1 {{ request()->routeIs('taxonomy.requests.index') ? 'bg-primary text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
                            <i class="ti ti-send text-lg"></i>
                            <span>Pengajuan Publik</span>
                        </a>

                        <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('profile.edit') ? 'bg-primary text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
                            <i class="ti ti-user-cog text-lg"></i>
                            <span>Profil Akun</span>
                        </a>
                    </div>
                </nav>

                <div class="p-4 border-t border-slate-100 shrink-0 z-10 relative bg-white">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2.5 text-sm font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                            <i class="ti ti-logout text-lg mr-3"></i> Keluar Akun
                        </button>
                    </form>
                </div>
            </aside>

            <!-- KANAN: AREA KONTEN UTAMA -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">

                <!-- TOP NAVBAR -->
                <header class="relative z-40 h-16 shrink-0 bg-primary flex items-center justify-between px-4 sm:px-8 shadow-md">
                    <div class="flex items-center gap-4">
                        <button id="sidebarToggle" class="lg:hidden p-2 -ml-2 text-primary-100 hover:text-white rounded-lg hover:bg-primary-600 transition-colors">
                            <i class="ti ti-menu-2 text-2xl"></i>
                        </button>
                        <span class="text-white font-medium text-sm hidden lg:flex items-center gap-2">
                            <i class="ti ti-sparkles text-primary-200"></i> Selamat datang di perpustakaan digitalmu!
                        </span>
                    </div>

                    @auth
                    <!-- KELOMPOK MENU KANAN (Notifikasi & Profil) -->
                    <div class="flex items-center gap-3 sm:gap-5">

                        <!-- 1. Tombol Notifikasi (AJAX Alpine.js) -->
                        <div x-data="notifManager()" class="relative">
                            <!-- Ikon Bel -->
                            <button @click="openNotif = !openNotif" @click.away="openNotif = false" class="relative p-2 text-primary-100 hover:text-white hover:bg-primary-600 rounded-full transition-colors focus:outline-none">
                                <i class="ti ti-bell text-[22px]"></i>
                                <!-- Titik Merah (Dinamis dari Alpine) -->
                                <span x-show="unreadCount > 0" style="display: none;" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 border-2 border-primary rounded-full animate-pulse"></span>
                            </button>

                            <!-- Kotak Dropdown -->
                            <div x-show="openNotif" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                 class="absolute right-0 mt-3 w-80 sm:w-96 bg-white border border-slate-200 shadow-2xl shadow-slate-200/50 rounded-2xl overflow-hidden z-50">

                                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                                    <h4 class="font-extrabold text-slate-800 text-sm">Notifikasi (<span x-text="unreadCount"></span>)</h4>
                                    <button type="button" x-show="unreadCount > 0" @click="markAll()" class="text-[10px] font-bold text-primary hover:text-primary-700 transition-colors">Tandai semua dibaca</button>
                                </div>

                                <div class="max-h-[320px] overflow-y-auto custom-scrollbar divide-y divide-slate-50">
                                    @foreach(auth()->user()->unreadNotifications->take(5) as $notif)
                                        <div id="notif-item-{{ $notif->id }}" class="p-4 hover:bg-slate-50 transition-colors flex gap-3 sm:gap-4 bg-sky-50/40">
                                            @if(isset($notif->data['status']) && $notif->data['status'] === 'approved')
                                                <div class="w-9 h-9 rounded-full bg-emerald-100 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0 shadow-sm mt-0.5"><i class="ti ti-check text-lg"></i></div>
                                            @elseif(isset($notif->data['status']) && $notif->data['status'] === 'rejected')
                                                <div class="w-9 h-9 rounded-full bg-rose-100 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0 shadow-sm mt-0.5"><i class="ti ti-x text-lg"></i></div>
                                            @else
                                                <div class="w-9 h-9 rounded-full bg-primary-100 border border-primary-200 text-primary-600 flex items-center justify-center shrink-0 shadow-sm mt-0.5"><i class="ti ti-flame text-lg"></i></div>
                                            @endif

                                            <div class="flex-1 flex flex-col items-start text-left">
                                                <p class="text-xs text-slate-700 leading-relaxed">{!! $notif->data['message'] !!}</p>
                                                <div class="flex items-center justify-between w-full mt-1.5">
                                                    <span class="text-[10px] font-semibold text-slate-400">{{ $notif->created_at->diffForHumans() }}</span>
                                                    <!-- Tombol Tandai Dibaca (AJAX) -->
                                                    <button type="button" @click="markAsRead('{{ $notif->id }}')" class="text-[10px] font-bold text-primary hover:text-primary-700">Tandai dibaca</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Empty State (Muncul saat tidak ada unread via Alpine JS) -->
                                    <div x-show="unreadCount === 0" style="display: none;" class="p-6 text-center">
                                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-2"><i class="ti ti-bell-z text-2xl"></i></div>
                                        <p class="text-xs font-semibold text-slate-500">Belum ada notifikasi baru.</p>
                                    </div>
                                </div>

                                <a href="{{ route('notifications.index') }}" class="block p-3 text-center border-t border-slate-100 bg-slate-50/80 hover:bg-slate-100 transition-colors">
                                    <span class="text-xs font-bold text-primary">Lihat Semua Riwayat</span>
                                </a>
                            </div>
                        </div>

                        <!-- 2. Tombol Profil (KEMBALI HADIR) -->
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-1.5 pr-4 rounded-full bg-primary-600 hover:bg-primary-700 border border-primary-500 transition cursor-pointer text-white shadow-sm focus:outline-none">
                            <div class="w-7 h-7 rounded-full bg-white text-primary font-extrabold flex items-center justify-center shadow-sm text-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-semibold tracking-wide hidden sm:inline">
                                {{ Auth::user()->name }}
                            </span>
                        </a>
                    </div>
                    @else
                    <!-- Tombol Login (Kalau belum login) -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-primary-100 hover:text-white transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-white text-primary text-sm font-bold rounded-lg shadow-sm hover:bg-slate-50 transition-colors hidden sm:block">Daftar Gratis</a>
                    </div>
                    @endauth
                </header>

                <!-- MAIN CONTENT -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                    @if (isset($header))
                        <div class="mb-6 lg:mb-8">
                            {{ $header }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>

            </div>
        </div>

        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #94a3b8; }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const toggleBtn = document.getElementById('sidebarToggle');

                function toggleSidebar() {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                }

                if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
                if (overlay) overlay.addEventListener('click', toggleSidebar);
            });

            // Anti Double-Submit Form Global
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form.getAttribute('data-submitting') === 'true') { e.preventDefault(); return; }
                form.setAttribute('data-submitting', 'true');
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
                    submitBtn.innerHTML = '<i class="ti ti-loader animate-spin text-lg mr-2"></i> Loading...';
                }
            });

            // Alpine.js AJAX Notifikasi Manager
            function notifManager() {
                return {
                    openNotif: false,
                    unreadCount: {{ auth()->check() ? auth()->user()->unreadNotifications->count() : 0 }},

                    markAsRead(id) {
                        fetch(`/notifications/${id}/mark-read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        }).then(res => res.json()).then(data => {
                            if(data.success) {
                                document.getElementById(`notif-item-${id}`).remove();
                                this.unreadCount--;
                            }
                        });
                    },

                    markAll() {
                        fetch(`/notifications/mark-all-read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        }).then(res => res.json()).then(data => {
                            if(data.success) {
                                document.querySelectorAll('[id^="notif-item-"]').forEach(el => el.remove());
                                this.unreadCount = 0;
                            }
                        });
                    }
                }
            }
        </script>
    </body>
</html>
