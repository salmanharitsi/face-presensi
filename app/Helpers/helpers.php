<?php

use Illuminate\Support\Facades\Request;

if (!function_exists('menuActive')) {
    function menuActive($route) {
        return Request::routeIs($route)
            ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg hover-lift'
            : 'text-gray-700 hover:bg-gradient-to-r group hover:from-blue-50 hover:to-blue-100 hover:text-blue-700 transition-all duration-200 hover-lift';
    }
}

if (!function_exists('iconColor')) {
    function iconColor($route) {
        return Request::routeIs($route)
            ? 'text-white'
            : 'text-gray-500 group-hover:text-blue-700';
    }
}

if (!function_exists('breadcrumb')) {
    function breadcrumb()
    {
        $route = request()->route()->getName();

        // Daftar label dan parent (jika ada)
        $breadcrumbs = [
            'dashboard-guru' => [
                ['label' => 'Dashboard'],
            ],
            'presensi-guru' => [
                ['label' => 'Presensi'],
            ],
            'riwayat-presensi-guru' => [
                ['label' => 'Riwayat Presensi'],
            ],
            'face-detection-page' => [
                ['label' => 'Presensi', 'route' => 'presensi-guru'],
                ['label' => 'Deteksi Wajah'],
            ],
        ];

        return $breadcrumbs[$route] ?? [
            ['label' => ucfirst(str_replace('-', ' ', $route))],
        ];
    }
}


