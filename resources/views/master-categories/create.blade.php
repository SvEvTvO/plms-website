<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">

        <div class="mb-4">
            <a href="{{ route('categories.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 mb-4 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Kategori
            </a>
            <h2 class="text-3xl font-heading font-bold text-gray-900 leading-tight">Buat Folder Utama</h2>
            <p class="text-sm text-gray-500 mt-1">Buat wadah master untuk mengorganisasikan berbagai tipe website.</p>
        </div>

        <div class="bg-white rounded-[24px] p-6 sm:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
            <form action="{{ route('master-categories.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                    <div class="space-y-6">

                        <div x-data="{ color: '{{ old('color', '#0ea5e9') }}' }">
                            <label class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2">1. Warna Label <span class="text-red-500">*</span></label>
                            <p class="text-xs text-gray-400 mb-3">Pilih warna untuk membedakan identitas folder.</p>

                            <div class="relative flex items-center p-2 bg-gray-50 border border-gray-200 rounded-[16px] shadow-sm hover:border-gray-300 transition focus-within:ring-2 focus-within:ring-primary/20">
                                <div class="w-10 h-10 rounded-[10px] shadow-sm shrink-0 overflow-hidden relative cursor-pointer border border-black/10" :style="`background-color: ${color}`">
                                    <input type="color" name="color" x-model="color" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer">
                                </div>
                                <div class="ml-3 font-mono text-sm text-gray-600 font-bold uppercase tracking-wider" x-text="color"></div>
                            </div>
                        </div>

                        <div>
                            <label for="name" class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2">2. Nama Folder <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required class="w-full bg-gray-50 rounded-[14px] border-transparent focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm h-[46px]" value="{{ old('name') }}" placeholder="Contoh: AI Tools, Design...">
                        </div>

                        <div>
                            <label for="description" class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2">Deskripsi (Opsional)</label>
                            <textarea name="description" id="description" rows="3" class="w-full bg-gray-50 rounded-[14px] border-transparent focus:bg-white focus:border-primary focus:ring-primary shadow-sm text-sm" placeholder="Folder ini untuk apa?">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="space-y-6">

                        <div x-data="iconPicker()" @click.away="open = false" class="relative">
                            <label class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2">3. Pilih Ikon Tabler <span class="text-red-500">*</span></label>
                            <input type="hidden" name="icon" x-model="selectedIcon">

                            <button type="button" @click="open = !open" class="w-full flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-[16px] shadow-sm hover:border-gray-300 transition focus:outline-none focus:ring-2 focus:ring-primary/20">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-gray-700 border border-gray-100" x-html="getIconSvg(selectedIcon)"></div>
                                    <span class="text-sm font-bold text-gray-600 capitalize" x-text="selectedIcon || 'Pilih Ikon...'"></span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6l6 -6"></path></svg>
                            </button>

                            <div x-show="open" x-transition class="absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-[20px] shadow-xl overflow-hidden" style="display: none;">
                                <div class="p-3 border-b border-gray-100 bg-gray-50">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                                        </div>
                                        <input type="text" x-model="search" placeholder="Ketik nama ikon (cth: school, code)..." class="w-full py-2 pl-9 pr-3 text-sm bg-white border border-gray-200 rounded-[10px] focus:border-primary focus:ring-primary">
                                    </div>
                                </div>
                                <div class="max-h-60 overflow-y-auto p-2 custom-scrollbar">
                                    <template x-for="icon in filteredIcons" :key="icon.name">
                                        <div @click="select(icon.name)" class="flex items-center gap-3 p-3 hover:bg-primary/5 rounded-[12px] cursor-pointer transition text-gray-600 hover:text-primary">
                                            <div class="w-6 h-6 flex items-center justify-center" x-html="icon.svg"></div>
                                            <span class="text-sm font-medium capitalize" x-text="icon.name"></span>
                                        </div>
                                    </template>
                                    <div x-show="filteredIcons.length === 0" class="p-4 text-center text-sm text-gray-500 italic">Ikon tidak ditemukan.</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 p-5 rounded-[20px] border border-gray-100" x-data="{ subs: [''] }">
                            <label class="block font-bold text-xs tracking-wider text-gray-500 uppercase mb-3 flex items-center justify-between">
                                <span>4. Sub-Kategori (Opsional)</span>
                                <button type="button" @click="subs.push('')" class="text-[10px] font-bold text-primary hover:text-primary-dark transition flex items-center gap-1 bg-primary/10 px-2 py-1 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg> Tambah Kolom
                                </button>
                            </label>

                            <div class="space-y-3">
                                <template x-for="(sub, index) in subs" :key="index">
                                    <div class="flex gap-2">
                                        <input type="text" x-model="subs[index]" name="sub_categories[]" placeholder="Nama sub-kategori baru..." class="w-full bg-white rounded-[12px] border-gray-200 focus:border-primary focus:ring-primary shadow-sm text-sm">
                                        <button type="button" @click="subs.splice(index, 1)" x-show="subs.length > 1" class="px-3 bg-red-50 text-red-500 hover:bg-red-100 rounded-[12px] transition shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7l16 0"></path><path d="M10 11l0 6"></path><path d="M14 11l0 6"></path><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-3">Kamu bisa langsung membuat sub-kategori sekaligus untuk folder ini.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('categories.index') }}" class="px-6 py-3 text-sm font-medium text-gray-600 bg-gray-50 rounded-[14px] hover:bg-gray-100 transition">Batal</a>
                    <button type="submit" class="bg-gray-900 text-white px-8 py-3 rounded-[14px] text-sm font-medium hover:bg-gray-800 transition shadow-sm">
                        Simpan Folder Utama
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    function iconPicker() {
        // Fungsi pintar untuk membungkus path ke dalam tag SVG utuh
        const svgWrap = (paths) => `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${paths}</svg>`;

        const iconList = [
            // 📂 UMUM & FOLDER
            { name: 'folder', svg: svgWrap('<path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" />') },
            { name: 'star', svg: svgWrap('<path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />') },
            { name: 'bookmark', svg: svgWrap('<path d="M9 4h6a2 2 0 0 1 2 2v14l-5 -3l-5 3v-14a2 2 0 0 1 2 -2" />') },
            { name: 'heart', svg: svgWrap('<path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />') },

            // 🤖 TEKNOLOGI, DEV & AI
            { name: 'robot', svg: svgWrap('<path d="M6 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" /><path d="M2 8l4 0" /><path d="M18 8l4 0" /><path d="M2 16l4 0" /><path d="M18 16l4 0" /><path d="M9 4v-1" /><path d="M15 4v-1" /><path d="M9 12v.01" /><path d="M15 12v.01" /><path d="M9 16h6" />') },
            { name: 'brain', svg: svgWrap('<path d="M15.5 13a3.5 3.5 0 0 0 -3.5 3.5v1a3.5 3.5 0 0 0 7 0v-1.8" /><path d="M8.5 13a3.5 3.5 0 0 1 3.5 3.5v1a3.5 3.5 0 0 1 -7 0v-1.8" /><path d="M17.5 16a3.5 3.5 0 0 0 0 -7h-.5" /><path d="M19 9.3v-2.8a3.5 3.5 0 0 0 -7 0" /><path d="M6.5 16a3.5 3.5 0 0 1 0 -7h.5" /><path d="M5 9.3v-2.8a3.5 3.5 0 0 1 7 0v10" />') },
            { name: 'code', svg: svgWrap('<path d="M7 8l-4 4l4 4" /><path d="M17 8l4 4l-4 4" /><path d="M14 4l-4 16" />') },
            { name: 'database', svg: svgWrap('<path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" /><path d="M4 6v6c0 1.657 3.582 3 8 3s8 -1.343 8 -3v-6" /><path d="M4 12v6c0 1.657 3.582 3 8 3s8 -1.343 8 -3v-6" />') },
            { name: 'server', svg: svgWrap('<path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 14m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M7 8v.01" /><path d="M7 18v.01" />') },
            { name: 'cloud', svg: svgWrap('<path d="M6.657 18c-2.572 0 -4.657 -2.007 -4.657 -4.483c0 -2.475 2.085 -4.482 4.657 -4.482c.393 -1.762 1.794 -3.2 3.675 -3.773c1.88 -.572 3.956 -.193 5.444 1c1.488 1.19 2.162 3.007 1.77 4.769h.99c1.913 0 3.464 1.56 3.464 3.486c0 1.927 -1.551 3.487 -3.465 3.487h-11.878" />') },

            // 🎨 DESIGN & KREATIF
            { name: 'palette', svg: svgWrap('<path d="M12 21a9 9 0 0 1 0 -18c4.97 0 9 3.582 9 8c0 1.06 -.474 2.078 -1.318 2.828c-.844 .75 -1.989 1.172 -3.182 1.172h-2.5a2 2 0 0 0 -1 3.75a1.3 1.3 0 0 1 -1 2.25" /><path d="M8.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M16.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />') },
            { name: 'camera', svg: svgWrap('<path d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" /><path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />') },
            { name: 'video', svg: svgWrap('<path d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z" /><path d="M3 6m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />') },
            { name: 'headphones', svg: svgWrap('<path d="M4 14v-3a8 8 0 1 1 16 0v3" /><path d="M18 19c0 1.657 -2.686 3 -6 3" /><path d="M4 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z" /><path d="M15 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z" />') },
            { name: 'music', svg: svgWrap('<path d="M3 17a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M13 17a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M9 17v-13h10v13" /><path d="M9 8h10" />') },

            // 🎓 EDUKASI & BACAAN
            { name: 'school', svg: svgWrap('<path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />') },
            { name: 'books', svg: svgWrap('<path d="M5 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M9 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M5 8h4" /><path d="M9 16h4" /><path d="M13.803 4.56l2.184 -.53c.562 -.135 1.133 .19 1.282 .732l3.695 13.418a1.02 1.02 0 0 1 -.634 1.219l-.133 .041l-2.184 .53c-.562 .135 -1.133 -.19 -1.282 -.732l-3.695 -13.418a1.02 1.02 0 0 1 .634 -1.219l.133 -.041z" /><path d="M14 9l4 -1" /><path d="M16 16l3.923 -.98" />') },
            { name: 'news', svg: svgWrap('<path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11" /><path d="M8 8l4 0" /><path d="M8 12l4 0" /><path d="M8 16l4 0" />') },

            // 💼 BISNIS & PEKERJAAN
            { name: 'briefcase', svg: svgWrap('<path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" />') },
            { name: 'chart-pie', svg: svgWrap('<path d="M10 3.2a9 9 0 1 0 10.8 10.8a1 1 0 0 0 -1 -1h-6.8a2 2 0 0 1 -2 -2v-7a.9 .9 0 0 0 -1 -.8" /><path d="M15 3.5a9 9 0 0 1 5.5 5.5h-4.5a1 1 0 0 1 -1 -1v-4.5" />') },
            { name: 'shopping-cart', svg: svgWrap('<path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17h-11v-14h-2" /><path d="M6 5l14 1l-1 7h-13" />') },
            { name: 'store', svg: svgWrap('<path d="M3 21l18 0" /><path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" /><path d="M5 21l0 -10.15" /><path d="M19 21l0 -10.15" /><path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />') },
            { name: 'coin', svg: svgWrap('<path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" /><path d="M12 7v10" />') },
            { name: 'rocket', svg: svgWrap('<path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3" /><path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3" /><path d="M15 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />') },

            // 💬 KOMUNITAS & SOSIAL
            { name: 'users', svg: svgWrap('<path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />') },
            { name: 'message', svg: svgWrap('<path d="M3 20l1.3 -3.9c-2.324 -3.437 -1.426 -7.872 2.1 -10.374c3.526 -2.501 8.59 -2.296 11.845 .48c3.255 2.777 3.695 7.266 1.029 10.501c-2.666 3.235 -7.615 4.215 -11.574 2.293l-4.7 1z" />') },
            { name: 'gamepad', svg: svgWrap('<path d="M2 6m0 2a2 2 0 0 1 2 -2h16a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-16a2 2 0 0 1 -2 -2z" /><path d="M6 12h4m-2 -2v4" /><path d="M15 11l0 .01" /><path d="M18 13l0 .01" />') },

            // 🔧 UTILITAS & LAINNYA
            { name: 'tools', svg: svgWrap('<path d="M3 21h4l13 -13a1.5 1.5 0 0 0 -4 -4l-13 13v4" /><path d="M14.5 5.5l4 4" /><path d="M12 8l-5 -5l-4 4l5 5" /><path d="M7 8l-1.5 1.5" /><path d="M16 12l5 5l-4 4l-5 -5" /><path d="M16 17l-1.5 1.5" />') },
            { name: 'shield', svg: svgWrap('<path d="M11.46 20.846a12 12 0 0 1 -7.96 -14.846a12 12 0 0 0 8.5 -3a12 12 0 0 0 8.5 3a12 12 0 0 1 -1.116 9.376" /><path d="M15 19l2 2l4 -4" />') },
            { name: 'map-pin', svg: svgWrap('<path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />') },
            { name: 'home', svg: svgWrap('<path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />') },
        ];

        return {
            open: false,
            search: '',
            icons: iconList,
            // Di halaman Create ini memakai old('icon', 'folder'), di Edit memakai $masterCategory->icon
            selectedIcon: document.querySelector('input[name="icon"]').value || 'folder',

            get filteredIcons() {
                if (this.search === '') { return this.icons; }
                return this.icons.filter(icon => icon.name.toLowerCase().includes(this.search.toLowerCase()));
            },

            getIconSvg(name) {
                const icon = this.icons.find(i => i.name === name);
                return icon ? icon.svg : this.icons[0].svg;
            },

            select(name) {
                this.selectedIcon = name;
                this.open = false;
                this.search = '';
            }
        }
    }
</script>
