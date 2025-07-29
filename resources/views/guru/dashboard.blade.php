@extends('layouts.app')

@section('title', 'Guru dashboard')


@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2">Selamat Datang Kembali! 👋</h1>
                <p class="text-primary-100 text-lg mb-6">Laporkan kehadiran Anda dengan mudah dan cepat</p>
                <a href="{{ route('presensi-guru') }}" class="bg-white text-primary-600 px-6 py-3 rounded-xl font-semibold hover:bg-primary-50 transition-colors hover-lift">
                    Mulai Sekarang
                </a>
            </div>
            <div class="absolute right-0 top-0 w-64 h-64 bg-white bg-opacity-10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute right-0 bottom-0 w-48 h-48 bg-white bg-opacity-5 rounded-full -mr-24 -mb-24"></div>
        </div>
    </div>

    <div>
        Halaman Dashboard Guru
    </div>

@endsection
