<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6 relative">
        <div class="absolute top-0 right-0 -mr-24 -mt-10 w-72 h-72 rounded-full bg-amber-100/50 blur-3xl pointer-events-none"></div>

        <div class="mb-4 relative z-10">
            <h2 class="text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">Edit Website</h2>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi, kategori, atau tag dari website yang sudah kamu simpan.</p>
        </div>

        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/40 border border-slate-100 relative z-10">

            @php
                $alpineCategories = $categories->map(fn($cat) => [
                    'id' => $cat->id, 'name' => $cat->name, 'group' => $cat->group ? $cat->group->name : 'Kategori Lainnya', 'icon' => $cat->icon ?? 'folder'
                ])->values();

                $alpineTags = isset($allTags) ? $allTags->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'category_id' => $t->category_id])->values() : [];
            @endphp

            <form action="{{ route('bookmarks.update', $bookmark->id) }}" method="POST" x-data="bookmarkForm()" x-init="initSetup()">
                @csrf
                @method('PUT')

                <!-- 1. URL INPUT -->
                <div class="mb-8">
                    <label class="block font-bold text-xs tracking-wider text-slate-500 uppercase mb-2">1. Link URL <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="ti ti-link text-slate-400 text-lg"></i></div>
                        <input type="url" name="url" required value="{{ old('url', $bookmark->website->original_url) }}" class="w-full pl-11 bg-slate-50 rounded-2xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm h-12 transition-colors">
                    </div>
                    @error('url') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- 2. KATEGORI -->
                    <div class="relative" @click.away="closeCategoryDropdown()">
                        <label class="block font-bold text-xs tracking-wider text-slate-500 uppercase mb-2">2. Pilih Kategori <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="category_id" :value="categoryId" required>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors" :class="isCategoryOpen ? 'text-primary' : 'text-slate-400'">
                                <i class="ti ti-search text-lg" x-show="isCategoryOpen"></i><i class="ti ti-folder text-lg" x-show="!isCategoryOpen"></i>
                            </div>
                            <input type="text" x-model="categorySearch" @focus="isCategoryOpen = true; categorySearch = ''" placeholder="Ketik untuk mencari kategori..." class="w-full pl-11 pr-10 bg-slate-50 rounded-2xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm h-12 transition-colors font-semibold text-slate-700">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400"><i class="ti ti-chevron-down transition-transform" :class="{'rotate-180 text-primary': isCategoryOpen}"></i></div>
                        </div>
                        <div x-show="isCategoryOpen" style="display: none;" class="absolute z-50 w-full mt-2 bg-white border border-slate-200 shadow-xl rounded-2xl overflow-hidden max-h-72 overflow-y-auto custom-scrollbar pb-2">
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

                    <!-- 3. CUSTOM TITLE -->
                    <div>
                        <label class="block font-bold text-xs tracking-wider text-slate-500 uppercase mb-2">3. Nama Custom (Opsional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="ti ti-pencil text-slate-400 text-lg"></i></div>
                            <input type="text" name="custom_title" value="{{ old('custom_title', $bookmark->custom_title) }}" class="w-full pl-11 bg-slate-50 rounded-2xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm h-12 transition-colors">
                        </div>
                    </div>
                </div>

                <!-- 4. MULTI-TAGS -->
                <div class="bg-primary-50/40 p-5 sm:p-6 rounded-3xl border border-primary-100 relative z-0 mb-8">
                    <label class="block font-bold text-xs tracking-wider text-slate-600 uppercase mb-2">4. Tags / Kata Kunci (Max: 5)</label>
                    <div x-show="categoryId" x-transition>
                        <div class="flex flex-wrap gap-2.5 mb-5">
                            <template x-for="tag in availableTags" :key="tag.name">
                                <button type="button" @click="toggleTag(tag.name)" class="px-3 py-1.5 rounded-xl border text-xs font-bold transition-all flex items-center gap-1.5 focus:outline-none" :class="tags.includes(tag.name) ? 'bg-primary text-white border-primary shadow-md shadow-primary/20 scale-105' : 'bg-white text-slate-600 border-slate-200 hover:border-primary hover:text-primary'"><i class="ti ti-hash text-sm opacity-70"></i> <span x-text="tag.name"></span></button>
                            </template>
                        </div>
                        <div class="relative flex items-center max-w-sm mt-2">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400"><i class="ti ti-hash text-lg"></i></div>
                            <input type="text" x-model="newTag" @keydown.enter.prevent="addNewTag()" @keydown.comma.prevent="addNewTag()" placeholder="Ketik tag baru di sini..." class="w-full pl-10 pr-24 bg-white rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm h-12 transition-colors shadow-sm" :disabled="tags.length >= 5">
                            <button type="button" @click="addNewTag()" :disabled="tags.length >= 5 || newTag.trim() === ''" class="absolute right-1.5 px-4 py-2 bg-slate-500 text-white text-xs font-bold rounded-lg hover:bg-slate-600 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">Tambah</button>
                        </div>
                        <template x-for="(tag, index) in tags" :key="index"><input type="hidden" name="tags[]" :value="tag"></template>
                    </div>
                </div>

                <!-- 5. MODEL HARGA & LISENSI -->
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
                            <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Tipe Pembayaran</label>
                            <select name="payment_model" class="w-full bg-white rounded-xl border-slate-200 focus:border-primary text-sm h-12">
                                <option value="" disabled {{ !$bookmark->payment_model ? 'selected' : '' }}>Pilih Pembayaran...</option>
                                <option value="subscription" {{ old('payment_model', $bookmark->payment_model) == 'subscription' ? 'selected' : '' }}>Berlangganan / Per Bulan</option>
                                <option value="one_time" {{ old('payment_model', $bookmark->payment_model) == 'one_time' ? 'selected' : '' }}>Sekali Bayar (Lifetime)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Range Harga (Opsional)</label>
                            <select name="price_range" class="w-full bg-white rounded-xl border-slate-200 focus:border-primary text-sm h-12">
                                <option value="" {{ !$bookmark->price_range ? 'selected' : '' }}>Pilih Range...</option>
                                <option value="< Rp 20.000" {{ old('price_range', $bookmark->price_range) == '< Rp 20.000' ? 'selected' : '' }}>&lt; Rp 20.000</option>
                                <option value="Rp 20.000 - Rp 50.000" {{ old('price_range', $bookmark->price_range) == 'Rp 20.000 - Rp 50.000' ? 'selected' : '' }}>Rp 20.000 - Rp 50.000</option>
                                <option value="Rp 50.000 - Rp 100.000" {{ old('price_range', $bookmark->price_range) == 'Rp 50.000 - Rp 100.000' ? 'selected' : '' }}>Rp 50.000 - Rp 100.000</option>
                                <option value="Rp 100.000 - Rp 150.000" {{ old('price_range', $bookmark->price_range) == 'Rp 100.000 - Rp 150.000' ? 'selected' : '' }}>Rp 100.000 - Rp 150.000</option>
                                <option value="> Rp 150.000" {{ old('price_range', $bookmark->price_range) == '> Rp 150.000' ? 'selected' : '' }}>&gt; Rp 150.000</option>
                                <option value="Berbasis Dollar ($)" {{ old('price_range', $bookmark->price_range) == 'Berbasis Dollar ($)' ? 'selected' : '' }}>Berbasis Dollar ($)</option>
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
                categoryId: '{{ old("category_id", $bookmark->category_id) }}',
                categorySearch: '',
                selectedCategoryName: '',
                isCategoryOpen: false,

                // Menarik tag yang sudah dimiliki website ini ke dalam array
                tags: @json(old('tags', $bookmark->tags->pluck('name')->toArray())),
                availableTags: [],
                newTag: '',

                initSetup() {
                    if (this.categoryId) {
                        const cat = this.allCategories.find(c => c.id == this.categoryId);
                        if (cat) {
                            this.selectedCategoryName = cat.name;
                            this.categorySearch = cat.name;
                            this.loadCategoryTags();
                        }
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
                loadCategoryTags() {
                    if (!this.categoryId) return;
                    this.availableTags = this.allTags.filter(t => t.category_id == this.categoryId);
                    this.tags.forEach(t => {
                        if (!this.availableTags.some(at => at.name.toLowerCase() === t.toLowerCase())) { this.availableTags.push({ name: t }); }
                    });
                },
                toggleTag(tagName) {
                    if (this.tags.includes(tagName)) { this.tags = this.tags.filter(t => t !== tagName); }
                    else { if (this.tags.length < 5) { this.tags.push(tagName); } else { alert('Maksimal hanya 5 Tag!'); } }
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
            }
        }
    </script>
</x-app-layout>
