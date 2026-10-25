<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.user.index') }}" class="text-stone-400 hover:text-emerald-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div class="w-2 h-8 bg-gradient-to-b from-amber-500 to-amber-700 rounded-full"></div>
            <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                {{ __('Edit Karyawan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 via-emerald-500 to-amber-600"></div>

                <form method="POST" action="{{ route('admin.user.update', $user) }}" class="p-6 sm:p-8 space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Info User --}}
                    <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br {{ $user->role === 'admin' ? 'from-amber-400 to-amber-600' : 'from-emerald-400 to-emerald-600' }} flex items-center justify-center text-white text-lg font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-emerald-900">{{ $user->name }}</p>
                            <p class="text-xs text-stone-400">Bergabung {{ $user->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-stone-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-semibold text-stone-700 mb-1.5">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm">
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-stone-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-semibold text-stone-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                        <select name="role" id="role" required
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm">
                            <option value="karyawan" {{ old('role', $user->role) === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password (Opsional) --}}
                    <div class="pt-4 border-t border-stone-100">
                        <p class="text-xs text-stone-400 mb-3">Kosongkan jika tidak ingin mengubah password</p>
                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-stone-700 mb-1.5">Password Baru</label>
                                <input type="password" name="password" id="password"
                                    class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm"
                                    placeholder="Minimal 6 karakter">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-stone-700 mb-1.5">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm"
                                    placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
                        <a href="{{ route('admin.user.index') }}" class="inline-flex items-center px-4 py-2.5 bg-stone-100 text-stone-600 rounded-lg text-sm font-medium hover:bg-stone-200 transition">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-amber-600 to-amber-500 text-white rounded-lg text-sm font-semibold hover:from-amber-500 hover:to-amber-400 transition-all shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
