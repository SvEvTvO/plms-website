<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="mb-2">
            <a href="{{ route('categories.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 mb-4 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l14 0"></path><path d="M5 12l6 6"></path><path d="M5 12l6 -6"></path></svg>
                Kembali ke Kategori
            </a>
            <h2 class="text-3xl font-heading font-bold text-gray-900 leading-tight">Tambah Sub-Kategori</h2>
            <p class="text-sm text-gray-500 mt-1">Buat folder bagian dalam untuk mengelompokkan website secara lebih spesifik.</p>
        </div>

        <div class="bg-white rounded-[24px] p-6 sm:p-8 shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-gray-100">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="space-y-8">
                    <!-- Pilihan Master Category via Radio Cards -->
                    <div>
                        <label class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-3">1. Pilih Folder Utama (Master) <span class="text-primary">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-[220px] overflow-y-auto custom-scrollbar p-1">
                            @foreach($masterCategories as $master)
                                @php
                                    // Auto Convert Emoji ke SVG Tabler
                                    $iconSvg = match($master->icon) {
                                        '🤖' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" /><path d="M2 8l4 0" /><path d="M18 8l4 0" /><path d="M2 16l4 0" /><path d="M18 16l4 0" /><path d="M9 4v-1" /><path d="M15 4v-1" /><path d="M9 12v.01" /><path d="M15 12v.01" /><path d="M9 16h6" /></svg>',
                                        '🎨' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21a9 9 0 0 1 0 -18c4.97 0 9 3.582 9 8c0 1.06 -.474 2.078 -1.318 2.828c-.844 .75 -1.989 1.172 -3.182 1.172h-2.5a2 2 0 0 0 -1 3.75a1.3 1.3 0 0 1 -1 2.25" /><path d="M8.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M16.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>',
                                        '💻' => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 7l5 5l-5 5" /><path d="M12 19l7 0" /></svg>',
                                        default => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>'
                                    };
                                @endphp
                                <label class="cursor-pointer">
                                    <input type="radio" name="master_category_id" value="{{ $master->id }}" class="peer sr-only" required {{ old('master_category_id', request('master_id')) == $master->id ? 'checked' : '' }}>
                                    <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-[16px] hover:bg-gray-50 peer-checked:bg-blue-50 peer-checked:border-blue-200 peer-checked:ring-1 peer-checked:ring-blue-200 transition shadow-sm">
                                        <div class="w-10 h-10 rounded-[12px] bg-white border border-gray-100 flex items-center justify-center text-gray-400 peer-checked:text-blue-600 peer-checked:border-blue-200 shadow-sm shrink-0 transition">
                                            {!! $iconSvg !!}
                                        </div>
                                        <div class="font-bold text-gray-700 peer-checked:text-blue-800 text-sm line-clamp-2">
                                            {{ $master->name }}
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('master_category_id')" class="mt-2" />
                    </div>

                    <!-- Input Nama Sub-Kategori -->
                    <div class="bg-gray-50/50 p-5 rounded-[20px] border border-gray-100">
                        <label for="name" class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2">2. Nama Sub-Kategori <span class="text-primary">*</span></label>
                        <input type="text" name="name" id="name" required placeholder="Contoh: UI Inspiration, Frameworks..." class="w-full bg-white rounded-[14px] border-gray-200 focus:border-primary focus:ring-primary shadow-sm text-sm h-[46px]" value="{{ old('name') }}">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('categories.index') }}" class="w-full sm:w-auto text-center px-6 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-[14px] hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="w-full sm:w-auto bg-gray-900 text-white px-8 py-3 rounded-[14px] text-sm font-medium hover:bg-gray-800 transition shadow-sm">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
