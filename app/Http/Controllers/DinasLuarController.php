<?php

namespace App\Http\Controllers;

use App\Models\DinasLuar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DinasLuarController extends Controller
{
    public function pengajuan_dinas_luar(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'keterangan' => 'required|string|min:10|max:1000',
                'surat_izin'  => 'required|file|mimes:jpg,jpeg,png|max:200',
            ], [
                'keterangan.required' => 'Keterangan harus diisi.',
                'keterangan.min' => 'Keterangan minimal 10 karakter.',
                'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
            ]);

            $user = Auth::user();

            // Cek apakah user sudah mengajukan dinas luar hari ini
            $existingToday = DinasLuar::where('user_id', $user->id)
                ->whereDate('tanggal', now())
                ->first();

            if ($existingToday) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah mengajukan dinas luar untuk hari ini.'
                    ], 422);
                }

                return redirect(url('/presensi-guru'))->with([
                    'error' => [
                        "title" => "Anda sudah mengajukan dinas luar untuk hari ini."
                    ]
                ]);
            }

            $file = $request->file('surat_izin');
            $ext = $file->getClientOriginalExtension();

            $slugName = Str::slug($user->name, '-');
            $stamp = Carbon::now()->format('Ymd-His');

            $filename = "suratizin-{$slugName}-{$stamp}.{$ext}";

            // simpan ke disk public dalam folder surat-izin
            // hasil $storedPath misalnya: "surat-izin/suratizin-salman-haritsi-20250829-093012.jpg"
            $storedPath = $file->storeAs('surat-izin', $filename, 'public');

            // Simpan data dinas luar
            $dinasLuar = new DinasLuar();
            $dinasLuar->user_id = $user->id;
            $dinasLuar->tanggal = now();
            $dinasLuar->keterangan = $validated['keterangan'];
            $dinasLuar->status = 'menunggu';
            $dinasLuar->surat_izin = $storedPath;
            $dinasLuar->save();     

            // Response untuk AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan dinas luar berhasil diajukan.',
                    'data' => [
                        'id' => $dinasLuar->id,
                        'tanggal' => $dinasLuar->tanggal->format('d-m-Y'),
                        'status' => $dinasLuar->status,
                        'surat_izin' => $dinasLuar->surat_izin,
                        'created_at' => $dinasLuar->created_at->format('d-m-Y H:i:s')
                    ]
                ], 201);
            }

            // Response untuk regular form submission
            return redirect(url('/presensi-guru'))->with([
                'success' => [
                    "title" => "Pengajuan dinas luar berhasil diajukan."
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang dimasukkan tidak valid.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect(url('/presensi-guru'))->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error creating Dinas Luar', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
                ], 500);
            }

            return redirect(url('/presensi-guru'))->with([
                'error' => [
                    "title" => "Terjadi kesalahan sistem. Silakan coba lagi."
                ]
            ]);
        }
    }
}
