<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function get_dashboard_admin_page()
    {
        return view('admin.dashboard');
    }
}
