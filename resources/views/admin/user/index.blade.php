<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-gradient-to-b from-stone-400 to-stone-600 rounded-full"></div>
                <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                    {{ __('Manajemen Pengguna') }}
                </h2>
            </div>
            <a href="{{ route('admin.user.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-lg text-sm font-semibold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md hover:shadow-lg">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Karyawan
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Message --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filter & Search --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-6">
                <form method="GET" action="{{ route('admin.user.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, username, atau email..."
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm placeholder:text-stone-400">
                    </div>
                    <select name="role" class="border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="karyawan" {{ request('role') === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                    </select>
                    <select name="status" class="border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-lg text-sm font-semibold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari
                        </button>
                        @if(request()->hasAny(['search', 'role', 'status']))
                            <a href="{{ route('admin.user.index') }}" class="inline-flex items-center px-4 py-2 bg-stone-100 text-stone-600 rounded-lg text-sm font-medium hover:bg-stone-200 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel User --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>

                {{-- Desktop Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50/80">
                            <tr>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">#</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Nama</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Username</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Email</th>
                                <th class="text-center px-6 py-3 font-semibold text-stone-600">Role</th>
                                <th class="text-center px-6 py-3 font-semibold text-stone-600">Status</th>
                                <th class="text-center px-6 py-3 font-semibold text-stone-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($users as $user)
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="px-6 py-4 text-stone-400">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ $user->role === 'admin' ? 'from-amber-400 to-amber-600' : 'from-emerald-400 to-emerald-600' }} flex items-center justify-center text-white text-xs font-bold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-emerald-800">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-stone-600">{{ $user->username }}</td>
                                    <td class="px-6 py-4 text-stone-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <a href="{{ route('admin.user.edit', $user) }}" class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 text-amber-700 rounded-lg text-xs font-medium hover:bg-amber-100 transition" title="Edit">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.user.toggle-active', $user) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium transition
                                                    {{ $user->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                                    onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} karyawan ini?')">
                                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-stone-400">Belum ada data pengguna</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="sm:hidden divide-y divide-stone-100">
                    @forelse ($users as $user)
                        <div class="p-4 hover:bg-emerald-50/30 transition duration-150">
                            <div class="flex justify-between items-start mb-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ $user->role === 'admin' ? 'from-amber-400 to-amber-600' : 'from-emerald-400 to-emerald-600' }} flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-emerald-800">{{ $user->name }}</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </div>
                            <p class="text-xs text-stone-500 ml-10">@{{ $user->username }} · {{ $user->email }}</p>
                            <div class="mt-2 ml-10 flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                                <a href="{{ route('admin.user.edit', $user) }}" class="text-xs text-amber-600 font-medium hover:underline">Edit</a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-stone-400 text-sm">Belum ada data pengguna</div>
                    @endforelse
                </div>

                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t border-stone-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
