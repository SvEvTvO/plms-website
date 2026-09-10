<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Koleksi Pribadi</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola semua referensi website yang telah kamu simpan di satu tempat.</p>
            </div>
            <a href="{{ route('bookmarks.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white font-bold text-sm rounded-xl shadow-sm hover:bg-primary-600 hover:-translate-y-0.5 transition-all w-full sm:w-auto">
                <i class="ti ti-square-plus text-lg"></i> Tambah Website
            </a>
        </div>
    </x-slot>

    <!-- Pesan Sukses -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600"><i class="ti ti-check text-lg"></i></div>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><i class="ti ti-x"></i></button>
        </div>
    @endif

    <!-- WRAPPER BESAR ALPINE.JS -->
    <div x-data="dashboardFilters()" class="pb-10">

        <div class="mb-8 relative z-30">
            <!-- 1. SMART SEARCH BAR -->
            <form x-ref="filterForm" @submit.prevent.stop="fetchResults()" class="relative mb-5" @click.away="showDropdown = false">
                <div class="bg-white border-2 border-slate-200 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/20 rounded-2xl p-2.5 flex items-center gap-2 shadow-sm transition-all min-h-[56px] flex-wrap relative z-20" @click="showDropdown = true; $refs.searchInput.focus()">
                    <i class="ti ti-search text-slate-400 text-xl pl-2"></i>

                    <template x-if="selectedCategory"><span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 rounded-lg text-xs font-bold shadow-sm"><i class="ti ti-folder"></i> <span x-text="selectedCategory.name"></span><button type="button" @click.stop="removeCategory()" class="hover:text-rose-800 ml-1 focus:outline-none"><i class="ti ti-x"></i></button></span></template>
                    <template x-for="(tag, index) in selectedTags" :key="tag.id"><span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 text-sky-600 border border-sky-200 rounded-lg text-xs font-bold shadow-sm"><i class="ti ti-hash"></i> <span x-text="tag.name"></span><button type="button" @click.stop="removeTag(index)" class="hover:text-sky-800 ml-1 focus:outline-none"><i class="ti ti-x"></i></button></span></template>
                    <template x-if="selectedWebsite"><span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-xs font-bold shadow-sm"><i class="ti ti-world"></i> <span x-text="selectedWebsite.name"></span><button type="button" @click.stop="removeWebsite()" class="hover:text-emerald-800 ml-1 focus:outline-none"><i class="ti ti-x"></i></button></span></template>

                    <input x-ref="searchInput" type="text" x-model="query" @focus="showDropdown = true" placeholder="Cari di koleksi pribadimu..." class="flex-1 min-w-[200px] border-none bg-transparent p-1 focus:ring-0 text-sm text-slate-700 placeholder-slate-400 h-8">
                    <button type="submit" class="bg-primary hover:bg-primary-600 text-white font-bold text-sm px-5 py-2 rounded-xl transition-colors shadow-sm shrink-0 flex items-center gap-2"><span x-show="!isLoading">Cari</span><i x-show="isLoading" class="ti ti-loader animate-spin"></i></button>
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

        <!-- GRID KONTEN ASLI (DASBOR PRIBADI) -->
        <div x-show="!isLoading" id="ajax-content-wrapper">
            <div id="grid-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 relative z-0">
                @forelse($bookmarks as $bookmark)
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col h-full relative">

                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 p-1.5 shadow-inner">
                                @if($bookmark->website->icon_url) <img src="{{ $bookmark->website->icon_url }}" alt="Icon" class="w-full h-full object-contain rounded-md"> @else <i class="ti ti-world text-xl text-slate-400"></i> @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if($bookmark->is_public)
                                    <div class="flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-md text-[10px] font-extrabold uppercase tracking-wider border border-emerald-200 shadow-sm cursor-help" title="Sudah tampil di Komunitas Publik"><i class="ti ti-world"></i> Dipublish</div>
                                @else
                                    <div class="flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-md text-[10px] font-extrabold uppercase tracking-wider border border-slate-200 shadow-sm cursor-help" title="Hanya kamu yang bisa melihat ini"><i class="ti ti-lock"></i> Pribadi</div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-5 flex-1 flex flex-col">
                            <h3 class="font-bold text-slate-900 text-lg leading-tight mb-2 hover:text-primary transition-colors line-clamp-1">{{ $bookmark->custom_title ?? $bookmark->website->title }}</h3>
                            <p class="text-sm text-slate-500 line-clamp-2 leading-relaxed mb-4">{{ $bookmark->website->description ?? 'Tidak ada deskripsi tersedia untuk website ini.' }}</p>

                            <!-- BADGE HARGA & LISENSI LENGKAP -->
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @if($bookmark->pricing_type === 'free')
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-extrabold uppercase tracking-wider rounded border border-emerald-100 flex items-center gap-1"><i class="ti ti-free-rights text-xs"></i> Gratis</span>
                                @elseif($bookmark->pricing_type === 'freemium')
                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[10px] font-extrabold uppercase tracking-wider rounded border border-amber-100 flex items-center gap-1"><i class="ti ti-star-half-filled text-xs"></i> Freemium</span>
                                @elseif($bookmark->pricing_type === 'premium')
                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-[10px] font-extrabold uppercase tracking-wider rounded border border-rose-100 flex items-center gap-1"><i class="ti ti-diamond text-xs"></i> Premium</span>
                                @endif

                                @if($bookmark->payment_model === 'subscription')
                                    <span class="px-2 py-0.5 bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider rounded border border-slate-200"><i class="ti ti-calendar-repeat"></i> Langganan</span>
                                @elseif($bookmark->payment_model === 'one_time')
                                    <span class="px-2 py-0.5 bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider rounded border border-slate-200"><i class="ti ti-cash"></i> Sekali Bayar</span>
                                @endif

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

                        <!-- FOOTER KARTU -->
                        <div class="pt-4 border-t border-slate-100 mt-auto flex items-center justify-between gap-3 relative z-10">
                            <div class="flex items-center gap-2">
                                <!-- Tombol Edit (Baru) -->
                                <a href="{{ route('bookmarks.edit', $bookmark->id) }}" class="px-3 py-1.5 bg-amber-50 text-amber-600 hover:text-white hover:bg-amber-500 text-xs font-bold rounded-lg transition-colors flex items-center gap-1 border border-amber-200 shadow-sm" title="Edit Website">
                                    <i class="ti ti-edit text-sm"></i>
                                </a>

                                <!-- Tombol Publish / Unpublish -->
                                @if(!$bookmark->is_public)
                                    <button type="button" @click="publishModal.open({{ $bookmark->id }}, '{{ addslashes($bookmark->custom_title ?? $bookmark->website->title) }}', {{ $bookmark->tags->pluck('name')->toJson() }})" class="px-4 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5 border border-indigo-200 shadow-sm"><i class="ti ti-rocket text-sm"></i> Publish ke Publik</button>
                                @else
                                    <form action="{{ route('bookmarks.unpublish', $bookmark->id) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 bg-slate-50 text-slate-500 hover:text-rose-600 text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5 border border-slate-200 hover:border-rose-200 hover:bg-rose-50 shadow-sm"><i class="ti ti-world-pause text-sm"></i> Batalkan Publikasi</button>
                                    </form>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ $bookmark->website->original_url }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-500 flex items-center justify-center hover:bg-primary hover:text-white transition-colors border border-slate-200 shadow-sm" title="Kunjungi Website"><i class="ti ti-external-link text-base"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white rounded-2xl border border-slate-200 p-12 flex flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mb-4"><i class="ti ti-bookmarks text-3xl"></i></div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Koleksi Masih Kosong</h3>
                            <p class="text-sm text-slate-500 max-w-sm mb-6">Belum ada website yang disimpan dengan kriteria tersebut.</p>
                            <a href="{{ route('bookmarks.create') }}" class="px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-primary-600 transition-colors">Mulai Simpan Website</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div id="pagination-container" class="mt-8">
                {{ $bookmarks->links() }}
            </div>
        </div>

        <!-- MODAL PUBLISH LENGKAP -->

        <div x-show="publishModal.isOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
            <div x-show="publishModal.isOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="publishModal.close()"></div>

            <!-- Ditambahkan max-w-2xl, max-h-[90vh], dan flex-col -->
            <div x-show="publishModal.isOpen" x-transition.scale.origin.bottom class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-100 z-50 flex flex-col max-h-[90vh]">

                <!-- HEADER (Tetap / Tidak ikut ter-scroll) -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50 shrink-0">
                    <h3 class="font-extrabold text-lg text-slate-800">Publish ke Publik</h3>
                    <button type="button" @click="publishModal.close()" class="text-slate-400 hover:text-rose-500"><i class="ti ti-x text-xl"></i></button>
                </div>

                <!-- FORM (Area yang bisa di-scroll) -->
                <form :action="'/bookmarks/' + publishModal.bookmarkId + '/publish'" method="POST" class="p-5 sm:p-6 space-y-5 overflow-y-auto custom-scrollbar">
                    @csrf @method('PATCH')

                    <p class="text-xs text-slate-500 leading-relaxed">Website ini akan diindeks di Komunitas Publik. Pastikan data di bawah sudah benar.</p>

                    <!-- Judul Custom -->
                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Judul Publikasi <span class="text-rose-500">*</span></label>
                        <input type="text" name="custom_title" required x-model="publishModal.bookmarkTitle" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary text-sm h-12">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Kategori Global <span class="text-rose-500">*</span></label>
                        <select name="category_id" required x-model="publishModal.categoryId" @change="publishModal.loadCategoryTags()" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary text-sm h-12">
                            <option value="" disabled selected>Pilih Kategori Komunitas...</option>
                            @foreach($adminCategories as $cat)
                                <option value="{{ $cat->id }}">[{{ $cat->group->name ?? 'Lainnya' }}] - {{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bubble Tags UI -->
                    <div class="bg-primary-50/40 p-4 rounded-xl border border-primary-100">
                        <label class="block font-bold text-xs text-slate-600 uppercase mb-2">Tags (Max: 5)</label>
                        <div x-show="!publishModal.categoryId" class="text-[11px] text-amber-600 font-semibold mb-1">Pilih kategori di atas terlebih dahulu.</div>

                        <div x-show="publishModal.categoryId" style="display: none;">

                            <!-- Ditambahkan max-h-56 dan overflow-y-auto khusus untuk kotak bubble tag -->
                            <div class="flex flex-wrap gap-2 mb-3 max-h-56 overflow-y-auto custom-scrollbar p-1">
                                <template x-for="tag in publishModal.availableTags" :key="tag.name">
                                    <button type="button" @click="publishModal.toggleTag(tag.name)"
                                        class="px-2.5 py-1 rounded-lg border text-[11px] font-bold transition-all focus:outline-none"
                                        :class="publishModal.tags.includes(tag.name) ? 'bg-primary text-white border-primary shadow-md scale-105' : 'bg-white text-slate-600 border-slate-200 hover:border-primary'">
                                        <span x-text="tag.name"></span>
                                    </button>
                                </template>
                            </div>

                            <div class="relative flex items-center mt-2">
                                <input type="text" x-model="publishModal.newTag" @keydown.enter.prevent="publishModal.addNewTag()" @keydown.comma.prevent="publishModal.addNewTag()" placeholder="Tambah tag baru..." class="w-full pl-3 pr-20 bg-white rounded-lg border-slate-200 focus:border-primary text-xs h-10" :disabled="publishModal.tags.length >= 5">
                                <button type="button" @click="publishModal.addNewTag()" :disabled="publishModal.tags.length >= 5 || publishModal.newTag.trim() === ''" class="absolute right-1 px-3 py-1.5 bg-slate-500 text-white text-[10px] font-bold rounded shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">Tambah</button>
                            </div>

                            <template x-for="(tag, index) in publishModal.tags" :key="index">
                                <input type="hidden" name="tags[]" :value="tag">
                            </template>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-md hover:bg-indigo-700 transition-all flex justify-center items-center gap-2">
                            <i class="ti ti-rocket text-lg"></i> Mengudara Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- SCRIPT ALPINE.JS AJAX & FILTER PINTAR -->
    <script>
        function dashboardFilters() {
            return {
                searchData: @json($searchData['categories']).concat(@json($searchData['tags'])).concat(@json($searchData['websites'])),
                selectedCategory: @json($selectedCategory), selectedGroup: @json($selectedGroup), activeGroup: @json($selectedGroup), selectedTags: @json($selectedTags), selectedWebsite: @json($selectedWebsite), query: '{{ $searchQueryText }}', showDropdown: false, isLoading: false,
                publishModal: {
                    isOpen: false, bookmarkId: null, bookmarkTitle: '',
                    categoryId: '', tags: [], availableTags: [], newTag: '',
                    allAdminTags: @json($adminTags ?? []),

                    open(id, title, existingTags) {
                        this.bookmarkId = id;
                        this.bookmarkTitle = title;
                        this.categoryId = '';
                        this.tags = existingTags || [];
                        this.availableTags = [];
                        this.newTag = '';
                        this.isOpen = true;
                        document.body.style.overflow = 'hidden';
                    },
                    close() {
                        this.isOpen = false;
                        setTimeout(() => { this.bookmarkId = null; }, 300);
                        document.body.style.overflow = '';
                    },
                    loadCategoryTags() {
                        if (!this.categoryId) return;
                        this.availableTags = this.allAdminTags.filter(t => t.category_id == this.categoryId);
                        // Gabungkan tag pribadi ke list agar bisa diklik/dipilih
                        this.tags.forEach(t => {
                            if (!this.availableTags.some(at => at.name.toLowerCase() === t.toLowerCase())) {
                                this.availableTags.push({ name: t });
                            }
                        });
                    },
                    toggleTag(tagName) {
                        if (this.tags.includes(tagName)) { this.tags = this.tags.filter(t => t !== tagName); }
                        else { if (this.tags.length < 5) { this.tags.push(tagName); } else { alert('Maksimal 5 Tag!'); } }
                    },
                    addNewTag() {
                        let tagText = this.newTag.trim();
                        if (tagText !== '') {
                            if (tagText.startsWith('#')) tagText = tagText.substring(1).trim();
                            if (!this.availableTags.some(at => at.name.toLowerCase() === tagText.toLowerCase())) { this.availableTags.push({ name: tagText }); }
                            if (!this.tags.some(t => t.toLowerCase() === tagText.toLowerCase()) && this.tags.length < 5) { this.tags.push(tagText); }
                        }
                        this.newTag = '';
                    }
                },
                init() {
                    this.$el.addEventListener('click', (e) => { const link = e.target.closest('#pagination-container a'); if (link) { e.preventDefault(); this.fetchResults(link.href); } });
                    window.addEventListener('popstate', () => { window.location.reload(); });
                },
                get filteredSuggestions() {
                    const lowerQuery = this.query.toLowerCase().trim();
                    if (lowerQuery === '') { if (this.selectedCategory) return this.searchData.filter(item => item.type === 'tag' && item.category_slug === this.selectedCategory.id && !this.selectedTags.some(t => t.id === item.id)); return []; }
                    return this.searchData.filter(item => {
                        const matchName = item.name.toLowerCase().includes(lowerQuery);
                        const notSelectedCat = !(this.selectedCategory && this.selectedCategory.id === item.id && item.type === 'category');
                        const notSelectedTag = !this.selectedTags.some(t => t.id === item.id && item.type === 'tag');
                        const notSelectedWeb = !(this.selectedWebsite && this.selectedWebsite.id === item.id && item.type === 'website');
                        let allowedContext = true;
                        if (this.selectedCategory) { if (item.type === 'category') allowedContext = false; if (item.type === 'tag' && item.category_slug !== this.selectedCategory.id) allowedContext = false; }
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
                    let url = targetUrl ? new URL(targetUrl) : new URL('{{ route('dashboard') }}', window.location.origin);
                    if(!targetUrl) {
                        if (this.selectedGroup && !this.selectedCategory) url.searchParams.append('group', this.selectedGroup);
                        if (this.selectedCategory) url.searchParams.append('category', this.selectedCategory.id);
                        if (this.selectedWebsite) url.searchParams.append('website', this.selectedWebsite.id);
                        if (this.query.trim() !== '') url.searchParams.append('q', this.query.trim());
                        this.selectedTags.forEach(tag => { url.searchParams.append('tags[]', tag.id); });
                    }
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(res => res.text()).then(html => {
                        const parser = new DOMParser(); const doc = parser.parseFromString(html, 'text/html');
                        const newGrid = doc.getElementById('grid-container'); if (newGrid) document.getElementById('grid-container').innerHTML = newGrid.innerHTML;
                        const newPagination = doc.getElementById('pagination-container'); const oldPagination = document.getElementById('pagination-container');
                        if(newPagination && oldPagination) { oldPagination.innerHTML = newPagination.innerHTML; } else if (oldPagination) { oldPagination.innerHTML = ''; }
                        window.history.pushState({}, '', url); window.scrollTo({ top: 0, behavior: 'smooth' }); setTimeout(() => { this.isLoading = false; }, 200);
                    }).catch(err => { console.error('AJAX Error:', err); this.isLoading = false; });
                }
            }
        }
    </script>
</x-app-layout>
