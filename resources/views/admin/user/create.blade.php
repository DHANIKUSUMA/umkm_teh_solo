<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.user.index') }}" class="text-stone-400 hover:text-emerald-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-emerald-700 rounded-full"></div>
            <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                {{ __('Tambah Karyawan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>

                <form method="POST" action="{{ route('admin.user.store') }}" class="p-6 sm:p-8 space-y-5">
                    @csrf

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-stone-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm"
                            placeholder="Contoh: Siti Rahmawati">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-semibold text-stone-700 mb-1.5">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm"
                            placeholder="Contoh: kasir2">
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-stone-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm"
                            placeholder="Contoh: kasir2@tehsolo.test">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-semibold text-stone-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                        <select name="role" id="role" required
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm">
                            <option value="karyawan" {{ old('role', 'karyawan') === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-stone-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" required
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm"
                            placeholder="Minimal 6 karakter">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-stone-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm"
                            placeholder="Ulangi password">
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
                        <a href="{{ route('admin.user.index') }}" class="inline-flex items-center px-4 py-2.5 bg-stone-100 text-stone-600 rounded-lg text-sm font-medium hover:bg-stone-200 transition">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-lg text-sm font-semibold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
