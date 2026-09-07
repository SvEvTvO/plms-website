<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PLMS') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body text-gray-800 bg-[#F4F7F6] antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        <!-- Backdrop Mobile -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden" @click="sidebarOpen = false" style="display: none;"></div>

        <!-- Sidebar Kiri -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-100 flex flex-col flex-shrink-0 transition-transform duration-300 lg:static lg:translate-x-0 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
            <!-- Logo Area -->
            <div class="h-16 flex items-center px-6 border-b border-gray-50 shrink-0">
                <div class="w-8 h-8 bg-primary text-white rounded-[10px] flex items-center justify-center mr-3 shadow-sm shadow-primary-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z"></path><path d="M19 16h-12a2 2 0 0 0 -2 2"></path><path d="M9 8h6"></path></svg>
                </div>
                <span class="font-heading font-bold text-xl text-gray-900 tracking-tight">PLMS</span>
            </div>

            <!-- Navigation Links -->
            <div class="p-4 flex-1 overflow-y-auto">

                <div class="text-[11px] font-bold text-gray-400 mb-2 mt-2 uppercase tracking-wider px-2">Menu Utama</div>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-[14px] text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-md shadow-primary-200' : 'text-gray-500 hover:bg-primary-50 hover:text-primary' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h6v8h-6z"></path><path d="M4 16h6v4h-6z"></path><path d="M14 12h6v8h-6z"></path><path d="M14 4h6v4h-6z"></path></svg>
                    Dashboard
                </a>

                <div class="text-[11px] font-bold text-gray-400 mb-2 mt-6 uppercase tracking-wider px-2">Library</div>

                <a href="{{ route('websites.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-[14px] text-sm font-medium transition-all mb-1 {{ request()->routeIs('websites.*') ? 'bg-primary text-white shadow-md shadow-primary-200' : 'text-gray-500 hover:bg-primary-50 hover:text-primary' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19.5 7a9 9 0 0 0 -7.5 -4a8.991 8.991 0 0 0 -7.484 4"></path><path d="M11.5 3a16.989 16.989 0 0 0 -1.826 4"></path><path d="M12.5 3a16.989 16.989 0 0 1 1.828 4"></path><path d="M19.5 17a9 9 0 0 1 -7.5 4a8.991 8.991 0 0 1 -7.484 -4"></path><path d="M11.5 21a16.989 16.989 0 0 1 -1.826 -4"></path><path d="M12.5 21a16.989 16.989 0 0 0 1.828 -4"></path><path d="M2 10l1 4l1.5 -4l1.5 4l1 -4"></path><path d="M17 10l1 4l1.5 -4l1.5 4l1 -4"></path><path d="M9.5 10l1 4l1.5 -4l1.5 4l1 -4"></path></svg>
                    Semua Website
                </a>

                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-[14px] text-sm font-medium transition-all mb-1 {{ request()->routeIs('categories.*') || request()->routeIs('master-categories.*') ? 'bg-primary text-white shadow-md shadow-primary-200' : 'text-gray-500 hover:bg-primary-50 hover:text-primary' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h6v6h-6z"></path><path d="M14 4h6v6h-6z"></path><path d="M4 14h6v6h-6z"></path><path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path></svg>
                    Kategori Folder
                </a>

                <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-[14px] text-sm font-medium transition-all {{ request()->routeIs('favorites.*') ? 'bg-primary text-white shadow-md shadow-primary-200' : 'text-gray-500 hover:bg-primary-50 hover:text-primary' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path></svg>
                    Favorites
                </a>

                <div class="text-[11px] font-bold text-gray-400 mb-2 mt-6 uppercase tracking-wider px-2">Pengaturan</div>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-[14px] text-sm font-medium transition-all text-gray-500 hover:bg-gray-50 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path></svg>
                    Profil Akun
                </a>
            </div>

            <div class="p-4 border-t border-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 w-full rounded-[14px] text-sm font-medium transition-all text-red-500 hover:bg-red-50 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path><path d="M9 12h12l-3 -3"></path><path d="M18 15l3 -3"></path></svg>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden relative">
            <header class="h-16 bg-primary text-white flex justify-between items-center px-6 lg:px-8 shadow-md z-10 shrink-0 relative overflow-hidden">
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute -right-10 -top-24 w-64 h-64 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="absolute right-40 top-4 w-32 h-32 rounded-full bg-black/5 blur-xl"></div>
                </div>

                <div class="flex items-center gap-3 relative z-10">
                    <button @click="sidebarOpen = true" class="lg:hidden text-white hover:text-primary-100 mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6l16 0"></path><path d="M4 12l16 0"></path><path d="M4 18l16 0"></path></svg>
                    </button>
                    <span class="text-sm font-medium hidden sm:block">
                        👋 Selamat datang kembali, semangat kelola digital library-mu!
                    </span>
                </div>

                <div class="flex items-center gap-3 relative z-10">
                    <span class="text-sm font-medium hidden md:block">{{ Auth::user()->name }}</span>
                    <div class="w-9 h-9 rounded-full bg-white text-primary flex items-center justify-center font-bold text-sm shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @isset($header)
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endisset
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
