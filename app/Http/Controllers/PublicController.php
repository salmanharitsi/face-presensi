<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    /**
     * Get the authenticated user and determine the layout based on role.
     *
     * @return array [$user, $layout]
     */
    private function getAuthUserWithLayout(): array
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['guru', 'kepsek', 'admin'])) {
            abort(403, 'Unauthorized access');
        }

        $layoutMap = [
            'guru' => 'layouts.app',
            'kepsek' => 'layouts.kepsek',
            'admin' => 'layouts.admin',
        ];

        $layout = $layoutMap[$user->role] ?? abort(403, 'Unauthorized access');

        return [$user, $layout];
    }
    
    public function get_profil_page()
    {
        [$user, $layout] = $this->getAuthUserWithLayout();

        return view('public.profil', compact('user', 'layout'));
    }

    public function get_rekapitulasi_presensi_page()
    {
        [, $layout] = $this->getAuthUserWithLayout();

        return view('public.rekapitulasi-presensi', compact('layout'));
    }

    public function get_data_pengajar_page()
    {
        [, $layout] = $this->getAuthUserWithLayout();

        return view('public.data-pengajar', compact('layout'));
    }

    public function get_data_presensi_page()
    {
        [, $layout] = $this->getAuthUserWithLayout();

        return view('public.data-presensi', compact('layout'));
    }
}
