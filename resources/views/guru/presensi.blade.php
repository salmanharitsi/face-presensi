@extends($layout)

@section('title', 'Guru Presensi')

@section('content')

    <!-- Main Cards Container -->
    <div class="mx-auto">
        <div class="grid lg:grid-cols-2 gap-6">

            @php
                $currentTime = \Carbon\Carbon::now()->format('H:i');
                $isBeforeAttendanceTime = $currentTime < '06:30';
                $isAttendanceTimeAvailable = $currentTime >= '06:30' && $currentTime <= '07:15';
                $isAfterAttendanceTime = $currentTime > '07:15';
                $isPulangTimeAvailable = $currentTime >= '13:00' && $currentTime <= '18:00';

                $tidakHadir = auth()->user()->presensi()
                    ->whereDate('created_at', \Carbon\Carbon::today())
                    ->where('status', 'tidak-hadir')
                    ->first();

                $presensiHariIni = auth()->user()->presensi()
                    ->whereDate('created_at', \Carbon\Carbon::today())
                    ->whereNotNull('jam_masuk')
                    ->first();

                $dinasLuarStatus = auth()->user()->dinasLuar()
                    ->whereDate('tanggal', \Carbon\Carbon::today())
                    ->first()->status ?? 'menunggu';
            @endphp

            @if ($tidakHadir)
                <!-- Card Tidak Hadir -->
                <div class="lg:col-span-2">
                    <div class="group relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-red-400 to-rose-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                        </div>

                        <div class="relative">
                            <div
                                class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-red-500 hover:bg-red-50">

                                <!-- Card Header -->
                                <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-14 h-14 bg-gradient-to-r from-red-500 to-rose-500 rounded-xl flex items-center justify-center shadow-lg">
                                            <i class="fas fa-exclamation-triangle text-white text-xl flex-shrink-0"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-800">Tidak Hadir</h3>
                                            <p class="text-red-600 font-medium">Absensi Terlewat</p>
                                        </div>
                                    </div>
                                    <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Absent
                                    </div>
                                </div>

                                <!-- Illustration -->
                                <div class="mb-8 relative">
                                    <div
                                        class="w-full h-48 bg-gradient-to-br from-red-50 to-rose-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                        <!-- Background Pattern -->
                                        <div class="absolute inset-0 opacity-10">
                                            <div class="absolute top-4 left-4 w-8 h-8 bg-red-400 rounded-full animate-pulse">
                                            </div>
                                            <div
                                                class="absolute top-12 right-8 w-6 h-6 bg-rose-400 rounded-full animate-pulse delay-150">
                                            </div>
                                            <div
                                                class="absolute bottom-8 left-12 w-4 h-4 bg-red-300 rounded-full animate-pulse delay-300">
                                            </div>
                                            <div
                                                class="absolute bottom-4 right-4 w-10 h-10 bg-rose-300 rounded-full animate-pulse delay-500">
                                            </div>
                                        </div>

                                        <!-- Main Icon -->
                                        <div class="relative z-10">
                                            <svg class="w-24 h-24 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11H7v-2h10v2z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="space-y-6 text-center">
                                    <h4 class="text-3xl font-bold text-gray-800">Anda tidak hadir hari ini</h4>
                                    <p class="text-gray-600 text-lg leading-relaxed">
                                        Absensi untuk hari ini belum dilakukan atau telah terlewat. Silakan hubungi atasan atau
                                        HR untuk informasi lebih lanjut.
                                    </p>

                                    <!-- Status Badge -->
                                    <div
                                        class="inline-flex items-center bg-red-100 text-red-800 px-6 py-3 rounded-full text-lg font-semibold">
                                        <i class="fas fa-calendar-times mr-2 text-red-500"></i>
                                        Tidak Hadir Hari Ini
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(\Carbon\Carbon::now()->isSunday())
                <!-- Card Hari Minggu -->
                <div class="lg:col-span-2">
                    <div class="group relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                        </div>

                        <div class="relative">
                            <div
                                class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-blue-500 hover:bg-blue-50">

                                <!-- Card Header -->
                                <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                                            <i class="fas fa-bed text-white text-xl flex-shrink-0"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-800">Hari Libur</h3>
                                            <p class="text-blue-600 font-medium">Minggu - Weekend</p>
                                        </div>
                                    </div>
                                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Weekend
                                    </div>
                                </div>

                                <!-- Illustration -->
                                <div class="mb-8 relative">
                                    <div
                                        class="w-full h-48 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                        <!-- Background Pattern -->
                                        <div class="absolute inset-0 opacity-10">
                                            <div class="absolute top-4 left-4 w-8 h-8 bg-blue-400 rounded-full animate-bounce">
                                            </div>
                                            <div
                                                class="absolute top-12 right-8 w-6 h-6 bg-indigo-400 rounded-full animate-bounce delay-150">
                                            </div>
                                            <div
                                                class="absolute bottom-8 left-12 w-4 h-4 bg-blue-300 rounded-full animate-bounce delay-300">
                                            </div>
                                            <div
                                                class="absolute bottom-4 right-4 w-10 h-10 bg-indigo-300 rounded-full animate-bounce delay-500">
                                            </div>
                                        </div>

                                        <!-- Main Icon -->
                                        <div class="relative z-10">
                                            <svg class="w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2c-5.52 0-10 4.48-10 10s4.48 10 10 10 10-4.48 10-10-4.48-10-10-10zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-1-4h2v2h-2zm0-8h2v6h-2z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="space-y-6 text-center">
                                    <h4 class="text-3xl font-bold text-gray-800">Selamat Hari Minggu! 🌞</h4>
                                    <p class="text-gray-600 text-lg leading-relaxed">
                                        Hari ini adalah hari libur (Minggu). Presensi tidak diperlukan pada hari ini. Selamat
                                        beristirahat dan nikmati waktu libur Anda!
                                    </p>

                                    <!-- Status Badge -->
                                    <div
                                        class="inline-flex items-center bg-blue-100 text-blue-800 px-6 py-3 rounded-full text-lg font-semibold">
                                        <i class="fas fa-calendar-check mr-2 text-blue-500"></i>
                                        Hari Libur - Presensi Tidak Tersedia
                                    </div>

                                    <!-- Additional Info -->
                                    <div class="bg-blue-50 rounded-lg p-4 mt-6">
                                        <div class="flex items-center justify-center space-x-2 text-blue-700">
                                            <i class="fas fa-info-circle"></i>
                                            <span class="text-sm font-medium">Presensi akan kembali tersedia pada hari
                                                Senin</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @if($dinasLuarHariIni)
                    @if($dinasLuarStatus == 'menunggu')
                        <!-- Card Menunggu Persetujuan Dinas Luar -->
                        <div class="lg:col-span-2">
                            <div class="group relative">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-amber-400 to-orange-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                                </div>

                                <div class="relative">
                                    <div
                                        class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-amber-600 hover:bg-amber-50">

                                        <!-- Card Header -->
                                        <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="w-14 h-14 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-clock text-white text-xl flex-shrink-0"></i>
                                                </div>
                                                <div>
                                                    <h3 class="text-2xl font-bold text-gray-800">Menunggu Persetujuan</h3>
                                                    <p class="text-amber-600 font-medium">Pengajuan Dinas Luar</p>
                                                </div>
                                            </div>
                                            <div class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm font-semibold">
                                                <i class="fas fa-hourglass-half mr-1"></i>
                                                Pending
                                            </div>
                                        </div>

                                        <!-- Illustration -->
                                        <div class="mb-8 relative">
                                            <div
                                                class="w-full h-48 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                                <!-- Background Pattern -->
                                                <div class="absolute inset-0 opacity-10">
                                                    <div class="absolute top-4 left-4 w-8 h-8 bg-amber-400 rounded-full animate-pulse">
                                                    </div>
                                                    <div
                                                        class="absolute top-12 right-8 w-6 h-6 bg-orange-400 rounded-full animate-pulse delay-150">
                                                    </div>
                                                    <div
                                                        class="absolute bottom-8 left-12 w-4 h-4 bg-amber-300 rounded-full animate-pulse delay-300">
                                                    </div>
                                                    <div
                                                        class="absolute bottom-4 right-4 w-10 h-10 bg-orange-300 rounded-full animate-pulse delay-500">
                                                    </div>
                                                </div>

                                                <!-- Main Icon -->
                                                <div class="relative z-10">
                                                    <svg class="w-24 h-24 text-amber-500 animate-spin-slow" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Content -->
                                        <div class="space-y-4">
                                            <p class="text-gray-600 text-lg leading-relaxed text-center">
                                                Pengajuan dinas luar Anda sedang menunggu persetujuan dari Kepala Sekolah.
                                                Mohon tunggu informasi selanjutnya.
                                            </p>
                                        </div>

                                        <!-- Status Info -->
                                        <div class="mt-8 bg-amber-50 border border-amber-200 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-info-circle text-amber-500 mr-3"></i>
                                                <div>
                                                    <p class="text-amber-800 font-medium">Status: Menunggu Persetujuan</p>
                                                    <p class="text-amber-600 text-sm">Silahkan cek sistem secara berkala</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($dinasLuarStatus == 'ditolak')
                        <!-- Card Dinas Luar Ditolak -->
                        <div class="lg:col-span-2">
                            <div class="group relative">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-red-400 to-rose-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                                </div>

                                <div class="relative">
                                    <div
                                        class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-red-600 hover:bg-red-50">

                                        <!-- Pesan Penolakan -->
                                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                                                <div>
                                                    <p class="text-red-800 font-bold text-lg">Dinas Luar Kamu sebelumnya ditolak</p>
                                                    <p class="text-red-600 text-sm">Silakan lapor presensi seperti biasa atau ajukan
                                                        dinas luar yang baru</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Header -->
                                        <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="w-14 h-14 bg-gradient-to-r from-red-500 to-rose-500 rounded-xl flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-times-circle text-white text-xl flex-shrink-0"></i>
                                                </div>
                                                <div>
                                                    <h3 class="text-2xl font-bold text-gray-800">Pengajuan Ditolak</h3>
                                                    <p class="text-red-600 font-medium">Pilih Tindakan Selanjutnya</p>
                                                </div>
                                            </div>
                                            <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Ditolak
                                            </div>
                                        </div>

                                        <!-- Illustration -->
                                        <div class="mb-8 relative">
                                            <div
                                                class="w-full h-48 bg-gradient-to-br from-red-50 to-rose-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                                <!-- Background Pattern -->
                                                <div class="absolute inset-0 opacity-10">
                                                    <div class="absolute top-4 left-4 w-8 h-8 bg-red-400 rounded-full animate-pulse">
                                                    </div>
                                                    <div
                                                        class="absolute top-12 right-8 w-6 h-6 bg-rose-400 rounded-full animate-pulse delay-150">
                                                    </div>
                                                    <div
                                                        class="absolute bottom-8 left-12 w-4 h-4 bg-red-300 rounded-full animate-pulse delay-300">
                                                    </div>
                                                    <div
                                                        class="absolute bottom-4 right-4 w-10 h-10 bg-rose-300 rounded-full animate-pulse delay-500">
                                                    </div>
                                                </div>

                                                <!-- Main Icon -->
                                                <div class="relative z-10">
                                                    <svg class="w-24 h-24 text-red-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Content -->
                                        <div class="space-y-4 mb-8">
                                            <p class="text-gray-600 text-lg leading-relaxed text-center">
                                                Pengajuan dinas luar Anda sebelumnya tidak dapat disetujui. Anda dapat melakukan
                                                presensi normal atau mengajukan dinas luar yang baru.
                                            </p>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Button Lapor Presensi -->
                                            <a href="{{ route('face-detection-page') }}" class="group/btn relative block">
                                                <div
                                                    class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl blur opacity-20 group-hover/btn:opacity-30 transition-opacity duration-300">
                                                </div>
                                                <div
                                                    class="relative bg-white border-2 border-blue-200 rounded-xl p-4 hover:border-blue-400 hover:bg-blue-50 transition-all duration-300 group-hover/btn:-translate-y-1">
                                                    <div class="flex flex-col items-center text-center space-y-3">
                                                        <div
                                                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-user-check text-white text-lg"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="font-bold text-gray-800 text-lg">Lapor Presensi</h4>
                                                            <p class="text-gray-600 text-sm">Lakukan presensi normal di sekolah</p>
                                                        </div>
                                                        <div
                                                            class="w-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white py-2 px-4 rounded-lg text-sm font-semibold group-hover/btn:shadow-lg transition-shadow duration-300">
                                                            Mulai Presensi
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>

                                            <!-- Button Ajukan Dinas Luar Lagi -->
                                            <a href="javascript:void(0)" onclick="openDinasLuarModal()" class="group/btn relative block">
                                                <div
                                                    class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-xl blur opacity-20 group-hover/btn:opacity-30 transition-opacity duration-300">
                                                </div>
                                                <div
                                                    class="relative bg-white border-2 border-emerald-200 rounded-xl p-4 hover:border-emerald-400 hover:bg-emerald-50 transition-all duration-300 group-hover/btn:-translate-y-1">
                                                    <div class="flex flex-col items-center text-center space-y-3">
                                                        <div
                                                            class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-plus-circle text-white text-lg"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="font-bold text-gray-800 text-lg">Ajukan Lagi</h4>
                                                            <p class="text-gray-600 text-sm">Buat pengajuan dinas luar baru</p>
                                                        </div>
                                                        <div
                                                            class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white py-2 px-4 rounded-lg text-sm font-semibold group-hover/btn:shadow-lg transition-shadow duration-300">
                                                            Ajukan Dinas Luar
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <!-- Info tambahan -->
                                        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-info-circle text-gray-500 mr-3"></i>
                                                <div>
                                                    <p class="text-gray-700 font-medium text-sm">Tips:</p>
                                                    <p class="text-gray-600 text-sm">Pastikan alasan dan dokumen pendukung lengkap saat
                                                        mengajukan dinas luar yang baru</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($dinasLuarStatus == 'disetujui' && $belumLaporKehadiran)
                        <!-- Card Laporkan Kehadiran (Dinas Luar Disetujui) -->
                        <div class="lg:col-span-2">
                            <div class="group relative">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-cyan-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                                </div>

                                <a href="{{ route('face-detection-page') }}" class="relative block">
                                    <div
                                        class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-emerald-600 hover:bg-emerald-50 group-hover:-translate-y-2">

                                        <!-- Card Header -->
                                        <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="w-14 h-14 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-user-circle text-white text-xl flex-shrink-0"></i>
                                                </div>
                                                <div>
                                                    <h3 class="text-2xl font-bold text-gray-800">Laporkan Kehadiran</h3>
                                                    <p class="text-emerald-600 font-medium">Dinas Luar Disetujui</p>
                                                </div>
                                            </div>
                                            <div class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-semibold">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Disetujui
                                            </div>
                                        </div>

                                        <!-- Illustration -->
                                        <div class="mb-8 relative">
                                            <div
                                                class="w-full h-48 bg-gradient-to-br from-emerald-50 to-cyan-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                                <!-- Background Pattern -->
                                                <div class="absolute inset-0 opacity-10">
                                                    <div class="absolute top-4 left-4 w-8 h-8 bg-emerald-400 rounded-full"></div>
                                                    <div class="absolute top-12 right-8 w-6 h-6 bg-cyan-400 rounded-full"></div>
                                                    <div class="absolute bottom-8 left-12 w-4 h-4 bg-emerald-300 rounded-full"></div>
                                                    <div class="absolute bottom-4 right-4 w-10 h-10 bg-cyan-300 rounded-full"></div>
                                                </div>

                                                <!-- Main Icon -->
                                                <div class="relative z-10">
                                                    <svg class="w-24 h-24 text-emerald-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Content -->
                                        <div class="space-y-4">
                                            <p class="text-gray-600 text-lg leading-relaxed">
                                                Pengajuan dinas luar Anda telah disetujui. Silakan laporkan kehadiran Anda untuk memulai
                                                aktivitas dinas luar.
                                            </p>
                                        </div>

                                        <!-- Action Button -->
                                        <div class="mt-8">
                                            <div
                                                class="w-full bg-gradient-to-r from-emerald-500 to-cyan-500 text-white py-3 px-6 rounded-xl font-semibold text-lg shadow-lg group-hover:shadow-xl transition-all duration-300 text-center">
                                                Mulai Presensi
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @elseif($dinasLuarStatus == 'disetujui' && $belumLaporPulang && $isPulangTimeAvailable)
                        <!-- Card Lapor Pulang (Dinas Luar) -->
                        <div class="lg:col-span-2">
                            <div class="group relative">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                                </div>

                                <a href="{{ route('face-detection-page') }}" class="relative block">
                                    <div
                                        class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-blue-600 hover:bg-blue-50 group-hover:-translate-y-2">

                                        <!-- Card Header -->
                                        <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-sign-out-alt text-white text-xl flex-shrink-0"></i>
                                                </div>
                                                <div>
                                                    <h3 class="text-2xl font-bold text-gray-800">Laporkan Kepulangan</h3>
                                                    <p class="text-blue-600 font-medium">Dinas Luar Selesai</p>
                                                </div>
                                            </div>
                                            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Hadir
                                            </div>
                                        </div>

                                        <!-- Illustration -->
                                        <div class="mb-8 relative">
                                            <div
                                                class="w-full h-48 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                                <!-- Background Pattern -->
                                                <div class="absolute inset-0 opacity-10">
                                                    <div class="absolute top-4 left-4 w-8 h-8 bg-blue-400 rounded-full"></div>
                                                    <div class="absolute top-12 right-8 w-6 h-6 bg-indigo-400 rounded-full"></div>
                                                    <div class="absolute bottom-8 left-12 w-4 h-4 bg-blue-300 rounded-full"></div>
                                                    <div class="absolute bottom-4 right-4 w-10 h-10 bg-indigo-300 rounded-full"></div>
                                                </div>

                                                <!-- Main Icon -->
                                                <div class="relative z-10">
                                                    <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Content -->
                                        <div class="space-y-4">
                                            <p class="text-gray-600 text-lg leading-relaxed">
                                                Anda sudah berhasil melaporkan kehadiran untuk dinas luar hari ini. Sekarang laporkan
                                                kepulangan Anda setelah menyelesaikan aktivitas dinas luar.
                                            </p>
                                        </div>

                                        <!-- Action Button -->
                                        <div class="mt-8">
                                            <div
                                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white py-3 px-6 rounded-xl font-semibold text-lg shadow-lg group-hover:shadow-xl transition-all duration-300 text-center">
                                                <i class="fas fa-sign-out-alt mr-2"></i>
                                                Lapor Pulang
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                    @elseif($dinasLuarStatus == 'disetujui' && $belumLaporPulang && !$isPulangTimeAvailable)
                        <!-- Card Lapor Pulang Belum Tersedia -->
                        <div class="lg:col-span-2">
                            <div class="group relative">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                                </div>

                                <div class="relative">
                                    <div
                                        class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-blue-600 hover:bg-blue-50">

                                        <!-- Card Header -->
                                        <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-clock text-white text-xl flex-shrink-0"></i>
                                                </div>
                                                <div>
                                                    <h3 class="text-2xl font-bold text-gray-800">Fitur Belum Tersedia</h3>
                                                    <p class="text-blue-600 font-medium">Lapor Pulang</p>
                                                </div>
                                            </div>
                                            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Belum Tersedia
                                            </div>
                                        </div>

                                        <!-- Illustration -->
                                        <div class="mb-8 relative">
                                            <div
                                                class="w-full h-48 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                                <!-- Background Pattern -->
                                                <div class="absolute inset-0 opacity-10">
                                                    <div class="absolute top-4 left-4 w-8 h-8 bg-blue-400 rounded-full"></div>
                                                    <div class="absolute top-12 right-8 w-6 h-6 bg-indigo-400 rounded-full"></div>
                                                    <div class="absolute bottom-8 left-12 w-4 h-4 bg-blue-300 rounded-full"></div>
                                                    <div class="absolute bottom-4 right-4 w-10 h-10 bg-indigo-300 rounded-full"></div>
                                                </div>

                                                <!-- Main Icon -->
                                                <div class="relative z-10">
                                                    <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Content -->
                                        <div class="space-y-4">
                                            <p class="text-gray-600 text-lg leading-relaxed text-center">
                                                Fitur lapor pulang hanya tersedia pada jam 13.00 - 18.00. Silakan tunggu hingga waktu
                                                yang telah ditentukan.
                                            </p>
                                        </div>

                                        <!-- Status Info -->
                                        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-clock text-blue-500 mr-3"></i>
                                                <div>
                                                    <p class="text-blue-800 font-medium">Waktu Sekarang: {{ $currentTime }}</p>
                                                    <p class="text-blue-600 text-sm">Fitur lapor pulang tersedia jam 13.00 - 18.00</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @elseif($dinasLuarStatus == 'disetujui' && $sudahLaporPulang)
                        <!-- Card Terimakasih (Dinas Luar) -->
                        <div class="lg:col-span-2">
                            <div class="group relative">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-green-400 to-emerald-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                                </div>

                                <div class="relative">
                                    <div
                                        class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-green-600 hover:bg-green-50">

                                        <!-- Card Header -->
                                        <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="w-14 h-14 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-heart text-white text-xl flex-shrink-0"></i>
                                                </div>
                                                <div>
                                                    <h3 class="text-2xl font-bold text-gray-800">Terima Kasih!</h3>
                                                    <p class="text-green-600 font-medium">Dinas Luar Selesai</p>
                                                </div>
                                            </div>
                                            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                                <i class="fas fa-check-double mr-1"></i>
                                                Selesai
                                            </div>
                                        </div>

                                        <!-- Illustration -->
                                        <div class="mb-8 relative">
                                            <div
                                                class="w-full h-48 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                                <!-- Background Pattern -->
                                                <div class="absolute inset-0 opacity-10">
                                                    <div class="absolute top-4 left-4 w-8 h-8 bg-green-400 rounded-full animate-bounce">
                                                    </div>
                                                    <div
                                                        class="absolute top-12 right-8 w-6 h-6 bg-emerald-400 rounded-full animate-bounce delay-150">
                                                    </div>
                                                    <div
                                                        class="absolute bottom-8 left-12 w-4 h-4 bg-green-300 rounded-full animate-bounce delay-300">
                                                    </div>
                                                    <div
                                                        class="absolute bottom-4 right-4 w-10 h-10 bg-emerald-300 rounded-full animate-bounce delay-500">
                                                    </div>
                                                </div>

                                                <!-- Main Icon -->
                                                <div class="relative z-10">
                                                    <svg class="w-24 h-24 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Content -->
                                        <div class="space-y-6 text-center">
                                            <h4 class="text-3xl font-bold text-gray-800">Terima kasih sudah bekerja keras hari ini!</h4>
                                            <p class="text-gray-600 text-lg leading-relaxed">
                                                Anda telah menyelesaikan dinas luar dan absensi untuk hari ini. Selamat beristirahat dan
                                                sampai jumpa besok.
                                            </p>

                                            <!-- Achievement Badge -->
                                            <div
                                                class="inline-flex items-center bg-green-100 text-green-800 px-6 py-3 rounded-full text-lg font-semibold">
                                                <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                                                Dinas Luar Hari Ini Selesai
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                @elseif($isBeforeAttendanceTime)
                    <!-- Card Before 6:30 -->
                    <div class="lg:col-span-2">
                        <div class="group relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-gray-400 to-blue-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                            </div>

                            <div class="relative">
                                <div
                                    class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-blue-600 hover:bg-blue-50">

                                    <!-- Card Header -->
                                    <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-14 h-14 bg-gradient-to-r from-gray-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                                                <i class="fas fa-clock text-white text-xl flex-shrink-0"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold text-gray-800">Fitur Belum Tersedia</h3>
                                                <p class="text-blue-600 font-medium">Presensi Kehadiran</p>
                                            </div>
                                        </div>
                                        <div class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Informasi
                                        </div>
                                    </div>

                                    <!-- Illustration -->
                                    <div class="mb-8 relative">
                                        <div
                                            class="w-full h-48 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                            <!-- Background Pattern -->
                                            <div class="absolute inset-0 opacity-10">
                                                <div class="absolute top-4 left-4 w-8 h-8 bg-gray-400 rounded-full"></div>
                                                <div class="absolute top-12 right-8 w-6 h-6 bg-blue-400 rounded-full"></div>
                                                <div class="absolute bottom-8 left-12 w-4 h-4 bg-gray-300 rounded-full"></div>
                                                <div class="absolute bottom-4 right-4 w-10 h-10 bg-blue-300 rounded-full"></div>
                                            </div>

                                            <!-- Main Icon -->
                                            <div class="relative z-10">
                                                <svg class="w-24 h-24 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="space-y-4">
                                        <p class="text-gray-600 text-lg leading-relaxed text-center">
                                            Fitur lapor kehadiran tersedia mulai jam 06.30 - 07.15. Silakan kembali pada waktu yang
                                            telah ditentukan.
                                        </p>
                                    </div>

                                    <!-- Status Info -->
                                    <div class="mt-8 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-gray-500 mr-3"></i>
                                            <div>
                                                <p class="text-gray-800 font-medium">Waktu Sekarang: {{ $currentTime }}</p>
                                                <p class="text-gray-600 text-sm">Presensi kehadiran belum dibuka</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($isAfterAttendanceTime && !$presensiHariIni && !$dinasLuarHariIni)
                    <!-- Card After 7:15 with no attendance or dinas luar -->
                    <div class="lg:col-span-2">
                        <div class="group relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-red-400 to-orange-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                            </div>

                            <div class="relative">
                                <div
                                    class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-red-600 hover:bg-red-50">

                                    <!-- Card Header -->
                                    <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-14 h-14 bg-gradient-to-r from-red-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                                <i class="fas fa-times-circle text-white text-xl flex-shrink-0"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold text-gray-800">Fitur Tidak Tersedia</h3>
                                                <p class="text-red-600 font-medium">Presensi Kehadiran</p>
                                            </div>
                                        </div>
                                        <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Tidak Tersedia
                                        </div>
                                    </div>

                                    <!-- Illustration -->
                                    <div class="mb-8 relative">
                                        <div
                                            class="w-full h-48 bg-gradient-to-br from-red-50 to-orange-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                            <!-- Background Pattern -->
                                            <div class="absolute inset-0 opacity-10">
                                                <div class="absolute top-4 left-4 w-8 h-8 bg-red-400 rounded-full"></div>
                                                <div class="absolute top-12 right-8 w-6 h-6 bg-orange-400 rounded-full"></div>
                                                <div class="absolute bottom-8 left-12 w-4 h-4 bg-red-300 rounded-full"></div>
                                                <div class="absolute bottom-4 right-4 w-10 h-10 bg-orange-300 rounded-full"></div>
                                            </div>

                                            <!-- Main Icon -->
                                            <div class="relative z-10">
                                                <svg class="w-24 h-24 text-red-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="space-y-4">
                                        <p class="text-gray-600 text-lg leading-relaxed text-center">
                                            Waktu lapor kehadiran telah berakhir (06.30 - 07.15). Anda belum melaporkan kehadiran
                                            atau mengajukan dinas luar hari ini.
                                        </p>
                                    </div>

                                    <!-- Status Info -->
                                    <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-red-500 mr-3"></i>
                                            <div>
                                                <p class="text-red-800 font-medium">Waktu Sekarang: {{ $currentTime }}</p>
                                                <p class="text-red-600 text-sm">Batas waktu lapor kehadiran telah lewat</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($belumLaporKehadiran && $isAttendanceTimeAvailable)
                    <!-- Laporkan Kehadiran Card -->
                    <div class="group relative {{ Auth::user()->role == 'kepsek' ? 'col-span-full' : '' }}">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-cyan-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                        </div>

                        <a href="{{ route('face-detection-page') }}" class="relative block">
                            <div
                                class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-emerald-600 hover:bg-emerald-50 group-hover:-translate-y-2">

                                <!-- Card Header -->
                                <div class="flex items-center justify-between mb-8">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-14 h-14 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                                            <i class="fas fa-user-circle text-white text-xl flex-shrink-0"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-800">Laporkan Kehadiran</h3>
                                            <p class="text-emerald-600 font-medium">Absensi Harian</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Illustration -->
                                <div class="mb-8 relative">
                                    <div
                                        class="w-full h-48 bg-gradient-to-br from-emerald-50 to-cyan-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                        <!-- Background Pattern -->
                                        <div class="absolute inset-0 opacity-10">
                                            <div class="absolute top-4 left-4 w-8 h-8 bg-emerald-400 rounded-full"></div>
                                            <div class="absolute top-12 right-8 w-6 h-6 bg-cyan-400 rounded-full"></div>
                                            <div class="absolute bottom-8 left-12 w-4 h-4 bg-emerald-300 rounded-full"></div>
                                            <div class="absolute bottom-4 right-4 w-10 h-10 bg-cyan-300 rounded-full"></div>
                                        </div>

                                        <!-- Main Icon -->
                                        <div class="relative z-10">
                                            <svg class="w-24 h-24 text-emerald-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="space-y-4">
                                    <p class="text-gray-600 text-lg leading-relaxed">
                                        Laporkan kehadiran Anda di sekolah untuk hari ini. Sistem akan mengecek wajah Anda.
                                    </p>
                                </div>

                                <!-- Action Button -->
                                <div class="mt-8">
                                    <div
                                        class="w-full bg-gradient-to-r from-emerald-500 to-cyan-500 text-white py-3 px-6 rounded-xl font-semibold text-lg shadow-lg group-hover:shadow-xl transition-all duration-300 text-center">
                                        Mulai Presensi
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    @if (Auth::user()->role == 'guru')
                        <!-- Laporkan Dinas Luar Card -->
                        <div class="group relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-purple-400 to-pink-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                            </div>

                            <a href="javascript:void(0)" onclick="openDinasLuarModal()" class="relative block">
                                <div
                                    class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:border-purple-600 hover:bg-purple-100 hover:shadow-3xl group-hover:-translate-y-2">

                                    <!-- Card Header -->
                                    <div class="flex items-center justify-between mb-8">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-14 h-14 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                                                <i class="fas fa-location text-white text-xl flex-shrink-0"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold text-gray-800">Laporkan Dinas Luar</h3>
                                                <p class="text-purple-600 font-medium">Tugas Eksternal</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Illustration -->
                                    <div class="mb-8 relative">
                                        <div
                                            class="w-full h-48 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                            <!-- Background Pattern -->
                                            <div class="absolute inset-0 opacity-10">
                                                <div class="absolute top-4 left-4 w-8 h-8 bg-purple-400 rounded-full"></div>
                                                <div class="absolute top-12 right-8 w-6 h-6 bg-pink-400 rounded-full"></div>
                                                <div class="absolute bottom-8 left-12 w-4 h-4 bg-purple-300 rounded-full"></div>
                                                <div class="absolute bottom-4 right-4 w-10 h-10 bg-pink-300 rounded-full"></div>
                                            </div>

                                            <!-- Main Icon -->
                                            <div class="relative z-10">
                                                <svg class="w-24 h-24 text-purple-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="space-y-4">
                                        <p class="text-gray-600 text-lg leading-relaxed">
                                            Laporkan pengajuan aktivitas dinas luar yang dilakukan di luar lingkungan sekolah.
                                        </p>
                                    </div>

                                    <!-- Action Button -->
                                    <div class="mt-8">
                                        <div
                                            class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-3 px-6 rounded-xl font-semibold text-lg shadow-lg group-hover:shadow-xl transition-all duration-300 text-center">
                                            Buat Laporan
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>    
                    @endif

                @elseif($belumLaporPulang && $isPulangTimeAvailable)
                    <!-- Card Lapor Pulang -->
                    <div class="lg:col-span-2">
                        <div class="group relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                            </div>

                            <a href="{{ route('face-detection-page') }}" class="relative block">
                                <div
                                    class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-blue-600 hover:bg-blue-50 group-hover:-translate-y-2">

                                    <!-- Card Header -->
                                    <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                                                <i class="fas fa-sign-out-alt text-white text-xl flex-shrink-0"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold text-gray-800">Laporkan Kepulangan</h3>
                                                <p class="text-blue-600 font-medium">Absensi Keluar</p>
                                            </div>
                                        </div>
                                        <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Hadir
                                        </div>
                                    </div>

                                    <!-- Illustration -->
                                    <div class="mb-8 relative">
                                        <div
                                            class="w-full h-48 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                            <!-- Background Pattern -->
                                            <div class="absolute inset-0 opacity-10">
                                                <div class="absolute top-4 left-4 w-8 h-8 bg-blue-400 rounded-full"></div>
                                                <div class="absolute top-12 right-8 w-6 h-6 bg-indigo-400 rounded-full"></div>
                                                <div class="absolute bottom-8 left-12 w-4 h-4 bg-blue-300 rounded-full"></div>
                                                <div class="absolute bottom-4 right-4 w-10 h-10 bg-indigo-300 rounded-full"></div>
                                            </div>

                                            <!-- Main Icon -->
                                            <div class="relative z-10">
                                                <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="space-y-4">
                                        <p class="text-gray-600 text-lg leading-relaxed">
                                            Anda sudah berhasil melaporkan kehadiran hari ini. Sekarang laporkan kepulangan Anda
                                            sebelum meninggalkan sekolah.
                                        </p>
                                    </div>

                                    <!-- Action Button -->
                                    <div class="mt-8">
                                        <div
                                            class="w-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white py-3 px-6 rounded-xl font-semibold text-lg shadow-lg group-hover:shadow-xl transition-all duration-300 text-center">
                                            <i class="fas fa-sign-out-alt mr-2"></i>
                                            Lapor Pulang
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                @elseif($belumLaporPulang && !$isPulangTimeAvailable)
                    <!-- Card Lapor Pulang Belum Tersedia -->
                    <div class="lg:col-span-2">
                        <div class="group relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                            </div>

                            <div class="relative">
                                <div
                                    class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-blue-600 hover:bg-blue-50">

                                    <!-- Card Header -->
                                    <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                                                <i class="fas fa-clock text-white text-xl flex-shrink-0"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold text-gray-800">Fitur Belum Tersedia</h3>
                                                <p class="text-blue-600 font-medium">Lapor Pulang</p>
                                            </div>
                                        </div>
                                        <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Belum Tersedia
                                        </div>
                                    </div>

                                    <!-- Illustration -->
                                    <div class="mb-8 relative">
                                        <div
                                            class="w-full h-48 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                            <!-- Background Pattern -->
                                            <div class="absolute inset-0 opacity-10">
                                                <div class="absolute top-4 left-4 w-8 h-8 bg-blue-400 rounded-full"></div>
                                                <div class="absolute top-12 right-8 w-6 h-6 bg-indigo-400 rounded-full"></div>
                                                <div class="absolute bottom-8 left-12 w-4 h-4 bg-blue-300 rounded-full"></div>
                                                <div class="absolute bottom-4 right-4 w-10 h-10 bg-indigo-300 rounded-full"></div>
                                            </div>

                                            <!-- Main Icon -->
                                            <div class="relative z-10">
                                                <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="space-y-4">
                                        <p class="text-gray-600 text-lg leading-relaxed text-center">
                                            Fitur lapor pulang hanya tersedia pada jam 13.00 - 18.00. Silakan tunggu hingga waktu
                                            yang telah ditentukan.
                                        </p>
                                    </div>

                                    <!-- Status Info -->
                                    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-blue-500 mr-3"></i>
                                            <div>
                                                <p class="text-blue-800 font-medium">Waktu Sekarang: {{ $currentTime }}</p>
                                                <p class="text-blue-600 text-sm">Fitur lapor pulang tersedia jam 13.00 - 18.00</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($sudahLaporPulang)
                    <!-- Card Terimakasih -->
                    <div class="lg:col-span-2">
                        <div class="group relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-green-400 to-emerald-500 rounded-xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                            </div>

                            <div class="relative">
                                <div
                                    class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100 transform transition-all duration-500 hover:shadow-3xl hover:border-green-600 hover:bg-green-50">

                                    <!-- Card Header -->
                                    <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-8">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-14 h-14 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                                <i class="fas fa-heart text-white text-xl flex-shrink-0"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold text-gray-800">Terima Kasih!</h3>
                                                <p class="text-green-600 font-medium">Absensi Selesai</p>
                                            </div>
                                        </div>
                                        <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            <i class="fas fa-check-double mr-1"></i>
                                            Selesai
                                        </div>
                                    </div>

                                    <!-- Illustration -->
                                    <div class="mb-8 relative">
                                        <div
                                            class="w-full h-48 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                            <!-- Background Pattern -->
                                            <div class="absolute inset-0 opacity-10">
                                                <div class="absolute top-4 left-4 w-8 h-8 bg-green-400 rounded-full animate-bounce">
                                                </div>
                                                <div
                                                    class="absolute top-12 right-8 w-6 h-6 bg-emerald-400 rounded-full animate-bounce delay-150">
                                                </div>
                                                <div
                                                    class="absolute bottom-8 left-12 w-4 h-4 bg-green-300 rounded-full animate-bounce delay-300">
                                                </div>
                                                <div
                                                    class="absolute bottom-4 right-4 w-10 h-10 bg-emerald-300 rounded-full animate-bounce delay-500">
                                                </div>
                                            </div>

                                            <!-- Main Icon -->
                                            <div class="relative z-10">
                                                <svg class="w-24 h-24 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="space-y-6 text-center">
                                        <h4 class="text-3xl font-bold text-gray-800">Terima kasih sudah bekerja keras hari ini!</h4>
                                        <p class="text-gray-600 text-lg leading-relaxed">
                                            Anda telah menyelesaikan absensi untuk hari ini. Selamat beristirahat dan sampai jumpa
                                            besok.
                                        </p>

                                        <!-- Achievement Badge -->
                                        <div
                                            class="inline-flex items-center bg-green-100 text-green-800 px-6 py-3 rounded-full text-lg font-semibold">
                                            <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                                            Absensi Hari Ini Selesai
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif


        </div>
    </div>

    <!-- Modal Dinas Luar -->
    @if($belumLaporKehadiran)
        <div id="dinasLuarModal"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl max-w-lg w-full mx-4 shadow-3xl transform transition-all duration-300 scale-95 opacity-0"
                id="modalContent">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-t-xl p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-location text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Form Dinas Luar</h3>
                                <p class="text-purple-100 text-sm">Tugas Eksternal</p>
                            </div>
                        </div>
                        <button onclick="closeDinasLuarModal()"
                            class="text-white hover:text-purple-200 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form id="dinasLuarForm" action="{{ url('/api/process-pengajuan-dinas-luar') }}" method="POST" enctype="multipart/form-data" class="p-6 !mb-0">
                    @csrf

                    <!-- Nama Field -->
                    <div class="mb-6">
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-purple-500 mr-2"></i>Nama
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ auth()->user()->name }}" readonly
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 focus:outline-none cursor-not-allowed">
                    </div>

                    <!-- Tanggal Field -->
                    <div class="mb-6">
                        <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar text-purple-500 mr-2"></i>Tanggal
                        </label>
                        <input type="date" id="tanggal" name="tanggal" required disabled
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    </div>

                    <!-- Surat Izin Upload Field -->
                    <div class="mb-3">
                        <label for="surat_izin" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-file-upload text-purple-500 mr-2"></i>Surat Izin <span class="text-xs">(max 200kb)</span>
                        </label>
                        <input type="file" id="surat_izin" name="surat_izin" accept=".jpg,.jpeg,.png" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('surat_izin')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan Field -->
                    <div class="mb-3">
                        <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-edit text-purple-500 mr-2"></i>Keterangan
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="4"
                            placeholder="Jelaskan detail aktivitas dinas luar yang akan dilakukan..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-none"></textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex space-x-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeDinasLuarModal()"
                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-semibold">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-3 rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                            <i class="fas fa-paper-plane mr-2"></i>Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Bottom Info Section -->
    <div class="mx-auto mt-6">
        <div class="bg-white rounded-xl p-8 shadow-lg border border-gray-100">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Informasi Penting</h3>
                <p class="text-gray-600 leading-relaxed">
                    Pastikan untuk melaporkan presensi sebelum batas waktu yang ditentukan.
                    Untuk pertanyaan lebih lanjut, hubungi administrator sistem.
                </p>
            </div>
        </div>
    </div>

    <style>
        .shadow-3xl {
            box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 3s linear infinite;
        }

        .group:hover .relative>div:first-child svg {
            animation: float 2s ease-in-out infinite;
        }

        /* Custom hover effects */
        .group:hover .bg-gradient-to-br {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
        }

        .group:last-child:hover .bg-gradient-to-br {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);
        }

        /* Animasi untuk modal */
        #modalContent {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Custom scrollbar untuk textarea */
        textarea::-webkit-scrollbar {
            width: 6px;
        }

        textarea::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        textarea::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        textarea::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endsection

