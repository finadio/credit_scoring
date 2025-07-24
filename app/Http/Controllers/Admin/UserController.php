<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Import model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Validation\Rule; // Untuk validasi unik email saat update

class UserController extends Controller
{
    // Pastikan user adalah admin
    public function __construct()
    {
        $this->middleware('can:manage-users'); // Memastikan hanya admin yang bisa akses controller ini
    }

    /**
     * Display a listing of the resource (Daftar Pengguna).
     */
    public function index()
    {
        $users = User::all(); // Ambil semua data user
        return view('admin.users.index', compact('users')); // Arahkan ke view index
    }

    /**
     * Show the form for creating a new resource (Form Tambah Pengguna).
     */
    public function create()
    {
        $roles = ['admin', 'teller', 'kabag', 'direksi']; // Pilihan role
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage (Simpan Pengguna Baru).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'teller', 'kabag', 'direksi'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // Otomatis verifikasi email saat dibuat Admin
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * Tidak terlalu relevan untuk manajemen user sederhana, tapi bisa untuk detail user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource (Form Edit Pengguna).
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'teller', 'kabag', 'direksi'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage (Update Pengguna).
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Email harus unik kecuali untuk user yang sedang diedit
            ],
            'role' => ['required', Rule::in(['admin', 'teller', 'kabag', 'direksi'])],
            'password' => 'nullable|string|min:8|confirmed', // Password bisa kosong jika tidak ingin diubah
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) { // Jika password diisi, hash dan update
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage (Hapus Pengguna).
     */
    public function destroy(User $user)
    {
        // Pencegahan agar admin tidak bisa menghapus dirinya sendiri atau admin lain (opsional)
        if (auth()->user()->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak bisa menghapus admin terakhir di sistem.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}