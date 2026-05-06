<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran as PembayaranModel;
use Illuminate\Http\Request;

class Pembayaran extends Controller
{
    public function index()
    {
        return response()->json(PembayaranModel::with('parkir')->get());
    }

    public function show($id)
    {
        return response()->json(PembayaranModel::with('parkir')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ID_PEMBAYARAN' => 'required|integer',
            'ID_PARKIR' => 'nullable|integer',
            'METODE' => 'required|string|max:50',
            'STATUS' => 'required|string|max:20',
            'JUMLAH' => 'required|numeric',
            'WAKTU_BAYAR' => 'required|date',
        ]);

        $pembayaran = PembayaranModel::create($data);

        return response()->json($pembayaran, 201);
    }

    public function update(Request $request, $id)
    {
        $pembayaran = PembayaranModel::findOrFail($id);

        $data = $request->validate([
            'ID_PARKIR' => 'nullable|integer',
            'METODE' => 'required|string|max:50',
            'STATUS' => 'required|string|max:20',
            'JUMLAH' => 'required|numeric',
            'WAKTU_BAYAR' => 'required|date',
        ]);

        $pembayaran->update($data);

        return response()->json($pembayaran);
    }

    public function destroy($id)
    {
        $pembayaran = PembayaranModel::findOrFail($id);
        $pembayaran->delete();

        return response()->noContent();
    }
}
