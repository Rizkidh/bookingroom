<x-app-layout>
    <div class="flex-1 flex flex-col min-h-0 bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="section-header p-4 border-b border-gray-100 flex-shrink-0">
            <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profil Pengguna
            </h2>
        </div>

        <div class="flex-1 overflow-y-auto bg-gray-50/50 p-6">
            <div class="max-w-4xl mx-auto flex flex-col gap-6">
                <!-- Profile Information -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Informasi Profil</h3>
                    <p class="text-sm text-gray-500 mb-6">Perbarui informasi profil akun dan alamat email Anda.</p>
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Perbarui Password</h3>
                    <p class="text-sm text-gray-500 mb-6">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete User -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-red-600 mb-1">Hapus Akun</h3>
                    <p class="text-sm text-gray-500 mb-6">Setelah akun dihapus, semua data dan sumber daya akan dihapus secara permanen.</p>
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

                <!-- LOGOUT (Mobile Only) -->
                <div class="lg:hidden bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Keluar</h3>
                    <p class="text-sm text-gray-500 mb-6">Keluar dari sesi aplikasi.</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex justify-center items-center gap-2 px-4 py-3 bg-red-50 text-red-600 border border-red-200 rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-red-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar Aplikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
