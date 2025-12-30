<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller; // Wajib ada
use Illuminate\Http\Request;         // Wajib untuk $request
use Illuminate\Support\Facades\Hash; // Wajib untuk Hash::make
use Illuminate\Support\Facades\Auth; // Opsional tapi baik digunakan
class ProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil
     */
    public function edit()
    {
        return view('pengajar.profile');
    }

    /**
     * Memperbarui data profil
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:8',
        ]);

        // Update data dasar
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update foto jika ada unggahan baru
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}