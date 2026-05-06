<?php

namespace App\Http\Controllers;

use App\Models\Parkir as ParkirModel;
use Illuminate\Http\Request;

class Parkir extends Controller
{
    public function index()
    {
        return response()->json(ParkirModel::with(['parentParkir', 'childParkirs', 'kendaraan', 'penggunaParkir', 'pembayarans'])->get());
    }

    public function show($id)
    {
        return response()->json(ParkirModel::with(['parentParkir', 'childParkirs', 'kendaraan', 'penggunaParkir', 'pembayarans'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ID_PARKIR' => 'required|integer',
            'PAR_ID_PARKIR' => 'nullable|integer',
            'NO_PLAT' => 'nullable|string|max:15',
            'ID_PENGGUNA' => 'nullable|integer',
            'WAKTU_MASUK' => 'required|date',
            'WAKTU_KELUAR' => 'required|date',
            'BIAYA' => 'required|numeric',
            'STATUS_PARKIR' => 'required|string|max:20',
        ]);

        $parkir = ParkirModel::create($data);

        return response()->json($parkir, 201);
    }

    public function update(Request $request, $id)
    {
        $parkir = ParkirModel::findOrFail($id);

        $data = $request->validate([
            'PAR_ID_PARKIR' => 'nullable|integer',
            'NO_PLAT' => 'nullable|string|max:15',
            'ID_PENGGUNA' => 'nullable|integer',
            'WAKTU_MASUK' => 'required|date',
            'WAKTU_KELUAR' => 'required|date',
            'BIAYA' => 'required|numeric',
            'STATUS_PARKIR' => 'required|string|max:20',
        ]);

        $parkir->update($data);

        return response()->json($parkir);
    }

    public function destroy($id)
    {
        $parkir = ParkirModel::findOrFail($id);
        $parkir->delete();

        return response()->noContent();
    }
}
