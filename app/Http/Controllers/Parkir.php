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

    public function getUserHistory(Request $request)
    {
        $userId = session('user')['ID_PENGGUNA'] ?? null;
        if (!$userId) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $parkirs = ParkirModel::where('ID_PENGGUNA', $userId)
            ->with(['kendaraan', 'penggunaParkir'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($parkirs);
    }

    public function scanMasuk(Request $request, $qrCode)
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'User ID required'], 400);
        }

        // Create new parking record
        $parkir = ParkirModel::create([
            'NO_PLAT' => 'AUTO_' . $qrCode, // Will be updated when vehicle is selected
            'ID_PENGGUNA' => $userId,
            'WAKTU_MASUK' => now(),
            'STATUS_PARKIR' => 'Sedang Parkir',
            'BIAYA' => 0, // Will be calculated on exit
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil masuk parkir',
            'parking_id' => $parkir->ID_PARKIR
        ]);
    }

    public function scanKeluar(Request $request, $qrCode)
    {
        $parkingId = $request->input('parking_id');
        $userId = $request->input('user_id');

        if (!$parkingId || !$userId) {
            return response()->json(['success' => false, 'message' => 'Parking ID and User ID required'], 400);
        }

        $parkir = ParkirModel::where('ID_PARKIR', $parkingId)
            ->where('ID_PENGGUNA', $userId)
            ->where('STATUS_PARKIR', 'Sedang Parkir')
            ->first();

        if (!$parkir) {
            return response()->json(['success' => false, 'message' => 'Parking record not found'], 404);
        }

        // Calculate cost (Rp 5,000 per hour)
        $startTime = new \DateTime($parkir->WAKTU_MASUK ?? $parkir->created_at);
        $endTime = new \DateTime();
        $hours = max(1, ceil(($endTime->getTimestamp() - $startTime->getTimestamp()) / 3600));
        $cost = $hours * 5000;

        // Update parking record
        $parkir->update([
            'WAKTU_KELUAR' => $endTime->format('Y-m-d H:i:s'),
            'BIAYA' => $cost,
            'STATUS_PARKIR' => 'Selesai'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil keluar parkir',
            'cost' => $cost
        ]);
    }
}
