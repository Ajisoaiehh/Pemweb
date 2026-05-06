<?php

namespace App\Http\Controllers;

use App\Models\QR_Code as QRCodeModel;
use Illuminate\Http\Request;

class QR_Code extends Controller
{
    public function index()
    {
        return response()->json(QRCodeModel::with(['kendaraan', 'gerbang', 'gerbangViaQr', 'kendaraans'])->get());
    }

    public function show($id)
    {
        return response()->json(QRCodeModel::with(['kendaraan', 'gerbang', 'gerbangViaQr', 'kendaraans'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ID_QR' => 'required|integer',
            'NO_PLAT' => 'nullable|string|max:15',
            'ID_GERBANG' => 'nullable|integer',
            'TIPE' => 'required|string|max:10',
            'WAKTU_DIBUAT' => 'required|date',
            'VALID_UNTIL' => 'required|date',
        ]);

        $qrCode = QRCodeModel::create($data);

        return response()->json($qrCode, 201);
    }

    public function update(Request $request, $id)
    {
        $qrCode = QRCodeModel::findOrFail($id);

        $data = $request->validate([
            'NO_PLAT' => 'nullable|string|max:15',
            'ID_GERBANG' => 'nullable|integer',
            'TIPE' => 'required|string|max:10',
            'WAKTU_DIBUAT' => 'required|date',
            'VALID_UNTIL' => 'required|date',
        ]);

        $qrCode->update($data);

        return response()->json($qrCode);
    }

    public function destroy($id)
    {
        $qrCode = QRCodeModel::findOrFail($id);
        $qrCode->delete();

        return response()->noContent();
    }
}
