<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Manajemen Label & Kategori</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola hierarki referensi pribadimu secara bebas di sini.</p>
            </div>
        </div>
    </x-slot>

    <!-- WRAPPER ALPINE.JS UNTUK MODAL -->
    <div x-data="taxonomyManager()" class="pb-10">

        <!-- Pesan Sukses -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600"><i class="ti ti-check text-lg"></i></div>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><i class="ti ti-x"></i></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside text-sm font-semibold">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <!-- LAYOUT 3 KOLOM SEJAJAR -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative z-10">

            <!-- ===================================== -->
            <!-- KOLOM 1: GRUP                         -->
            <!-- ===================================== -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col h-[600px] overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="ti ti-layout-grid text-primary"></i> 1. Grup Kategori</h3>
                    <button @click="openModal('group')" class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center hover:bg-primary-600 transition-colors shadow-sm" title="Tambah Grup">
                        <i class="ti ti-plus"></i>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto custom-scrollbar flex-1 space-y-3">
                    @forelse($groups as $group)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center"><i class="ti ti-{{ $group->icon ?? 'folder' }}"></i></div>
                                <span class="font-bold text-sm text-slate-700">{{ $group->name }}</span>
                            </div>
                            <form action="{{ route('taxonomy.group.destroy', $group->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus grup ini? Kategori di dalamnya tidak akan terhapus, namun tidak akan memiliki grup lagi.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors opacity-0 group-hover:opacity-100"><i class="ti ti-trash"></i></button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-sm font-medium">Belum ada Grup dibuat.</div>
                    @endforelse
                </div>
            </div>

            <!-- ===================================== -->
            <!-- KOLOM 2: KATEGORI                     -->
            <!-- ===================================== -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col h-[600px] overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="ti ti-folder text-primary"></i> 2. Kategori</h3>
                    <button @click="openModal('category')" class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center hover:bg-primary-600 transition-colors shadow-sm" title="Tambah Kategori">
                        <i class="ti ti-plus"></i>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto custom-scrollbar flex-1 space-y-3">
                    @forelse($categories as $category)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50 transition-colors group">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="flex items-center justify-center w-5 h-5 rounded" style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                                        <i class="ti ti-{{ $category->icon ?? 'folder' }} text-[10px]"></i>
                                    </div>
                                    <span class="font-bold text-sm text-slate-700">{{ $category->name }}</span>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-400 pl-7">Grup: {{ $category->group->name ?? 'Lainnya' }}</span>
                            </div>
                            <form action="{{ route('taxonomy.category.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Menghapus Kategori akan menghapus semua Tag di dalamnya secara permanen. Lanjutkan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors opacity-0 group-hover:opacity-100"><i class="ti ti-trash"></i></button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-sm font-medium">Belum ada Kategori dibuat.</div>
                    @endforelse
                </div>
            </div>

            <!-- ===================================== -->
            <!-- KOLOM 3: TAG                          -->
            <!-- ===================================== -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col h-[600px] overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="ti ti-hash text-primary"></i> 3. Tags</h3>
                    <button @click="openModal('tag')" class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center hover:bg-primary-600 transition-colors shadow-sm" title="Tambah Tag">
                        <i class="ti ti-plus"></i>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto custom-scrollbar flex-1 space-y-3">
                    @forelse($tags as $tag)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50 transition-colors group">
                            <div>
                                <span class="font-bold text-sm text-sky-600 bg-sky-50 px-2 py-0.5 rounded border border-sky-100">#{{ $tag->name }}</span>
                                <div class="text-[10px] font-semibold text-slate-400 mt-1.5">Kategori: {{ $tag->category->name ?? 'Terhapus' }}</div>
                            </div>
                            <form action="{{ route('taxonomy.tag.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tag ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors opacity-0 group-hover:opacity-100"><i class="ti ti-trash"></i></button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-sm font-medium">Belum ada Tag dibuat.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ======================================================= -->
        <!-- AREA POPUP / MODALS (ALPINE.JS)                         -->
        <!-- ======================================================= -->

        <!-- MODAL TAMBAH GRUP -->
        <div x-show="modals.group" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center">
            <div x-show="modals.group" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal('group')"></div>
            <div x-show="modals.group" x-transition.scale.origin.bottom class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-slate-100">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <h3 class="font-extrabold text-lg text-slate-800">Buat Grup Baru</h3>
                    <button @click="closeModal('group')" class="text-slate-400 hover:text-rose-500"><i class="ti ti-x text-xl"></i></button>
                </div>
                <form action="{{ route('taxonomy.group.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Nama Grup</label>
                        <input type="text" name="name" required placeholder="Contoh: Design & Kreatif" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary text-sm h-12">
                    </div>
                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Nama Icon (Tabler Icons) - Opsional</label>
                        <input type="text" name="icon" placeholder="Contoh: layout-grid" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary text-sm h-12">
                        <p class="text-[10px] mt-1 text-slate-400">Lihat referensi icon di tabler-icons.io</p>
                    </div>
                    <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl shadow-md hover:bg-primary-600 transition-colors">Simpan Grup</button>
                </form>
            </div>
        </div>

        <!-- MODAL TAMBAH KATEGORI -->
        <div x-show="modals.category" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center">
            <div x-show="modals.category" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal('category')"></div>
            <div x-show="modals.category" x-transition.scale.origin.bottom class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-slate-100">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <h3 class="font-extrabold text-lg text-slate-800">Buat Kategori Baru</h3>
                    <button @click="closeModal('category')" class="text-slate-400 hover:text-rose-500"><i class="ti ti-x text-xl"></i></button>
                </div>
                <form action="{{ route('taxonomy.category.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Induk Grup (Opsional)</label>
                        <select name="category_group_id" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary text-sm h-12">
                            <option value="">Tanpa Grup (Lainnya)</option>
                            @foreach($groups as $group) <option value="{{ $group->id }}">{{ $group->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: UI/UX Design" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary text-sm h-12">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Icon</label>
                            <input type="text" name="icon" placeholder="Contoh: palette" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary text-sm h-12">
                        </div>
                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Warna (Hex)</label>
                            <input type="color" name="color" value="#0089ba" class="w-full bg-slate-50 rounded-xl border-slate-200 p-1 cursor-pointer h-12">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl shadow-md hover:bg-primary-600 transition-colors">Simpan Kategori</button>
                </form>
            </div>
        </div>

        <!-- MODAL TAMBAH TAG -->
        <div x-show="modals.tag" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center">
            <div x-show="modals.tag" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal('tag')"></div>
            <div x-show="modals.tag" x-transition.scale.origin.bottom class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-slate-100">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <h3 class="font-extrabold text-lg text-slate-800">Buat Tag Baru</h3>
                    <button @click="closeModal('tag')" class="text-slate-400 hover:text-rose-500"><i class="ti ti-x text-xl"></i></button>
                </div>
                <form action="{{ route('taxonomy.tag.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Induk Kategori <span class="text-rose-500">*</span></label>
                        <select name="category_id" required class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary text-sm h-12">
                            <option value="" disabled selected>Pilih Kategori...</option>
                            @foreach($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
                        </select>
                        <p class="text-[10px] mt-1 text-slate-400">Tag terikat secara spesifik pada 1 kategori.</p>
                    </div>
                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Nama Tag <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Figma" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary text-sm h-12">
                    </div>
                    <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl shadow-md hover:bg-primary-600 transition-colors">Simpan Tag</button>
                </form>
            </div>
        </div>

    </div>

    <!-- SCRIPT ALPINE.JS MODAL MANAGER -->
    <script>
        function taxonomyManager() {
            return {
                modals: {
                    group: false,
                    category: false,
                    tag: false
                },
                openModal(type) {
                    this.modals[type] = true;
                    document.body.style.overflow = 'hidden'; // Kunci scroll layar utama
                },
                closeModal(type) {
                    this.modals[type] = false;
                    document.body.style.overflow = ''; // Kembalikan scroll
                }
            }
        }
    </script>
</x-app-layout>
