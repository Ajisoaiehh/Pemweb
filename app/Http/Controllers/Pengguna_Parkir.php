<?php

namespace App\Http\Controllers;

use App\Models\Pengguna_Parkir as PenggunaParkirModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Pengguna_Parkir extends Controller
{
    public function index()
    {
        return response()->json(PenggunaParkirModel::with(['kendaraans', 'parkirs'])->get());
    }

    public function show($id)
    {
        return response()->json(PenggunaParkirModel::with(['kendaraans', 'parkirs'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ID_PENGGUNA' => 'required|integer',
            'NAMA' => 'required|string|max:100',
            'NO_HP' => 'required|string|max:15',
            'EMAIL' => 'required|string|max:100',
            'PASSWORD' => 'required|string|max:100',
            'SALDO' => 'required|numeric',
        ]);

        $data['PASSWORD'] = Hash::make($data['PASSWORD']);

        $pengguna = PenggunaParkirModel::create($data);

        return response()->json($pengguna, 201);
    }

    public function update(Request $request, $id)
    {
        $pengguna = PenggunaParkirModel::findOrFail($id);

        $data = $request->validate([
            'NAMA' => 'required|string|max:100',
            'NO_HP' => 'required|string|max:15',
            'EMAIL' => 'required|string|max:100',
            'PASSWORD' => 'sometimes|string|max:100',
            'SALDO' => 'required|numeric',
        ]);

        if (isset($data['PASSWORD'])) {
            $data['PASSWORD'] = Hash::make($data['PASSWORD']);
        }

        $pengguna->update($data);

        return response()->json($pengguna);
    }

    public function destroy($id)
    {
        $pengguna = PenggunaParkirModel::findOrFail($id);
        $pengguna->delete();

        return response()->noContent();
    }

    // Authentication Methods
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|string|max:100|unique:pengguna_parkir,EMAIL',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $userId = PenggunaParkirModel::max('ID_PENGGUNA') + 1;

        $pengguna = PenggunaParkirModel::create([
            'ID_PENGGUNA' => $userId,
            'NAMA' => $data['nama'],
            'EMAIL' => $data['email'],
            'NO_HP' => $data['no_hp'],
            'PASSWORD' => Hash::make($data['password']),
            'SALDO' => 0,
        ]);

        Session::put('user', $pengguna->toArray());

        return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil!');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $pengguna = PenggunaParkirModel::where('EMAIL', $data['email'])->first();

        if ($pengguna && Hash::check($data['password'], $pengguna->PASSWORD)) {
            Session::put('user', $pengguna->toArray());
            return redirect()->route('user.dashboard')->with('success', 'Login berhasil!');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function logout(Request $request)
    {
        Session::forget('user');
        return redirect()->route('home')->with('success', 'Logout berhasil!');
    }

    public function updateProfile(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:pengguna_parkir,EMAIL,' . $user->ID_PENGGUNA . ',ID_PENGGUNA',
        ]);

        $pengguna = PenggunaParkirModel::findOrFail($user['ID_PENGGUNA']);
        $pengguna->update([
            'NAMA' => $data['nama'],
            'NO_HP' => $data['no_hp'],
            'EMAIL' => $data['email'],
        ]);

        Session::put('user', $pengguna->toArray());

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function getBalance()
    {
        $user = Session::get('user');
        return response()->json(['saldo' => $user ? $user->SALDO : 0]);
    }
}
