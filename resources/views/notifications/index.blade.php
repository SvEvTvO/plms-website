<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Riwayat Notifikasi</h2>
                <p class="text-sm text-slate-500 mt-1">Pantau semua aktivitas, pengajuan, dan pemberitahuan sistem di sini.</p>
            </div>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <button onclick="markAll()" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-200 transition-colors shadow-sm">
                    <i class="ti ti-checks text-lg align-text-bottom"></i> Tandai Semua Dibaca
                </button>
            @endif
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-10">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden divide-y divide-slate-100">
            @forelse($notifications as $notif)
                <div class="p-5 flex gap-4 {{ is_null($notif->read_at) ? 'bg-sky-50/50' : 'hover:bg-slate-50 transition-colors' }}">

                    <!-- Ikon Berdasarkan Status -->
                    @if(isset($notif->data['status']) && $notif->data['status'] === 'approved')
                        <div class="w-10 h-10 rounded-full bg-emerald-100 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0 shadow-sm"><i class="ti ti-check text-xl"></i></div>
                    @elseif(isset($notif->data['status']) && $notif->data['status'] === 'rejected')
                        <div class="w-10 h-10 rounded-full bg-rose-100 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0 shadow-sm"><i class="ti ti-x text-xl"></i></div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-primary-100 border border-primary-200 text-primary-600 flex items-center justify-center shrink-0 shadow-sm"><i class="ti ti-flame text-xl"></i></div>
                    @endif

                    <div class="flex-1">
                        <p class="text-sm text-slate-700 leading-relaxed mb-2">{!! $notif->data['message'] !!}</p>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold text-slate-400"><i class="ti ti-clock"></i> {{ $notif->created_at->diffForHumans() }}</span>

                            @if(is_null($notif->read_at))
                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                <span class="text-xs font-bold text-primary">Baru</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-16 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 border border-slate-100"><i class="ti ti-bell-z text-3xl"></i></div>
                    <h3 class="font-bold text-slate-700 text-lg mb-1">Pemberitahuan Kosong</h3>
                    <p class="text-sm text-slate-500">Kamu belum memiliki riwayat notifikasi apapun.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    </div>

    <script>
        function markAll() {
            fetch('{{ route('notifications.markAllRead') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => window.location.reload());
        }
    </script>
</x-app-layout>
