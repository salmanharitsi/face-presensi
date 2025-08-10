@extends('layouts.kepsek')

@section('title', 'Kepala Sekolah dashboard')

@section('content')
@php
    use Carbon\Carbon;

    $today = Carbon::today();
    $isSunday = $today->isSunday();
    $todayPresensi = Auth::user()->presensi()->whereDate('tanggal', $today)->first();

    $message = '';
    $cardClass = '';
    $icon = '';

    if ($isSunday) {
        $message = 'Selamat beristirahat, hari ini adalah hari Minggu';
        $cardClass = 'bg-purple-50 border-purple-200 text-purple-800';
        $icon = '😌';
    } elseif (!$todayPresensi) {
        $message = 'Laporkan kehadiran anda segera sebelum melewati batas waktu yang ditentukan';
        $cardClass = 'bg-yellow-50 border-yellow-200 text-yellow-800';
        $icon = '⏰';
    } elseif ($todayPresensi->status == 'tidak-hadir') {
        $message = 'Anda tidak hadir hari ini';
        $cardClass = 'bg-red-50 border-red-200 text-red-800';
        $icon = '❌';
    } elseif ($todayPresensi->jam_masuk && !$todayPresensi->jam_keluar) {
        $message = 'Laporkan kepulangan anda sesuai dengan waktu yang telah ditentukan';
        $cardClass = 'bg-blue-50 border-blue-200 text-blue-800';
        $icon = '🏠';
    } elseif ($todayPresensi->jam_masuk && $todayPresensi->jam_keluar) {
        $message = 'Terimakasih untuk kerja kerasnya hari ini';
        $cardClass = 'bg-green-50 border-green-200 text-green-800';
        $icon = '✅';
    }

    // Presensi Count
    $user = Auth::user();
    $totalHadir = $user->presensi()->where('status', 'hadir')->count();
    $totalHadirDl = $user->presensi()->where('status', 'hadir-dl')->count();
    $totalHadirTidakLaporPulang = $user->presensi()->where('status', 'hadir-tidak-lapor-pulang')->count();
    $totalTidakHadir = $user->presensi()->where('status', 'tidak-hadir')->count();
    $totalPresensi = $totalHadir + $totalHadirDl + $totalHadirTidakLaporPulang + $totalTidakHadir;
    $persentaseHadir = $totalPresensi > 0 ? round((($totalHadir + $totalHadirDl + $totalHadirTidakLaporPulang) / $totalPresensi) * 100, 1) : 0;

    $thisMonth = Carbon::now()->startOfMonth();
    $monthlyHadir = $user->presensi()->whereIn('status', ['hadir', 'hadir-dl', 'hadir-tidak-lapor-pulang'])->where('tanggal', '>=', $thisMonth)->count();

    // Weekly Chart
    $weeklyLabels = [];
    $weeklyData = [];
    $daysBack = 0;
    $workDaysFound = 0;
    $today = Carbon::now();

    while ($workDaysFound < 6 && $daysBack < 14) {
        $date = $today->copy()->subDays($daysBack);
        if ($date->dayOfWeek >= 1 && $date->dayOfWeek <= 6) {
            $attendance = $user->presensi()->whereDate('tanggal', $date)->first();
            $isHadir = $attendance && in_array($attendance->status, ['hadir', 'hadir-dl', 'hadir-tidak-lapor-pulang']);
            array_unshift($weeklyLabels, $date->format('D'));
            array_unshift($weeklyData, $isHadir ? 1 : 0);
            $workDaysFound++;
        }
        $daysBack++;
    }
@endphp

<!-- Welcome Section -->
<div class="mb-6">
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Selamat Datang Kembali! 👋</h1>
            <p class="text-primary-100 text-lg mb-6">Laporkan kehadiran Anda dengan mudah dan cepat</p>
            <a href="{{ route('presensi-guru') }}"
               class="bg-white text-primary-600 px-6 py-3 rounded-xl font-semibold hover:bg-primary-50 transition-colors hover-lift">
                Mulai Sekarang
            </a>
        </div>
        <div class="absolute right-0 top-0 w-64 h-64 bg-white bg-opacity-10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute right-0 bottom-0 w-48 h-48 bg-white bg-opacity-5 rounded-full -mr-24 -mb-24"></div>
    </div>
</div>

<!-- Status Card Today -->
<div class="mb-6">
    <div class="border-2 rounded-xl p-6 {{ $cardClass }}">
        <div class="flex items-center space-x-3">
            <span class="text-2xl">{{ $icon }}</span>
            <div>
                <h3 class="font-semibold text-lg">Status Hari Ini</h3>
                <p class="text-sm opacity-90">{{ $message }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Hadir</p>
                <p class="text-2xl font-bold text-green-600">{{ $totalHadir + $totalHadirDl + $totalHadirTidakLaporPulang }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <span class="text-green-600 text-xl">✓</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Tidak Hadir</p>
                <p class="text-2xl font-bold text-red-600">{{ $totalTidakHadir }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <span class="text-red-600 text-xl">✗</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Persentase Kehadiran</p>
                <p class="text-2xl font-bold text-blue-600">{{ $persentaseHadir }}%</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-blue-600 text-xl">📊</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Hadir Bulan Ini</p>
                <p class="text-2xl font-bold text-purple-600">{{ $monthlyHadir }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <span class="text-purple-600 text-xl">📅</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Pie Chart -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold mb-4">Distribusi Kehadiran</h3>
        <div class="flex items-center justify-center" style="height: 300px;">
            <canvas id="attendanceChart"></canvas>
        </div>
        <div class="flex justify-center space-x-4 mt-4 flex-wrap text-sm text-gray-600">
            <div class="flex items-center space-x-2"><div class="w-3 h-3 bg-green-500 rounded-full"></div><span>Hadir ({{ $totalHadir }})</span></div>
            <div class="flex items-center space-x-2"><div class="w-3 h-3 bg-yellow-400 rounded-full"></div><span>Hadir - DL ({{ $totalHadirDl }})</span></div>
            <div class="flex items-center space-x-2"><div class="w-3 h-3 bg-blue-400 rounded-full"></div><span>Hadir - Tidak Lapor Pulang ({{ $totalHadirTidakLaporPulang }})</span></div>
            <div class="flex items-center space-x-2"><div class="w-3 h-3 bg-red-500 rounded-full"></div><span>Tidak Hadir ({{ $totalTidakHadir }})</span></div>
        </div>
    </div>

    <!-- Weekly Chart -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold mb-4">Kehadiran 6 Hari Kerja Terakhir</h3>
        <div style="height: 300px;">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // PIE Chart
        const ctx1 = document.getElementById('attendanceChart');
        if (ctx1) {
            new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Hadir - DL', 'Hadir - Tidak Lapor Pulang', 'Tidak Hadir'],
                    datasets: [{
                        data: [{{ $totalHadir }}, {{ $totalHadirDl }}, {{ $totalHadirTidakLaporPulang }}, {{ $totalTidakHadir }}],
                        backgroundColor: ['#10B981', '#FBBF24', '#3B82F6', '#EF4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // WEEKLY Chart
        const ctx2 = document.getElementById('weeklyChart');
        if (ctx2) {
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($weeklyLabels) !!},
                    datasets: [{
                        label: 'Kehadiran',
                        data: {!! json_encode($weeklyData) !!},
                        backgroundColor: '#3B82F6',
                        borderRadius: 6
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return value === 1 ? 'Hadir' : 'Tidak Hadir';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    });
</script>
@endsection
