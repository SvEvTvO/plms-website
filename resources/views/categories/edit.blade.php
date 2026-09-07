<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="mb-4">
            <a href="{{ route('categories.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary mb-4 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l14 0"></path><path d="M5 12l6 6"></path><path d="M5 12l6 -6"></path></svg>
                Kembali ke Kategori
            </a>
            <h2 class="text-3xl font-heading font-bold text-gray-900 leading-tight">Edit Sub-Kategori</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui nama, deskripsi, atau pindahkan <span class="font-bold text-gray-900">{{ $category->name }}</span> ke folder utama lain.</p>
        </div>

        <div class="bg-white rounded-[24px] p-6 sm:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-8">

                    <!-- ======================================= -->
                    <!-- 1. PILIH MASTER CATEGORY (RADIO CARDS)  -->
                    <!-- ======================================= -->
                    <div>
                        <label class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-3">1. Pindahkan Folder Utama (Master) <span class="text-red-500">*</span></label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-[260px] overflow-y-auto custom-scrollbar p-1">
                            @foreach($masterCategories as $master)
                                @php
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

                                <label class="cursor-pointer group relative block">
                                    <input type="radio" name="master_category_id" value="{{ $master->id }}" class="peer sr-only" required {{ old('master_category_id', $category->master_category_id) == $master->id ? 'checked' : '' }}>

                                    <!-- Pr-10 ditambahkan -->
                                    <div class="relative flex items-center gap-3 p-3 pr-10 bg-white border border-gray-200 rounded-[16px] hover:border-gray-300 peer-checked:border-transparent peer-checked:ring-2 transition shadow-sm" style="--tw-ring-color: {{ $hexColor }};">

                                        <div class="w-10 h-10 rounded-[12px] flex items-center justify-center shrink-0 transition" style="background-color: {{ $hexColor }}20; color: {{ $hexColor }};">
                                            {!! $fullSvg !!}
                                        </div>

                                        <div class="font-bold text-gray-600 peer-checked:text-gray-900 text-sm line-clamp-2">
                                            {{ $master->name }}
                                        </div>

                                        <!-- Indikator Centang di DALAM Kartu -->
                                        <div class="absolute top-1/2 -translate-y-1/2 right-3 w-5 h-5 rounded-full text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity shadow-sm" style="background-color: {{ $hexColor }};">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"></path></svg>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('master_category_id')" class="mt-2" />
                    </div>

                    <!-- ======================================= -->
                    <!-- 2. DETAIL SUB-KATEGORI                  -->
                    <!-- ======================================= -->
                    <div class="bg-gray-50/50 p-6 sm:p-8 rounded-[24px] border border-gray-100 space-y-6">

                        <div>
                            <label for="name" class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2">2. Nama Sub-Kategori <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required class="w-full bg-white rounded-[14px] border-gray-200 focus:border-primary focus:ring-primary shadow-sm text-sm h-[46px]" value="{{ old('name', $category->name) }}">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="description" class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2">Deskripsi (Opsional)</label>
                            <textarea name="description" id="description" rows="3" class="w-full bg-white rounded-[14px] border-gray-200 focus:border-primary focus:ring-primary shadow-sm text-sm">{{ old('description', $category->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('categories.index') }}" class="w-full sm:w-auto text-center px-6 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-[14px] hover:bg-gray-50 transition">Batalkan</a>
                    <button type="submit" class="w-full sm:w-auto bg-gray-900 text-white px-8 py-3 rounded-[14px] text-sm font-medium hover:bg-gray-800 transition shadow-sm">
                        Perbarui Sub-Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
