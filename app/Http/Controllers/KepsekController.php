<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KepsekController extends Controller
{
    public function get_dashboard_kepsek_page()
    {
        return view('kepsek.dashboard');
    }

    public function get_pengajuan_dinas_luar_page()
    {
        return view('kepsek.pengajuan-dinas-luar');
    }
}
