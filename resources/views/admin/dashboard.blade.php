@extends('layouts.admin')

@section('title', 'Admin dashboard')

@section('content')
    @php
        use Carbon\Carbon;

        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisWeek = Carbon::now()->startOfWeek();

        // Data Overview - Sesuaikan dengan model yang ada
        $totalGuru = \App\Models\User::where('role', 'guru')->count();
        $totalKepsek = \App\Models\User::where('role', 'kepsek')->count();
        $totalAdmin = \App\Models\User::where('role', 'admin')->count();
        $totalUsers = $totalGuru + $totalKepsek + $totalAdmin;

        // Presensi Statistics Today
        $todayPresensi = \App\Models\Presensi::whereDate('tanggal', $today)->get();
        $hadir = $todayPresensi->whereIn('status', ['hadir', 'hadir-dl', 'hadir-tidak-lapor-pulang'])->count();
        $tidakHadir = $todayPresensi->where('status', 'tidak-hadir')->count();
        $belumPresensi = $totalGuru + $totalKepsek - $todayPresensi->count();

        // Monthly Statistics
        $monthlyPresensi = \App\Models\Presensi::where('tanggal', '>=', $thisMonth)->get();
        $monthlyHadir = $monthlyPresensi->whereIn('status', ['hadir', 'hadir-dl', 'hadir-tidak-lapor-pulang'])->count();
        $monthlyTidakHadir = $monthlyPresensi->where('status', 'tidak-hadir')->count();

        // Weekly Attendance Data for Chart
        $weeklyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            if (!$date->isSunday()) {
                $dayPresensi = \App\Models\Presensi::whereDate('tanggal', $date)->get();
                $weeklyStats[] = [
                    'date' => $date->format('D'),
                    'hadir' => $dayPresensi->whereIn('status', ['hadir', 'hadir-dl', 'hadir-tidak-lapor-pulang'])->count(),
                    'tidak_hadir' => $dayPresensi->where('status', 'tidak-hadir')->count(),
                    'belum_presensi' => ($totalGuru + $totalKepsek) - $dayPresensi->count()
                ];
            }
        }

        // Status Distribution for Pie Chart
        $statusDistribution = [
            'hadir' => $monthlyPresensi->where('status', 'hadir')->count(),
            'hadir_dl' => $monthlyPresensi->where('status', 'hadir-dl')->count(),
            'hadir_tidak_lapor' => $monthlyPresensi->where('status', 'hadir-tidak-lapor-pulang')->count(),
            'tidak_hadir' => $monthlyPresensi->where('status', 'tidak-hadir')->count()
        ];

        // Recent Activity (Last 5 presensi entries)
        $recentActivity = \App\Models\Presensi::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Attendance Percentage
        $totalExpectedPresensi = ($totalGuru + $totalKepsek) * Carbon::now()->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isSunday();
        }, $thisMonth);

        $attendancePercentage = $totalExpectedPresensi > 0 ? round(($monthlyHadir / $totalExpectedPresensi) * 100, 1) : 0;
    @endphp

    <!-- Welcome Section -->
    <div class="mb-6">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2">Selamat Datang Kembali! 👋</h1>
                <p class="text-primary-100 text-lg mb-6">Kelola data pengajar dengan mudah dan cepat</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('data-pengajar') }}"
                        class="bg-white text-primary-600 px-6 py-3 rounded-xl font-semibold hover:bg-primary-50 transition-colors hover-lift">
                        Kelola Pengguna
                    </a>
                    <a href="{{ route('data-presensi') }}"
                        class="bg-primary-400 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-300 transition-colors hover-lift border border-primary-400">
                        Lihat Laporan
                    </a>
                </div>
            </div>
            <div class="absolute right-0 top-0 w-64 h-64 bg-white bg-opacity-10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute right-0 bottom-0 w-48 h-48 bg-white bg-opacity-5 rounded-full -mr-24 -mb-24"></div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pengguna</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalUsers }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $totalGuru }} Guru, {{ $totalKepsek }} Kepsek</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 text-xl">👥</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Hadir Hari Ini</p>
                    <p class="text-2xl font-bold text-green-600">{{ $hadir }}</p>
                    <p class="text-xs text-gray-400 mt-1">dari {{ $totalGuru + $totalKepsek }} orang</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="text-green-600 text-xl">✅</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Tidak Hadir Hari Ini</p>
                    <p class="text-2xl font-bold text-red-600">{{ $tidakHadir }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $belumPresensi }} belum presensi</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <span class="text-red-600 text-xl">❌</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Tingkat Kehadiran</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $attendancePercentage }}%</p>
                    <p class="text-xs text-gray-400 mt-1">Bulan ini</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <span class="text-purple-600 text-xl">📊</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Weekly Attendance Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Kehadiran 7 Hari Terakhir</h3>
                <div class="flex space-x-4 text-sm">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-gray-600">Hadir</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-gray-600">Tidak Hadir</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                        <span class="text-gray-600">Belum Presensi</span>
                    </div>
                </div>
            </div>
            <div style="height: 300px;">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold mb-4">Distribusi Status (Bulan Ini)</h3>
            <div class="flex items-center justify-center" style="height: 250px;">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span>Hadir</span>
                    </div>
                    <span class="font-semibold">{{ $statusDistribution['hadir'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                        <span>Hadir - DL</span>
                    </div>
                    <span class="font-semibold">{{ $statusDistribution['hadir_dl'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                        <span>Hadir - Tidak Lapor</span>
                    </div>
                    <span class="font-semibold">{{ $statusDistribution['hadir_tidak_lapor'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span>Tidak Hadir</span>
                    </div>
                    <span class="font-semibold">{{ $statusDistribution['tidak_hadir'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Aktivitas Terbaru</h3>
                <a href="{{ route('data-presensi') }}" class="text-primary-600 text-sm hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($recentActivity as $activity)
                    <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            @if($activity->status == 'hadir')
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-600 text-sm">✓</span>
                                </div>
                            @elseif($activity->status == 'tidak-hadir')
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <span class="text-red-600 text-sm">✗</span>
                                </div>
                            @else
                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <span class="text-yellow-600 text-sm">!</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $activity->user->name }}</p>
                            <p class="text-xs text-gray-500">
                                {{ ucfirst(str_replace('-', ' ', $activity->status)) }} •
                                {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <span class="text-4xl mb-2 block">📋</span>
                        <p>Belum ada aktivitas presensi</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold mb-4">Menu Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('data-pengajar') }}"
                    class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                    <div
                        class="w-12 h-12 bg-blue-100 group-hover:bg-blue-200 rounded-full flex items-center justify-center mb-3 transition-colors">
                        <span class="text-blue-600 text-xl">👤</span>
                    </div>
                    <span class="text-sm font-medium text-blue-700">Tambah Pengguna</span>
                </a>

                <a href="{{ route('data-presensi') }}"
                    class="flex flex-col items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                    <div
                        class="w-12 h-12 bg-green-100 group-hover:bg-green-200 rounded-full flex items-center justify-center mb-3 transition-colors">
                        <span class="text-green-600 text-xl">📊</span>
                    </div>
                    <span class="text-sm font-medium text-green-700">Laporan Presensi</span>
                </a>

                <a href="{{ route('data-pengajar') }}"
                    class="flex flex-col col-span-2 items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                    <div
                        class="w-12 h-12 bg-purple-100 group-hover:bg-purple-200 rounded-full flex items-center justify-center mb-3 transition-colors">
                        <span class="text-purple-600 text-xl">⚙️</span>
                    </div>
                    <span class="text-sm font-medium text-purple-700">Kelola Data</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Weekly Chart
            const ctx1 = document.getElementById('weeklyChart');
            if (ctx1) {
                const weeklyData = @json($weeklyStats);

                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: weeklyData.map(item => item.date),
                        datasets: [
                            {
                                label: 'Hadir',
                                data: weeklyData.map(item => item.hadir),
                                backgroundColor: '#10B981',
                                borderRadius: 6
                            },
                            {
                                label: 'Tidak Hadir',
                                data: weeklyData.map(item => item.tidak_hadir),
                                backgroundColor: '#EF4444',
                                borderRadius: 6
                            },
                            {
                                label: 'Belum Presensi',
                                data: weeklyData.map(item => item.belum_presensi),
                                backgroundColor: '#9CA3AF',
                                borderRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            // Status Distribution Chart
            const ctx2 = document.getElementById('statusChart');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['Hadir', 'Hadir - DL', 'Hadir - Tidak Lapor', 'Tidak Hadir'],
                        datasets: [{
                            data: [
                            {{ $statusDistribution['hadir'] }},
                            {{ $statusDistribution['hadir_dl'] }},
                            {{ $statusDistribution['hadir_tidak_lapor'] }},
                                {{ $statusDistribution['tidak_hadir'] }}
                            ],
                            backgroundColor: ['#10B981', '#FBBF24', '#3B82F6', '#EF4444'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        cutout: '60%'
                    }
                });
            }
        });
    </script>
@endsection