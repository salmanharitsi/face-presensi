@extends($layout)

@section('title', 'Data Presensi')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 lg:gap-x-6 gap-x-0 lg:gap-y-6 gap-y-6">
        <!-- Simple Header -->
        <div class="col-span-4 bg-white shadow-sm border rounded-xl">
            <div class="container mx-auto px-6 py-6">
                <h1 class="text-2xl font-semibold text-gray-900">Data Presensi</h1>
                @if (Auth::user()->role == 'admin')
                    <p class="text-gray-600 mt-1">Kelola data presensi pengajar</p>
                @else
                    <p class="text-gray-600 mt-1">Lihat data presensi pengajar</p>
                @endif
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-span-4 bg-blue-100 border border-blue-500 rounded-xl">
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-blue-800 font-medium">Pilih pengajar untuk melihat data presensi miliknya</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-span-4 border bg-white relative rounded-xl overflow-hidden">
            @livewire('data-presensi')
        </div>
    </div>
@endsection