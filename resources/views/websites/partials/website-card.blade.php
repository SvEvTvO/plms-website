@php
    // Warna border atas dinamis berdasarkan pricing
    $borderColor = match($website->pricing_type) {
        'free' => 'border-emerald-500',
        'freemium' => 'border-blue-500',
        'paid' => 'border-orange-500',
        default => 'border-primary',
    };

    $badgeBg = match($website->pricing_type) {
        'free' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'freemium' => 'bg-blue-50 text-blue-600 border-blue-100',
        'paid' => 'bg-orange-50 text-orange-600 border-orange-100',
        default => 'bg-gray-50 text-gray-600 border-gray-100',
    };

    // Ekstrak domain untuk mengambil logo via Google Favicon API
    $domain = parse_url($website->url, PHP_URL_HOST);
    $domain = preg_replace('/^www\./', '', $domain); // Bersihkan 'www.' agar lebih akurat
@endphp

<div class="bg-white rounded-xl shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-gray-200 border-t-[4px] {{ $borderColor }} flex flex-col group {{ $website->status === 'archived' ? 'opacity-60 grayscale' : '' }}">
    <div class="p-4 flex-1 flex flex-col">

        <!-- Header (Logo, Judul, Badge, Link Kanan) -->
        <div class="flex justify-between items-start mb-3 gap-3">
            <div class="flex items-start gap-3">

                <!-- Box Logo Website (Auto Fetch) -->
                <div class="w-10 h-10 shrink-0 bg-white border border-gray-100 shadow-sm rounded-lg flex items-center justify-center p-1.5 overflow-hidden relative">
                    <img src="https://s2.googleusercontent.com/s2/favicons?domain={{ $domain }}&sz=64" alt="Logo" class="w-full h-full object-contain rounded-sm relative z-10">
                    <!-- Fallback background text jika API gagal (opsional) -->
                    <span class="absolute inset-0 flex items-center justify-center text-gray-300 font-bold text-lg bg-gray-50 z-0">
                        {{ strtoupper(substr($website->name, 0, 1)) }}
                    </span>
                </div>

                <!-- Title & Badges -->
                <div class="flex flex-col gap-1.5 mt-0.5">
                    <h3 class="text-[15px] font-bold text-gray-900 leading-none line-clamp-1" title="{{ $website->name }}">
                        {{ $website->name }}
                    </h3>

                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="px-1.5 py-0.5 text-[8px] font-bold tracking-wider uppercase rounded border {{ $badgeBg }}">
                            {{ $website->pricing_type }}
                        </span>

                        @if($website->status === 'active')
                            <span class="px-1.5 py-0.5 text-[8px] font-bold tracking-wider uppercase rounded border bg-emerald-50 text-emerald-600 border-emerald-100">
                                AKTIF
                            </span>
                        @else
                            <span class="px-1.5 py-0.5 text-[8px] font-bold tracking-wider uppercase rounded border bg-gray-50 text-gray-600 border-gray-200">
                                ARSIP
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <a href="{{ $website->url }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-primary transition shrink-0 mt-0.5" title="Buka Link">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-[16px] h-[16px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6"></path><path d="M11 13l9 -9"></path><path d="M15 4h5v5"></path></svg>
            </a>
        </div>

        <!-- Sub Header (Kategori) -->
        <p class="text-[12px] text-gray-500 mb-3 line-clamp-1">
            {{ $website->category_name ?? ($website->categories->first()->name ?? 'Uncategorized') }}
        </p>

        <!-- Body (Label & Nilai) -->
        <div class="flex-1 mb-1">
            <p class="text-[10px] font-bold tracking-wider text-gray-400 mb-1 uppercase">Deskripsi Singkat</p>
            <p class="text-[13px] font-bold text-gray-900 line-clamp-2 leading-snug" title="{{ $website->description }}">
                {{ $website->description ?? 'Tidak ada deskripsi.' }}
            </p>
        </div>

        <!-- Divider -->
        <hr class="border-gray-100 my-3">

        <!-- Footer Actions -->
        <div class="flex justify-between items-center">
            <div class="flex gap-4">
                <!-- Edit -->
                <a href="{{ route('websites.edit', $website) }}" class="text-gray-400 hover:text-blue-500 transition" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[16px] h-[16px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path><path d="M13.5 6.5l4 4"></path></svg>
                </a>
                <!-- Delete -->
                <button @click="showDeleteModal = true; deleteUrl = '{{ route('websites.destroy', $website) }}'; deleteName = '{{ addslashes($website->name) }}'" class="text-gray-400 hover:text-red-500 transition" title="Delete">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[16px] h-[16px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7l16 0"></path><path d="M10 11l0 6"></path><path d="M14 11l0 6"></path><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>
                </button>
                <!-- Favorite Toggle -->
                <button x-data="{ 
                        isFavorite: {{ $website->is_favorite ? 'true' : 'false' }},
                        isLoading: false,
                        toggle() {
                            if (this.isLoading) return;
                            this.isLoading = true;
                            fetch('{{ route('websites.favorite', $website->id) }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                            })
                            .then(r => r.json())
                            .then(d => { if (d.is_favorite !== undefined) this.isFavorite = d.is_favorite; })
                            .finally(() => this.isLoading = false);
                        }
                    }"
                    @click.prevent="toggle"
                    :disabled="isLoading"
                    class="text-gray-400 hover:text-primary transition"
                    :class="isFavorite ? 'text-primary' : ''"
                    title="Favorite">
                    <svg xmlns="http://www.w3.org/2000/svg" x-show="isFavorite" class="w-[16px] h-[16px] text-primary" style="display: none;" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037 .033l.034 -.03a6 6 0 0 1 4.733 -1.44l.246 .036a6 6 0 0 1 3.364 10.008l-.18 .185l-.048 .041l-7.45 7.379a1 1 0 0 1 -1.313 .082l-.094 -.082l-7.493 -7.422a6 6 0 0 1 3.176 -10.215z"></path></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" x-show="!isFavorite" class="w-[16px] h-[16px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path></svg>
                </button>
            </div>

            <a href="{{ $website->url }}" target="_blank" rel="noopener noreferrer" class="text-[12px] font-medium text-emerald-600 hover:text-emerald-700 transition">
                Kunjungi Link
            </a>
        </div>
    </div>
</div>
