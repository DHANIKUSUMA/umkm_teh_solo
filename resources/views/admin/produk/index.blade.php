<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-emerald-700 rounded-full"></div>
            <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                {{ __('Data Produk') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter & Search --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-6">
                <form method="GET" action="{{ route('admin.produk.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..."
                            class="w-full border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm placeholder:text-stone-400">
                    </div>
                    <div>
                        <select name="status" class="w-full sm:w-auto border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-lg text-sm font-semibold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.produk.index') }}" class="inline-flex items-center px-4 py-2 bg-stone-100 text-stone-600 rounded-lg text-sm font-medium hover:bg-stone-200 transition">
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
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">#</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Nama Produk</th>
                                <th class="text-center px-6 py-3 font-semibold text-stone-600">Jumlah Varian</th>
                                <th class="text-center px-6 py-3 font-semibold text-stone-600">Status</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Dibuat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($produks as $produk)
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="px-6 py-4 text-stone-400">{{ $loop->iteration + ($produks->currentPage() - 1) * $produks->perPage() }}</td>
                                    <td class="px-6 py-4 font-medium text-emerald-800">{{ $produk->nama }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            {{ $produk->varians_count }} varian
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $produk->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $produk->is_active ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-stone-500">{{ $produk->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-stone-400">Belum ada data produk</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="sm:hidden divide-y divide-stone-100">
                    @forelse ($produks as $produk)
                        <div class="p-4 hover:bg-emerald-50/30 transition duration-150">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-medium text-emerald-800">{{ $produk->nama }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $produk->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $produk->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-xs text-stone-500">
                                <span>{{ $produk->varians_count }} varian</span>
                                <span>{{ $produk->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-stone-400 text-sm">Belum ada data produk</div>
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
