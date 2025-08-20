<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;

class PresensiController extends Controller
{
    /**
     * Process presensi after face recognition
     */
    public function processPresensi(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'user_name' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated'
                ], 401);
            }

            // Normalisasi nama (hapus spasi ekstra dan lowercase)
            $recognizedName = strtolower(trim($request->user_name));
            $loggedInName = strtolower(trim($user->name));

            if ($recognizedName !== $loggedInName) {
                return response()->json([
                    'error' => 'Face recognition name does not match current user',
                    'recognized_name' => $recognizedName,
                    'logged_in_name' => $loggedInName
                ], 400);
            }

            $today = Carbon::today('Asia/Jakarta');
            $now = Carbon::now('Asia/Jakarta');

            // Cek apakah user sudah presensi hari ini
            $existingPresensi = Presensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();

            if ($existingPresensi) {
                // Jika sudah presensi masuk
                if ($existingPresensi->jam_masuk !== null) {
                    // Cek apakah sudah lapor pulang
                    if ($existingPresensi->jam_keluar !== null) {
                        return response()->json([
                            'error' => 'Anda sudah melakukan presensi masuk dan pulang untuk hari ini',
                            'presensi' => $existingPresensi
                        ], 400);
                    }

                    // Simpan jam_keluar dan koordinat keluar
                    $existingPresensi->jam_keluar = $now->format('H:i:s');
                    $existingPresensi->latitude_keluar = $request->latitude;
                    $existingPresensi->longitude_keluar = $request->longitude;
                    $existingPresensi->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Presensi pulang berhasil dicatat',
                        'type' => 'pulang',
                        'presensi' => [
                            'id' => $existingPresensi->id,
                            'tanggal' => $existingPresensi->tanggal,
                            'jam_masuk' => $existingPresensi->jam_masuk,
                            'jam_keluar' => $existingPresensi->jam_keluar,
                            'status' => $existingPresensi->status,
                            'latitude_masuk' => $existingPresensi->latitude_masuk,
                            'longitude_masuk' => $existingPresensi->longitude_masuk,
                            'latitude_keluar' => $existingPresensi->latitude_keluar,
                            'longitude_keluar' => $existingPresensi->longitude_keluar,
                            'user_name' => $user->name
                        ]
                    ]);
                }
            }

            // Pengecekan status berdasarkan dinas luar
            $status = 'hadir'; // default status
            
            // Cek apakah user memiliki dinas luar yang diterima untuk hari ini
            $dinasLuar = $user->dinasLuar()
                ->where('status', 'disetujui')
                ->whereDate('tanggal', '<=', $today)
                ->first();

            if ($dinasLuar) {
                $status = 'hadir-dl';
            }

            // Buat presensi baru (lapor masuk)
            $presensi = new Presensi();
            $presensi->user_id = $user->id;
            $presensi->tanggal = $today;
            $presensi->jam_masuk = $now->format('H:i:s');
            $presensi->jam_keluar = null;
            $presensi->status = $status;
            $presensi->latitude_masuk = $request->latitude;
            $presensi->longitude_masuk = $request->longitude;
            $presensi->latitude_keluar = null; // Kosongkan untuk keluar
            $presensi->longitude_keluar = null; // Kosongkan untuk keluar
            $presensi->save();

            // Tentukan pesan berdasarkan status
            $message = $status === 'hadir-dl' ? 
                'Presensi masuk berhasil dicatat (Dinas Luar)' : 
                'Presensi masuk berhasil dicatat';

            return response()->json([
                'success' => true,
                'message' => $message,
                'type' => 'masuk',
                'presensi' => [
                    'id' => $presensi->id,
                    'tanggal' => $presensi->tanggal,
                    'jam_masuk' => $presensi->jam_masuk,
                    'jam_keluar' => $presensi->jam_keluar,
                    'status' => $presensi->status,
                    'latitude_masuk' => $presensi->latitude_masuk,
                    'longitude_masuk' => $presensi->longitude_masuk,
                    'latitude_keluar' => $presensi->latitude_keluar,
                    'longitude_keluar' => $presensi->longitude_keluar,
                    'user_name' => $user->name
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validasi gagal',
                'messages' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memproses presensi',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}