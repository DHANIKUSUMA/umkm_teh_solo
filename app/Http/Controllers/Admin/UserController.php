<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna/karyawan.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Filter pencarian berdasarkan nama, username, atau email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('username', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status aktif
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.user.index', compact('users'));
    }

    /**
     * Tampilkan form tambah karyawan baru.
     */
    public function create(): View
    {
        return view('admin.user.create');
    }

    /**
     * Simpan karyawan baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:admin,karyawan'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        // Sync Spatie role
        $role = Role::firstOrCreate(['name' => $validated['role'], 'guard_name' => 'web']);
        $user->syncRoles([$role]);

        return redirect()->route('admin.user.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit karyawan.
     */
    public function edit(User $user): View
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update data karyawan di database.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:admin,karyawan'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        if (! empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Sync Spatie role
        $role = Role::firstOrCreate(['name' => $validated['role'], 'guard_name' => 'web']);
        $user->syncRoles([$role]);

        return redirect()->route('admin.user.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Toggle status aktif/nonaktif karyawan.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.user.index')
            ->with('success', "Karyawan berhasil {$status}.");
    }
}
