<?php

namespace App\Http\Controllers;

use App\Models\Gerbang as GerbangModel;
use Illuminate\Http\Request;

class Gerbang extends Controller
{
    public function index()
    {
        return response()->json(GerbangModel::with(['qrCode', 'qrCodes'])->get());
    }

    public function show($id)
    {
        return response()->json(GerbangModel::with(['qrCode', 'qrCodes'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ID_GERBANG' => 'required|integer',
            'ID_QR' => 'nullable|integer',
            'LOKASI' => 'required|string|max:100',
            'STATUS_PLANG' => 'required|string|max:20',
        ]);

        $gerbang = GerbangModel::create($data);

        return response()->json($gerbang, 201);
    }

    public function update(Request $request, $id)
    {
        $gerbang = GerbangModel::findOrFail($id);

        $data = $request->validate([
            'ID_QR' => 'nullable|integer',
            'LOKASI' => 'required|string|max:100',
            'STATUS_PLANG' => 'required|string|max:20',
        ]);

        $gerbang->update($data);

        return response()->json($gerbang);
    }

    public function destroy($id)
    {
        $gerbang = GerbangModel::findOrFail($id);
        $gerbang->delete();

        return response()->noContent();
    }
}
