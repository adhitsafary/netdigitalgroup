<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|string',
            'password' => 'required|string|min:6', // Validasi password minimal 6 karakter
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file foto
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'role.required' => 'Role wajib dipilih.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus memiliki minimal 6 karakter.',
            'foto.required' => 'Foto wajib diunggah.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Format foto yang diizinkan: jpeg, png, jpg, gif.',
            'foto.max' => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        try {
            // Simpan foto ke direktori public/asset/img/user
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName(); // Nama file unik
            $path = 'asset/img/user';
            $file->move(public_path($path), $filename); // Simpan ke folder public

            // Simpan data pengguna baru
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'foto' => $path . '/' . $filename, // Simpan path foto ke database
            ]);

            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan user. Silakan coba lagi.');
        }
    }




    public function store2(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|string',
            'password' => 'required|string|min:6',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file foto
        ]);

        // Simpan foto
        $path = $request->file('foto')->store('foto_users', 'public');

        // Simpan data pengguna baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'foto' => $path, // Simpan path foto ke database
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }


    // Menampilkan form input user baru
    public function create()
    {
        return view('users.create');
    }



    // Menampilkan daftar user
    public function index()
    {
        $users = User::whereNotIn('name', ['devine'])->get();

        return view('users.index', compact('users'));
    }

    // Menampilkan form edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update2(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:teknisi,admin,superadmin',
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto baru
        ]);

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Update password jika ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update foto jika ada foto baru
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_users', 'public');
            $user->foto = $path;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }


    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:teknisi,admin,superadmin',
        'password' => 'nullable|string|min:6',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Update password jika ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update foto jika ada foto baru
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'asset/img/user';
            $file->move(public_path($path), $filename);

            // Hapus foto lama jika ada
            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }

            $user->foto = $path . '/' . $filename;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Gagal memperbarui data pengguna: ' . $e->getMessage()]);
    }
}



    // Menghapus data user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
