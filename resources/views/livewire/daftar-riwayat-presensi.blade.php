<div>
    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
        <div class="w-full md:w-1/5">
            <form class="flex items-center">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-lg text-gray-500"></i>
                    </div>
                    <input wire:model.live="search" type="text" id="simple-search"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2"
                        placeholder="cari tanggal (ex: 2023-01-01)" required="">
                </div>
            </form>
        </div>
        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
            <div class="flex items-center gap-2 h-full">
                <p class="font-semibold">Filter:</p>
                <div class="relative h-full">
                    <select wire:model.live="statusFilter" id="status-filter" 
                        class="h-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full min-h-[37.6px] px-2.5">
                        <option value="">Semua Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="tidak-hadir">Tidak Hadir</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table id="dataIkuTable" class="w-full text-sm text-left rtl:text-left">
            <thead class="text-md text-gray-700 uppercase bg-gray-100 h-full">
                <tr class="h-full">
                    <th scope="col" class="p-4 w-4 text-left">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-left">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-center whitespace-nowrap">
                        Jam Masuk
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-center whitespace-nowrap">
                        Jam Keluar
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-center whitespace-nowrap">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($presensi as $index => $data)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="py-4 px-6 w-[30px]">{{ $presensi->firstItem() + $index }}</td>
                        <td class="py-4 px-6 text-left">
                            {{ $data->tanggal }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if ($data->jam_masuk == null)
                                --
                            @else
                                {{ $data->jam_masuk }}
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if ($data->jam_keluar == null)
                                --
                            @else
                                {{ $data->jam_keluar }}
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div
                                class="text-[13px] w-fit items-center mx-auto">
                                @if ($data->status == 'hadir')
                                    <p class="text-green-700 border-green-600 bg-green-50 border-2 rounded-full whitespace-nowrap px-3 py-1 ">Hadir</p>
                                @elseif($data->status == 'hadir-dl')
                                    <p class="text-purple-700 border-purple-600 bg-purple-50 border-2 rounded-full whitespace-nowrap px-3 py-1 ">Hadir (Dinas Luar)</p>
                                @elseif($data->status == 'tidak-hadir')
                                    <p class="text-red-700 border-red-600 bg-red-50 border-2 rounded-full whitespace-nowrap px-3 py-1 ">Tidak Hadir</p>
                                @elseif($data->status == 'hadir-tidak-lapor-pulang')
                                    <p class="text-amber-700 border-amber-600 bg-amber-50 border-2 rounded-full whitespace-nowrap px-3 py-1 ">Hadir Tidak Lapor Pulang</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b hover:bg-gray-50 text-center">
                        <td colspan="7" class="py-10 text-gray-300">
                            <div class="text-4xl text-gray-300 mb-4">📋</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data presensi</h3>
                            <p class="text-gray-500">Data presensi akan muncul di sini setelah ada aktivitas</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Custom Pagination -->
        {{ $presensi->links('vendor.pagination.custom-pagination') }}
    </div>
</div>
