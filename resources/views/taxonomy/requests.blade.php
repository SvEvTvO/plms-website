<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Pengajuan Komunitas</h2>
            <p class="text-sm text-slate-500 mt-1">Ajukan referensi pribadimu agar dapat diakses dan bermanfaat bagi seluruh komunitas.</p>
        </div>
    </x-slot>

    <!-- WRAPPER ALPINE.JS -->
    <div x-data="requestFormManager()" class="max-w-5xl mx-auto pb-12 pt-6 grid grid-cols-1 lg:grid-cols-3 gap-8 relative">

        <!-- ============================================== -->
        <!-- KOLOM KIRI: FORM PENGAJUAN (Bisa Create & Edit)-->
        <!-- ============================================== -->
        <div class="lg:col-span-1">
            <div class="bg-white border shadow-sm rounded-3xl p-6 sticky top-8 z-40 transition-colors" :class="editMode ? 'border-amber-300' : 'border-slate-200'">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center border transition-colors" :class="editMode ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-sky-50 text-sky-600 border-sky-100'">
                            <i class="ti text-xl" :class="editMode ? 'ti-edit' : 'ti-send'"></i>
                        </div>
                        <h3 class="font-extrabold text-lg text-slate-800" x-text="editMode ? 'Edit Pengajuan' : 'Form Pengajuan'"></h3>
                    </div>
                    
                    <!-- Tombol Batal Edit -->
                    <button type="button" x-show="editMode" @click="cancelEdit()" style="display: none;" class="text-xs font-bold text-slate-400 hover:text-rose-500 bg-slate-50 hover:bg-rose-50 px-2 py-1 rounded-md transition-colors border border-slate-100 hover:border-rose-100">
                        Batal
                    </button>
                </div>

                <!-- PESAN SUKSES / ERROR (Opsional, di dalam form) -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-3 py-2.5 rounded-xl flex items-center justify-between shadow-sm text-sm">
                        <div class="flex items-center gap-2"><i class="ti ti-check text-emerald-600"></i> <span class="font-semibold">{{ session('success') }}</span></div>
                        <button @click="show = false" type="button" class="text-emerald-500 hover:text-emerald-700"><i class="ti ti-x"></i></button>
                    </div>
                @endif

                <form :action="formAction" method="POST" class="space-y-5">
                    @csrf
                    <!-- Inject Method PUT jika Mode Edit -->
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                    <!-- 1. CUSTOM SELECT: JENIS PENGAJUAN -->
                    <div class="relative" @click.away="isTypeOpen = false">
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Apa yang ingin kamu ajukan?</label>
                        <input type="hidden" name="type" :value="requestType">

                        <button type="button" @click.prevent="openTypeDropdown()" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm h-12 transition-colors flex items-center justify-between px-4 text-slate-700 font-semibold border" :disabled="editMode" :class="editMode ? 'opacity-60 cursor-not-allowed' : ''">
                            <span class="flex items-center gap-2.5">
                                <i class="ti text-lg" :class="typeIcon + ' ' + typeColor"></i> 
                                <span x-text="typeDisplay"></span>
                            </span>
                            <i class="ti ti-chevron-down text-slate-400 transition-transform" x-show="!editMode" :class="isTypeOpen ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="isTypeOpen && !editMode" style="display: none;" x-transition class="absolute z-50 left-0 right-0 top-full mt-2 p-1.5 bg-white border border-slate-200 shadow-xl rounded-xl overflow-hidden">
                            <button type="button" @click.prevent="setType('tag', 'Tag Baru', 'ti-hash', 'text-sky-500')" class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors flex items-center gap-2.5" :class="requestType === 'tag' ? 'bg-primary-50 text-primary' : 'text-slate-700'"><i class="ti ti-hash text-sky-500 text-lg"></i> Tag Baru</button>
                            <button type="button" @click.prevent="setType('category', 'Kategori Baru', 'ti-folder', 'text-rose-500')" class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors flex items-center gap-2.5" :class="requestType === 'category' ? 'bg-primary-50 text-primary' : 'text-slate-700'"><i class="ti ti-folder text-rose-500 text-lg"></i> Kategori Baru</button>
                            <button type="button" @click.prevent="setType('group', 'Grup Baru', 'ti-layout-grid', 'text-emerald-500')" class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors flex items-center gap-2.5" :class="requestType === 'group' ? 'bg-primary-50 text-primary' : 'text-slate-700'"><i class="ti ti-layout-grid text-emerald-500 text-lg"></i> Grup Baru</button>
                        </div>
                    </div>

                    <!-- 2. SEARCHABLE DROPDOWN NAMA -->
                    <div class="relative" @click.away="isNameOpen = false">
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Nama <span x-text="typeDisplay.split(' ')[0]"></span> <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="ti ti-pencil text-lg"></i></div>
                            <input type="text" name="name" required x-model="nameSearch" @focus="openNameDropdown()" @click="openNameDropdown()" placeholder="Ketik baru atau pilih..." autocomplete="off" class="w-full pl-11 bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm h-12 transition-colors font-semibold text-slate-700">
                        </div>
                        <div x-show="isNameOpen" style="display: none;" x-transition class="absolute z-50 left-0 right-0 top-full mt-2 bg-white border border-slate-200 shadow-xl rounded-xl overflow-hidden flex flex-col">
                            <div class="max-h-56 overflow-y-auto overscroll-contain custom-scrollbar p-1.5">
                                <button type="button" x-show="nameSearch.trim() !== ''" @click.prevent="isNameOpen = false" class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-bold text-primary bg-primary-50 hover:bg-primary-100 transition-colors flex items-center gap-2.5 mb-2 border border-primary-100">
                                    <i class="ti ti-plus text-lg"></i> Ajukan "<span x-text="nameSearch"></span>"
                                </button>
                                <div class="px-3 py-1.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1 border-t border-slate-100 pt-2 mt-1">Pilih Dari Koleksi Pribadi:</div>
                                <div x-show="filteredPrivate.length === 0" class="p-2 text-center text-xs text-slate-400 italic">Tidak ada nama yang cocok di koleksimu.</div>
                                <template x-for="item in filteredPrivate" :key="'name-'+item.id">
                                    <button type="button" @click.prevent="nameSearch = item.name; isNameOpen = false" class="w-full text-left px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors flex items-center gap-2.5">
                                        <i class="ti text-lg text-slate-400" :class="typeIcon"></i> <span x-text="item.name"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- 3. SEARCHABLE DROPDOWN INDUK: (HANYA PUBLIK) -->
                    <div x-show="requestType !== 'group'" x-transition class="relative" @click.away="closeParentDropdown()">
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Masuk ke <span x-text="requestType === 'tag' ? 'Kategori Global' : 'Grup Global'"></span> mana? <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="parent_id" :value="parentId" :required="requestType !== 'group'">
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors" :class="isParentOpen ? 'text-indigo-500' : 'text-slate-400'"><i class="ti text-lg" :class="isParentOpen ? 'ti-search' : (requestType === 'tag' ? 'ti-folder' : 'ti-layout-grid')"></i></div>
                            <input type="text" x-model="parentSearch" @focus="openParentDropdown()" @click="openParentDropdown()" placeholder="Cari Induk Publik..." autocomplete="off" class="w-full pl-11 pr-10 bg-indigo-50/50 rounded-xl border-indigo-100 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm h-12 transition-colors font-semibold text-indigo-900 placeholder:text-indigo-400 placeholder:font-normal">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-indigo-400"><i class="ti ti-chevron-up transition-transform" :class="{'rotate-180 text-indigo-600': isParentOpen}"></i></div>
                        </div>

                        <!-- Drop-Up Induk (Membuka ke atas) -->
                        <div x-show="isParentOpen" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute z-50 left-0 right-0 bottom-[calc(100%+8px)] bg-white border border-slate-200 shadow-xl shadow-slate-200/50 rounded-xl overflow-hidden flex flex-col origin-bottom">
                            <div class="px-4 py-2.5 bg-indigo-50 border-b border-indigo-100 text-xs font-bold text-indigo-700 flex items-center gap-1.5"><i class="ti ti-world"></i> Database Publik</div>
                            <div class="max-h-56 overflow-y-auto overscroll-contain custom-scrollbar p-1.5 flex flex-col">
                                <div x-show="filteredPublic.length === 0" class="p-4 text-center">
                                    <i class="ti ti-mood-empty text-2xl text-slate-300 mb-1"></i>
                                    <p class="text-xs text-slate-500">Tidak ada induk publik yang cocok.</p>
                                </div>
                                <template x-for="item in filteredPublic" :key="'pub-'+item.id">
                                    <button type="button" @click.prevent="selectParent(item.id, item.name)" class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center justify-between" :class="parentId == item.id ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50'">
                                        <span class="flex items-center gap-2.5"><i class="ti text-lg" :class="requestType === 'tag' ? 'ti-folder text-amber-500' : 'ti-layout-grid text-emerald-500'"></i> <span x-text="item.name"></span></span>
                                        <i x-show="parentId == item.id" class="ti ti-check text-indigo-600 text-lg"></i>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 mt-2 text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2" :class="editMode ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/30' : 'bg-primary hover:bg-primary-600 shadow-primary/30'">
                        <i class="ti text-lg" :class="editMode ? 'ti-device-floppy' : 'ti-rocket'"></i> 
                        <span x-text="editMode ? 'Perbarui Pengajuan' : 'Kirim Pengajuan'"></span>
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- KOLOM KANAN: RIWAYAT PENGAJUAN                 -->
        <!-- ============================================== -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200 shadow-sm rounded-3xl p-6 lg:p-8 h-full">
                <h3 class="font-extrabold text-lg text-slate-800 mb-6 flex items-center gap-2">
                    <i class="ti ti-history text-primary"></i> Riwayat Pengajuan
                </h3>
                
                @if($requests->count() > 0)
                    <div class="space-y-3">
                        @foreach($requests as $req)
                            
                            <!-- LOGIKA MENCARI NAMA INDUK DARI KOLEKSI PUBLIK MENGGUNAKAN target_parent_id -->
                            @php
                                $parentName = null;
                                if ($req->type === 'category' && $req->target_parent_id) {
                                    $parent = $publicGroups->firstWhere('id', $req->target_parent_id);
                                    $parentName = $parent ? $parent->name : 'Grup ID: '.$req->target_parent_id;
                                } elseif ($req->type === 'tag' && $req->target_parent_id) {
                                    $parent = $publicCategories->firstWhere('id', $req->target_parent_id);
                                    $parentName = $parent ? $parent->name : 'Kategori ID: '.$req->target_parent_id;
                                }
                            @endphp

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 hover:border-slate-200 transition-all gap-4 group">
                                <div class="flex items-start sm:items-center gap-4 w-full">
                                    
                                    @if($req->type === 'tag')
                                        <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center shrink-0 border border-sky-200"><i class="ti ti-hash text-2xl"></i></div>
                                    @elseif($req->type === 'category')
                                        <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200"><i class="ti ti-folder text-2xl"></i></div>
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-200"><i class="ti ti-layout-grid text-2xl"></i></div>
                                    @endif

                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-800 text-sm sm:text-base group-hover:text-primary transition-colors">{{ $req->name }}</h4>
                                        <div class="flex flex-wrap items-center gap-2 mt-1">
                                            <span class="text-xs text-slate-500 font-semibold capitalize">{{ $req->type }} Baru</span>
                                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                            <span class="text-[11px] text-slate-400 font-medium">{{ $req->created_at->diffForHumans() }}</span>
                                            
                                            <!-- MENAMPILKAN INDUK PUBLIK -->
                                            @if($parentName)
                                                <span class="w-1 h-1 rounded-full bg-slate-300 hidden sm:block"></span>
                                                <span class="text-[11px] font-bold text-indigo-500 flex items-center gap-1 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
                                                    <i class="ti ti-arrow-forward-up"></i> {{ $parentName }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 flex items-center justify-between sm:justify-end gap-3 sm:gap-4 w-full sm:w-auto mt-3 sm:mt-0 pt-3 sm:pt-0 border-t sm:border-0 border-slate-100">
                                    
                                    <!-- TOMBOL EDIT & HAPUS (Hanya muncul jika Pending) -->
                                    @if($req->status === 'pending')
                                        <div class="flex items-center gap-1.5">
                                            <!-- PASTIKAN MENGIRIMKAN target_parent_id -->
                                            <button @click="editRequest({{ $req->id }}, '{{ $req->type }}', '{{ addslashes($req->name) }}', '{{ $req->target_parent_id }}')" class="w-8 h-8 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 flex items-center justify-center transition-colors shadow-sm border border-transparent hover:border-amber-200" title="Edit Pengajuan"><i class="ti ti-edit text-lg"></i></button>
                                            <button @click="deleteRequest({{ $req->id }}, '{{ addslashes($req->name) }}')" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 flex items-center justify-center transition-colors shadow-sm border border-transparent hover:border-rose-200" title="Batalkan Pengajuan"><i class="ti ti-trash text-lg"></i></button>
                                        </div>
                                    @endif

                                    <!-- BADGE STATUS -->
                                    @if($req->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 text-[11px] font-extrabold uppercase tracking-wider rounded-lg border border-emerald-200 shadow-sm w-fit">
                                            <i class="ti ti-check text-base"></i> Diterima
                                        </span>
                                    @elseif($req->status === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 text-[11px] font-extrabold uppercase tracking-wider rounded-lg border border-rose-200 shadow-sm w-fit">
                                            <i class="ti ti-x text-base"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-600 text-[11px] font-extrabold uppercase tracking-wider rounded-lg border border-amber-200 shadow-sm w-fit">
                                            <i class="ti ti-clock text-base animate-pulse"></i> Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-16 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 h-full flex flex-col items-center justify-center">
                        <div class="w-14 h-14 bg-white border border-slate-100 rounded-full flex items-center justify-center text-slate-300 mb-3 mx-auto shadow-sm"><i class="ti ti-inbox text-3xl"></i></div>
                        <p class="text-sm font-semibold text-slate-600">Belum ada riwayat pengajuan.</p>
                        <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Ajukan label, kategori, atau grup pertamamu melalui form di samping.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- MODAL KONFIRMASI HAPUS PENGAJUAN -->
        <div x-show="deleteModal.isOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="deleteModal.isOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="deleteModal.isOpen = false"></div>
            <div x-show="deleteModal.isOpen" x-transition.scale.origin.bottom class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 text-center z-50">
                <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center mx-auto mb-4"><i class="ti ti-alert-triangle text-3xl"></i></div>
                <h3 class="font-extrabold text-xl text-slate-800 mb-2">Batalkan Pengajuan?</h3>
                <p class="text-sm text-slate-500 mb-6">Apakah kamu yakin ingin membatalkan pengajuan <strong>"<span x-text="deleteModal.name"></span>"</strong>?</p>
                
                <form :action="deleteModal.actionUrl" method="POST" class="flex items-center gap-3">
                    @csrf @method('DELETE')
                    <button type="button" @click="deleteModal.isOpen = false" class="flex-1 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors">Kembali</button>
                    <button type="submit" class="flex-1 py-2.5 bg-rose-500 text-white font-bold rounded-xl hover:bg-rose-600 shadow-lg shadow-rose-500/30 transition-colors">Ya, Batalkan</button>
                </form>
            </div>
        </div>

    </div>

    <!-- SCRIPT ALPINE.JS -->
    <script>
        function requestFormManager() {
            return {
                requestType: 'tag',
                typeDisplay: 'Tag Baru',
                typeIcon: 'ti-hash',
                typeColor: 'text-sky-500',
                isTypeOpen: false,

                nameSearch: '',
                isNameOpen: false,

                parentId: '',
                parentSearch: '',
                parentNameDisplay: '',
                isParentOpen: false,

                // STATE EDIT MODE
                editMode: false,
                editId: null,
                formAction: '{{ route('taxonomy.requests.store') }}',

                // STATE DELETE MODAL
                deleteModal: { isOpen: false, id: null, name: '', actionUrl: '' },

                privateGroups: @json($privateGroups),
                privateCategories: @json($privateCategories),
                privateTags: @json($privateTags),
                publicGroups: @json($publicGroups),
                publicCategories: @json($publicCategories),

                openTypeDropdown() { this.isTypeOpen = !this.isTypeOpen; this.isNameOpen = false; this.isParentOpen = false; },
                openNameDropdown() { this.isNameOpen = true; this.isTypeOpen = false; this.isParentOpen = false; },
                openParentDropdown() { this.isParentOpen = true; this.isTypeOpen = false; this.isNameOpen = false; },

                setType(type, display, icon, color) {
                    this.requestType = type; this.typeDisplay = display; this.typeIcon = icon; this.typeColor = color; this.isTypeOpen = false;
                    this.nameSearch = ''; this.parentId = ''; this.parentSearch = ''; this.parentNameDisplay = '';
                },

                get filteredPrivate() {
                    const query = this.nameSearch.toLowerCase().trim();
                    let list = [];
                    if (this.requestType === 'group') list = this.privateGroups;
                    if (this.requestType === 'category') list = this.privateCategories;
                    if (this.requestType === 'tag') list = this.privateTags;
                    return query !== '' ? list.filter(item => item.name.toLowerCase().includes(query)) : list;
                },

                get filteredPublic() {
                    const query = this.parentSearch.toLowerCase().trim();
                    let list = [];
                    if (this.requestType === 'category') list = this.publicGroups;
                    if (this.requestType === 'tag') list = this.publicCategories;
                    return query !== '' ? list.filter(item => item.name.toLowerCase().includes(query)) : list;
                },

                selectParent(id, name) {
                    this.parentId = id; this.parentSearch = name; this.parentNameDisplay = name; this.isParentOpen = false;
                },

                closeParentDropdown() {
                    this.isParentOpen = false; this.parentSearch = this.parentNameDisplay; 
                },

                // FUNGSI EDIT PENGAJUAN
                editRequest(id, type, name, parent_id) {
                    this.editMode = true;
                    this.editId = id;
                    this.formAction = '/taxonomy-requests/' + id; 

                    // Set Type
                    let display = 'Tag Baru', icon = 'ti-hash', color = 'text-sky-500';
                    if(type === 'category') { display = 'Kategori Baru'; icon = 'ti-folder'; color = 'text-rose-500'; }
                    if(type === 'group') { display = 'Grup Baru'; icon = 'ti-layout-grid'; color = 'text-emerald-500'; }
                    
                    this.requestType = type; this.typeDisplay = display; this.typeIcon = icon; this.typeColor = color;
                    
                    // Set Name
                    this.nameSearch = name;

                    // Set Parent
                    this.parentId = parent_id || '';
                    if(this.parentId) {
                        let p = null;
                        if (type === 'category') p = this.publicGroups.find(g => g.id == this.parentId);
                        if (type === 'tag') p = this.publicCategories.find(c => c.id == this.parentId);
                        this.parentNameDisplay = p ? p.name : '';
                        this.parentSearch = this.parentNameDisplay;
                    } else {
                        this.parentNameDisplay = '';
                        this.parentSearch = '';
                    }

                    // Auto scroll ke atas
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                // FUNGSI BATAL EDIT
                cancelEdit() {
                    this.editMode = false;
                    this.editId = null;
                    this.formAction = '{{ route('taxonomy.requests.store') }}'; 
                    this.setType('tag', 'Tag Baru', 'ti-hash', 'text-sky-500'); 
                },

                // FUNGSI DELETE MODAL
                deleteRequest(id, name) {
                    this.deleteModal.id = id;
                    this.deleteModal.name = name;
                    this.deleteModal.actionUrl = '/taxonomy-requests/' + id;
                    this.deleteModal.isOpen = true;
                }
            }
        }
    </script>
</x-app-layout>
