<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManagementuserController extends Controller
{
    public function manageUsers()
    {
        $title = 'User Management';
        $name = 'User Management';
        $users = User::paginate(10); // Ambil data user dengan paginasi
        return view('admin.page.user.manage', compact('users', 'title', 'name'));
    }

    public function createUser()
    {
        $roles = [
            'ADM' => 'Admin',
            'USR' => 'User',
            'PGW' => 'Pegawai',
        ];

        $title = 'Tambah User';
        $name = 'Tambah User';
        return view('admin.page.user.create', compact('roles', 'title', 'name'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:ADM,USR,PGW', // Validasi role
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role, // Simpan role
        ]);

        return redirect()->route('manage.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id); // Ambil satu user berdasarkan ID
        $roles = [
            'ADM' => 'Admin',
            'USR' => 'User',
            'PGW' => 'Pegawai',
        ];
        $title = 'Edit User';
        $name = 'Edit User';
        return view('admin.page.user.edit', compact('user', 'roles', 'title', 'name'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string|in:ADM,USR,PGW',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'role' => $request->role,
        ]);

        return redirect()->route('manage.users')->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('manage.users')->with('success', 'User berhasil dihapus.');
    }

    // Tambahkan data user secara langsung
    public function seedUsers()
    {
        $users = [
            'Indra',
            'Bu Wati',
            'Hari',
            'Muljo',
            'Joko',
            'Upik',
            'Gina',
            'Ijam',
            'Lestari',
            'Surya',
            'Eko'
        ];

        foreach ($users as $name) {
            User::create([
                'username' => $name,
                'email' => strtolower(str_replace(' ', '', $name)) . '@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'USR', // Default role sebagai User
            ]);
        }

        return redirect()->route('manage.users')->with('success', 'Data user berhasil ditambahkan.');
    }
}
