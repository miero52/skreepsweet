<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan semua user
    public function index()
    {
        $users = User::where('role', 'masyarakat')->latest()->paginate(10);
        return view('petugas.users.index', compact('users'));
    }

    // Edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('petugas.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only('name', 'phone', 'address', 'status'));

        return redirect()->route('petugas.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('petugas.users.index')->with('success', 'Data pengguna berhasil dihapus.');
    }
}
