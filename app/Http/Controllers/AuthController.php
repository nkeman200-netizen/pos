<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('name', $request->name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'pesan' => 'Akses Ditolak! Nama atau Password salah.'
            ], 401);
        }

        $token = $user->createToken('KartuAksesPOS')->plainTextToken;

        return response()->json([
            'pesan' => 'Login Berhasil!',
            'token' => $token,
            'role' => $user->role 
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'pesan' => 'Logout Berhasil! Token telah dihancurkan dan akses ditutup.'
        ]);
    }
}
