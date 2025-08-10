<div>
    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4 bg-transparent">
        <!-- Filter Section -->
        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-start md:space-x-3">
            <div class="flex items-center gap-2 h-full">
                <p class="font-semibold">Tahun:</p>
                <div class="relative h-full">
                    <select wire:model.live="selectedYear" id="year-filter" 
                        class="h-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full min-h-[37.6px] px-2.5">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Download Section -->
        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
            <button wire:click="downloadExcel" type="button"
                class="flex items-center justify-center text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2">
                <i class="fas fa-download mr-2"></i>
                Download Excel
            </button>
            <button wire:click="downloadCsv" type="button"
                class="flex items-center justify-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                <i class="fas fa-download mr-2"></i>
                Download CSV
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto px-5">
        <table class="w-full text-xs text-left rtl:text-left border-collapse border border-gray-300 ">
            <!-- Header Row 1 -->
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th rowspan="2" class="border border-gray-400 p-2 text-center font-bold min-w-[150px]">
                        NAMA/BULAN
                    </th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">JANUARI</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">FEBRUARI</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">MARET</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">APRIL</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">MEI</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">JUNI</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">JULI</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">AGUSTUS</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">SEPTEMBER</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">OKTOBER</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">NOVEMBER</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">DESEMBER</th>
                    <th colspan="4" class="border border-gray-400 p-1 text-center font-bold">JUMLAH</th>
                </tr>
                <!-- Header Row 2 -->
                <tr class="bg-blue-500 text-white">
                    @for($i = 1; $i <= 12; $i++)
                        <th class="border border-gray-400 p-1 text-center font-bold w-8 px-2">H</th>
                        <th class="border border-gray-400 p-1 text-center font-bold w-8">HDL</th>
                        <th class="border border-gray-400 p-1 text-center font-bold w-8">HTLP</th>
                        <th class="border border-gray-400 p-1 text-center font-bold w-8">TH</th>
                    @endfor
                    <th class="border border-gray-400 p-1 text-center font-bold w-8 px-2">H</th>
                    <th class="border border-gray-400 p-1 text-center font-bold w-8">HDL</th>
                    <th class="border border-gray-400 p-1 text-center font-bold w-8">HTLP</th>
                    <th class="border border-gray-400 p-1 text-center font-bold w-8">TH</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody>
                @forelse($rekapitulasi as $index => $user)
                    <tr class="{{ $index === 0 && Auth::user()->role === 'kepsek' ? 'bg-blue-200' : 'bg-white' }}">
                        <td class="border border-gray-400 p-2 font-medium text-gray-900">
                            {{ $user['name'] }}
                            @if($index === 0 && Auth::user()->role === 'kepsek')
                                <small class="block text-xs opacity-90">(Anda)</small>
                            @endif
                        </td>
                        
                        @foreach($user['months'] as $month)
                            <td class="border border-gray-400 p-1 text-center text-gray-900">
                                {{ $month['hadir'] ?: '-' }}
                            </td>
                            <td class="border border-gray-400 p-1 text-center text-gray-900">
                                {{ $month['hadir_dl'] ?: '-' }}
                            </td>
                            <td class="border border-gray-400 p-1 text-center text-gray-900">
                                {{ $month['hadir_tidak_lapor_pulang'] ?: '-' }}
                            </td>
                            <td class="border border-gray-400 p-1 text-center text-gray-900">
                                {{ $month['tidak_hadir'] ?: '-' }}
                            </td>
                        @endforeach
                        
                        <!-- Yearly totals -->
                        <td class="border border-gray-400 p-1 text-center font-semibold text-gray-900">
                            {{ $user['yearly_hadir'] }}
                        </td>
                        <td class="border border-gray-400 p-1 text-center font-semibold text-gray-900">
                            {{ $user['yearly_hadir_dl'] }}
                        </td>
                        <td class="border border-gray-400 p-1 text-center font-semibold text-gray-900">
                            {{ $user['yearly_hadir_tidak_lapor_pulang'] }}
                        </td>
                        <td class="border border-gray-400 p-1 text-center font-semibold text-gray-900">
                            {{ $user['yearly_tidak_hadir'] }}
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white">
                        <td colspan="50" class="border border-gray-400 py-10 text-center text-gray-500">
                            <div class="text-4xl text-gray-300 mb-4">📊</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data rekapitulasi</h3>
                            <p class="text-gray-500">Data rekapitulasi akan muncul di sini setelah ada data presensi</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Info Section -->
    <div class="px-5 pb-5 pt-1">
        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Keterangan:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>H</strong> = Hadir</li>
                        <li><strong>HDL</strong> = Hadir - Dinas Luar</li>
                        <li><strong>HTLP</strong> = Hadir Tidak Lapor Pulang</li>
                        <li><strong>TH</strong> = Tidak Hadir</li>
                        <li>Baris biru menandakan data presensi Anda</li>
                        <li>Data yang ditampilkan adalah rekapitulasi untuk tahun {{ $selectedYear }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>