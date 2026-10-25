<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-emerald-700 rounded-full"></div>
                <div>
                    <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                        {{ __('Data Menu & Produk Teh') }}
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Kelola produk, varian cup, dan harga jual</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.produk.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-sm font-semibold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md shadow-emerald-700/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Tambah Menu Baru</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- Filter & Search --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-6">
                <form method="GET" action="{{ route('admin.produk.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk / menu teh..."
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm text-sm placeholder:text-stone-400">
                    </div>
                    <div>
                        <select name="status" class="w-full sm:w-auto border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm text-sm">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-sm font-semibold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.produk.index') }}" class="inline-flex items-center px-4 py-2.5 bg-stone-100 text-stone-600 rounded-xl text-sm font-medium hover:bg-stone-200 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel Produk --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>

                {{-- Desktop Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50/80">
                            <tr>
                                <th class="text-left px-6 py-3.5 font-semibold text-stone-600">#</th>
                                <th class="text-left px-6 py-3.5 font-semibold text-stone-600">Nama Menu / Produk</th>
                                <th class="text-center px-6 py-3.5 font-semibold text-stone-600">Jumlah Varian</th>
                                <th class="text-center px-6 py-3.5 font-semibold text-stone-600">Status</th>
                                <th class="text-left px-6 py-3.5 font-semibold text-stone-600">Dibuat</th>
                                <th class="text-right px-6 py-3.5 font-semibold text-stone-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($produks as $produk)
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="px-6 py-4 text-stone-400">{{ $loop->iteration + ($produks->currentPage() - 1) * $produks->perPage() }}</td>
                                    <td class="px-6 py-4 font-bold text-emerald-950">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full {{ $produk->is_active ? 'bg-emerald-500' : 'bg-stone-300' }}"></span>
                                            <span>{{ $produk->nama }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.produk.show', $produk) }}" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100/80 hover:bg-emerald-200 text-emerald-900 transition">
                                            <span>{{ $produk->varians_count }} varian</span>
                                            <svg class="w-3 h-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form method="POST" action="{{ route('admin.produk.toggle-active', $produk) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="Klik untuk ubah status" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold cursor-pointer transition
                                                {{ $produk->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                                                {{ $produk->is_active ? '● Aktif' : '○ Non-Aktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-stone-500">{{ $produk->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.produk.show', $produk) }}" class="p-1.5 rounded-lg text-emerald-700 hover:bg-emerald-50 transition" title="Kelola Varian & Harga">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.produk.edit', $produk) }}" class="p-1.5 rounded-lg text-amber-700 hover:bg-amber-50 transition" title="Edit Nama Menu">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.produk.destroy', $produk) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini beserta seluruh variannya?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition" title="Hapus Produk">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-stone-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <span class="text-3xl">🍵</span>
                                            <p class="font-medium text-stone-600">Belum ada data menu / produk</p>
                                            <a href="{{ route('admin.produk.create') }}" class="text-emerald-700 hover:underline text-sm font-semibold">
                                                + Tambah Produk Pertama Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="sm:hidden divide-y divide-stone-100">
                    @forelse ($produks as $produk)
                        <div class="p-4 hover:bg-emerald-50/30 transition duration-150 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-bold text-base text-emerald-950">{{ $produk->nama }}</span>
                                    <div class="text-xs text-stone-500 mt-0.5">{{ $produk->created_at->format('d M Y') }}</div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                    {{ $produk->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-600' }}">
                                    {{ $produk->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <a href="{{ route('admin.produk.show', $produk) }}" class="text-xs font-semibold text-emerald-700 hover:underline">
                                    {{ $produk->varians_count }} varian &rarr;
                                </a>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.produk.show', $produk) }}" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Varian
                                    </a>
                                    <a href="{{ route('admin.produk.edit', $produk) }}" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-50 text-amber-800 border border-amber-200">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.produk.destroy', $produk) }}" onsubmit="return confirm('Hapus menu ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-rose-50 text-rose-700 border border-rose-200">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-stone-400 text-sm">
                            Belum ada menu / produk tersedia
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($produks->hasPages())
                    <div class="px-6 py-4 border-t border-stone-100">
                        {{ $produks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
