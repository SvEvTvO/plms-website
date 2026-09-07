<x-guest-layout>
    <!-- Header Text -->
    <div class="mb-8 text-center">
        <h1 class="font-extrabold text-2xl text-gray-900 mb-1.5">Selamat Datang Kembali</h1>
        <p class="text-sm text-gray-500">Silakan masuk ke akun Anda untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 mb-2">Email</label>
            <div class="relative flex items-center">
                <div class="absolute left-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path><path d="M3 7l9 6l9 -6"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan alamat email" class="w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent focus:bg-white focus:border-gray-900 focus:ring-0 rounded-[14px] text-sm text-gray-900 placeholder:text-gray-400 transition-colors shadow-sm">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-xs font-bold text-gray-700">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">
                        Lupa sandi?
                    </a>
                @endif
            </div>
            <div class="relative flex items-center">
                <div class="absolute left-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"></path><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path><path d="M8 11v-4a4 4 0 1 1 8 0v4"></path></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent focus:bg-white focus:border-gray-900 focus:ring-0 rounded-[14px] text-sm text-gray-900 placeholder:text-gray-400 transition-colors shadow-sm">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded-[6px] border-gray-300 text-primary shadow-sm focus:ring-primary w-4 h-4 cursor-pointer" name="remember">
                <span class="ms-2 text-xs font-medium text-gray-500">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-3.5 bg-primary hover:bg-primary-dark text-white rounded-[14px] text-sm font-bold shadow-sm transition-all focus:ring-4 focus:ring-primary/20">
                Masuk ke Dasbor
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l14 0"></path><path d="M13 18l6 -6"></path><path d="M13 6l6 6"></path></svg>
            </button>
        </div>

        <!-- Register Link -->
        <div class="text-center mt-6">
            <p class="text-xs font-medium text-gray-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold text-primary hover:underline ml-1">Daftar sekarang</a>
            </p>
        </div>
    </form>
</x-guest-layout>
