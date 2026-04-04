<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input yang masuk (Harus ada nama dan password)
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        // 2. Cari user di database berdasarkan namanya
        $user = User::where('name', $request->name)->first();

        // 3. Jika user tidak ada ATAU passwordnya salah (Cek Hash)
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'pesan' => 'Akses Ditolak! Nama atau Password salah.'
            ], 401);
        }

        // 4. Jika benar, cetak "Kartu Akses" (Token)
        $token = $user->createToken('KartuAksesPOS')->plainTextToken;

        // 5. Berikan token tersebut ke Frontend
        return response()->json([
            'pesan' => 'Login Berhasil!',
            'token' => $token,
            'role' => $user->role // Kita kirim role agar Frontend tahu ini Admin atau Kasir
        ]);
    }

    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'pesan' => 'Logout Berhasil! Token telah dihancurkan dan akses ditutup.'
        ]);
    }
}
