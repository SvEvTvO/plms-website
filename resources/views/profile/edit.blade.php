<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-8">

        <div class="mb-4">
            <h2 class="text-3xl font-heading font-bold text-gray-900 leading-tight">Profil Akun</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi data diri dan pengaturan keamanan akunmu.</p>
        </div>

        <div class="bg-white rounded-[24px] p-6 sm:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
            <div class="flex items-start sm:items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-[14px] flex items-center justify-center border border-blue-100 shadow-sm shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-heading font-bold text-gray-900">Informasi Pribadi</h3>
                    <p class="text-sm text-gray-500 mt-1">Perbarui nama lengkap dan alamat email akunmu di sini.</p>
                </div>
            </div>

            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="bg-white rounded-[24px] p-6 sm:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
            <div class="flex items-start sm:items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-[14px] flex items-center justify-center border border-emerald-100 shadow-sm shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"></path><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path><path d="M8 11v-4a4 4 0 1 1 8 0v4"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-heading font-bold text-gray-900">Ubah Password</h3>
                    <p class="text-sm text-gray-500 mt-1">Pastikan akunmu menggunakan kata sandi acak yang kuat agar tetap aman.</p>
                </div>
            </div>

            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="bg-white rounded-[24px] p-6 sm:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-red-100">
            <div class="flex items-start sm:items-center gap-4 mb-6 pb-6 border-b border-red-50">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-[14px] flex items-center justify-center border border-red-100 shadow-sm shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-heading font-bold text-red-600">Hapus Akun Permanen</h3>
                    <p class="text-sm text-gray-500 mt-1">Setelah akun dihapus, seluruh data dan library-mu tidak dapat dikembalikan.</p>
                </div>
            </div>

            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</x-app-layout>
