@extends('layouts.kepsek')

@section('title', 'Pengajuan Dinas Luar')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 lg:gap-x-6 gap-x-0 lg:gap-y-6 gap-y-6">
        <!-- Simple Header -->
        <div class="col-span-4 bg-white shadow-sm border rounded-xl">
            <div class="container mx-auto px-6 py-6">
                <h1 class="text-2xl font-semibold text-gray-900">Pengajuan Dinas Luar</h1>
                <p class="text-gray-600 mt-1">Periksa pengajuan dinas luar guru</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-span-4 border bg-white relative rounded-xl overflow-hidden">
            @livewire('daftar-pengajuan-dl')
        </div>
    </div>
@endsection
