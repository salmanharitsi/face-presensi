<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    public function get_dashboard_guru_page()
    {
        return view('guru.dashboard');
    }

    public function get_presensi_page()
    {
        $user = Auth::user();

        $presensiHariIni = $user->presensi()
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->first();

        $dinasLuarHariIni = $user->dinasLuar()
            ->whereDate('tanggal', \Carbon\Carbon::today())
            ->exists();

        $belumLaporPulang = false;
        $sudahLaporPulang = false;
        $belumLaporKehadiran = false;

        if ($presensiHariIni) {
            if ($presensiHariIni->jam_masuk !== null && $presensiHariIni->jam_keluar === null) {
                $belumLaporPulang = true;
            } elseif ($presensiHariIni->jam_masuk !== null && $presensiHariIni->jam_keluar !== null) {
                $sudahLaporPulang = true;
            }
        } else {
            $belumLaporKehadiran = true;
        }

        return view('guru.presensi', [
            'belumLaporPulang' => $belumLaporPulang,
            'sudahLaporPulang' => $sudahLaporPulang,
            'belumLaporKehadiran' => $belumLaporKehadiran,
            'dinasLuarHariIni' => $dinasLuarHariIni,
        ]);
    }

    public function get_face_detection_page()
    {
        $user = Auth::user();

        $presensiHariIni = $user->presensi()
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->whereNotNull('jam_masuk')
            ->first();

        return view('guru.face-presensi', [
            'userName' => $user->name,
            'presensiHariIni' => $presensiHariIni
        ]);
    }

    public function get_riwayat_presensi_guru_page(Request $request)
    {
        $user = Auth::user();

        $query = $user->presensi()->orderBy('tanggal', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('tanggal') && $request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Set per_page from request or default to 10
        $perPage = $request->get('per_page', 2);
        $presensi = $query->paginate($perPage);

        // Preserve query parameters in pagination links
        $presensi->appends($request->query());

        if ($request->ajax()) {
            // For AJAX requests, return only the table part
            $html = view('guru.partials.presensi-table', compact('presensi'))->render();
            return response()->json([
                'html' => $html,
                'total' => $presensi->total(),
                'current_page' => $presensi->currentPage(),
                'last_page' => $presensi->lastPage()
            ]);
        }

        return view('guru.riwayat-presensi', compact('presensi'));
    }
}
