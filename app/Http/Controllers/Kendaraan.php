<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan as KendaraanModel;
use Illuminate\Http\Request;

class Kendaraan extends Controller
{
    public function index()
    {
        return response()->json(KendaraanModel::with(['penggunaParkir', 'qrCode', 'parkirs'])->get());
    }

    public function show($id)
    {
        return response()->json(KendaraanModel::with(['penggunaParkir', 'qrCode', 'parkirs'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'NO_PLAT' => 'required|string|max:15',
            'ID_PENGGUNA' => 'nullable|integer',
            'ID_QR' => 'nullable|integer',
            'JENIS_KENDARAAN' => 'required|string|max:50',
            'STATUS_KENDARAAN' => 'required|string|max:20',
        ]);

        $kendaraan = KendaraanModel::create($data);

        return response()->json($kendaraan, 201);
    }

    public function update(Request $request, $id)
    {
        $kendaraan = KendaraanModel::findOrFail($id);

        $data = $request->validate([
            'ID_PENGGUNA' => 'nullable|integer',
            'ID_QR' => 'nullable|integer',
            'JENIS_KENDARAAN' => 'required|string|max:50',
            'STATUS_KENDARAAN' => 'required|string|max:20',
        ]);

        $kendaraan->update($data);

        return response()->json($kendaraan);
    }

    public function destroy($id)
    {
        $kendaraan = KendaraanModel::findOrFail($id);
        $kendaraan->delete();

        return response()->noContent();
    }
}
