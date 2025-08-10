@extends($layout)

@section('title', 'Rekapitulasi Presensi')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 lg:gap-x-6 gap-x-0 lg:gap-y-6 gap-y-6">
        <!-- Simple Header -->
        <div class="col-span-4 bg-white shadow-sm border rounded-xl">
            <div class="container mx-auto px-6 py-6">
                <h1 class="text-2xl font-semibold text-gray-900">Rekapitulasi Presensi</h1>
                <p class="text-gray-600 mt-1">Rekapitulasi presensi guru</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-span-4 border bg-white relative rounded-xl overflow-hidden">
            @livewire('rekapitulasi-presensi')
        </div>
        
    </div>
@endsection
