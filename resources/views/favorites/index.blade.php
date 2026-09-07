<x-app-layout>
    <!-- Tambahkan state Delete Modal agar tombol hapus di dalam Card berfungsi -->
    <div class="max-w-7xl mx-auto space-y-6" x-data="{ showDeleteModal: false, deleteUrl: '', deleteName: '' }">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-14 h-14 bg-red-50 text-primary rounded-[16px] border border-red-100 shadow-sm shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037 .033l.034 -.03a6 6 0 0 1 4.733 -1.44l.246 .036a6 6 0 0 1 3.364 10.008l-.18 .185l-.048 .041l-7.45 7.379a1 1 0 0 1 -1.313 .082l-.094 -.082l-7.493 -7.422a6 6 0 0 1 3.176 -10.215z"></path></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-heading font-bold text-gray-900 leading-tight">Favorites</h2>
                    <p class="text-sm text-gray-500 mt-1">Kumpulan website favorit yang paling sering kamu gunakan.</p>
                </div>
            </div>
            <a href="{{ route('websites.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-gray-600 transition bg-white border border-gray-200 rounded-[14px] hover:bg-gray-50 shadow-sm">
                Lihat Semua Library
            </a>
        </div>

        <!-- Terapkan Grid 4 Kolom dan Panggil Partial Card -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse ($websites as $website)
                <!-- Memanggil komponen Card yang sama persis dengan halaman Index -->
                @include('websites.partials.website-card', ['website' => $website])
            @empty
                <!-- Empty State -->
                <div class="col-span-full flex flex-col items-center justify-center p-16 text-center bg-white rounded-[24px] shadow-sm border border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-red-50 text-primary rounded-[20px] border border-red-100 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path><path d="M12 12l-2 2l-2 -2"></path></svg>
                    </div>
                    <h3 class="text-lg font-heading font-bold text-gray-900">Belum Ada Favorit</h3>
                    <p class="mt-2 text-sm text-gray-500 mb-6 max-w-sm">Tandai website yang paling sering kamu gunakan dengan menekan ikon hati di Library.</p>
                    <a href="{{ route('websites.index') }}" class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-[14px] hover:bg-gray-100 shadow-sm transition">Jelajahi Library</a>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $websites->links() }}
        </div>

        <!-- Modal Konfirmasi Hapus (Sama dengan Index) -->
        <div x-show="showDeleteModal" x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-gray-900/40 backdrop-blur-sm">
            <div @click.away="showDeleteModal = false" class="w-full max-w-sm p-8 bg-white border border-gray-100 rounded-[24px] shadow-2xl">
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex items-center justify-center w-12 h-12 text-primary bg-red-50 rounded-[16px] shrink-0">
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
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-[14px] hover:bg-red-700 shadow-sm shadow-red-200 transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7l16 0"></path><path d="M10 11l0 6"></path><path d="M14 11l0 6"></path><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
