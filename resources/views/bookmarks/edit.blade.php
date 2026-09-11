<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6 relative">

        <div class="absolute top-0 right-0 -mr-24 -mt-10 w-72 h-72 rounded-full bg-amber-100/50 blur-3xl pointer-events-none"></div>

        <div class="mb-4 relative z-10">
            <h2 class="text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">Edit Website</h2>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi, kategori, atau tag dari website yang sudah kamu simpan.</p>
        </div>

        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/40 border border-slate-100 relative z-10">

            @php
                $alpineCategories = $categories->map(fn($cat) => ['id' => $cat->id, 'name' => $cat->name, 'group' => $cat->group ? $cat->group->name : 'Kategori Lainnya', 'icon' => $cat->icon ?? 'folder'])->values();
                $alpineTags = isset($allTags) ? $allTags->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'category_id' => $t->category_id])->values() : [];
                $alpineGroups = isset($groups) ? $groups->map(fn($g) => ['id' => $g->id, 'name' => $g->name])->values() : [];
            @endphp

            <form action="{{ route('bookmarks.update', $bookmark->id) }}" method="POST" x-data="bookmarkForm()" x-init="initSetup()">
                @csrf
                @method('PUT')

                <!-- =========================== -->
                <!-- 1. URL INPUT                -->
                <!-- =========================== -->
                <div class="mb-8">
                    <label for="url" class="block font-bold text-xs tracking-wider text-slate-500 uppercase mb-2">1. Link URL <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="ti ti-link text-slate-400 text-lg"></i></div>
                        <input type="url" name="url" id="url" required value="{{ old('url', $bookmark->website->original_url) }}" class="w-full pl-11 bg-slate-50 rounded-2xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm h-12 transition-colors">
                    </div>
                    @error('url') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

                    <!-- =========================== -->
                    <!-- 2. KATEGORI (INLINE CREATE) -->
                    <!-- =========================== -->
                    <div>
                        <label class="block font-bold text-xs tracking-wider text-slate-500 uppercase mb-2">2. Pilih Kategori <span class="text-rose-500">*</span></label>

                        <input type="hidden" name="category_id" :value="categoryId">
                        <input type="hidden" name="new_category_name" :value="newCategoryName">
                        <input type="hidden" name="category_icon" :value="categoryIcon">
                        <input type="hidden" name="group_id" :value="groupId">
                        <input type="hidden" name="new_group_name" :value="newGroupName">
                        <input type="hidden" name="group_icon" :value="groupIcon">

                        <div x-show="!isCreatingCategory" class="relative" @click.away="closeCategoryDropdown()">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors" :class="isCategoryOpen ? 'text-primary' : 'text-slate-400'">
                                    <i class="ti ti-search text-lg" x-show="isCategoryOpen"></i>
                                    <i class="ti ti-folder text-lg" x-show="!isCategoryOpen"></i>
                                </div>
                                <input type="text" x-model="categorySearch" @focus="isCategoryOpen = true; categorySearch = ''" placeholder="Cari kategori..." class="w-full pl-11 pr-10 bg-slate-50 rounded-2xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm h-12 transition-colors font-semibold text-slate-700">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400"><i class="ti ti-chevron-down transition-transform" :class="{'rotate-180 text-primary': isCategoryOpen}"></i></div>
                            </div>

                            <div x-show="isCategoryOpen" style="display: none;" class="absolute z-50 w-full mt-2 bg-white border border-slate-200 shadow-xl rounded-2xl overflow-hidden max-h-72 overflow-y-auto custom-scrollbar pb-2">
                                <div x-show="Object.keys(groupedFilteredCategories).length === 0" class="p-2">
                                    <p class="text-xs text-slate-500 px-3 py-2">Kategori tidak ditemukan.</p>
                                    <button type="button" @click="startCreateCategory()" class="w-full text-left px-3 py-2.5 rounded-xl text-sm font-bold text-primary bg-primary-50 hover:bg-primary-100 transition-colors flex items-center gap-2">
                                        <i class="ti ti-plus text-lg"></i> Buat Kategori "<span x-text="categorySearch"></span>"
                                    </button>
                                </div>
                                <template x-for="(cats, groupName) in groupedFilteredCategories" :key="groupName">
                                    <div class="px-3 pt-3">
                                        <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5 px-2">&mdash; <span x-text="groupName"></span></div>
                                        <div class="space-y-0.5">
                                            <template x-for="cat in cats" :key="cat.id">
                                                <button type="button" @click="selectCategory(cat)" class="w-full text-left px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-primary-50 hover:text-primary transition-colors flex items-center gap-2.5" :class="categoryId == cat.id ? 'bg-primary-50 text-primary' : ''"><i :class="'ti ti-' + cat.icon + ' text-lg'" :class="categoryId == cat.id ? 'text-primary' : 'text-slate-400'"></i><span x-text="cat.name"></span></button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="isCreatingCategory" style="display: none;" x-transition class="p-4 bg-primary-50/50 border border-primary-100 rounded-2xl">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-primary uppercase tracking-wider"><i class="ti ti-folder-plus text-sm mr-1"></i> Kategori Baru</span>
                                <button type="button" @click="cancelCreateCategory()" class="text-xs font-bold text-slate-400 hover:text-rose-500"><i class="ti ti-x text-sm"></i> Batal</button>
                            </div>

                            <div class="relative flex items-center gap-2 mb-3">
                                <div class="relative" @click.away="isCategoryIconPickerOpen = false">
                                    <button type="button" @click="isCategoryIconPickerOpen = !isCategoryIconPickerOpen" class="w-11 h-11 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-primary text-xl hover:bg-slate-50 transition-colors shadow-sm" title="Pilih Ikon Kategori">
                                        <i :class="'ti ti-' + categoryIcon"></i>
                                    </button>
                                    <div x-show="isCategoryIconPickerOpen" style="display: none;" x-transition class="absolute z-50 left-0 top-full mt-2 p-3 bg-white border border-slate-200 shadow-xl rounded-2xl w-64 max-h-48 overflow-y-auto custom-scrollbar grid grid-cols-5 gap-2">
                                        <template x-for="icon in popularIcons" :key="icon">
                                            <button type="button" @click="categoryIcon = icon; isCategoryIconPickerOpen = false" class="w-9 h-9 rounded-lg flex items-center justify-center text-lg hover:bg-primary-50 hover:text-primary transition-colors border" :class="categoryIcon === icon ? 'bg-primary-50 border-primary text-primary font-bold' : 'border-slate-100 text-slate-600'"><i :class="'ti ti-' + icon"></i></button>
                                        </template>
                                    </div>
                                </div>
                                <input type="text" x-model="newCategoryName" placeholder="Nama Kategori..." class="flex-1 bg-white rounded-xl border-slate-200 focus:border-primary text-sm h-11 font-semibold text-primary">
                            </div>

                            <div x-show="!isCreatingGroup" class="relative" @click.away="isGroupOpen = false">
                                <div class="relative">
                                    <input type="text" x-model="groupSearch" @focus="isGroupOpen = true; groupSearch = ''" placeholder="Masukkan ke Grup mana?" class="w-full pl-3 pr-10 bg-white rounded-xl border-slate-200 focus:border-primary text-xs h-10 transition-colors">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400"><i class="ti ti-chevron-down" :class="{'rotate-180': isGroupOpen}"></i></div>
                                </div>
                                <div x-show="isGroupOpen" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-slate-200 shadow-xl rounded-xl overflow-hidden max-h-48 overflow-y-auto custom-scrollbar p-1">
                                    <div x-show="filteredGroups.length === 0" class="p-1">
                                        <button type="button" @click="startCreateGroup()" class="w-full text-left px-3 py-2 rounded-lg text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition-colors flex items-center gap-1.5"><i class="ti ti-plus"></i> Buat Grup "<span x-text="groupSearch"></span>"</button>
                                    </div>
                                    <template x-for="g in filteredGroups" :key="g.id">
                                        <button type="button" @click="selectGroup(g)" class="w-full text-left px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors" :class="groupId == g.id ? 'bg-primary-50 text-primary' : ''"><span x-text="g.name"></span></button>
                                    </template>
                                </div>
                            </div>

                            <div x-show="isCreatingGroup" style="display: none;" x-transition class="relative">
                                <p class="text-[10px] text-slate-500 mb-1.5 font-semibold">Grup Baru:</p>
                                <div class="flex items-center gap-2">
                                    <div class="relative" @click.away="isGroupIconPickerOpen = false">
                                        <button type="button" @click="isGroupIconPickerOpen = !isGroupIconPickerOpen" class="w-10 h-10 bg-white border border-emerald-200 rounded-xl flex items-center justify-center text-emerald-600 text-lg hover:bg-emerald-50 transition-colors shadow-sm" title="Pilih Ikon Grup">
                                            <i :class="'ti ti-' + groupIcon"></i>
                                        </button>
                                        <div x-show="isGroupIconPickerOpen" style="display: none;" x-transition class="absolute z-50 left-0 top-full mt-2 p-3 bg-white border border-slate-200 shadow-xl rounded-2xl w-64 max-h-48 overflow-y-auto custom-scrollbar grid grid-cols-5 gap-2">
                                            <template x-for="icon in popularIcons" :key="'grp-' + icon">
                                                <button type="button" @click="groupIcon = icon; isGroupIconPickerOpen = false" class="w-9 h-9 rounded-lg flex items-center justify-center text-lg hover:bg-emerald-50 hover:text-emerald-600 transition-colors border" :class="groupIcon === icon ? 'bg-emerald-50 border-emerald-500 text-emerald-600 font-bold' : 'border-slate-100 text-slate-600'"><i :class="'ti ti-' + icon"></i></button>
                                            </template>
                                        </div>
                                    </div>
                                    <input type="text" x-model="newGroupName" placeholder="Nama Grup..." class="flex-1 bg-white rounded-xl border-emerald-200 focus:border-emerald-500 text-xs h-10 font-semibold text-emerald-700">
                                    <button type="button" @click="cancelCreateGroup()" class="w-10 h-10 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-rose-500 flex items-center justify-center shrink-0"><i class="ti ti-x"></i></button>
                                </div>
                            </div>
                        </div>

                        @error('category_id') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                        @error('new_category_name') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- =========================== -->
                    <!-- 3. CUSTOM TITLE             -->
                    <!-- =========================== -->
                    <div>
                        <label for="custom_title" class="block font-bold text-xs tracking-wider text-slate-500 uppercase mb-2">3. Nama Custom (Opsional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="ti ti-pencil text-slate-400 text-lg"></i>
                            </div>
                            <input type="text" name="custom_title" id="custom_title" value="{{ old('custom_title', $bookmark->custom_title) }}" placeholder="Contoh: Tools Desain UI Keren"
                                class="w-full pl-11 bg-slate-50 rounded-2xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm h-12 transition-colors">
                        </div>
                        <p class="mt-2 text-[11px] text-slate-400">Kosongkan agar sistem menggunakan judul asli website.</p>
                    </div>
                </div>

                <!-- =========================== -->
                <!-- 4. MULTI-TAGS (BUBBLE UI)   -->
                <!-- =========================== -->
                <div class="bg-primary-50/40 p-5 sm:p-6 rounded-3xl border border-primary-100 relative z-0 mb-8">
                    <label class="block font-bold text-xs tracking-wider text-slate-600 uppercase mb-2">4. Tags / Kata Kunci (Max: 5)</label>

                    <div x-show="!categoryId && !isCreatingCategory" x-transition class="flex items-center gap-2 p-3 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-xs font-semibold">
                        <i class="ti ti-alert-circle text-lg"></i> Pilih Kategori (Langkah 2) terlebih dahulu untuk memunculkan Tag.
                    </div>

                    <div x-show="categoryId || isCreatingCategory" style="display: none;" x-transition>
                        <p class="text-[11px] text-slate-500 mb-4">Pilih tag rekomendasi, atau ketik manual jika membuat Kategori Baru.</p>

                        <div class="flex flex-wrap gap-2.5 mb-5" x-show="!isCreatingCategory">
                            <template x-for="tag in availableTags" :key="tag.name">
                                <button type="button" @click="toggleTag(tag.name)" class="px-3 py-1.5 rounded-xl border text-xs font-bold transition-all flex items-center gap-1.5 focus:outline-none" :class="tags.includes(tag.name) ? 'bg-primary text-white border-primary shadow-md shadow-primary/20 scale-105' : 'bg-white text-slate-600 border-slate-200 hover:border-primary hover:text-primary'">
                                    <i class="ti ti-hash text-sm opacity-70"></i> <span x-text="tag.name"></span>
                                </button>
                            </template>
                            <div x-show="availableTags.length === 0" class="text-xs text-slate-400 italic py-1">Belum ada tag tersedia. Silakan buat baru!</div>
                        </div>

                        <div class="relative flex items-center max-w-sm mt-2">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400"><i class="ti ti-hash text-lg"></i></div>
                            <input type="text" x-model="newTag" @keydown.enter.prevent="addNewTag()" @keydown.comma.prevent="addNewTag()" placeholder="Ketik tag baru di sini..." class="w-full pl-10 pr-24 bg-white rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm h-12 transition-colors shadow-sm" :disabled="tags.length >= 5">
                            <button type="button" @click="addNewTag()" :disabled="tags.length >= 5 || newTag.trim() === ''" class="absolute right-1.5 px-4 py-2 bg-slate-500 text-white text-xs font-bold rounded-lg hover:bg-slate-600 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">Tambah</button>
                        </div>
                        <div x-show="tags.length >= 5" class="mt-2 text-xs text-rose-500 font-medium">Batas maksimal 5 tag telah tercapai.</div>

                        <template x-for="(tag, index) in tags" :key="index"><input type="hidden" name="tags[]" :value="tag"></template>
                        
                        <div class="flex flex-wrap gap-2 mt-3" x-show="tags.length > 0 && isCreatingCategory">
                            <template x-for="(tag, index) in tags" :key="index">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-primary text-white text-[10px] font-bold rounded border border-primary-600 shadow-sm"><i class="ti ti-hash"></i><span x-text="tag"></span><button type="button" @click="removeTag(index)" class="hover:text-rose-200"><i class="ti ti-x"></i></button></span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- =========================== -->
                <!-- 5. MODEL HARGA & LISENSI    -->
                <!-- =========================== -->
                <div x-data="{ pricingType: '{{ old('pricing_type', $bookmark->pricing_type) }}' }" class="p-5 sm:p-6 rounded-3xl border border-slate-200 bg-slate-50/50 shadow-sm relative z-10 mb-8">
                    <label class="block font-bold text-xs tracking-wider text-slate-600 uppercase mb-4">5. Model Harga & Lisensi <span class="text-rose-500">*</span></label>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
                        <label class="relative cursor-pointer h-full">
                            <input type="radio" name="pricing_type" value="free" x-model="pricingType" class="peer sr-only" required>
                            <div class="h-full flex flex-col items-center justify-center p-4 border-2 border-slate-200 bg-white rounded-xl text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all hover:border-emerald-200"><i class="ti ti-free-rights text-2xl text-emerald-500 mb-1.5"></i><div class="font-bold text-sm text-slate-700">Gratis (Free)</div></div>
                        </label>
                        <label class="relative cursor-pointer h-full">
                            <input type="radio" name="pricing_type" value="freemium" x-model="pricingType" class="peer sr-only">
                            <div class="h-full flex flex-col items-center justify-center p-4 border-2 border-slate-200 bg-white rounded-xl text-center peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-all hover:border-amber-200"><i class="ti ti-star text-2xl text-amber-500 mb-1.5"></i><div class="font-bold text-sm text-slate-700">Freemium</div></div>
                        </label>
                        <label class="relative cursor-pointer h-full">
                            <input type="radio" name="pricing_type" value="premium" x-model="pricingType" class="peer sr-only">
                            <div class="h-full flex flex-col items-center justify-center p-4 border-2 border-slate-200 bg-white rounded-xl text-center peer-checked:border-rose-500 peer-checked:bg-rose-50 transition-all hover:border-rose-200"><i class="ti ti-diamond text-2xl text-rose-500 mb-1.5"></i><div class="font-bold text-sm text-slate-700">Premium</div></div>
                        </label>
                    </div>

                    <div x-show="pricingType === 'freemium' || pricingType === 'premium'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-200">
                        <div>
                            <label class="block font-bold text-xs tracking-wider text-slate-500 uppercase mb-2">Tipe Pembayaran</label>
                            <select name="payment_model" class="w-full bg-white rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm h-12">
                                <option value="" disabled selected>Pilih Tipe Pembayaran...</option>
                                <option value="subscription" {{ old('payment_model', $bookmark->payment_model) == 'subscription' ? 'selected' : '' }}>Berlangganan / Per Bulan</option>
                                <option value="one_time" {{ old('payment_model', $bookmark->payment_model) == 'one_time' ? 'selected' : '' }}>Sekali Bayar (Lifetime)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-xs tracking-wider text-slate-500 uppercase mb-2">Range Harga (Opsional)</label>
                            <select name="price_range" class="w-full bg-white rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm h-12">
                                <option value="" selected>Pilih Range Harga...</option>
                                <option value="< Rp 20.000" {{ old('price_range', $bookmark->price_range) == '< Rp 20.000' ? 'selected' : '' }}>&lt; Rp 20.000</option>
                                <option value="Rp 20.000 - Rp 50.000" {{ old('price_range', $bookmark->price_range) == 'Rp 20.000 - Rp 50.000' ? 'selected' : '' }}>Rp 20.000 - Rp 50.000</option>
                                <option value="Rp 50.000 - Rp 100.000" {{ old('price_range', $bookmark->price_range) == 'Rp 50.000 - Rp 100.000' ? 'selected' : '' }}>Rp 50.000 - Rp 100.000</option>
                                <option value="Rp 100.000 - Rp 150.000" {{ old('price_range', $bookmark->price_range) == 'Rp 100.000 - Rp 150.000' ? 'selected' : '' }}>Rp 100.000 - Rp 150.000</option>
                                <option value="> Rp 150.000" {{ old('price_range', $bookmark->price_range) == '> Rp 150.000' ? 'selected' : '' }}>&gt; Rp 150.000</option>
                                <option value="Berbasis Dollar ($)" {{ old('price_range', $bookmark->price_range) == 'Berbasis Dollar ($)' ? 'selected' : '' }}>Berbasis Kurs Dollar ($)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors text-center">Batal</a>
                    <button type="submit" class="px-8 py-3 text-sm font-bold text-white bg-amber-500 rounded-xl hover:bg-amber-600 transition-colors shadow-lg shadow-amber-500/30 text-center flex items-center justify-center gap-2">
                        <i class="ti ti-device-floppy text-lg"></i> Perbarui Website
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT ALPINE.JS -->
    <script>
        function bookmarkForm() {
            return {
                allCategories: @json($alpineCategories),
                allTags: @json($alpineTags), 
                allGroups: @json($alpineGroups),
                
                popularIcons: ['folder', 'code', 'palette', 'device-laptop', 'brain', 'rocket', 'sparkles', 'flame', 'star', 'book', 'shopping-cart', 'bulb', 'tool', 'heart', 'camera', 'music', 'world', 'chart-bar', 'news', 'shield', 'compass', 'cpu', 'database', 'cloud', 'briefcase'],

                categoryId: '{{ old("category_id", $bookmark->category_id) }}',
                categorySearch: '',
                selectedCategoryName: '',
                isCategoryOpen: false,

                isCreatingCategory: false,
                newCategoryName: '{{ old("new_category_name", "") }}',
                categoryIcon: 'folder', 
                isCategoryIconPickerOpen: false,
                
                groupId: '{{ old("group_id", "") }}',
                groupSearch: '',
                selectedGroupName: '',
                isGroupOpen: false,
                isCreatingGroup: false,
                newGroupName: '{{ old("new_group_name", "") }}',
                groupIcon: 'folder',
                isGroupIconPickerOpen: false,

                // Load tag lama dari database otomatis
                tags: @json(old('tags', $bookmark->tags->pluck('name')->toArray())),
                availableTags: [],
                newTag: '',

                initSetup() {
                    if (this.newCategoryName !== '') {
                        this.isCreatingCategory = true;
                        if (this.newGroupName !== '') {
                            this.isCreatingGroup = true;
                        } else if (this.groupId !== '') {
                            const grp = this.allGroups.find(g => g.id == this.groupId);
                            if(grp) { this.groupSearch = grp.name; this.selectedGroupName = grp.name; }
                        }
                    } else if (this.categoryId) {
                        const cat = this.allCategories.find(c => c.id == this.categoryId);
                        if (cat) { this.selectedCategoryName = cat.name; this.categorySearch = cat.name; this.loadCategoryTags(); }
                    }
                },
                get groupedFilteredCategories() {
                    const query = this.categorySearch.toLowerCase().trim();
                    let filtered = this.allCategories;
                    if (query !== '') filtered = this.allCategories.filter(c => c.name.toLowerCase().includes(query) || (c.group && c.group.toLowerCase().includes(query)));
                    const groups = {};
                    filtered.forEach(c => { const g = c.group || 'Kategori Lainnya'; if (!groups[g]) groups[g] = []; groups[g].push(c); });
                    return groups;
                },
                selectCategory(cat) {
                    this.categoryId = cat.id; this.categorySearch = cat.name; this.selectedCategoryName = cat.name; this.isCategoryOpen = false;
                    this.tags = []; this.newTag = ''; this.loadCategoryTags(); 
                },
                closeCategoryDropdown() { this.isCategoryOpen = false; this.categorySearch = this.selectedCategoryName; },
                
                startCreateCategory() {
                    this.isCreatingCategory = true; this.newCategoryName = this.categorySearch; this.categoryId = ''; 
                    this.categoryIcon = 'folder'; this.isCategoryOpen = false; this.tags = [];
                },
                cancelCreateCategory() {
                    this.isCreatingCategory = false; this.newCategoryName = ''; this.categoryIcon = 'folder';
                    this.cancelCreateGroup(); this.categorySearch = '';
                },

                get filteredGroups() {
                    const query = this.groupSearch.toLowerCase().trim();
                    if(query === '') return this.allGroups;
                    return this.allGroups.filter(g => g.name.toLowerCase().includes(query));
                },
                selectGroup(grp) { this.groupId = grp.id; this.groupSearch = grp.name; this.selectedGroupName = grp.name; this.isGroupOpen = false; },
                startCreateGroup() { this.isCreatingGroup = true; this.newGroupName = this.groupSearch; this.groupIcon = 'folder'; this.groupId = ''; this.isGroupOpen = false; },
                cancelCreateGroup() { this.isCreatingGroup = false; this.newGroupName = ''; this.groupIcon = 'folder'; this.groupSearch = ''; this.groupId = ''; },

                loadCategoryTags() {
                    if (!this.categoryId) return;
                    this.availableTags = this.allTags.filter(t => t.category_id == this.categoryId);
                    this.tags.forEach(t => {
                        if (!this.availableTags.some(at => at.name.toLowerCase() === t.toLowerCase())) { this.availableTags.push({ name: t }); }
                    });
                },
                toggleTag(tagName) {
                    if (this.tags.includes(tagName)) { this.tags = this.tags.filter(t => t !== tagName); } 
                    else { if (this.tags.length < 5) { this.tags.push(tagName); } else { alert('Maksimal hanya 5 Tag yang diizinkan!'); } }
                },
                addNewTag() {
                    let tagText = this.newTag.trim();
                    if (tagText !== '') {
                        if (tagText.startsWith('#')) tagText = tagText.substring(1).trim();
                        if (!this.availableTags.some(at => at.name.toLowerCase() === tagText.toLowerCase())) { this.availableTags.push({ name: tagText }); }
                        if (!this.tags.some(t => t.toLowerCase() === tagText.toLowerCase()) && this.tags.length < 5) { this.tags.push(tagText); }
                    }
                    this.newTag = ''; 
                },
                removeTag(index) { this.tags.splice(index, 1); }
            }
        }
    </script>
</x-app-layout>
