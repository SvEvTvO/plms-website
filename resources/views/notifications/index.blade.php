<x-app-layout>
    
    <!-- SLOT HEADER: Dipisah dan menggunakan $dispatch agar bisa komunikasi dengan Alpine di body -->
    <x-slot name="header">
        <div x-data class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Riwayat Notifikasi</h2>
                <p class="text-sm text-slate-500 mt-1">Pantau semua aktivitas, pengajuan, dan pemberitahuan sistem di sini.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <!-- Tombol Tandai Semua -->
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-50 hover:text-primary transition-all shadow-sm flex items-center gap-2">
                            <i class="ti ti-checks text-lg"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                @endif

                <!-- Tombol Bersihkan Semua (Memicu Custom Modal) -->
                @if(auth()->user()->notifications->count() > 0)
                    <button type="button" @click="$dispatch('open-clear-modal')" class="px-4 py-2.5 bg-rose-50 border border-rose-100 text-rose-600 font-bold text-sm rounded-xl hover:bg-rose-100 transition-all shadow-sm flex items-center gap-2" title="Bersihkan Semua">
                        <i class="ti ti-trash-x text-lg"></i> Hapus Semua
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- WRAPPER ALPINE UTAMA -->
    <div x-data="{ isClearModalOpen: false }" @open-clear-modal.window="isClearModalOpen = true" class="max-w-4xl mx-auto pb-12 pt-4 space-y-6 relative">
        
        <!-- Pesan Sukses -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600"><i class="ti ti-check"></i></div>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><i class="ti ti-x"></i></button>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($notifications as $notif)
                    <div class="p-5 sm:p-6 flex gap-4 sm:gap-5 group relative transition-colors hover:bg-slate-50 {{ is_null($notif->read_at) ? 'bg-sky-50/30' : '' }}">
                        
                        @if(is_null($notif->read_at))
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary"></div>
                        @endif

                        <!-- FIX: IKON DIPERBAIKI MENGGUNAKAN STANDAR TABLER YANG PASTI MUNCUL -->
                        @if(isset($notif->data['status']) && $notif->data['status'] === 'approved')
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0 shadow-sm"><i class="ti ti-check text-3xl"></i></div>
                        @elseif(isset($notif->data['status']) && $notif->data['status'] === 'rejected')
                            <div class="w-12 h-12 rounded-2xl bg-rose-100 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0 shadow-sm"><i class="ti ti-x text-3xl"></i></div>
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-primary-50 border border-primary-100 text-primary-600 flex items-center justify-center shrink-0 shadow-sm"><i class="ti ti-bell text-3xl"></i></div>
                        @endif

                        <div class="flex-1 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    @if(is_null($notif->read_at))
                                        <span class="px-2 py-0.5 rounded-md bg-primary text-white text-[10px] font-extrabold uppercase tracking-widest shadow-sm">Baru</span>
                                    @endif
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="ti ti-clock"></i> {{ $notif->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-800 leading-relaxed font-medium">{!! $notif->data['message'] !!}</p>
                            </div>

                            <div class="flex items-center gap-2 sm:opacity-0 group-hover:opacity-100 transition-opacity shrink-0 border-t sm:border-0 pt-3 sm:pt-0 border-slate-100">
                                @if(is_null($notif->read_at))
                                    <form action="{{ route('notifications.markRead', $notif->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors shadow-sm border border-transparent hover:border-emerald-200" title="Tandai sudah dibaca">
                                            <i class="ti ti-check text-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors shadow-sm border border-transparent hover:border-rose-200" title="Hapus notifikasi ini">
                                        <i class="ti ti-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4 border border-slate-100 shadow-sm"><i class="ti ti-bell-z text-3xl"></i></div>
                        <h3 class="font-bold text-slate-800 text-lg mb-1">Pemberitahuan Kosong</h3>
                        <p class="text-sm text-slate-500">Kamu belum memiliki riwayat notifikasi apapun saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-8">
            {{ $notifications->links() }}
        </div>

        <!-- ========================================================== -->
        <!-- CUSTOM MODAL: KONFIRMASI HAPUS SEMUA NOTIFIKASI            -->
        <!-- ========================================================== -->
        <div x-show="isClearModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="isClearModalOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isClearModalOpen = false"></div>
            
            <div x-show="isClearModalOpen" x-transition.scale.origin.bottom class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 text-center z-50">
                <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-alert-triangle text-3xl"></i>
                </div>
                <h3 class="font-extrabold text-xl text-slate-800 mb-2">Bersihkan Semua?</h3>
                <p class="text-sm text-slate-500 mb-6">Apakah kamu yakin ingin menghapus seluruh riwayat notifikasi? Tindakan ini tidak dapat dibatalkan.</p>
                
                <form action="{{ route('notifications.clearAll') }}" method="POST" class="flex items-center gap-3">
                    @csrf @method('DELETE')
                    <button type="button" @click="isClearModalOpen = false" class="flex-1 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-rose-500 text-white font-bold rounded-xl hover:bg-rose-600 shadow-lg shadow-rose-500/30 transition-colors">Ya, Bersihkan</button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
