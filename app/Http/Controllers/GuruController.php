<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    private function get_auth_user()
    {
        $user = Auth::user();

        if(!$user || !in_array($user->role, haystack: ['guru', 'kepsek', 'admin'])) {
            abort(403, 'Unauthorized access');
        }

        switch ($user->role) {
            case 'guru':
                $layout = 'layouts.app';
                break;
            case 'kepsek':
                $layout = 'layouts.kepsek';
                break;
            case 'admin':
                $layout = 'layouts.admin';
                break;
            default:
                abort(403, 'Unauthorized access');
        }

        return compact('user', 'layout');
    }

    public function get_dashboard_guru_page()
    {
        return view('guru.dashboard');
    }

    public function get_presensi_page()
    {
        $user = Auth::user();

        $auth_user = $this->get_auth_user();

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
            'layout' => $auth_user['layout']
        ]);
    }

    public function get_face_detection_page()
    {
        $user = Auth::user();

        $auth_user = $this->get_auth_user();

        $presensiHariIni = $user->presensi()
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->whereNotNull('jam_masuk')
            ->first();

        return view('guru.face-presensi', [
            'userName' => $user->name,
            'presensiHariIni' => $presensiHariIni,
            'layout' => $auth_user['layout']
        ]);
    }

    public function get_riwayat_presensi_guru_page(Request $request)
    {
        $auth_user = $this->get_auth_user();

        return view('guru.riwayat-presensi', [
            'layout' => $auth_user['layout']
        ]);
    }
}
