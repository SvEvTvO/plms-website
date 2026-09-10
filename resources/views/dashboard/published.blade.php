<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Publikasi Saya</h2>
                <p class="text-sm text-slate-500 mt-1">Daftar website milikmu yang telah dibagikan dan berkontribusi ke Komunitas Publik.</p>
            </div>
        </div>
    </x-slot>

    <!-- WRAPPER BESAR ALPINE.JS -->
    <div x-data="publishedFilters()" class="pb-10">

        <div class="mb-8 relative z-30">
            <!-- 1. SMART SEARCH BAR -->
            <form x-ref="filterForm" @submit.prevent.stop="fetchResults()" class="relative mb-5" @click.away="showDropdown = false">
                <div class="bg-white border-2 border-slate-200 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/20 rounded-2xl p-2.5 flex items-center gap-2 shadow-sm transition-all min-h-[56px] flex-wrap relative z-20" @click="showDropdown = true; $refs.searchInput.focus()">
                    <i class="ti ti-search text-slate-400 text-xl pl-2"></i>

                    <template x-if="selectedCategory"><span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 rounded-lg text-xs font-bold shadow-sm"><i class="ti ti-folder"></i> <span x-text="selectedCategory.name"></span><button type="button" @click.stop="removeCategory()" class="hover:text-rose-800 ml-1 focus:outline-none"><i class="ti ti-x"></i></button></span></template>
                    <template x-for="(tag, index) in selectedTags" :key="tag.id"><span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 text-sky-600 border border-sky-200 rounded-lg text-xs font-bold shadow-sm"><i class="ti ti-hash"></i> <span x-text="tag.name"></span><button type="button" @click.stop="removeTag(index)" class="hover:text-sky-800 ml-1 focus:outline-none"><i class="ti ti-x"></i></button></span></template>
                    <template x-if="selectedWebsite"><span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-xs font-bold shadow-sm"><i class="ti ti-world"></i> <span x-text="selectedWebsite.name"></span><button type="button" @click.stop="removeWebsite()" class="hover:text-emerald-800 ml-1 focus:outline-none"><i class="ti ti-x"></i></button></span></template>

                    <input x-ref="searchInput" type="text" x-model="query" @focus="showDropdown = true" placeholder="Cari di daftar publikasimu..." class="flex-1 min-w-[200px] border-none bg-transparent p-1 focus:ring-0 text-sm text-slate-700 placeholder-slate-400 h-8">
                    <button type="submit" class="bg-primary hover:bg-primary-600 text-white font-bold text-sm px-5 py-2 rounded-xl transition-colors shadow-sm shrink-0 flex items-center gap-2">
                        <span x-show="!isLoading">Cari</span><i x-show="isLoading" class="ti ti-loader animate-spin"></i>
                    </button>
                </div>

                <!-- Dropdown Rekomendasi -->
                <div x-show="showDropdown && filteredSuggestions.length > 0" x-transition.opacity class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 shadow-xl rounded-2xl overflow-hidden z-30 max-h-64 overflow-y-auto custom-scrollbar">
                    <ul>
                        <template x-for="item in filteredSuggestions" :key="item.type + item.id">
                            <li @click="selectItem(item)" class="px-5 py-3 hover:bg-slate-50 cursor-pointer flex items-center justify-between border-b border-slate-100 last:border-0 transition-colors">
                                <span class="font-semibold text-slate-700 text-sm" x-text="item.name"></span>
                                <template x-if="item.type === 'category'"><span class="px-2.5 py-1 bg-rose-50 text-rose-600 text-[10px] font-extrabold uppercase tracking-widest rounded-md border border-rose-100">Kategori</span></template>
                                <template x-if="item.type === 'tag'"><span class="px-2.5 py-1 bg-sky-50 text-sky-600 text-[10px] font-extrabold uppercase tracking-widest rounded-md border border-sky-100">Tag</span></template>
                                <template x-if="item.type === 'website'"><span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-extrabold uppercase tracking-widest rounded-md border border-emerald-100">Website</span></template>
                            </li>
                        </template>
                    </ul>
                </div>
            </form>

            <!-- 2. MENU KATEGORI BESAR -->
            <div class="flex overflow-x-auto pb-3 gap-3 custom-scrollbar relative z-10">
                <button type="button" @click="clearFilters()" :class="!selectedCategory && selectedTags.length === 0 && !selectedWebsite && !selectedGroup && query === '' ? 'bg-primary text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-colors">🔥 Semua</button>
                @foreach($groupedCategories as $group)
                    <button type="button" @click="toggleGroup('{{ $group->name }}')" :class="activeGroup === '{{ $group->name }}' ? 'bg-slate-800 text-white shadow-sm border-slate-800' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-colors flex items-center gap-2">
                        @if(isset($group->icon) && $group->icon) <i class="ti ti-{{ $group->icon }}"></i> @endif {{ $group->name }} <i class="ti ti-chevron-down text-xs transition-transform" :class="activeGroup === '{{ $group->name }}' ? 'rotate-180' : ''"></i>
                    </button>
                @endforeach
            </div>

            <!-- 3. SUB-KATEGORI -->
            <div x-show="activeGroup" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm mt-1 mb-6 relative z-10">
                @foreach($groupedCategories as $group)
                    <div x-show="activeGroup === '{{ $group->name }}'" class="flex flex-wrap gap-2.5">
                        <button type="button" @click="selectGroupOnly('{{ $group->name }}')" :class="selectedGroup === '{{ $group->name }}' && !selectedCategory ? 'bg-slate-800 text-white shadow-sm border-slate-800' : 'bg-slate-50 text-slate-600 border border-slate-100 hover:bg-slate-100 hover:border-slate-200'" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2"><i class="ti ti-layout-grid text-base"></i> Semua di Grup</button>
                        @foreach($group->categories as $cat)
                            <button type="button" @click="selectFromMenu('{{ $cat->slug }}', '{{ addslashes($cat->name) }}', '{{ $group->name }}')" :class="selectedCategory && selectedCategory.id === '{{ $cat->slug }}' ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-slate-50 text-slate-600 border border-slate-100 hover:bg-slate-100 hover:border-slate-200'" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2"><i class="ti ti-{{ $cat->icon }} text-base"></i> {{ $cat->name }}</button>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <!-- SKELETON LOADING -->
        <div x-show="isLoading" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 relative z-0">
            <template x-for="i in 6" :key="i">
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col h-full animate-pulse min-h-[240px]">
                    <div class="flex items-start justify-between mb-4"><div class="w-12 h-12 rounded-xl bg-slate-200"></div><div class="w-12 h-6 rounded-md bg-slate-200"></div></div>
                    <div class="mb-5 flex-1"><div class="h-5 bg-slate-200 rounded w-3/4 mb-3"></div><div class="h-3 bg-slate-200 rounded w-full mb-2"></div><div class="h-3 bg-slate-200 rounded w-5/6 mb-4"></div></div>
                </div>
            </template>
        </div>

        <!-- GRID KONTEN ASLI (PUBLIKASI) -->
        <div x-show="!isLoading" id="ajax-content-wrapper">
            <div id="grid-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 relative z-0">
                @forelse($bookmarks as $bookmark)
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col h-full relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 p-1.5 shadow-inner">
                                @if($bookmark->website->icon_url) <img src="{{ $bookmark->website->icon_url }}" alt="Icon" class="w-full h-full object-contain rounded-md"> @else <i class="ti ti-world text-xl text-slate-400"></i> @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-md text-[10px] font-extrabold uppercase tracking-wider border border-emerald-200 shadow-sm cursor-help" title="Dibagikan ke Komunitas Publik"><i class="ti ti-world"></i> Dipublish</div>
                            </div>
                        </div>

                        <div class="mb-5 flex-1 flex flex-col">
                            <h3 class="font-bold text-slate-900 text-lg leading-tight mb-2 hover:text-primary transition-colors line-clamp-1">{{ $bookmark->custom_title ?? $bookmark->website->title }}</h3>
                            <p class="text-sm text-slate-500 line-clamp-2 leading-relaxed mb-4">{{ $bookmark->website->description ?? 'Tidak ada deskripsi tersedia untuk website ini.' }}</p>

                            <!-- BADGE HARGA & LISENSI -->
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <!-- Status Lisensi -->
                                @if($bookmark->pricing_type === 'free')
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-extrabold uppercase tracking-wider rounded border border-emerald-100 flex items-center gap-1"><i class="ti ti-free-rights text-xs"></i> Gratis</span>
                                @elseif($bookmark->pricing_type === 'freemium')
                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[10px] font-extrabold uppercase tracking-wider rounded border border-amber-100 flex items-center gap-1"><i class="ti ti-star-half-filled text-xs"></i> Freemium</span>
                                @elseif($bookmark->pricing_type === 'premium')
                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-[10px] font-extrabold uppercase tracking-wider rounded border border-rose-100 flex items-center gap-1"><i class="ti ti-diamond text-xs"></i> Premium</span>
                                @endif

                                <!-- Tipe Pembayaran -->
                                @if($bookmark->payment_model === 'subscription')
                                    <span class="px-2 py-0.5 bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider rounded border border-slate-200"><i class="ti ti-calendar-repeat"></i> Langganan</span>
                                @elseif($bookmark->payment_model === 'one_time')
                                    <span class="px-2 py-0.5 bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider rounded border border-slate-200"><i class="ti ti-cash"></i> Sekali Bayar</span>
                                @endif

                                <!-- Range Harga -->
                                @if($bookmark->price_range)
                                    <span class="px-2 py-0.5 bg-slate-50 text-slate-500 text-[10px] font-bold tracking-wider rounded border border-slate-200">{{ $bookmark->price_range }}</span>
                                @endif
                            </div>

                            @if($bookmark->tags->count() > 0)
                                <div class="flex flex-wrap gap-1.5 mb-4 border-t border-slate-100 pt-3">
                                    @foreach($bookmark->tags->take(4) as $tag) <span class="px-2 py-0.5 rounded bg-sky-50 text-sky-600 border border-sky-100 text-[10px] font-bold hover:bg-primary hover:text-white transition-colors cursor-default">#{{ $tag->name }}</span> @endforeach
                                    @if($bookmark->tags->count() > 4) <span class="px-2 py-0.5 rounded bg-slate-50 text-slate-500 border border-slate-200 text-[10px] font-bold">+{{ $bookmark->tags->count() - 4 }}</span> @endif
                                </div>
                            @endif

                            <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-auto">
                                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border" style="background-color: {{ $bookmark->category->color }}15; color: {{ $bookmark->category->color }}; border-color: {{ $bookmark->category->color }}30;">
                                    <i class="ti ti-{{ $bookmark->category->icon }} text-xs"></i> {{ $bookmark->category->name }}
                                </div>
                                <span class="text-[10px] font-semibold text-slate-400">{{ $bookmark->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 mt-auto flex items-center justify-between gap-3 relative z-10">
                            <div>
                                <form action="{{ route('bookmarks.unpublish', $bookmark->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 bg-slate-50 text-slate-500 hover:text-rose-600 text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5 border border-slate-200 hover:border-rose-200 hover:bg-rose-50 shadow-sm"><i class="ti ti-world-pause text-sm"></i> Batalkan Publikasi</button>
                                </form>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ $bookmark->website->original_url }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-500 flex items-center justify-center hover:bg-primary hover:text-white transition-colors border border-slate-200 shadow-sm" title="Kunjungi Website"><i class="ti ti-external-link text-base"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white rounded-2xl border border-slate-200 p-12 flex flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mb-4"><i class="ti ti-rocket text-3xl"></i></div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Publikasi</h3>
                            <p class="text-sm text-slate-500 max-w-sm mb-6">Kamu belum membagikan satupun website ke komunitas publik.</p>
                            <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-primary-600 transition-colors">Lihat Dasbor Pribadi</a>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <div id="pagination-container" class="mt-8">
                {{ $bookmarks->links() }}
            </div>
        </div>
    </div>

    <!-- SCRIPT ALPINE.JS -->
    <script>
        function publishedFilters() {
            return {
                searchData: @json($searchData['categories']).concat(@json($searchData['tags'])).concat(@json($searchData['websites'])),
                selectedCategory: @json($selectedCategory), selectedGroup: @json($selectedGroup), activeGroup: @json($selectedGroup), selectedTags: @json($selectedTags), selectedWebsite: @json($selectedWebsite), query: '{{ $searchQueryText }}', showDropdown: false, isLoading: false,

                init() {
                    this.$el.addEventListener('click', (e) => { const link = e.target.closest('#pagination-container a'); if (link) { e.preventDefault(); this.fetchResults(link.href); } });
                    window.addEventListener('popstate', () => { window.location.reload(); });
                },

                get filteredSuggestions() {
                    const lowerQuery = this.query.toLowerCase().trim();
                    if (lowerQuery === '') {
                        if (this.selectedCategory) return this.searchData.filter(item => item.type === 'tag' && item.category_slug === this.selectedCategory.id && !this.selectedTags.some(t => t.id === item.id));
                        return [];
                    }
                    return this.searchData.filter(item => {
                        const matchName = item.name.toLowerCase().includes(lowerQuery);
                        const notSelectedCat = !(this.selectedCategory && this.selectedCategory.id === item.id && item.type === 'category');
                        const notSelectedTag = !this.selectedTags.some(t => t.id === item.id && item.type === 'tag');
                        const notSelectedWeb = !(this.selectedWebsite && this.selectedWebsite.id === item.id && item.type === 'website');

                        let allowedContext = true;
                        if (this.selectedCategory) {
                            if (item.type === 'category') allowedContext = false;
                            if (item.type === 'tag' && item.category_slug !== this.selectedCategory.id) allowedContext = false;
                        }
                        return matchName && notSelectedCat && notSelectedTag && notSelectedWeb && allowedContext;
                    }).slice(0, 8);
                },

                selectItem(item) {
                    if (item.type === 'category') { this.selectedCategory = item; this.selectedGroup = item.group_name; this.activeGroup = item.group_name; this.selectedTags = []; this.selectedWebsite = null; } 
                    else if (item.type === 'tag') { this.selectedTags.push(item); this.selectedWebsite = null; } 
                    else if (item.type === 'website') { this.selectedWebsite = item; this.selectedCategory = null; this.selectedGroup = null; this.activeGroup = null; this.selectedTags = []; }
                    this.query = ''; this.showDropdown = false; this.$refs.searchInput.focus();
                },

                removeCategory() { this.selectedCategory = null; this.selectedTags = []; this.fetchResults(); },
                removeTag(index) { this.selectedTags.splice(index, 1); this.fetchResults(); },
                removeWebsite() { this.selectedWebsite = null; this.fetchResults(); },
                toggleGroup(groupName) { if (this.activeGroup === groupName) { this.activeGroup = null; this.selectedGroup = null; } else { this.activeGroup = groupName; this.selectedGroup = groupName; } this.selectedCategory = null; this.selectedTags = []; this.selectedWebsite = null; this.fetchResults(); },
                selectGroupOnly(groupName) { this.selectedGroup = groupName; this.selectedCategory = null; this.selectedTags = []; this.selectedWebsite = null; this.fetchResults(); },
                selectFromMenu(id, name, groupName) { this.selectedCategory = { id: id, name: name, type: 'category' }; this.selectedGroup = groupName; this.selectedTags = []; this.selectedWebsite = null; this.fetchResults(); },
                clearFilters() { this.selectedCategory = null; this.selectedTags = []; this.selectedWebsite = null; this.query = ''; this.selectedGroup = null; this.activeGroup = null; this.fetchResults(); },

                fetchResults(targetUrl = null) {
                    this.isLoading = true; this.showDropdown = false;
                    let url = targetUrl ? new URL(targetUrl) : new URL('{{ route('dashboard.published') }}', window.location.origin);
                    if (!targetUrl) {
                        if (this.selectedGroup && !this.selectedCategory) url.searchParams.append('group', this.selectedGroup);
                        if (this.selectedCategory) url.searchParams.append('category', this.selectedCategory.id);
                        if (this.selectedWebsite) url.searchParams.append('website', this.selectedWebsite.id);
                        if (this.query.trim() !== '') url.searchParams.append('q', this.query.trim());
                        this.selectedTags.forEach(tag => { url.searchParams.append('tags[]', tag.id); });
                    }

                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser(); const doc = parser.parseFromString(html, 'text/html');
                        const newGrid = doc.getElementById('grid-container'); if (newGrid) document.getElementById('grid-container').innerHTML = newGrid.innerHTML;
                        const newPagination = doc.getElementById('pagination-container'); const oldPagination = document.getElementById('pagination-container');
                        if(newPagination && oldPagination) { oldPagination.innerHTML = newPagination.innerHTML; } else if (oldPagination) { oldPagination.innerHTML = ''; }
                        window.history.pushState({}, '', url); window.scrollTo({ top: 0, behavior: 'smooth' }); 
                        setTimeout(() => { this.isLoading = false; }, 200);
                    }).catch(err => { console.error('AJAX Error:', err); this.isLoading = false; });
                }
            }
        }
    </script>
</x-app-layout>
