<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-6">

        <div class="mb-4">
            <a href="{{ route('websites.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary mb-4 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l14 0"></path><path d="M5 12l6 6"></path><path d="M5 12l6 -6"></path></svg>
                Kembali ke Library
            </a>
            <h2 class="text-3xl font-heading font-bold text-gray-900 leading-tight">Tambah Website</h2>
            <p class="text-sm text-gray-500 mt-1">Simpan referensi tool atau website baru ke dalam perpustakaan digitalmu.</p>
        </div>

        <div class="bg-white rounded-[24px] p-6 sm:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
            <form action="{{ route('websites.store') }}" method="POST"
                x-data="{ 
                    url: '{{ old('url') }}', 
                    name: '{{ old('name') }}', 
                    description: '{{ old('description') }}',
                    isFetching: false,
                    fetchData() {
                        if(!this.url) return;
                        this.isFetching = true;
                        fetch('{{ route('websites.fetch-metadata') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                            body: JSON.stringify({ url: this.url })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if(data.title) this.name = data.title;
                            if(data.description) this.description = data.description;
                        })
                        .catch(err => console.error(err))
                        .finally(() => { this.isFetching = false; });
                    }
                }">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

                    <!-- ======================================= -->
                    <!-- KOLOM KIRI: Informasi Utama Website     -->
                    <!-- ======================================= -->
                    <div class="lg:col-span-6 space-y-6">

                        <!-- Auto-Fetch URL -->
                        <div class="bg-primary/5 border border-primary/10 rounded-[20px] p-5">
                            <label for="url" class="block font-bold text-xs tracking-wider text-primary uppercase mb-2">1. Masukkan URL <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <input type="url" name="url" id="url" required x-model="url" placeholder="https://..." class="w-full bg-white rounded-[12px] border-gray-200 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                <button type="button" @click="fetchData" :disabled="isFetching || !url" class="bg-primary text-white px-5 rounded-[12px] font-medium text-sm hover:bg-primary-dark transition disabled:opacity-50 flex items-center gap-2 shadow-sm shrink-0">
                                    <svg x-show="isFetching" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <svg x-show="!isFetching" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 21v-6.5a3.5 3.5 0 0 0 -7 0v6.5h18v-6l-4.5 -4.5l-2.5 2.5l-4 -4l-4 4"></path></svg>
                                    <span x-text="isFetching ? 'Sedang menarik data...' : 'Auto-Fill'"></span>
                                </button>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-2">Klik Auto-Fill untuk mengambil nama & deskripsi web secara otomatis.</p>
                            <x-input-error :messages="$errors->get('url')" class="mt-1" />
                        </div>

                        <div>
                            <label for="name" class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2">Nama Website <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required x-model="name" placeholder="Contoh: Figma, Cursor AI..." class="w-full bg-gray-50 rounded-[14px] border-transparent focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm">
                        </div>

                        <div>
                            <label for="description" class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2">Deskripsi Singkat</label>
                            <textarea name="description" id="description" rows="3" x-model="description" placeholder="Website ini tentang apa?" class="w-full bg-gray-50 rounded-[14px] border-transparent focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm"></textarea>
                        </div>

                        <div>
                            <label for="tags" class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2">Tags / Kata Kunci Tambahan</label>
                            <input type="text" name="tags" id="tags" placeholder="e.g. ui, tailwind, productivity" class="w-full bg-gray-50 rounded-[14px] border-transparent focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm" value="{{ old('tags') }}">
                            <p class="text-[11px] text-gray-400 mt-1.5">Pisahkan dengan koma.</p>
                        </div>
                    </div>

                    <!-- ======================================= -->
                    <!-- KOLOM KANAN: Klasifikasi Kategori       -->
                    <!-- ======================================= -->
                    <div class="lg:col-span-6 space-y-8">

                        <!-- UI BARU: Radio Cards untuk Tipe Harga -->
                        <div>
                            <label class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-3">Pilih Tipe Harga <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="pricing_type" value="free" class="peer sr-only" required {{ old('pricing_type') == 'free' ? 'checked' : '' }}>
                                    <div class="text-center px-2 py-2.5 text-xs font-bold text-gray-500 bg-white border border-gray-200 rounded-[12px] peer-checked:bg-emerald-50 peer-checked:text-emerald-700 peer-checked:border-emerald-200 peer-checked:ring-1 peer-checked:ring-emerald-200 hover:bg-gray-50 transition">
                                        Free (Gratis)
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="pricing_type" value="freemium" class="peer sr-only" required {{ old('pricing_type') == 'freemium' ? 'checked' : '' }}>
                                    <div class="text-center px-2 py-2.5 text-xs font-bold text-gray-500 bg-white border border-gray-200 rounded-[12px] peer-checked:bg-blue-50 peer-checked:text-blue-700 peer-checked:border-blue-200 peer-checked:ring-1 peer-checked:ring-blue-200 hover:bg-gray-50 transition">
                                        Freemium
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="pricing_type" value="paid" class="peer sr-only" required {{ old('pricing_type') == 'paid' ? 'checked' : '' }}>
                                    <div class="text-center px-2 py-2.5 text-xs font-bold text-gray-500 bg-white border border-gray-200 rounded-[12px] peer-checked:bg-orange-50 peer-checked:text-orange-700 peer-checked:border-orange-200 peer-checked:ring-1 peer-checked:ring-orange-200 hover:bg-gray-50 transition">
                                        Paid (Berbayar)
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- UI BARU: Clickable Pills untuk Kategori -->
                        <div class="bg-gray-50/50 rounded-[20px] p-5 border border-gray-100">
                            <label class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-3">Alokasikan ke Kategori <span class="text-red-500">*</span></label>

                            <div class="space-y-5 max-h-[340px] overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($masterCategories as $master)
                                    @php
                                        // Logika Ikon SVG agar seragam dengan halaman index
                                        $iconSvg = match($master->icon) {
                                            '🤖' => '<svg class="w-4 h-4 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" /><path d="M2 8l4 0" /><path d="M18 8l4 0" /><path d="M2 16l4 0" /><path d="M18 16l4 0" /><path d="M9 4v-1" /><path d="M15 4v-1" /><path d="M9 12v.01" /><path d="M15 12v.01" /><path d="M9 16h6" /></svg>',
                                            '🎨' => '<svg class="w-4 h-4 text-pink-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21a9 9 0 0 1 0 -18c4.97 0 9 3.582 9 8c0 1.06 -.474 2.078 -1.318 2.828c-.844 .75 -1.989 1.172 -3.182 1.172h-2.5a2 2 0 0 0 -1 3.75a1.3 1.3 0 0 1 -1 2.25" /><path d="M8.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M16.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>',
                                            '💻' => '<svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 7l5 5l-5 5" /><path d="M12 19l7 0" /></svg>',
                                            default => '<svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>'
                                        };
                                    @endphp

                                    <div>
                                        <div class="flex items-center gap-1.5 mb-2 text-sm font-bold text-gray-700">
                                            {!! $iconSvg !!}
                                            {{ $master->name }}
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($master->categories as $category)
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="peer sr-only" {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) ? 'checked' : '' }}>
                                                    <span class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-white border border-gray-200 rounded-[10px] hover:bg-gray-50 peer-checked:bg-primary/10 peer-checked:text-primary peer-checked:border-primary peer-checked:ring-1 peer-checked:ring-primary transition-all">
                                                        {{ $category->name }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-[11px] text-gray-400 mt-4 border-t border-gray-200 pt-3">Kamu bisa memilih lebih dari satu kategori (Multi-select).</p>
                        </div>
                    </div>
                </div>

                <!-- ======================================= -->
                <!-- BAGIAN BAWAH: Notes                     -->
                <!-- ======================================= -->
                <div class="mt-8 pt-8 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="why_saved" class="block font-bold text-xs tracking-wider text-primary uppercase mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Kenapa website ini disimpan?
                        </label>
                        <textarea name="why_saved" id="why_saved" rows="2" placeholder="Alasan personal kamu menyimpan web ini..." class="w-full bg-primary/5 rounded-[14px] border-primary/20 focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm placeholder:text-gray-400">{{ old('why_saved') }}</textarea>
                    </div>
                    <div>
                        <label for="notes" class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2">Catatan Pribadi (Opsional)</label>
                        <textarea name="notes" id="notes" rows="2" placeholder="Tulis pengingat atau hal yang ingin dicoba nanti..." class="w-full bg-gray-50 rounded-[14px] border-transparent focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm placeholder:text-gray-400">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Submit Action -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('websites.index') }}" class="w-full sm:w-auto text-center px-6 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-[14px] hover:bg-gray-50 transition">Batalkan</a>
                    <button type="submit" class="w-full sm:w-auto bg-primary text-white px-8 py-3 rounded-[14px] text-sm font-medium hover:bg-primary-dark transition shadow-sm shadow-primary/30 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"></path></svg>
                        Simpan ke Library
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
