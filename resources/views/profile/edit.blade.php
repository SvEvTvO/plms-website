<x-app-layout>
    <!-- SLOT HEADER: Menyesuaikan dengan standar halaman lain -->
    <x-slot name="header">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Profil Akun</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola informasi data diri dan pengaturan keamanan akunmu.</p>
        </div>
    </x-slot>

    <!-- WRAPPER HALAMAN (Lebar disamakan dengan halaman lain) -->
    <div class="max-w-5xl mx-auto pb-16 pt-6 space-y-10 sm:space-y-12">

        <!-- ============================================== -->
        <!-- SECTION 1: INFORMASI PRIBADI                   -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <!-- Kolom Kiri: Konteks -->
            <div class="md:col-span-1">
                <div class="sticky top-8">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 mb-4 shadow-sm">
                        <i class="ti ti-user-edit text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-800">Informasi Pribadi</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Perbarui nama lengkap dan alamat email yang terhubung dengan akun komunitasmu.</p>
                </div>
            </div>

            <!-- Kolom Kanan: Form Eksekusi -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 transition-shadow hover:shadow-md">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        <!-- ============================================== -->
        <!-- SECTION 2: UBAH PASSWORD                       -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <!-- Kolom Kiri: Konteks -->
            <div class="md:col-span-1">
                <div class="sticky top-8">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 mb-4 shadow-sm">
                        <i class="ti ti-shield-lock text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-800">Ubah Password</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Pastikan akunmu menggunakan kata sandi acak yang panjang dan kuat agar tetap aman dari ancaman.</p>
                </div>
            </div>

            <!-- Kolom Kanan: Form Eksekusi -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 transition-shadow hover:shadow-md">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        <!-- ============================================== -->
        <!-- SECTION 3: HAPUS AKUN (ZONA MERAH)             -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <!-- Kolom Kiri: Konteks -->
            <div class="md:col-span-1">
                <div class="sticky top-8">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 mb-4 shadow-sm">
                        <i class="ti ti-user-x text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-rose-600">Hapus Akun Permanen</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Setelah akun dihapus, seluruh data, referensi, dan pengajuan publikmu akan dimusnahkan dan tidak dapat dikembalikan. Harap berhati-hati.</p>
                </div>
            </div>

            <!-- Kolom Kanan: Form Eksekusi -->
            <div class="md:col-span-2">
                <!-- Border dan efek hover diberi nuansa kemerahan sebagai peringatan alam bawah sadar -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-rose-100 hover:border-rose-300 transition-all hover:shadow-md hover:shadow-rose-100">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
