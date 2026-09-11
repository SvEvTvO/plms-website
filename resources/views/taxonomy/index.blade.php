<x-app-layout>
    
    <!-- SLOT HEADER DIKELUARKAN & DIBERI x-data KOSONG AGAR ALPINE AKTIF -->
    <x-slot name="header">
        <div x-data class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Manajemen Label & Kategori</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola hierarki referensi pribadimu secara bebas di sini.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="button" @click="$dispatch('open-taxonomy-modal', { mode: 'create', type: 'group' })" class="px-4 py-2 bg-emerald-50 text-emerald-600 font-bold text-sm rounded-xl shadow-sm border border-emerald-200 hover:bg-emerald-100 transition-colors flex items-center gap-2">
                    <i class="ti ti-layout-grid-add text-lg"></i> Grup
                </button>
                <button type="button" @click="$dispatch('open-taxonomy-modal', { mode: 'create', type: 'category' })" class="px-4 py-2 bg-rose-50 text-rose-600 font-bold text-sm rounded-xl shadow-sm border border-rose-200 hover:bg-rose-100 transition-colors flex items-center gap-2">
                    <i class="ti ti-folder-plus text-lg"></i> Kategori
                </button>
                <button type="button" @click="$dispatch('open-taxonomy-modal', { mode: 'create', type: 'tag' })" class="px-4 py-2 bg-sky-50 text-sky-600 font-bold text-sm rounded-xl shadow-sm border border-sky-200 hover:bg-sky-100 transition-colors flex items-center gap-2">
                    <i class="ti ti-hash text-lg"></i> Tag
                </button>
            </div>
        </div>
    </x-slot>

    <!-- WRAPPER ALPINE UTAMA -->
    <div x-data="taxonomyManager()" @open-taxonomy-modal.window="openFormModal($event.detail.mode, $event.detail.type, $event.detail.data)">

        <!-- Pesan Sukses -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600"><i class="ti ti-check"></i></div>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><i class="ti ti-x"></i></button>
            </div>
        @endif

        <div class="max-w-5xl mx-auto pb-12">
            
            <!-- ============================================== -->
            <!-- KOMPONEN: TAGS BARU DIBUAT                     -->
            <!-- ============================================== -->
            @if($recentTags->count() > 0)
                <div class="mb-8">
                    <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-4 pl-2">Tags Baru Dibuat</h3>
                    <div class="flex flex-nowrap overflow-x-auto gap-3 pb-3 custom-scrollbar snap-x">
                        @foreach($recentTags as $tag)
                            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm shrink-0 w-56 snap-start hover:border-sky-300 transition-colors group">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-sky-600 mb-1 flex items-center gap-1.5"><i class="ti ti-hash opacity-60"></i> {{ $tag->name }}</h4>
                                        <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider line-clamp-1"><i class="ti ti-folder text-slate-400"></i> {{ $tag->category->name ?? 'Tanpa Kategori' }}</p>
                                    </div>
                                    <div class="flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="openFormModal('edit', 'tag', {id: {{ $tag->id }}, name: '{{ addslashes($tag->name) }}', parent_id: '{{ $tag->category_id }}'})" class="text-slate-400 hover:text-amber-500"><i class="ti ti-edit"></i></button>
                                        <button @click="openDeleteModal('tag', {{ $tag->id }}, '{{ addslashes($tag->name) }}')" class="text-slate-400 hover:text-rose-500"><i class="ti ti-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-4 pl-2">Koleksi Terstruktur</h3>
            <div class="space-y-6">

                <!-- ============================================== -->
                <!-- AREA 1: KATEGORI TANPA GRUP (Jika Ada)         -->
                <!-- ============================================== -->
                @if($ungroupedCategories->count() > 0)
                    <div class="space-y-4 mb-6">
                        @foreach($ungroupedCategories as $category)
                            <div x-data="{ isCatOpen: false }" class="bg-white border border-slate-200 shadow-sm rounded-2xl overflow-hidden transition-all hover:border-slate-300 relative before:absolute before:left-0 before:top-0 before:bottom-0 before:w-1 before:bg-rose-400">
                                <div @click="isCatOpen = !isCatOpen" class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 transition-colors select-none">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center border border-rose-100"><i class="ti ti-{{ $category->icon ?? 'folder' }} text-xl"></i></div>
                                        <div>
                                            <h4 class="font-bold text-slate-800">{{ $category->name }}</h4>
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $category->tags->count() }} Tags</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button @click.stop="openFormModal('create', 'tag', { parent_id: '{{ $category->id }}' })" class="w-8 h-8 rounded-lg text-slate-400 hover:text-sky-500 hover:bg-sky-50 flex items-center justify-center transition-colors" title="Tambah Tag Disini"><i class="ti ti-plus text-lg"></i></button>
                                        <div class="w-px h-5 bg-slate-200 mx-1"></div>
                                        
                                        <button @click.stop="openFormModal('edit', 'category', {id: {{ $category->id }}, name: '{{ addslashes($category->name) }}', icon: '{{ $category->icon ?? 'folder' }}', parent_id: ''})" class="w-8 h-8 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 flex items-center justify-center transition-colors" title="Edit"><i class="ti ti-edit text-lg"></i></button>
                                        <button @click.stop="openDeleteModal('category', {{ $category->id }}, '{{ addslashes($category->name) }}')" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 flex items-center justify-center transition-colors" title="Hapus"><i class="ti ti-trash text-lg"></i></button>
                                        <div class="w-px h-6 bg-slate-200 mx-1"></div>
                                        <i class="ti ti-chevron-down text-slate-400 transition-transform duration-300" :class="isCatOpen ? 'rotate-180' : ''"></i>
                                    </div>
                                </div>
                                <div x-show="isCatOpen" x-collapse class="bg-slate-50 border-t border-slate-100 p-4">
                                    @if($category->tags->count() > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($category->tags as $tag)
                                                <div class="group flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 shadow-sm rounded-xl text-xs font-bold text-slate-600 hover:border-primary hover:text-primary transition-colors cursor-default">
                                                    <i class="ti ti-hash text-slate-400 group-hover:text-primary"></i> <span @click.stop="openFormModal('edit', 'tag', {id: {{ $tag->id }}, name: '{{ addslashes($tag->name) }}', parent_id: '{{ $tag->category_id }}'})" class="cursor-pointer">{{ $tag->name }}</span>
                                                    <button @click.stop="openDeleteModal('tag', {{ $tag->id }}, '{{ addslashes($tag->name) }}')" class="ml-1 text-slate-300 hover:text-rose-500 focus:outline-none"><i class="ti ti-x"></i></button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-xs text-slate-400 italic">Belum ada tag di kategori ini.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- ============================================== -->
                <!-- AREA 2: GRUP BESERTA HIRARKINYA                -->
                <!-- ============================================== -->
                @forelse($groups as $group)
                    <div x-data="{ isGroupOpen: true }" class="bg-white border border-slate-200 shadow-sm rounded-3xl overflow-hidden transition-all hover:shadow-md">
                        
                        <!-- HEADER GRUP -->
                        <div @click="isGroupOpen = !isGroupOpen" class="flex items-center justify-between p-5 sm:p-6 cursor-pointer bg-slate-50/50 hover:bg-slate-50 transition-colors select-none">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-800 text-white flex items-center justify-center shadow-md shadow-slate-800/20"><i class="ti ti-{{ $group->icon ?? 'layout-grid' }} text-2xl"></i></div>
                                <div>
                                    <h3 class="text-lg font-extrabold text-slate-800">{{ $group->name }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 bg-slate-200 text-slate-600 rounded-md text-[10px] font-bold uppercase tracking-wider">{{ $group->categories->count() }} Kategori</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click.stop="openFormModal('create', 'category', { parent_id: '{{ $group->id }}' })" class="w-9 h-9 rounded-xl text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 flex items-center justify-center transition-colors" title="Tambah Kategori Disini"><i class="ti ti-folder-plus text-xl"></i></button>
                                <div class="w-px h-6 bg-slate-200 mx-1"></div>

                                <button @click.stop="openFormModal('edit', 'group', {id: {{ $group->id }}, name: '{{ addslashes($group->name) }}', icon: '{{ $group->icon ?? 'layout-grid' }}', parent_id: ''})" class="w-9 h-9 rounded-xl text-slate-400 hover:text-amber-500 hover:bg-amber-50 flex items-center justify-center transition-colors" title="Edit Grup"><i class="ti ti-edit text-xl"></i></button>
                                <button @click.stop="openDeleteModal('group', {{ $group->id }}, '{{ addslashes($group->name) }}')" class="w-9 h-9 rounded-xl text-slate-400 hover:text-rose-500 hover:bg-rose-50 flex items-center justify-center transition-colors" title="Hapus Grup"><i class="ti ti-trash text-xl"></i></button>
                                <div class="w-px h-8 bg-slate-200 mx-2"></div>
                                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 shadow-sm">
                                    <i class="ti ti-chevron-down transition-transform duration-300" :class="isGroupOpen ? 'rotate-180' : ''"></i>
                                </div>
                            </div>
                        </div>

                        <!-- KONTEN GRUP (LIST KATEGORI) -->
                        <div x-show="isGroupOpen" x-collapse class="p-5 sm:p-6 border-t border-slate-100 bg-white">
                            @if($group->categories->count() > 0)
                                <div class="space-y-4">
                                    @foreach($group->categories as $category)
                                        <div x-data="{ isCatOpen: false }" class="bg-white border border-slate-200 shadow-sm rounded-2xl overflow-hidden transition-all hover:border-slate-300 relative before:absolute before:left-0 before:top-0 before:bottom-0 before:w-1 before:bg-rose-400">
                                            <div @click="isCatOpen = !isCatOpen" class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 transition-colors pl-6 select-none">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center border border-rose-100"><i class="ti ti-{{ $category->icon ?? 'folder' }} text-lg"></i></div>
                                                    <div>
                                                        <h4 class="font-bold text-slate-800 text-sm">{{ $category->name }}</h4>
                                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $category->tags->count() }} Tags</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button @click.stop="openFormModal('create', 'tag', { parent_id: '{{ $category->id }}' })" class="w-8 h-8 rounded-lg text-slate-400 hover:text-sky-500 hover:bg-sky-50 flex items-center justify-center transition-colors" title="Tambah Tag Disini"><i class="ti ti-plus text-lg"></i></button>
                                                    <div class="w-px h-5 bg-slate-200 mx-1"></div>

                                                    <button @click.stop="openFormModal('edit', 'category', {id: {{ $category->id }}, name: '{{ addslashes($category->name) }}', icon: '{{ $category->icon ?? 'folder' }}', parent_id: '{{ $group->id }}'})" class="w-8 h-8 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 flex items-center justify-center transition-colors" title="Edit Kategori"><i class="ti ti-edit text-lg"></i></button>
                                                    <button @click.stop="openDeleteModal('category', {{ $category->id }}, '{{ addslashes($category->name) }}')" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 flex items-center justify-center transition-colors" title="Hapus Kategori"><i class="ti ti-trash text-lg"></i></button>
                                                    <i class="ti ti-chevron-down text-slate-400 transition-transform duration-300 ml-2" :class="isCatOpen ? 'rotate-180 text-primary' : ''"></i>
                                                </div>
                                            </div>
                                            
                                            <div x-show="isCatOpen" x-collapse class="bg-slate-50 border-t border-slate-100 p-4 pl-6">
                                                @if($category->tags->count() > 0)
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($category->tags as $tag)
                                                            <div class="group flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 shadow-sm rounded-lg text-xs font-bold text-slate-600 hover:border-primary hover:text-primary transition-colors cursor-default">
                                                                <i class="ti ti-hash text-slate-400 group-hover:text-primary"></i> <span @click.stop="openFormModal('edit', 'tag', {id: {{ $tag->id }}, name: '{{ addslashes($tag->name) }}', parent_id: '{{ $tag->category_id }}'})" class="cursor-pointer">{{ $tag->name }}</span>
                                                                <button @click.stop="openDeleteModal('tag', {{ $tag->id }}, '{{ addslashes($tag->name) }}')" class="ml-1 text-slate-300 hover:text-rose-500 focus:outline-none"><i class="ti ti-x"></i></button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-[11px] text-slate-400 italic">Belum ada tag di kategori ini.</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-6 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                    <p class="text-sm text-slate-500">Grup ini masih kosong, belum ada kategori di dalamnya.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    @if($ungroupedCategories->count() === 0)
                        <div class="py-16 text-center bg-white rounded-3xl border border-dashed border-slate-200 shadow-sm">
                            <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mb-4 mx-auto"><i class="ti ti-category text-3xl"></i></div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Hierarki</h3>
                            <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">Kamu belum membuat Grup atau Kategori apapun. Yuk, mulai tata rapi referensimu!</p>
                        </div>
                    @endif
                @endforelse
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- MODAL 1: FORM CREATE & EDIT (DINAMIS UNTUK GRUP/KATEGORI/TAG)  -->
        <!-- ============================================================== -->
        <div x-show="formModal.isOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
            <div x-show="formModal.isOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeFormModal()"></div>
            
            <div x-show="formModal.isOpen" x-transition.scale.origin.bottom class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 z-50">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <h3 class="font-extrabold text-lg text-slate-800" x-text="(formModal.mode === 'create' ? 'Tambah ' : 'Edit ') + formModal.typeLabel"></h3>
                    <button type="button" @click="closeFormModal()" class="text-slate-400 hover:text-rose-500"><i class="ti ti-x text-xl"></i></button>
                </div>
                
                <form :action="formModal.actionUrl" method="POST" class="p-6 space-y-5">
                    @csrf 
                    <template x-if="formModal.mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>

                    <!-- Input Nama -->
                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Nama <span x-text="formModal.typeLabel"></span> <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required x-model="formModal.name" class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary text-sm h-12">
                    </div>

                    <!-- CUSTOM SEARCHABLE DROPDOWN (Khusus Kategori & Tag) -->
                    <div x-show="formModal.type === 'category' || formModal.type === 'tag'" class="relative" @click.away="closeParentDropdown()">
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">
                            <span x-text="formModal.type === 'category' ? 'Grup Induk (Opsional)' : 'Kategori Induk'"></span> 
                            <span x-show="formModal.type === 'tag'" class="text-rose-500">*</span>
                        </label>
                        
                        <!-- Input Hidden Untuk Backend -->
                        <input type="hidden" name="parent_id" :value="formModal.parentId">

                        <!-- Trigger Bar (Mirip Select) -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors" :class="isParentDropdownOpen ? 'text-primary' : 'text-slate-400'">
                                <i class="ti text-lg" :class="isParentDropdownOpen ? 'ti-search' : (formModal.type === 'category' ? 'ti-layout-grid' : 'ti-folder')"></i>
                            </div>

                            <input type="text" 
                                x-model="parentSearch" 
                                @focus="isParentDropdownOpen = true; parentSearch = ''"
                                :placeholder="formModal.type === 'category' ? 'Cari / Pilih Grup...' : 'Cari / Pilih Kategori...'"
                                class="w-full pl-11 pr-10 bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm h-12 transition-colors font-semibold text-slate-700 placeholder:font-normal">

                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                <i class="ti ti-chevron-down transition-transform" :class="{'rotate-180 text-primary': isParentDropdownOpen}"></i>
                            </div>
                        </div>

                        <!-- Opsi Dropdown Custom -->
                        <div x-show="isParentDropdownOpen" style="display: none;" x-transition class="absolute z-50 w-full mt-2 bg-white border border-slate-200 shadow-xl rounded-xl overflow-hidden max-h-56 overflow-y-auto custom-scrollbar p-1.5">
                            
                            <!-- Tombol Reset / Kosongkan (Hanya untuk Kategori karena grup opsional) -->
                            <div x-show="formModal.type === 'category'" class="mb-1">
                                <button type="button" @click="selectParent('', 'Tanpa Grup')" class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-semibold text-slate-500 hover:bg-slate-50 transition-colors flex items-center gap-2.5" :class="formModal.parentId === '' ? 'bg-slate-100' : ''">
                                    <i class="ti ti-ban text-lg"></i> Tanpa Grup
                                </button>
                            </div>

                            <div x-show="filteredParentOptions.length === 0" class="p-3 text-center text-xs text-slate-500">Pencarian tidak ditemukan.</div>

                            <template x-for="item in filteredParentOptions" :key="item.id">
                                <button type="button" @click="selectParent(item.id, item.name)"
                                    class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center justify-between"
                                    :class="formModal.parentId == item.id ? 'bg-primary-50 text-primary' : 'text-slate-700 hover:bg-slate-50'">
                                    <span class="flex items-center gap-2.5">
                                        <i class="ti text-lg" :class="formModal.type === 'category' ? 'ti-layout-grid' : 'ti-folder'"></i>
                                        <span x-text="item.name"></span>
                                    </span>
                                    <i x-show="formModal.parentId == item.id" class="ti ti-check text-primary text-lg"></i>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Input Icon Picker (Khusus Grup & Kategori) -->
                    <div x-show="formModal.type === 'group' || formModal.type === 'category'" class="relative" @click.away="isIconPickerOpen = false">
                        <label class="block font-bold text-xs text-slate-500 uppercase mb-2">Ikon</label>
                        <input type="hidden" name="icon" :value="formModal.icon">
                        
                        <button type="button" @click="isIconPickerOpen = !isIconPickerOpen" class="w-full h-12 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between px-4 text-slate-600 hover:bg-white focus:border-primary transition-colors">
                            <div class="flex items-center gap-3"><i :class="'text-xl ti ti-' + formModal.icon"></i> <span class="text-sm font-semibold" x-text="formModal.icon"></span></div>
                            <i class="ti ti-chevron-down text-slate-400"></i>
                        </button>
                        
                        <div x-show="isIconPickerOpen" style="display: none;" x-transition class="absolute z-50 left-0 right-0 top-full mt-2 p-3 bg-white border border-slate-200 shadow-xl rounded-2xl max-h-56 overflow-y-auto custom-scrollbar grid grid-cols-6 gap-2">
                            <template x-for="icon in popularIcons" :key="icon">
                                <button type="button" @click="formModal.icon = icon; isIconPickerOpen = false" class="aspect-square rounded-lg flex items-center justify-center text-xl hover:bg-primary-50 hover:text-primary transition-colors border" :class="formModal.icon === icon ? 'bg-primary-50 border-primary text-primary font-bold shadow-sm' : 'border-slate-100 text-slate-600'"><i :class="'ti ti-' + icon"></i></button>
                            </template>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 mt-4 text-white font-bold rounded-xl shadow-md transition-all flex justify-center items-center gap-2" :class="formModal.mode === 'edit' ? 'bg-amber-500 hover:bg-amber-600' : 'bg-primary hover:bg-primary-600'">
                        <i class="ti ti-device-floppy text-lg"></i> <span x-text="formModal.mode === 'create' ? 'Simpan' : 'Perbarui'"></span>
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- MODAL 2: KONFIRMASI HAPUS DINAMIS                              -->
        <!-- ============================================================== -->
        <div x-show="deleteModal.isOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="deleteModal.isOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="deleteModal.isOpen = false"></div>
            <div x-show="deleteModal.isOpen" x-transition.scale.origin.bottom class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 text-center z-50">
                <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center mx-auto mb-4"><i class="ti ti-alert-triangle text-3xl"></i></div>
                <h3 class="font-extrabold text-xl text-slate-800 mb-2">Hapus <span class="capitalize" x-text="deleteModal.typeLabel"></span>?</h3>
                <p class="text-sm text-slate-500 mb-6">Apakah kamu yakin ingin menghapus <strong>"<span x-text="deleteModal.name"></span>"</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                
                <form :action="deleteModal.actionUrl" method="POST" class="flex items-center gap-3">
                    @csrf @method('DELETE')
                    <button type="button" @click="deleteModal.isOpen = false" class="flex-1 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-rose-500 text-white font-bold rounded-xl hover:bg-rose-600 shadow-lg shadow-rose-500/30 transition-colors">Ya, Hapus</button>
                </form>
            </div>
        </div>

    </div>

    <!-- SCRIPT ALPINE.JS -->
    <script>
        function taxonomyManager() {
            return {
                allGroups: @json($groups->map(fn($g) => ['id' => $g->id, 'name' => $g->name])),
                allCategoriesFlat: @json($allCategoriesFlat),
                popularIcons: ['folder', 'layout-grid', 'tag', 'book', 'briefcase', 'palette', 'device-laptop', 'brain', 'rocket', 'sparkles', 'flame', 'star', 'shopping-cart', 'bulb', 'tool', 'heart', 'camera', 'music', 'world', 'chart-bar', 'news', 'shield', 'compass', 'cpu', 'database', 'cloud'],
                isIconPickerOpen: false,

                // STATE DROPDOWN CUSTOM
                isParentDropdownOpen: false,
                parentSearch: '',
                parentNameDisplay: '',

                // STATE MODAL FORM
                formModal: {
                    isOpen: false, mode: 'create', type: 'group', typeLabel: 'Grup',
                    id: '', name: '', icon: 'folder', parentId: '', actionUrl: ''
                },
                
                // STATE MODAL DELETE
                deleteModal: {
                    isOpen: false, typeLabel: '', name: '', actionUrl: ''
                },

                openFormModal(mode, type, data = null) {
                    this.formModal.isOpen = true;
                    this.formModal.mode = mode;
                    this.formModal.type = type;
                    
                    if(type === 'group') this.formModal.typeLabel = 'Grup';
                    if(type === 'category') this.formModal.typeLabel = 'Kategori';
                    if(type === 'tag') this.formModal.typeLabel = 'Tag';

                    let baseUrl = '/' + (type === 'category' ? 'categories' : type + 's'); 

                    if(mode === 'create') {
                        this.formModal.id = '';
                        this.formModal.name = '';
                        this.formModal.icon = type === 'tag' ? 'hash' : 'folder';
                        this.formModal.parentId = (data && data.parent_id) ? data.parent_id : ''; 
                        this.formModal.actionUrl = baseUrl;
                    } else if(mode === 'edit' && data) {
                        this.formModal.id = data.id;
                        this.formModal.name = data.name;
                        this.formModal.icon = data.icon || (type === 'tag' ? 'hash' : 'folder');
                        this.formModal.parentId = data.parent_id || '';
                        this.formModal.actionUrl = baseUrl + '/' + data.id;
                    }
                    
                    // Set Parent Name Display agar placeholder/teks input otomatis terisi nama grup/kategori sebelumnya
                    if (this.formModal.parentId) {
                        let p = null;
                        if (type === 'category') p = this.allGroups.find(g => g.id == this.formModal.parentId);
                        if (type === 'tag') p = this.allCategoriesFlat.find(c => c.id == this.formModal.parentId);
                        this.parentNameDisplay = p ? p.name : (type === 'category' ? 'Tanpa Grup' : '');
                        this.parentSearch = this.parentNameDisplay;
                    } else {
                        this.parentNameDisplay = type === 'category' ? 'Tanpa Grup' : '';
                        this.parentSearch = this.parentNameDisplay;
                    }

                    this.isIconPickerOpen = false;
                    this.isParentDropdownOpen = false;
                },

                closeFormModal() {
                    this.formModal.isOpen = false;
                },

                openDeleteModal(type, id, name) {
                    this.deleteModal.isOpen = true;
                    this.deleteModal.name = name;
                    
                    if(type === 'group') this.deleteModal.typeLabel = 'Grup';
                    if(type === 'category') this.deleteModal.typeLabel = 'Kategori';
                    if(type === 'tag') this.deleteModal.typeLabel = 'Tag';

                    let baseUrl = '/' + (type === 'category' ? 'categories' : type + 's'); 
                    this.deleteModal.actionUrl = baseUrl + '/' + id;
                },

                // LOGIKA CUSTOM DROPDOWN
                get filteredParentOptions() {
                    const query = this.parentSearch.toLowerCase().trim();
                    let list = this.formModal.type === 'category' ? this.allGroups : this.allCategoriesFlat;

                    if (query !== '') {
                        list = list.filter(item => item.name.toLowerCase().includes(query));
                    }
                    return list;
                },

                selectParent(id, name) {
                    this.formModal.parentId = id;
                    this.parentSearch = name;
                    this.parentNameDisplay = name;
                    this.isParentDropdownOpen = false;
                },

                closeParentDropdown() {
                    this.isParentDropdownOpen = false;
                    // Kembalikan teks input ke nama yang terakhir dipilih jika user klik di luar (blur)
                    this.parentSearch = this.parentNameDisplay;
                }
            }
        }
    </script>
</x-app-layout>
