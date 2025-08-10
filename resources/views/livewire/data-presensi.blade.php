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
                        placeholder="cari tanggal atau nama" required="">
                </div>
            </form>
        </div>
        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
            <div class="flex items-center gap-2 h-full">
                <p class="font-semibold">Filter Pengajar:</p>
                <div class="relative h-full">
                    <select wire:model.live="userFilter" id="user-filter" 
                        class="h-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full min-h-[37.6px] px-2.5 min-w-[150px]">
                        <option value="">Semua Pengajar</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 h-full">
                <p class="font-semibold">Filter Status:</p>
                <div class="relative h-full">
                    <select wire:model.live="statusFilter" id="status-filter" 
                        class="h-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full min-h-[37.6px] px-2.5">
                        <option value="">Semua Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="hadir-dl">Hadir (Dinas Luar)</option>
                        <option value="tidak-hadir">Tidak Hadir</option>
                        <option value="hadir-tidak-lapor-pulang">Hadir Tidak Lapor Pulang</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table id="dataPresensiTable" class="w-full text-sm text-left rtl:text-left">
            <thead class="text-md text-gray-700 uppercase bg-gray-100 h-full">
                <tr class="h-full">
                    <th scope="col" class="p-4 w-4 text-left">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-left">
                        Nama User
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
                    <th scope="col" class="px-6 py-3 border-l border-white text-center whitespace-nowrap">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($presensi as $index => $data)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="py-4 px-6 w-[30px]">{{ $presensi->firstItem() + $index }}</td>
                        <td class="py-4 px-6 text-left">
                            {{ $data->user->name }}
                        </td>
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
                            <div class="text-[13px] w-fit items-center mx-auto">
                                @if ($data->status == 'hadir')
                                    <p class="text-green-700 border-green-600 bg-green-50 border-2 rounded-full whitespace-nowrap px-3 py-1">Hadir</p>
                                @elseif($data->status == 'hadir-dl')
                                    <p class="text-purple-700 border-purple-600 bg-purple-50 border-2 rounded-full whitespace-nowrap px-3 py-1">Hadir (Dinas Luar)</p>
                                @elseif($data->status == 'tidak-hadir')
                                    <p class="text-red-700 border-red-600 bg-red-50 border-2 rounded-full whitespace-nowrap px-3 py-1">Tidak Hadir</p>
                                @elseif($data->status == 'hadir-tidak-lapor-pulang')
                                    <p class="text-amber-700 border-amber-600 bg-amber-50 border-2 rounded-full whitespace-nowrap px-3 py-1">Hadir Tidak Lapor Pulang</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Tombol Edit -->
                                <button wire:click.prevent="openEditModal('{{ $data->id }}')"
                                    class="flex items-center justify-center bg-green-600 border border-transparent p-2.5 rounded-lg text-white hover:bg-green-100 hover:border-green-600 hover:text-green-600 transition-all duration-200"
                                    title="Edit">
                                    <i class="fas fa-pen text-sm"></i>
                                </button>
                    
                                <!-- Tombol Hapus -->
                                <button type="button" wire:click="openDeleteModal('{{ $data->id }}')"
                                    class="flex items-center justify-center bg-red-600 border border-transparent p-2.5 rounded-lg text-white hover:bg-red-100 hover:border-red-600 hover:text-red-600 transition-all duration-200"
                                    title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
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

    <!-- Edit Presensi Modal -->
    @if($showEditModal && $presensiToEdit)
        <div class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-start sm:items-center justify-center p-2 sm:p-4" 
            wire:click="closeEditModal">
            <div class="relative w-full max-w-2xl shadow-2xl rounded-xl bg-white transform transition-all duration-300 ease-out scale-100 my-2 sm:my-4 max-h-[calc(100vh-16px)] sm:max-h-[calc(100vh-32px)] overflow-auto custom-scrollbar"
                wire:click.stop>
                <!-- Scrollable Container -->
                <div class="flex flex-col max-h-full">
                    <!-- Header Modal dengan Gradient - Fixed -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-xl flex-shrink-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-pen text-sm sm:text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-semibold">Edit Data Presensi</h3>
                                    <p class="text-sm text-blue-100">{{ $presensiToEdit->user->name }} - {{ $presensiToEdit->tanggal }}</p>
                                </div>
                            </div>
                            <button wire:click="closeEditModal" 
                                    class="text-white hover:bg-white hover:bg-opacity-20 p-1.5 sm:p-2 rounded-full transition-all duration-200">
                                <i class="fas fa-times text-sm sm:text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body Modal - Scrollable -->
                    <div class="flex-1 overflow-y-auto">
                        <div class="p-4 sm:p-6">
                            <form wire:submit.prevent="updatePresensi">
                                <div class="space-y-4 sm:space-y-6">
                                    <!-- Info User (Read Only) -->
                                    <div class="bg-gray-50 p-4 rounded-lg border">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="font-semibold text-gray-700">Nama:</span>
                                                <span class="text-gray-900">{{ $presensiToEdit->user->name }}</span>
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-700">Tanggal:</span>
                                                <span class="text-gray-900">{{ $presensiToEdit->tanggal }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Jam Masuk -->
                                    <div class="space-y-2">
                                        <label for="editJamMasuk" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-clock text-blue-500 w-4"></i>
                                            Jam Masuk
                                        </label>
                                        <input wire:model="editJamMasuk" type="time" id="editJamMasuk"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                            placeholder="HH:MM">
                                        @error('editJamMasuk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Jam Keluar -->
                                    <div class="space-y-2">
                                        <label for="editJamKeluar" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-clock text-blue-500 w-4"></i>
                                            Jam Keluar
                                        </label>
                                        <input wire:model="editJamKeluar" type="time" id="editJamKeluar"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                            placeholder="HH:MM">
                                        @error('editJamKeluar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Status -->
                                    <div class="space-y-2">
                                        <label for="editStatus" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-user-check text-blue-500 w-4"></i>
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="editStatus" id="editStatus"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                                            <option value="">Pilih Status</option>
                                            <option value="hadir">Hadir</option>
                                            <option value="hadir-dl">Hadir (Dinas Luar)</option>
                                            <option value="tidak-hadir">Tidak Hadir</option>
                                            <option value="hadir-tidak-lapor-pulang">Hadir Tidak Lapor Pulang</option>
                                        </select>
                                        @error('editStatus') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Note -->
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <div class="flex items-start gap-2">
                                            <i class="fas fa-info-circle text-blue-500 text-sm mt-0.5"></i>
                                            <div class="text-sm text-blue-700">
                                                <p class="font-semibold mb-1">Catatan:</p>
                                                <ul class="list-disc list-inside space-y-1 text-xs">
                                                    <li>Jam masuk dan jam keluar bersifat opsional</li>
                                                    <li>Jika diisi, jam keluar harus lebih besar dari jam masuk</li>
                                                    <li>Status wajib dipilih</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Footer Modal - Fixed -->
                    <div class="flex justify-end gap-3 px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 rounded-b-xl border-t flex-shrink-0">
                        <button wire:click="closeEditModal" type="button"
                                class="flex items-center gap-2 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm sm:text-base">
                            <i class="fas fa-times text-sm"></i>
                            Batal
                        </button>
                        <button wire:click="updatePresensi" type="button" wire:loading.attr="disabled"
                                class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm sm:text-base disabled:opacity-50">
                            <i class="fas fa-save text-sm" wire:loading.remove wire:target="updatePresensi"></i>
                            <i class="fas fa-spinner fa-spin text-sm" wire:loading wire:target="updatePresensi"></i>
                            <span wire:loading.remove wire:target="updatePresensi">Update</span>
                            <span wire:loading wire:target="updatePresensi">Memperbarui...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal && $selectedPresensi)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center"
             wire:click="closeDeleteModal">
            <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
                <div class="mt-3 text-center">
                    <!-- Icon -->
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-trash text-red-600 text-xl"></i>
                    </div>

                    <!-- Title -->
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">
                        Konfirmasi Hapus
                    </h3>

                    <!-- Message -->
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Apakah Anda yakin ingin
                            <span class="font-semibold text-red-600">menghapus</span>
                            data presensi
                            <span class="font-semibold text-gray-900">
                                {{ $selectedPresensi->user->name }}
                            </span>
                            tanggal
                            <span class="font-semibold text-gray-900">
                                {{ $selectedPresensi->tanggal }}
                            </span>?
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            Data yang dihapus tidak dapat dikembalikan.
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="items-center mt-5">
                        <div class="flex gap-3">
                            <!-- Cancel Button -->
                            <button wire:click="closeDeleteModal"
                                class="px-4 py-2 flex-1 bg-gray-500 text-white text-base font-medium rounded-md w-24 shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                                Batal
                            </button>

                            <!-- Confirm Button -->
                            <button wire:click="confirmDelete" 
                                class="px-4 py-2 flex-1 text-white text-base font-medium rounded-md w-24 shadow-sm focus:outline-none focus:ring-2 transition-all duration-200 bg-red-600 hover:bg-red-700 focus:ring-red-300">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>