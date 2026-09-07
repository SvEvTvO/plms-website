<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-heading font-bold text-gray-900 leading-tight">Categories</h2>
                <p class="text-sm text-gray-500 mt-1">Organisasikan library-mu menggunakan folder utama dan sub-kategori.</p>
            </div>
            <a href="{{ route('master-categories.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white transition bg-primary rounded-[14px] hover:bg-primary-700 shadow-sm shadow-primary-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                Master Kategori Baru
            </a>
        </div>
    </x-slot>

    <div x-data="{ showDeleteModal: false, deleteUrl: '', deleteName: '', deleteWarning: '' }">

        @if (session('success') || session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 flex items-center gap-3 p-4 text-sm font-medium rounded-[16px] border {{ session('success') ? 'bg-emerald-50 text-emerald-800 border-emerald-100' : 'bg-red-50 text-red-800 border-red-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 {{ session('success') ? 'text-emerald-600' : 'text-red-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    @if(session('success'))
                        <path d="M5 12l5 5l10 -10"></path>
                    @else
                        <path d="M12 9v4"></path><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"></path><path d="M12 16h.01"></path>
                    @endif
                </svg>
                {{ session('success') ?? session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($masterCategories as $master)
                @php
                    // 1. Skema Gaya Warna Dinamis
                    $colorScheme = match($master->color ?? 'blue') {
                        'emerald' => [
                            'card_border' => 'border-t-[5px] border-t-emerald-500',
                            'icon_box' => 'bg-emerald-50 border-emerald-100 text-emerald-600',
                            'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        ],
                        'purple' => [
                            'card_border' => 'border-t-[5px] border-t-purple-500',
                            'icon_box' => 'bg-purple-50 border-purple-100 text-purple-600',
                            'badge' => 'bg-purple-50 text-purple-700 border-purple-200',
                        ],
                        'rose' => [
                            'card_border' => 'border-t-[5px] border-t-rose-500',
                            'icon_box' => 'bg-rose-50 border-rose-100 text-rose-600',
                            'badge' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ],
                        'amber' => [
                            'card_border' => 'border-t-[5px] border-t-amber-500',
                            'icon_box' => 'bg-amber-50 border-amber-100 text-amber-600',
                            'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                        ],
                        'cyan' => [
                            'card_border' => 'border-t-[5px] border-t-cyan-500',
                            'icon_box' => 'bg-cyan-50 border-cyan-100 text-cyan-600',
                            'badge' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                        ],
                        default => [ // 'blue'
                            'card_border' => 'border-t-[5px] border-t-blue-500',
                            'icon_box' => 'bg-blue-50 border-blue-100 text-blue-600',
                            'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
                        ],
                    };

                    // 2. Ikon Vektor Tabler
                    $iconSvg = match($master->icon) {
                        '🤖' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" /><path d="M2 8l4 0" /><path d="M18 8l4 0" /><path d="M2 16l4 0" /><path d="M18 16l4 0" /><path d="M9 4v-1" /><path d="M15 4v-1" /><path d="M9 12v.01" /><path d="M15 12v.01" /><path d="M9 16h6" /></svg>',
                        '🎨' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21a9 9 0 0 1 0 -18c4.97 0 9 3.582 9 8c0 1.06 -.474 2.078 -1.318 2.828c-.844 .75 -1.989 1.172 -3.182 1.172h-2.5a2 2 0 0 0 -1 3.75a1.3 1.3 0 0 1 -1 2.25" /><path d="M8.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M16.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>',
                        '💻' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 7l5 5l-5 5" /><path d="M12 19l7 0" /></svg>',
                        default => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>'
                    };
                @endphp

                <div class="bg-white border border-gray-100 rounded-[24px] shadow-[0_2px_12px_rgba(0,0,0,0.03)] flex flex-col overflow-hidden {{ $colorScheme['card_border'] }}">
                    <!-- Header Panel -->
                    <div class="px-5 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 {{ $colorScheme['icon_box'] }} rounded-[12px] flex items-center justify-center border shadow-sm shrink-0">
                                {!! $iconSvg !!}
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm truncate" title="{{ $master->name }}">
                                {{ $master->name }}
                            </h3>
                        </div>

                        <div class="flex items-center gap-1 shrink-0">
                            <!-- Tombol Edit Master Category -->
                            <a href="{{ route('master-categories.edit', $master) }}" class="p-1.5 text-gray-400 rounded-[10px] hover:bg-white hover:text-blue-500 transition border border-transparent hover:border-gray-200 hover:shadow-sm" title="Edit Folder Utama">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path><path d="M13.5 6.5l4 4"></path></svg>
                            </a>
                            <!-- Tombol Hapus Master Category -->
                            <button
                                type="button"
                                @click="showDeleteModal = true;
                                        deleteUrl = '{{ route('master-categories.destroy', $master) }}';
                                        deleteName = '{{ addslashes($master->name) }}';
                                        deleteWarning = {{ $master->categories->count() > 0 ? 'true' : 'false' }};"
                                class="p-1.5 text-gray-400 rounded-[10px] hover:bg-white hover:text-red-500 transition border border-transparent hover:border-gray-200 hover:shadow-sm"
                                title="Hapus Folder Utama">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7l16 0"></path><path d="M10 11l0 6"></path><path d="M14 11l0 6"></path><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 p-2">
                        <ul class="divide-y divide-gray-50">
                            @forelse ($master->categories as $category)
                                <li class="flex justify-between items-center px-4 py-3 hover:bg-gray-50 rounded-[14px] transition group">
                                    <span class="text-sm font-medium text-gray-700 truncate" title="{{ $category->name }}">{{ $category->name }}</span>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-[8px] border {{ $colorScheme['badge'] }} transition">
                                            {{ $category->websites_count }} items
                                        </span>
                                        <div class="hidden sm:flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('categories.edit', $category) }}" class="p-1 text-gray-400 rounded-[8px] hover:bg-white hover:text-blue-500 transition" title="Edit Sub-kategori">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path><path d="M13.5 6.5l4 4"></path></svg>
                                            </a>
                                            <button
                                                type="button"
                                                @click="showDeleteModal = true;
                                                        deleteUrl = '{{ route('categories.destroy', $category) }}';
                                                        deleteName = '{{ addslashes($category->name) }}';
                                                        deleteWarning = {{ $category->websites_count > 0 ? 'true' : 'false' }};"
                                                class="p-1 text-gray-400 rounded-[8px] hover:bg-white hover:text-red-500 transition"
                                                title="Hapus Sub-kategori">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7l16 0"></path><path d="M10 11l0 6"></path><path d="M14 11l0 6"></path><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="px-4 py-8 text-center text-sm text-gray-400 italic">Belum ada sub-kategori.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="p-4 border-t border-gray-100 bg-white">
                        <a href="{{ route('categories.create', ['master_id' => $master->id]) }}" class="block w-full py-2.5 text-xs font-bold text-center text-gray-500 border border-dashed border-gray-300 rounded-[12px] hover:border-primary hover:text-primary hover:bg-primary-50 transition">
                            + Tambah Kategori
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center p-16 text-center bg-white rounded-[24px] border border-dashed border-gray-200 shadow-sm">
                    <div class="w-16 h-16 bg-primary-50 text-primary rounded-[20px] flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Belum Ada Folder Utama</h3>
                    <p class="mt-2 text-sm text-gray-500 mb-6">Mulai organisasikan link kamu dengan membuat Master Kategori.</p>
                    <a href="{{ route('master-categories.create') }}" class="px-6 py-3 text-sm font-medium text-white bg-primary rounded-[14px] hover:bg-primary-700 shadow-sm">Buat Master Kategori</a>
                </div>
            @endforelse
        </div>

        <!-- Modal Konfirmasi Hapus (Master Category & Sub-kategori) -->
        <div x-show="showDeleteModal" x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-gray-900/40 backdrop-blur-sm">
            <div @click.away="showDeleteModal = false" class="w-full max-w-sm p-8 bg-white border border-gray-100 rounded-[24px] shadow-2xl">
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex items-center justify-center w-12 h-12 text-red-600 bg-red-50 rounded-[16px] shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"></path><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"></path><path d="M12 16h.01"></path></svg>
                    </div>
                    <h3 class="text-xl font-heading font-bold text-gray-900">Hapus Kategori</h3>
                </div>
                <p class="text-sm text-gray-500 mb-4 leading-relaxed">
                    Yakin ingin menghapus <strong x-text="deleteName" class="text-gray-900"></strong>? Tindakan ini bersifat permanen.
                </p>
                <p x-show="deleteWarning" style="display: none;" class="text-xs font-medium text-amber-700 bg-amber-50 border border-amber-100 rounded-[12px] px-3 py-2.5 mb-4">
                    Kategori ini masih berisi sub-kategori atau website. Anda mungkin perlu memindahkannya terlebih dahulu.
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
