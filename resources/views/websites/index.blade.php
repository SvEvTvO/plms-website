<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6" x-data="{ showDeleteModal: false, deleteUrl: '', deleteName: '' }">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
            <div>
                <h2 class="text-3xl font-heading font-bold text-gray-900 leading-tight">My Library</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola dan organisasikan seluruh website simpananmu.</p>
            </div>
            <a href="{{ route('websites.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white transition bg-primary rounded-[14px] hover:bg-primary-700 shadow-sm shadow-primary-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                Tambah Website
            </a>
        </div>

        @php
            // Normalisasi array untuk multi-select dari request
            $selectedCategories = request('category', []);
            if (!is_array($selectedCategories)) {
                $selectedCategories = [$selectedCategories];
            }
        @endphp

        <!-- ========================================================= -->
        <!-- ULTIMATE UNIFIED SEARCH & FILTER BAR                      -->
        <!-- Desain menyatu dengan Custom Alpine.js Dropdown           -->
        <!-- ========================================================= -->
        <form action="{{ route('websites.index') }}" method="GET" x-ref="searchForm" class="bg-white rounded-[24px] p-2 shadow-[0_4px_20px_rgba(0,0,0,0.04)] border border-gray-200 flex flex-col md:flex-row items-stretch md:items-center gap-2 transition-all relative z-20">
            <input type="hidden" name="status" value="{{ $status }}">

            <!-- Bagian 1: Search Input (Sisi Kiri) -->
            <div class="relative flex-1 w-full flex items-center">
                <div class="absolute left-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, tag, atau deskripsi..." class="w-full py-3 pl-12 pr-4 bg-transparent border-none focus:ring-0 text-sm placeholder:text-gray-400 font-medium text-gray-900" onchange="$refs.searchForm.submit()">
            </div>

            <!-- Pemisah Vertikal Desktop -->
            <div class="hidden md:block w-[1px] h-10 bg-gray-100"></div>

            <!-- Bagian 2: Custom Dropdowns (Sisi Kanan) -->
            <div class="flex flex-col sm:flex-row w-full md:w-auto items-stretch sm:items-center gap-2 p-2 md:p-0 bg-gray-50 md:bg-transparent rounded-[16px] md:rounded-none">

                <!-- CUSTOM MULTI-SELECT KATEGORI -->
                <div x-data="{
                        open: false,
                        selected: {{ json_encode($selectedCategories) }},
                        remove(id) {
                            this.selected = this.selected.filter(i => i != id);
                            $nextTick(() => { $refs.searchForm.submit(); });
                        },
                        closeAndSubmit() {
                            this.open = false;
                            $refs.searchForm.submit();
                        }
                    }"
                    @click.away="open = false"
                    class="relative flex-1 md:w-72 lg:w-80"
                >
                    <!-- Trigger Area & Selected Pills -->
                    <div @click="open = !open" class="relative flex items-center w-full min-h-[46px] py-1.5 pl-10 pr-8 bg-transparent hover:bg-white md:hover:bg-gray-50 rounded-[16px] cursor-pointer transition border border-transparent hover:border-gray-200 md:hover:border-transparent">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>
                        </div>

                        <!-- Area Kapsul (Pills) -->
                        <div class="flex items-center gap-1.5 overflow-x-auto custom-scrollbar w-full py-0.5">
                            <span x-show="selected.length === 0" class="text-xs font-bold text-gray-500 whitespace-nowrap">Semua Kategori</span>

                            @foreach($masterCategories as $master)
                                @foreach($master->categories as $category)
                                    <span x-show="selected.includes('{{ $category->id }}')" style="display: none;" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white md:bg-gray-50 border border-gray-200 text-gray-700 rounded-[10px] text-[10px] font-bold shadow-sm whitespace-nowrap transition">
                                        {{ $category->name }}
                                        <!-- Tombol X pada Pill -->
                                        <div @click.stop="remove('{{ $category->id }}')" class="hover:bg-red-50 hover:text-red-500 text-gray-400 rounded-full transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                                        </div>
                                    </span>
                                @endforeach
                            @endforeach
                        </div>

                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6l6 -6"></path></svg>
                        </div>
                    </div>

                    <!-- Dropdown Panel Kategori -->
                    <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 top-full left-0 mt-3 w-full sm:w-80 bg-white border border-gray-100 rounded-[24px] shadow-[0_12px_40px_rgba(0,0,0,0.08)] overflow-hidden" style="display: none;">
                        <div class="max-h-72 overflow-y-auto p-3 custom-scrollbar">
                            @foreach($masterCategories as $master)
                                <div class="mb-3 last:mb-0">
                                    <div class="px-3 py-2 mb-1 bg-gray-50/80 rounded-[12px] flex items-center gap-2">
                                        <div class="w-5 h-5 bg-white rounded-md flex items-center justify-center text-gray-500 shadow-sm border border-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ $master->name }}</span>
                                    </div>
                                    <div class="space-y-0.5">
                                        @foreach($master->categories as $category)
                                            <label class="flex items-center gap-3 px-3 py-2.5 rounded-[14px] hover:bg-gray-50 cursor-pointer transition group">
                                                <div class="relative flex items-center justify-center w-5 h-5 rounded-[6px] border border-gray-300 group-hover:border-primary transition shrink-0" :class="selected.includes('{{ $category->id }}') ? 'bg-primary border-primary shadow-sm shadow-primary/30' : 'bg-white'">
                                                    <input type="checkbox" name="category[]" value="{{ $category->id }}" x-model="selected" class="sr-only">
                                                    <svg x-show="selected.includes('{{ $category->id }}')" style="display: none;" class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"></path></svg>
                                                </div>
                                                <span class="text-xs font-bold text-gray-700 group-hover:text-gray-900 transition" :class="selected.includes('{{ $category->id }}') ? 'text-primary' : ''">{{ $category->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-3 border-t border-gray-100 bg-white">
                            <button type="button" @click="closeAndSubmit()" class="w-full py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-xs font-bold rounded-[14px] transition shadow-sm">
                                Terapkan Filter Kategori
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pemisah Kecil Desktop -->
                <div class="hidden sm:block w-[1px] h-6 bg-gray-200"></div>

                <!-- CUSTOM DROPDOWN PRICING -->
                <div x-data="{
                        openPricing: false,
                        selectedPricing: '{{ request('pricing', '') }}',
                        selectPricing(val) {
                            this.selectedPricing = val;
                            this.openPricing = false;
                            $nextTick(() => $refs.searchForm.submit());
                        }
                    }"
                    @click.away="openPricing = false"
                    class="relative flex-1 md:w-44"
                >
                    <input type="hidden" name="pricing" :value="selectedPricing">

                    <div @click="openPricing = !openPricing" class="relative flex items-center w-full min-h-[46px] py-1.5 pl-10 pr-8 bg-transparent hover:bg-white md:hover:bg-gray-50 rounded-[16px] cursor-pointer transition border border-transparent hover:border-gray-200 md:hover:border-transparent">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1"></path><path d="M12 7v10"></path></svg>
                        </div>

                        <span class="text-xs font-bold text-gray-600 truncate" x-text="selectedPricing === 'free' ? 'Free (Gratis)' : (selectedPricing === 'freemium' ? 'Freemium' : (selectedPricing === 'paid' ? 'Paid (Berbayar)' : 'Semua Harga'))"></span>

                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform" :class="openPricing ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6l6 -6"></path></svg>
                        </div>
                    </div>

                    <!-- Dropdown Panel Pricing -->
                    <div x-show="openPricing" x-transition.opacity.duration.200ms class="absolute z-50 top-full right-0 mt-3 w-full sm:w-56 bg-white border border-gray-100 rounded-[20px] shadow-[0_12px_40px_rgba(0,0,0,0.08)] p-2 space-y-1 overflow-hidden" style="display: none;">
                        <div @click="selectPricing('')" class="px-4 py-2.5 rounded-[12px] text-xs font-bold cursor-pointer transition flex items-center justify-between group hover:bg-gray-50" :class="selectedPricing === '' ? 'text-gray-900 bg-gray-50' : 'text-gray-500'">
                            Semua Harga
                            <svg x-show="selectedPricing === ''" class="w-4 h-4 text-gray-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"></path></svg>
                        </div>
                        <div @click="selectPricing('free')" class="px-4 py-2.5 rounded-[12px] text-xs font-bold cursor-pointer transition flex items-center justify-between group hover:bg-emerald-50" :class="selectedPricing === 'free' ? 'text-emerald-700 bg-emerald-50' : 'text-gray-500'">
                            Free (Gratis)
                            <svg x-show="selectedPricing === 'free'" class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"></path></svg>
                        </div>
                        <div @click="selectPricing('freemium')" class="px-4 py-2.5 rounded-[12px] text-xs font-bold cursor-pointer transition flex items-center justify-between group hover:bg-blue-50" :class="selectedPricing === 'freemium' ? 'text-blue-700 bg-blue-50' : 'text-gray-500'">
                            Freemium
                            <svg x-show="selectedPricing === 'freemium'" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"></path></svg>
                        </div>
                        <div @click="selectPricing('paid')" class="px-4 py-2.5 rounded-[12px] text-xs font-bold cursor-pointer transition flex items-center justify-between group hover:bg-purple-50" :class="selectedPricing === 'paid' ? 'text-purple-700 bg-purple-50' : 'text-gray-500'">
                            Paid (Berbayar)
                            <svg x-show="selectedPricing === 'paid'" class="w-4 h-4 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Tombol Search / Submit Khusus Mobile -->
                <button type="submit" class="md:hidden w-full flex items-center justify-center gap-2 py-3 bg-gray-900 text-white rounded-[14px] hover:bg-gray-800 transition shadow-sm mt-2 font-bold text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                    Terapkan Pencarian
                </button>
            </div>
        </form>

        <!-- Tabs: Active vs Archived -->
        <div class="flex border-b border-gray-200">
            <a href="{{ route('websites.index', ['status' => 'active']) }}" class="px-6 py-3 text-sm font-bold border-b-2 transition-colors {{ $status === 'active' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-900' }}">
                Websites Aktif
            </a>
            <a href="{{ route('websites.index', ['status' => 'archived']) }}" class="px-6 py-3 text-sm font-bold border-b-2 transition-colors flex items-center gap-2 {{ $status === 'archived' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-900' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"></path><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10"></path><path d="M10 12l4 0"></path></svg>
                Arsip
            </a>
        </div>

        <!-- Toast Notification -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="flex items-center gap-3 p-4 text-sm font-medium text-emerald-800 bg-emerald-50 border border-emerald-100 rounded-[16px] shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- ============================================================== -->
        <!-- TAMPILAN 1: FOLDER VIEW (Dijalankan jika TIDAK ADA pencarian)  -->
        <!-- ============================================================== -->
        @if(!$isFiltering)
            <div class="space-y-8 mt-4">
                @php $hasAnyWebsite = false; @endphp

                @foreach ($masterCategories as $master)
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
                        $fullSvg = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $svgPaths . '</svg>';
                        $hexColor = $master->color ?? '#64748b'; 

                        // Kumpulkan website
                        $websitesInMaster = collect();
                        foreach($master->categories as $cat) {
                            foreach($cat->websites as $web) {
                                $web->category_name = $cat->name;
                                $websitesInMaster->push($web);
                            }
                        }
                        $websitesInMaster = $websitesInMaster->sortByDesc('created_at');
                    @endphp

                    @if($websitesInMaster->count() > 0)
                        @php $hasAnyWebsite = true; @endphp

                        <!-- PANEL ACCORDION BERAKSEN WARNA -->
                        <div x-data="{ expanded: true }" class="bg-white border border-gray-200 rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] overflow-hidden transition-all duration-300" style="border-top: 5px solid {{ $hexColor }}">
                            
                            <!-- Panel Header -->
                            <div @click="expanded = !expanded" class="px-6 py-5 flex items-center justify-between cursor-pointer hover:bg-gray-50/70 transition select-none">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-[14px] flex items-center justify-center shrink-0 border" style="background-color: {{ $hexColor }}15; color: {{ $hexColor }}; border-color: {{ $hexColor }}30;">
                                        {!! $fullSvg !!}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2.5">
                                            <h3 class="text-lg font-heading font-bold text-gray-900 leading-none">{{ $master->name }}</h3>
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-[8px] border" style="background-color: {{ $hexColor }}10; color: {{ $hexColor }}; border-color: {{ $hexColor }}30;">
                                                {{ $websitesInMaster->count() }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] font-medium text-gray-400 mt-1.5 uppercase tracking-wider">Folder Kategori</p>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6l6 -6"></path></svg>
                                </div>
                            </div>

                            <!-- Panel Body -->
                            <div x-show="expanded" x-transition class="bg-slate-50/50 border-t border-gray-100 p-6 sm:p-8">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                                    @foreach ($websitesInMaster as $website)
                                        @include('websites.partials.website-card', ['website' => $website])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if(!$hasAnyWebsite)
                    <div class="flex flex-col items-center justify-center p-16 text-center bg-white rounded-[24px] shadow-sm border border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-[20px] flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 11a7 7 0 0 1 14 0v7a1.78 1.78 0 0 1 -3.1 1.4a1.65 1.65 0 0 0 -2.6 0a1.65 1.65 0 0 1 -2.6 0a1.65 1.65 0 0 0 -2.6 0a1.78 1.78 0 0 1 -3.1 -1.4v-7"></path><path d="M10 10l.01 0"></path><path d="M14 10l.01 0"></path><path d="M10 14a3.5 3.5 0 0 0 4 0"></path></svg>
                        </div>
                        <h3 class="text-lg font-heading font-bold text-gray-900">{{ $status === 'archived' ? 'Tidak ada website yang diarsip.' : 'Library-mu masih kosong.' }}</h3>
                        <p class="mt-2 text-sm text-gray-500 mb-6 max-w-sm">Mulai bangun perpustakaan digital pribadimu dengan menyimpan website.</p>
                    </div>
                @endif
            </div>

        <!-- ============================================================== -->
        <!-- TAMPILAN 2: FLAT VIEW (Dijalankan JIKA sedang melakukan pencarian) -->
        <!-- ============================================================== -->
        @else
            <div class="mb-4 mt-6">
                <p class="text-sm text-gray-500 font-medium bg-blue-50 text-blue-700 px-4 py-3 rounded-[12px] inline-flex items-center gap-2 border border-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                    Menampilkan hasil pencarian.
                    <a href="{{ route('websites.index') }}" class="font-bold underline ml-1 hover:text-blue-900">Hapus Filter &times;</a>
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @forelse ($websites as $website)
                    @include('websites.partials.website-card', ['website' => $website])
                @empty
                    <div class="col-span-full p-16 text-center bg-white rounded-[24px] shadow-sm border border-gray-100">
                        <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-[20px] flex items-center justify-center mx-auto mb-4 border border-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Pencarian tidak ditemukan</h3>
                        <p class="mt-2 text-sm text-gray-500">Coba gunakan kata kunci atau ubah kombinasi filter.</p>
                        <a href="{{ route('websites.index') }}" class="mt-4 inline-block text-sm font-bold text-primary hover:underline">Reset Semua Filter</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $websites->links() }}
            </div>
        @endif

        <!-- Alpine.js Delete Modal -->
        <div x-show="showDeleteModal" x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-gray-900/40 backdrop-blur-sm">
            <div @click.away="showDeleteModal = false" class="w-full max-w-sm p-8 bg-white border border-gray-100 rounded-[24px] shadow-2xl">
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex items-center justify-center w-12 h-12 text-red-600 bg-red-50 rounded-[16px] shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"></path><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"></path><path d="M12 16h.01"></path></svg>
                    </div>
                    <h3 class="text-xl font-heading font-bold text-gray-900">Hapus Website</h3>
                </div>
                <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                    Yakin ingin menghapus <strong x-text="deleteName" class="text-gray-900"></strong>? Tindakan ini bersifat permanen.
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="showDeleteModal = false" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 border border-transparent rounded-[14px] hover:bg-gray-100 hover:text-gray-900 transition">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-[14px] hover:bg-red-700 shadow-sm shadow-red-200 transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7l16 0"></path><path d="M10 11l0 6"></path><path d="M14 11l0 6"></path><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
