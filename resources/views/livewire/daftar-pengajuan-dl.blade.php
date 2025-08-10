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
                        placeholder="cari nama..." required="">
                </div>
            </form>
        </div>
        <div
            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
            <div class="flex items-center gap-2 h-full">
                <p class="font-semibold">Filter:</p>
                <div class="relative h-full">
                    <select wire:model.live="statusFilter" id="status-filter"
                        class="h-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full min-h-[37.6px] px-2.5">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
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
                        Nama
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-center whitespace-nowrap">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-left">
                        Keterangan
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-center whitespace-nowrap">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-center whitespace-nowrap">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengajuan as $index => $data)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="py-4 px-6 w-[30px]">{{ $pengajuan->firstItem() + $index }}</td>
                        <td class="py-4 px-6 text-left">
                            {{ $data->user->name }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            {{ $data->tanggal }}
                        </td>
                        <td class="py-4 px-6 text-left">
                            {{ $data->keterangan }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="text-[13px] w-fit items-center mx-auto">
                                @if ($data->status == 'menunggu')
                                    <p
                                        class="text-amber-700 border-amber-600 bg-amber-50 border-2 rounded-full whitespace-nowrap px-3 py-1 ">
                                        Menunggu</p>
                                @elseif($data->status == 'disetujui')
                                    <p
                                        class="text-green-700 border-green-600 bg-green-50 border-2 rounded-full whitespace-nowrap px-3 py-1 ">
                                        Disetujui</p>
                                @elseif($data->status == 'ditolak')
                                    <p
                                        class="text-red-700 border-red-600 bg-red-50 border-2 rounded-full whitespace-nowrap px-3 py-1 ">
                                        Ditolak</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @if ($data->status == 'menunggu')
                                <div class="flex gap-2 justify-center">
                                    <!-- Tombol Setujui -->
                                    <button wire:click="openModal({{ $data->id }}, 'setujui')"
                                        class="flex items-center gap-1 bg-green-600 border border-transparent p-2.5 h-fit rounded-lg text-white hover:bg-green-100 hover:border hover:border-green-600 hover:text-green-600 transition-all duration-200"
                                        title="Setujui">
                                        <i class="fas fa-check text-sm"></i>
                                    </button>
                                    <!-- Tombol Tolak -->
                                    <button wire:click="openModal({{ $data->id }}, 'tolak')"
                                        class="flex items-center gap-1 bg-red-600 border border-transparent p-2.5 rounded-lg text-white hover:bg-red-100 hover:border hover:border-red-600 hover:text-red-600 transition-all duration-200"
                                        title="Tolak">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                            @else
                                <div class="text-center text-gray-500">
                                    <i class="fas fa-check-circle text-sm"></i>
                                    <span class="text-xs">Sudah diproses</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b hover:bg-gray-50 text-center">
                        <td colspan="6" class="py-10 text-gray-300">
                            <div class="text-4xl text-gray-300 mb-4">📋</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data pengajuan</h3>
                            <p class="text-gray-500">Data pengajuan akan muncul di sini setelah ada aktivitas</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Custom Pagination -->
        {{ $pengajuan->links('vendor.pagination.custom-pagination') }}
    </div>

    <!-- Modal Konfirmasi -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center"
            wire:click="closeModal">
            <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
                <div class="mt-3 text-center">
                    <!-- Icon -->
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full 
                        {{ $actionType === 'setujui' ? 'bg-green-100' : 'bg-red-100' }}">
                        @if($actionType === 'setujui')
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        @else
                            <i class="fas fa-times text-red-600 text-xl"></i>
                        @endif
                    </div>

                    <!-- Title -->
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">
                        Konfirmasi {{ ucfirst($actionType) }}
                    </h3>

                    <!-- Message -->
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Apakah Anda yakin ingin
                            <span class="font-semibold {{ $actionType === 'setujui' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $actionType === 'setujui' ? 'menyetujui' : 'menolak' }}
                            </span>
                            pengajuan dinas luar milik
                            <span class="font-semibold text-gray-900">
                                {{ $selectedPengajuan->user->name ?? '' }}
                            </span>?
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="items-center mt-5">
                        <div class="flex gap-3">
                            <!-- Cancel Button -->
                            <button wire:click="closeModal"
                                class="px-4 py-2 flex-1 bg-gray-500 text-white text-base font-medium rounded-md w-24 shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                                Batal
                            </button>

                            <!-- Confirm Button -->
                            <button wire:click="confirmAction" class="px-4 py-2 flex-1 text-white text-base font-medium rounded-md w-24 shadow-sm focus:outline-none focus:ring-2 transition-all duration-200
                                {{ $actionType === 'setujui'
                                    ? 'bg-green-600 hover:bg-green-700 focus:ring-green-300'
                                    : 'bg-red-600 hover:bg-red-700 focus:ring-red-300' }}">
                                {{ ucfirst($actionType) }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>