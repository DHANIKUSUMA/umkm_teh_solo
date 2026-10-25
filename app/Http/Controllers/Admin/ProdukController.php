<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\VarianProduk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdukController extends Controller
{
    /**
     * Tampilkan daftar semua produk beserta jumlah variannya.
     */
    public function index(Request $request): View
    {
        $query = Produk::withCount('varians');

        // Filter pencarian berdasarkan nama produk
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%'.$request->search.'%');
        }

        // Filter berdasarkan status aktif
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $produks = $query->latest()->paginate(10)->withQueryString();

        return view('admin.produk.index', compact('produks'));
    }

    /**
     * Tampilkan form tambah produk baru.
     */
    public function create(): View
    {
        return view('admin.produk.create');
    }

    /**
     * Simpan produk baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'varians' => ['required', 'array', 'min:1'],
            'varians.*.nama_varian' => ['required', 'string', 'max:50'],
            'varians.*.kategori' => ['nullable', 'string', 'max:50'],
            'varians.*.harga_modal' => ['required', 'numeric', 'min:0'],
            'varians.*.harga_jual' => ['required', 'numeric', 'min:0'],
        ]);

        $produk = Produk::create([
            'nama' => $validated['nama'],
            'is_active' => true,
        ]);

        foreach ($validated['varians'] as $varianData) {
            VarianProduk::create([
                'produk_id' => $produk->id,
                'kategori' => $varianData['kategori'] ?? $validated['nama'],
                'nama_varian' => $varianData['nama_varian'],
                'harga_modal' => $varianData['harga_modal'],
                'harga_jual' => $varianData['harga_jual'],
                'is_active' => true,
            ]);
        }

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail produk beserta semua variannya.
     */
    public function show(Produk $produk): View
    {
        $produk->load(['varians' => function ($q) {
            $q->latest();
        }]);

        // Statistik penjualan per varian
        $produk->varians->each(function ($varian) {
            $varian->total_terjual = $varian->detailPenjualans()
                ->whereHas('penjualan', fn ($q) => $q->where('status', 'selesai'))
                ->sum('qty');
        });

        return view('admin.produk.show', compact('produk'));
    }

    /**
     * Tampilkan form edit produk.
     */
    public function edit(Produk $produk): View
    {
        return view('admin.produk.edit', compact('produk'));
    }

    /**
     * Update data produk di database.
     */
    public function update(Request $request, Produk $produk): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
        ]);

        $produk->update($validated);

        return redirect()->route('admin.produk.show', $produk)
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Toggle status aktif/nonaktif produk.
     */
    public function toggleActive(Produk $produk): RedirectResponse
    {
        $produk->update(['is_active' => ! $produk->is_active]);

        $status = $produk->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.produk.index')
            ->with('success', "Produk berhasil {$status}.");
    }

    /**
     * Simpan varian baru untuk produk.
     */
    public function storeVarian(Request $request, Produk $produk): RedirectResponse
    {
        $validated = $request->validate([
            'nama_varian' => ['required', 'string', 'max:50'],
            'kategori' => ['nullable', 'string', 'max:50'],
            'harga_modal' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
        ]);

        VarianProduk::create([
            'produk_id' => $produk->id,
            'kategori' => $validated['kategori'] ?? $produk->nama,
            'nama_varian' => $validated['nama_varian'],
            'harga_modal' => $validated['harga_modal'],
            'harga_jual' => $validated['harga_jual'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.produk.show', $produk)
            ->with('success', 'Varian berhasil ditambahkan.');
    }

    /**
     * Update data varian produk (termasuk harga).
     */
    public function updateVarian(Request $request, Produk $produk, VarianProduk $varian): RedirectResponse
    {
        $validated = $request->validate([
            'nama_varian' => ['required', 'string', 'max:50'],
            'kategori' => ['nullable', 'string', 'max:50'],
            'harga_modal' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
        ]);

        $varian->update($validated);

        return redirect()->route('admin.produk.show', $produk)
            ->with('success', 'Varian berhasil diperbarui.');
    }

    /**
     * Toggle status aktif/nonaktif varian produk.
     */
    public function toggleVarianActive(Produk $produk, VarianProduk $varian): RedirectResponse
    {
        $varian->update(['is_active' => ! $varian->is_active]);

        $status = $varian->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.produk.show', $produk)
            ->with('success', "Varian berhasil {$status}.");
    }

    /**
     * Hapus varian produk jika belum ada riwayat penjualan.
     */
    public function destroyVarian(Produk $produk, VarianProduk $varian): RedirectResponse
    {
        if ($varian->detailPenjualans()->exists()) {
            return redirect()->route('admin.produk.show', $produk)
                ->with('error', 'Varian tidak dapat dihapus karena sudah memiliki data riwayat penjualan. Anda dapat menonaktifkan varian ini.');
        }

        $varian->delete();

        return redirect()->route('admin.produk.show', $produk)
            ->with('success', 'Varian berhasil dihapus.');
    }

    /**
     * Hapus produk beserta variannya jika belum ada transaksi.
     */
    public function destroy(Produk $produk): RedirectResponse
    {
        $hasSales = $produk->varians()->whereHas('detailPenjualans')->exists();

        if ($hasSales) {
            return redirect()->route('admin.produk.index')
                ->with('error', 'Produk tidak dapat dihapus karena sudah memiliki riwayat transaksi penjualan. Anda dapat menonaktifkan produk ini.');
        }

        $produk->varians()->delete();
        $produk->delete();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk dan variannya berhasil dihapus.');
    }
}
