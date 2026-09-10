<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Pengajuan Komunitas</h2>
                <p class="text-sm text-slate-500 mt-1">Bantu kami melengkapi direktori publik dengan mengajukan Grup, Kategori, atau Tag baru.</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-10">

        <!-- KOLOM KIRI: FORM PENGAJUAN -->
        <div class="lg:col-span-1 space-y-6">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0"><i class="ti ti-check text-lg"></i></div>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl shadow-sm">
                    <ul class="list-disc list-inside text-sm font-semibold">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden" x-data="{ type: 'tag' }">
                <h3 class="font-extrabold text-lg text-slate-800 mb-6 flex items-center gap-2">
                    <i class="ti ti-send text-primary"></i> Form Pengajuan
                </h3>

                <form action="{{ route('taxonomy.requests.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <!-- 1. Pilih Tipe -->
                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Apa yang ingin kamu ajukan?</label>
                        <select name="type" x-model="type" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm h-12 font-semibold text-slate-700">
                            <option value="tag">🏷️ Tag Baru</option>
                            <option value="category">📁 Kategori Baru</option>
                            <option value="group">🔥 Grup Baru</option>
                        </select>
                    </div>

                    <!-- 2. Nama -->
                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Nama <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: React Native" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm h-12">
                    </div>

                    <!-- 3. Induk Grup (MUNCUL JIKA KATEGORI) -->
                    <div x-show="type === 'category'" style="display: none;" x-transition>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Masuk ke Grup Mana?</label>
                        <select name="target_parent_id" :required="type === 'category'" :disabled="type !== 'category'" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm h-12">
                            <option value="" disabled selected>Pilih Induk Grup Global...</option>
                            @foreach($adminGroups as $g) <option value="{{ $g->id }}">{{ $g->name }}</option> @endforeach
                        </select>
                    </div>

                    <!-- 4. Induk Kategori (MUNCUL JIKA TAG) -->
                    <div x-show="type === 'tag'" x-transition>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Masuk ke Kategori Mana? <span class="text-rose-500">*</span></label>
                        <select name="target_parent_id" :required="type === 'tag'" :disabled="type !== 'tag'" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm h-12">
                            <option value="" disabled selected>Pilih Induk Kategori Global...</option>
                            @foreach($adminCategories as $c) <option value="{{ $c->id }}">[{{ $c->group->name ?? 'Lainnya' }}] - {{ $c->name }}</option> @endforeach
                        </select>
                    </div>

                    <!-- 5. Warna & Icon (MUNCUL JIKA GRUP / KATEGORI) -->
                    <div x-show="type !== 'tag'" style="display: none;" x-transition class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Icon (Tabler)</label>
                            <input type="text" name="icon" placeholder="e.g. folder" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm h-12">
                        </div>
                        <div x-show="type === 'category'">
                            <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Warna Kategori</label>
                            <input type="color" name="color" value="#3b82f6" class="w-full bg-slate-50 rounded-xl border-slate-200 p-1 h-12 cursor-pointer">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl shadow-md hover:bg-primary-600 transition-colors flex items-center justify-center gap-2">
                        <i class="ti ti-rocket"></i> Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: RIWAYAT PENGAJUAN -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="ti ti-history text-primary"></i> Riwayat Pengajuanmu</h3>
                    <span class="text-xs font-bold bg-slate-200 text-slate-600 px-3 py-1 rounded-lg">{{ $requests->count() }} Total</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($requests as $req)
                        <div class="p-5 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border 
                                        {{ $req->type === 'group' ? 'bg-rose-50 border-rose-100 text-rose-500' : ($req->type === 'category' ? 'bg-amber-50 border-amber-100 text-amber-500' : 'bg-sky-50 border-sky-100 text-sky-500') }}">
                                        <i class="ti {{ $req->type === 'group' ? 'ti-layout-grid' : ($req->type === 'category' ? 'ti-folder' : 'ti-hash') }} text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="font-extrabold text-slate-800 text-base">{{ $req->name }}</h4>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-0.5 rounded">{{ $req->type }}</span>
                                        </div>
                                        
                                        <!-- Tampilkan Induk Target -->
                                        @if($req->type === 'category')
                                            <p class="text-xs text-slate-500">Target Grup: <span class="font-semibold text-slate-700">{{ $req->targetGroup->name ?? 'Tanpa Grup' }}</span></p>
                                        @elseif($req->type === 'tag')
                                            <p class="text-xs text-slate-500">Target Kategori: <span class="font-semibold text-slate-700">{{ $req->targetCategory->name ?? 'Tidak ditemukan' }}</span></p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Status Badges -->
                                <div class="text-right shrink-0">
                                    @if($req->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-600 border border-amber-200 rounded-lg text-[10px] font-extrabold uppercase tracking-wider shadow-sm"><i class="ti ti-clock"></i> Ditinjau</span>
                                    @elseif($req->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-[10px] font-extrabold uppercase tracking-wider shadow-sm"><i class="ti ti-check"></i> Diterima</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 rounded-lg text-[10px] font-extrabold uppercase tracking-wider shadow-sm"><i class="ti ti-x"></i> Ditolak</span>
                                    @endif
                                    <div class="text-[10px] text-slate-400 mt-2 font-medium">{{ $req->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                            
                            <!-- Catatan Penolakan Admin -->
                            @if($req->status === 'rejected' && $req->admin_notes)
                                <div class="mt-3 p-3 bg-rose-50/50 border border-rose-100 rounded-xl">
                                    <p class="text-xs text-rose-600"><span class="font-bold">Catatan Admin:</span> {{ $req->admin_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-12 text-center flex flex-col items-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4 border border-slate-100"><i class="ti ti-send text-3xl"></i></div>
                            <h3 class="text-slate-700 font-bold text-lg mb-1">Belum Ada Pengajuan</h3>
                            <p class="text-sm text-slate-500">Ajukan grup, kategori, atau tag pertamamu melalui form di samping.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
