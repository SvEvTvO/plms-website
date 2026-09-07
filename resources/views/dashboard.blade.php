<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- HERO GREETING BANNER -->
        <div class="relative bg-white rounded-[32px] p-8 sm:p-10 shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
            <!-- Dekorasi Latar Belakang -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-gradient-to-br from-primary/20 to-primary/0 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-32 -mb-16 w-48 h-48 bg-gradient-to-tr from-blue-500/10 to-transparent rounded-full blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-bold tracking-wider uppercase mb-4 border border-primary/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z"></path></svg>
                        Dashboard Overview
                    </div>
                    <h1 class="text-4xl font-heading font-extrabold text-gray-900 tracking-tight">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                    <p class="text-base text-gray-500 mt-2 max-w-lg">Selamat datang kembali. Berikut adalah rekapitulasi dari seluruh koleksi perpustakaan digitalmu hari ini.</p>
                </div>

                <div class="flex items-center gap-3 bg-white/80 backdrop-blur-md border border-gray-200 px-5 py-3.5 rounded-[20px] shadow-sm">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path><path d="M16 3v4"></path><path d="M8 3v4"></path><path d="M4 11h16"></path><path d="M11 15h1"></path><path d="M12 15v3"></path></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tanggal Hari Ini</p>
                        <p class="text-sm font-bold text-gray-900">{{ now()->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATS METRICS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Card 1: Total Websites (Aksen Primary Utama) -->
            <div class="group bg-gradient-to-br from-primary to-blue-600 rounded-[24px] p-1 border border-primary/20 shadow-xl shadow-primary/20 relative overflow-hidden transition-transform hover:-translate-y-1">
                <!-- Ornamen Garis -->
                <div class="absolute right-0 top-0 w-32 h-full bg-white opacity-5 transform skew-x-12 translate-x-10 group-hover:translate-x-16 transition-transform duration-700"></div>
                
                <div class="bg-white/10 backdrop-blur-sm rounded-[22px] h-full p-6 flex flex-col justify-between relative z-10 border border-white/10">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-white/20 rounded-[14px] text-white flex items-center justify-center border border-white/30 backdrop-blur-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 1 -9 9m9 -9a9 9 0 0 0 -9 -9m9 9H3m9 9a9 9 0 0 1 -9 -9m9 9c1.657 0 3 -4.03 3 -9s-1.343 -9 -3 -9m0 18c-1.657 0 -3 -4.03 -3 -9s1.343 -9 3 -9m-9 9a9 9 0 0 1 9 -9"></path></svg>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white text-[11px] font-bold tracking-wider uppercase border border-white/20">
                            <div class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></div> Live
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-blue-100 mb-1">Total Link Disimpan</p>
                        <h3 class="text-5xl font-heading font-extrabold text-white tracking-tight">{{ $stats['total_websites'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Master Categories -->
            <div class="bg-white rounded-[24px] p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 flex flex-col justify-between hover:border-blue-200 transition-colors">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 rounded-[14px] bg-indigo-50 text-indigo-500 border border-indigo-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold text-gray-500 bg-gray-50 border border-gray-100 px-3 py-1.5 rounded-[8px] uppercase tracking-wider">Kategori</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400 mb-1">Total Folder Utama</p>
                    <h3 class="text-4xl font-heading font-bold text-gray-900">{{ $stats['total_categories'] }}</h3>
                </div>
            </div>

            <!-- Card 3: Total Favorites -->
            <div class="bg-white rounded-[24px] p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 flex flex-col justify-between hover:border-pink-200 transition-colors">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 rounded-[14px] bg-pink-50 text-pink-500 border border-pink-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037 .033l.034 -.03a6 6 0 0 1 4.733 -1.44l.246 .036a6 6 0 0 1 3.364 10.008l-.18 .185l-.048 .041l-7.45 7.379a1 1 0 0 1 -1.313 .082l-.094 -.082l-7.493 -7.422a6 6 0 0 1 3.176 -10.215z"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold text-gray-500 bg-gray-50 border border-gray-100 px-3 py-1.5 rounded-[8px] uppercase tracking-wider">Favorit</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400 mb-1">Sering Digunakan</p>
                    <h3 class="text-4xl font-heading font-bold text-gray-900">{{ $stats['total_favorites'] }}</h3>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- KOLOM KIRI (Akses Cepat Kategori) - Mengambil 4 Kolom Grid -->
            <div class="lg:col-span-4 bg-white rounded-[32px] p-6 sm:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 h-max">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0l8 -11"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Akses Cepat Folder</h3>
                </div>
                
                <div class="space-y-4">
                    @forelse ($quickCategories as $master)
                        @php
                            // Parsing SVG Icon
                            $svgPaths = match($master->icon) {
                                'robot' => '<path d="M6 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" /><path d="M2 8l4 0" /><path d="M18 8l4 0" /><path d="M2 16l4 0" /><path d="M18 16l4 0" /><path d="M9 4v-1" /><path d="M15 4v-1" /><path d="M9 12v.01" /><path d="M15 12v.01" /><path d="M9 16h6" />',
                                'code' => '<path d="M7 8l-4 4l4 4" /><path d="M17 8l4 4l-4 4" /><path d="M14 4l-4 16" />',
                                'palette' => '<path d="M12 21a9 9 0 0 1 0 -18c4.97 0 9 3.582 9 8c0 1.06 -.474 2.078 -1.318 2.828c-.844 .75 -1.989 1.172 -3.182 1.172h-2.5a2 2 0 0 0 -1 3.75a1.3 1.3 0 0 1 -1 2.25" /><path d="M8.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M16.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />',
                                'briefcase' => '<path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" />',
                                'books' => '<path d="M5 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M9 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M5 8h4" /><path d="M9 16h4" /><path d="M13.803 4.56l2.184 -.53c.562 -.135 1.133 .19 1.282 .732l3.695 13.418a1.02 1.02 0 0 1 -.634 1.219l-.133 .041l-2.184 .53c-.562 .135 -1.133 -.19 -1.282 -.732l-3.695 -13.418a1.02 1.02 0 0 1 .634 -1.219l.133 -.041z" /><path d="M14 9l4 -1" /><path d="M16 16l3.923 -.98" />',
                                'chart-pie' => '<path d="M10 3.2a9 9 0 1 0 10.8 10.8a1 1 0 0 0 -1 -1h-6.8a2 2 0 0 1 -2 -2v-7a.9 .9 0 0 0 -1 -.8" /><path d="M15 3.5a9 9 0 0 1 5.5 5.5h-4.5a1 1 0 0 1 -1 -1v-4.5" />',
                                default => '<path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" />'
                            };
                            $fullSvg = '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $svgPaths . '</svg>';
                            $hexColor = $master->color ?? '#64748b'; 
                        @endphp

                        <a href="{{ route('websites.index', ['category' => $master->id]) }}" class="group flex items-center justify-between p-3.5 bg-white border border-gray-100 rounded-[20px] hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5" style="border-left: 4px solid {{ $hexColor }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[14px] flex items-center justify-center shrink-0 transition" style="background-color: {{ $hexColor }}15; color: {{ $hexColor }};">
                                    {!! $fullSvg !!}
                                </div>
                                <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900">{{ $master->name }}</span>
                            </div>
                            <span class="text-xs font-bold text-gray-400 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100 group-hover:bg-white group-hover:text-gray-900 group-hover:border-gray-200 transition">{{ $master->categories_count }} Sub</span>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500 italic p-6 text-center bg-gray-50 rounded-[20px] border border-dashed border-gray-200">Kamu belum membuat Folder Utama.</p>
                    @endforelse
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('categories.index') }}" class="text-xs font-bold text-primary hover:text-primary-dark hover:underline flex items-center justify-center gap-1">
                        Kelola Semua Kategori
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l14 0"></path><path d="M13 18l6 -6"></path><path d="M13 6l6 6"></path></svg>
                    </a>
                </div>
            </div>

            <!-- KOLOM KANAN (Aktivitas Terbaru) - Mengambil 8 Kolom Grid -->
            <div class="lg:col-span-8 bg-white rounded-[32px] p-6 sm:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M12 12l3 -2"></path><path d="M12 7v5"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Website Terakhir Disimpan</h3>
                    </div>
                    <a href="{{ route('websites.create') }}" class="text-xs font-bold bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded-full shadow-sm shadow-primary/20 transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                        Tambah Baru
                    </a>
                </div>
                
                <div class="space-y-4">
                    @forelse ($recentWebsites as $website)
                        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50 border border-gray-100 rounded-[24px] hover:bg-white hover:shadow-lg hover:shadow-gray-200/40 transition duration-300 group">
                            <div class="flex items-start sm:items-center gap-4">
                                <div class="w-12 h-12 rounded-[16px] bg-white text-gray-400 border border-gray-200 flex items-center justify-center font-heading font-black text-xl group-hover:bg-primary group-hover:text-white group-hover:border-primary transition duration-300 shadow-sm shrink-0">
                                    {{ strtoupper(substr($website->name, 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <a href="{{ $website->url }}" target="_blank" rel="noopener noreferrer" class="text-base font-bold text-gray-900 group-hover:text-primary transition flex items-center gap-1">
                                        {{ $website->name }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6"></path><path d="M11 13l9 -9"></path><path d="M15 4h5v5"></path></svg>
                                    </a>
                                    <span class="text-sm text-gray-500 truncate max-w-[220px] md:max-w-xs mt-0.5">{{ $website->description ?? $website->url }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 self-start sm:self-center ml-16 sm:ml-0">
                                <span class="text-[10px] font-bold px-3 py-1.5 uppercase tracking-wider rounded-lg border bg-white
                                    {{ $website->pricing_type === 'free' ? 'text-emerald-700 border-emerald-100' : '' }}
                                    {{ $website->pricing_type === 'freemium' ? 'text-blue-700 border-blue-100' : '' }}
                                    {{ $website->pricing_type === 'paid' ? 'text-purple-700 border-purple-100' : '' }}
                                ">
                                    {{ $website->pricing_type }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center bg-gray-50 rounded-[24px] border border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-white border border-gray-100 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"></path><path d="M8 12h8"></path></svg>
                            </div>
                            <p class="text-base font-bold text-gray-900 mb-1">Library-mu masih kosong</p>
                            <p class="text-sm text-gray-500 mb-6">Mulai simpan link pertamamu sekarang.</p>
                            <a href="{{ route('websites.create') }}" class="text-sm font-medium text-white bg-primary px-6 py-3 rounded-full hover:bg-primary-dark shadow-sm transition">Tambah Website</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
