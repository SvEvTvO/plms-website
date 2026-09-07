<x-guest-layout>
    <!-- Header Text -->
    <div class="mb-8 text-center">
        <h1 class="font-extrabold text-2xl text-gray-900 mb-1.5">Buat Akun Baru</h1>
        <p class="text-sm text-gray-500">Mulai bangun perpustakaan digital pribadimu hari ini.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-gray-700 mb-2">Nama Lengkap</label>
            <div class="relative flex items-center">
                <div class="absolute left-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path></svg>
                </div>
                <!-- Menghapus autofocus agar tampilan awal seragam, dan memperbaiki warna focus -->
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="John Doe" class="w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-[14px] text-sm text-gray-900 placeholder:text-gray-400 transition-all shadow-sm">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 mb-2">Email</label>
            <div class="relative flex items-center">
                <div class="absolute left-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path><path d="M3 7l9 6l9 -6"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="alamat@email.com" class="w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-[14px] text-sm text-gray-900 placeholder:text-gray-400 transition-all shadow-sm">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 mb-2">Kata Sandi</label>
                <div class="relative flex items-center">
                    <div class="absolute left-4 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"></path><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path><path d="M8 11v-4a4 4 0 1 1 8 0v4"></path></svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 karakter" class="w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-[14px] text-sm text-gray-900 placeholder:text-gray-400 transition-all shadow-sm">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-2">Ulangi Sandi</label>
                <div class="relative flex items-center">
                    <div class="absolute left-4 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"></path><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path><path d="M8 11v-4a4 4 0 1 1 8 0v4"></path></svg>
                    </div>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi sandi" class="w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-[14px] text-sm text-gray-900 placeholder:text-gray-400 transition-all shadow-sm">
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <!-- Warna tombol diubah menjadi bg-primary agar sama dengan halaman Login -->
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-3.5 bg-primary hover:bg-primary-dark text-white rounded-[14px] text-sm font-bold shadow-sm transition-all focus:ring-4 focus:ring-primary/20">
                Daftar Sekarang
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l14 0"></path><path d="M13 18l6 -6"></path><path d="M13 6l6 6"></path></svg>
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-6">
            <p class="text-xs font-medium text-gray-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-primary hover:underline ml-1">Masuk di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>
