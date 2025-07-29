@extends('layouts.app')

@section('title', 'Guru Riwayat Presensi')

@section('content')
    <div class="min-h-screen">
        <!-- Simple Header -->
        <div class="bg-white shadow-sm border rounded-xl">
            <div class="container mx-auto px-6 py-6">
                <h1 class="text-2xl font-semibold text-gray-900">Riwayat Presensi</h1>
                <p class="text-gray-600 mt-1">Pantau kehadiran guru</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto mt-6">
            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-sm border mb-6 p-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                        <input type="date" id="searchTanggal"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                        <select id="filterStatus"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">Semua Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="tidak-hadir">Tidak Hadir</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div id="presensiTable">
                <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                    <!-- Table Header -->
                    <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                        <div class="grid grid-cols-4 gap-4">
                            <div class="text-sm font-medium text-gray-700 uppercase tracking-wider">
                                Tanggal
                            </div>
                            <div class="text-sm font-medium text-gray-700 uppercase tracking-wider">
                                Jam Masuk
                            </div>
                            <div class="text-sm font-medium text-gray-700 uppercase tracking-wider">
                                Jam Keluar
                            </div>
                            <div class="text-sm font-medium text-gray-700 uppercase tracking-wider">
                                Status
                            </div>
                        </div>
                    </div>

                    <!-- Table Body -->
                    <div id="presensiBody" class="divide-y divide-gray-200">
                        @forelse ($presensi as $p)
                            <div class="grid grid-cols-4 gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500 md:hidden font-medium mb-1">Tanggal</span>
                                    <span class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                                    </span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500 md:hidden font-medium mb-1">Jam Masuk</span>
                                    <span class="text-sm text-gray-700">
                                        {{ $p->jam_masuk ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500 md:hidden font-medium mb-1">Jam Keluar</span>
                                    <span class="text-sm text-gray-700">
                                        {{ $p->jam_keluar ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500 md:hidden font-medium mb-1">Status</span>
                                    <div class="flex items-start">
                                        @if ($p->status === 'hadir')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                Hadir
                                            </span>
                                        @elseif ($p->status === 'tidak-hadir')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                Tidak Hadir
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                Belum Ada Data
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-4xl text-gray-300 mb-4">📋</div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data presensi</h3>
                                <p class="text-gray-500">Data presensi akan muncul di sini setelah ada aktivitas</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6" id="paginationWrapper">
                    @include('components.modern-pagination', ['paginator' => $presensi])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function fetchPresensi(page = 1) {
            const status = document.getElementById('filterStatus').value;
            const tanggal = document.getElementById('searchTanggal').value;

            // Show loading state
            const presensiTable = document.getElementById('presensiTable');
            presensiTable.style.opacity = '0.6';
            presensiTable.style.pointerEvents = 'none';

            fetch(`?status=${status}&tanggal=${tanggal}&page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('presensiTable').innerHTML = data.html;

                    // Restore state
                    presensiTable.style.opacity = '1';
                    presensiTable.style.pointerEvents = 'auto';

                    // Re-bind pagination events
                    bindPaginationEvents();
                })
                .catch(error => {
                    console.error('Error:', error);
                    presensiTable.style.opacity = '1';
                    presensiTable.style.pointerEvents = 'auto';
                });
        }

        function bindPaginationEvents() {
            // Bind pagination clicks
            document.querySelectorAll('.pagination-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    if (page) {
                        fetchPresensi(page);
                    }
                });
            });
        }

        // Initial binding
        document.addEventListener('DOMContentLoaded', function () {
            bindPaginationEvents();

            document.getElementById('filterStatus').addEventListener('change', () => fetchPresensi(1));
            document.getElementById('searchTanggal').addEventListener('change', () => fetchPresensi(1));
        });
    </script>
@endpush