<!-- JavaScript for Modal -->
<script>
    function openDinasLuarModal() {
        const modal = document.getElementById('dinasLuarModal');
        const modalContent = document.getElementById('modalContent');

        modal.classList.remove('hidden');

        // Set tanggal hari ini sebagai default
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal').value = today;

        // Animasi masuk
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDinasLuarModal() {
        const modal = document.getElementById('dinasLuarModal');
        const modalContent = document.getElementById('modalContent');

        // Animasi keluar
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            // Reset form
            document.getElementById('dinasLuarForm').reset();
        }, 300);
    }

    // Tutup modal ketika klik di luar modal
    @if($belumLaporKehadiran)
        document.getElementById('dinasLuarModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeDinasLuarModal();
            }
        });

        // Handle form submission
        document.getElementById('dinasLuarForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Validasi form sebelum submit
            const keterangan = document.getElementById('keterangan').value.trim();
            if (keterangan.length < 10) {
                return;
            }

            // Disable submit button untuk prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

            // Ambil CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Prepare form data
            const formData = new FormData();
            formData.append('keterangan', keterangan);
            formData.append('_token', csrfToken);

            // Kirim form menggunakan fetch API
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(async response => {
                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Tampilkan notifikasi sukses
                        closeDinasLuarModal();

                        // Refresh halaman setelah 2 detik (opsional)
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        // Tampilkan error dari server
                        if (data.errors) {
                            // Handle validation errors
                            const errorMessages = Object.values(data.errors).flat();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });
    @endif
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->has('keterangan'))
            openDinasLuarModal();
        @endif
    });
</script>