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
                        placeholder="Cari nama, NIP, atau email..." required="">
                </div>
            </form>
        </div>
        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
            <div class="flex items-center gap-2 h-full">
                <p class="font-semibold">Filter Role:</p>
                <div class="relative h-full">
                    <select wire:model.live="roleFilter" id="role-filter" 
                        class="h-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full min-h-[37.6px] px-2.5">
                        <option value="">Semua Role</option>
                        <option value="kepsek">Kepala Sekolah</option>
                        <option value="guru">Guru</option>
                    </select>
                </div>
            </div>
            @if (Auth::user()->role == 'admin')
                <button wire:click="openAddModal" type="button"
                    class="flex items-center justify-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Pengajar
                </button>
            @endif
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="dataPengajarTable" class="w-full text-sm text-left rtl:text-left">
            <thead class="text-md text-gray-700 uppercase bg-gray-100 h-full">
                <tr class="h-full">
                    <th scope="col" class="p-4 w-4 text-left">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-left">
                        Nama
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-center whitespace-nowrap">
                        NIP
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-center whitespace-nowrap">
                        No Telepon
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-center whitespace-nowrap">
                        Role
                    </th>
                    <th scope="col" class="px-6 py-3 border-l border-white text-center whitespace-nowrap">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengajar as $index => $data)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="py-4 px-6 w-[30px]">{{ $pengajar->firstItem() + $index }}</td>
                        <td class="py-4 px-6 text-left">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    @if($data->foto_profil)
                                        <img src="{{ Storage::url($data->foto_profil) }}" 
                                             alt="{{ $data->name }}" 
                                             class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center border-2 border-gray-200">
                                            <i class="fas fa-user text-gray-500"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $data->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $data->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            {{ $data->nip ?? '--' }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            {{ $data->no_telepon ?? '--' }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="text-[13px] w-fit items-center mx-auto">
                                @if ($data->role == 'kepsek')
                                    <p class="text-purple-700 border-purple-600 bg-purple-50 border-2 rounded-full whitespace-nowrap px-3 py-1">Kepala Sekolah</p>
                                @elseif($data->role == 'guru')
                                    <p class="text-blue-700 border-blue-600 bg-blue-50 border-2 rounded-full whitespace-nowrap px-3 py-1">Guru</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex gap-2 justify-center">
                                @if (Auth::user()->role == 'admin')
                                    <!-- Tombol Edit -->
                                    <button wire:click.prevent="openEditModal('{{ $data->id }}')"
                                        class="flex items-center justify-center bg-green-600 border border-transparent p-2.5 rounded-lg text-white hover:bg-green-100 hover:border-green-600 hover:text-green-600 transition-all duration-200"
                                        title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </button>
                        
                                    <!-- Tombol Hapus -->
                                    <button wire:click.prevent="openDeleteModal('{{ $data->id }}')"
                                        class="flex items-center justify-center bg-red-600 border border-transparent p-2.5 rounded-lg text-white hover:bg-red-100 hover:border-red-600 hover:text-red-600 transition-all duration-200"
                                        title="Hapus">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                @endif
                        
                                <!-- Tombol Detail -->
                                <button wire:click.prevent="showDetail('{{ $data->id }}')"
                                    class="flex items-center justify-center bg-blue-600 border border-transparent p-2.5 rounded-lg text-white hover:bg-blue-100 hover:border-blue-600 hover:text-blue-600 transition-all duration-200"
                                    title="Lihat Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b hover:bg-gray-50 text-center">
                        <td colspan="6" class="py-10 text-gray-300">
                            <div class="text-4xl text-gray-300 mb-4">👨‍🏫</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data pengajar</h3>
                            <p class="text-gray-500">Data pengajar akan muncul di sini</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Pagination -->
        {{ $pengajar->links('vendor.pagination.custom-pagination') }}
    </div>

    <!-- Modal Konfirmasi Delete -->
    @if($showDeleteModal && $userToDelete)
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
                            pengajar
                            <span class="font-semibold text-gray-900">
                                {{ $userToDelete->name }}
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

    <!-- Modal Tambah Pengajar -->
    @if($showAddModal)
        <div class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-start sm:items-center justify-center p-2 sm:p-4" 
            wire:click="closeAddModal">
            <div class="relative w-full max-w-2xl shadow-2xl rounded-xl bg-white transform transition-all duration-300 ease-out scale-100 my-2 sm:my-4 max-h-[calc(100vh-16px)] sm:max-h-[calc(100vh-32px)] overflow-auto custom-scrollbar"
                wire:click.stop>
                <!-- Scrollable Container -->
                <div class="flex flex-col max-h-full">
                    <!-- Header Modal dengan Gradient - Fixed -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-xl flex-shrink-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-sm sm:text-lg"></i>
                                </div>
                                <h3 class="text-lg sm:text-xl font-semibold">Tambah Pengajar</h3>
                            </div>
                            <button wire:click="closeAddModal" 
                                    class="text-white hover:bg-white hover:bg-opacity-20 p-1.5 sm:p-2 rounded-full transition-all duration-200">
                                <i class="fas fa-times text-sm sm:text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body Modal - Scrollable -->
                    <div class="flex-1 overflow-y-auto">
                        <div class="p-4 sm:p-6">
                            <form wire:submit.prevent="savePengajar">
                                <div class="grid grid-cols-1 gap-4 sm:gap-6">
                                    <!-- Nama Lengkap -->
                                    <div class="space-y-2">
                                        <label for="name" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-user text-blue-500 w-4"></i>
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="name" type="text" id="name"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                            placeholder="Masukkan nama lengkap">
                                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- NIP -->
                                    <div class="space-y-2">
                                        <label for="nip" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-id-badge text-blue-500 w-4"></i>
                                            NIP <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="nip" type="text" id="nip"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                            placeholder="Masukkan NIP">
                                        @error('nip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="space-y-2">
                                        <label for="email" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-envelope text-blue-500 w-4"></i>
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="email" type="email" id="email"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                            placeholder="Masukkan alamat email">
                                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="space-y-2">
                                        <label for="password" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-lock text-blue-500 w-4"></i>
                                            Password <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="password" type="password" id="password"
                                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                                placeholder="Masukkan password (minimal 8 karakter)">
                                            <button type="button" onclick="togglePasswordVisibility('password')"
                                                class="absolute w-fit justify-center p-3 h-full right-0 top-0 flex items-center pr-3 text-gray-500 group-focus-within:text-blue-500">
                                                <i id="togglePasswordIcon_password" class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Footer Modal - Fixed -->
                    <div class="flex justify-end gap-3 px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 rounded-b-xl border-t flex-shrink-0">
                        <button wire:click="closeAddModal" type="button"
                                class="flex items-center gap-2 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm sm:text-base">
                            <i class="fas fa-times text-sm"></i>
                            Batal
                        </button>
                        <button wire:click="savePengajar" type="button" wire:loading.attr="disabled"
                                class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm sm:text-base disabled:opacity-50">
                            <i class="fas fa-save text-sm" wire:loading.remove wire:target="savePengajar"></i>
                            <i class="fas fa-spinner fa-spin text-sm" wire:loading wire:target="savePengajar"></i>
                            <span wire:loading.remove wire:target="savePengajar">Simpan</span>
                            <span wire:loading wire:target="savePengajar">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Detail Pengajar -->
    @if($showModal && $selectedUser)
        <div class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-start sm:items-center justify-center p-2 sm:p-4" 
            wire:click="closeModal">
            <div class="relative w-full max-w-4xl shadow-2xl rounded-xl bg-white transform transition-all duration-300 ease-out scale-100 my-2 sm:my-4 max-h-[calc(100vh-16px)] sm:max-h-[calc(100vh-32px)] overflow-auto custom-scrollbar"
                wire:click.stop>
                <!-- Scrollable Container -->
                <div class="flex flex-col max-h-full">
                    <!-- Header Modal dengan Gradient - Fixed -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-xl flex-shrink-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-tie text-sm sm:text-lg"></i>
                                </div>
                                <h3 class="text-lg sm:text-xl font-semibold">Detail Pengajar</h3>
                            </div>
                            <button wire:click="closeModal" 
                                    class="text-white hover:bg-white hover:bg-opacity-20 p-1.5 sm:p-2 rounded-full transition-all duration-200">
                                <i class="fas fa-times text-sm sm:text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body Modal - Scrollable -->
                    <div class="flex-1 overflow-y-auto">
                        <div class="p-4 sm:p-6">
                            <!-- Foto Profil Section -->
                            <div class="flex justify-center mb-6 sm:mb-8">
                                <div class="relative">
                                    @if($selectedUser->foto_profil)
                                        <img src="{{ Storage::url($selectedUser->foto_profil) }}" 
                                            alt="{{ $selectedUser->name }}" 
                                            class="w-20 h-20 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-blue-200 shadow-xl ring-4 ring-blue-50">
                                    @else
                                        <div class="w-20 h-20 sm:w-28 sm:h-28 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center border-4 border-blue-200 shadow-xl ring-4 ring-blue-50">
                                            <i class="fas fa-user text-gray-500 text-xl sm:text-3xl"></i>
                                        </div>
                                    @endif
                                    <div class="absolute -bottom-1 -right-1 sm:-bottom-2 sm:-right-2 w-6 h-6 sm:w-8 sm:h-8 bg-green-500 rounded-full border-2 sm:border-3 border-white flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Info dengan Grid -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                                <!-- Nama Lengkap -->
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <i class="fas fa-user text-blue-500 w-4"></i>
                                        Nama Lengkap
                                    </label>
                                    <div class="border border-blue-200 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg shadow-sm">
                                        <p class="text-gray-800 font-medium text-sm sm:text-base">{{ $selectedUser->name }}</p>
                                    </div>
                                </div>

                                <!-- NIP -->
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <i class="fas fa-id-badge text-blue-500 w-4"></i>
                                        NIP
                                    </label>
                                    <div class="border border-blue-200 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg shadow-sm">
                                        <p class="text-gray-800 text-sm sm:text-base">{{ $selectedUser->nip ?? 'Belum diatur' }}</p>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <i class="fas fa-envelope text-blue-500 w-4"></i>
                                        Email
                                    </label>
                                    <div class="border border-blue-200 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg shadow-sm">
                                        <p class="text-gray-800 break-all text-sm sm:text-base">{{ $selectedUser->email }}</p>
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <i class="fas fa-user-cog text-blue-500 w-4"></i>
                                        Role
                                    </label>
                                    <div class="px-3 sm:px-4 py-2.5 sm:py-3">
                                        @if ($selectedUser->role == 'kepsek')
                                            <span class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg">
                                                <i class="fas fa-crown text-xs"></i>
                                                Kepala Sekolah
                                            </span>
                                        @elseif($selectedUser->role == 'guru')
                                            <span class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg">
                                                <i class="fas fa-chalkboard-teacher text-xs"></i>
                                                Guru
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- No. Telepon -->
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <i class="fas fa-phone text-blue-500 w-4"></i>
                                        No. Telepon
                                    </label>
                                    <div class="border border-blue-200 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg shadow-sm">
                                        <p class="text-gray-800 text-sm sm:text-base">{{ $selectedUser->no_telepon ?? 'Belum diatur' }}</p>
                                    </div>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <i class="fas fa-calendar-alt text-blue-500 w-4"></i>
                                        Tanggal Lahir
                                    </label>
                                    <div class="border border-blue-200 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg shadow-sm">
                                        <p class="text-gray-800 text-sm sm:text-base">
                                            {{ $selectedUser->tanggal_lahir ? \Carbon\Carbon::parse($selectedUser->tanggal_lahir)->format('d F Y') : 'Belum diatur' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Alamat - Full Width -->
                                <div class="lg:col-span-2 space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <i class="fas fa-map-marker-alt text-blue-500 w-4"></i>
                                        Alamat
                                    </label>
                                    <div class="border border-blue-200 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg shadow-sm">
                                        <p class="text-gray-800 text-sm sm:text-base">{{ $selectedUser->alamat ?? 'Belum diatur' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Modal - Fixed -->
                    <div class="flex justify-end gap-3 px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 rounded-b-xl border-t flex-shrink-0">
                        <button wire:click="closeModal" 
                                class="flex items-center gap-2 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm sm:text-base">
                            <i class="fas fa-times text-sm"></i>
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Edit Pengajar -->
    @if($showEditModal && $userToEdit)
        <div class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-start sm:items-center justify-center p-2 sm:p-4" 
            wire:click="closeEditModal">
            <div class="relative w-full max-w-4xl shadow-2xl rounded-xl bg-white transform transition-all duration-300 ease-out scale-100 my-2 sm:my-4 max-h-[calc(100vh-16px)] sm:max-h-[calc(100vh-32px)] overflow-auto custom-scrollbar"
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
                                <h3 class="text-lg sm:text-xl font-semibold">Edit Data Pengajar</h3>
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
                            <form wire:submit.prevent="updatePengajar">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                                    <!-- Nama Lengkap -->
                                    <div class="space-y-2">
                                        <label for="editName" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-user text-blue-500 w-4"></i>
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="editName" type="text" id="editName"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                            placeholder="Masukkan nama lengkap">
                                        @error('editName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- NIP -->
                                    <div class="space-y-2">
                                        <label for="editNip" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-id-badge text-blue-500 w-4"></i>
                                            NIP <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="editNip" type="text" id="editNip"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                            placeholder="Masukkan NIP">
                                        @error('editNip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="space-y-2">
                                        <label for="editEmail" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-envelope text-blue-500 w-4"></i>
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="editEmail" type="email" id="editEmail"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                            placeholder="Masukkan alamat email">
                                        @error('editEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- No. Telepon -->
                                    <div class="space-y-2">
                                        <label for="editNoTelepon" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-phone text-blue-500 w-4"></i>
                                            No. Telepon
                                        </label>
                                        <input wire:model="editNoTelepon" type="text" id="editNoTelepon"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                            placeholder="Masukkan nomor telepon">
                                        @error('editNoTelepon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Tanggal Lahir -->
                                    <div class="space-y-2">
                                        <label for="editTanggalLahir" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-calendar-alt text-blue-500 w-4"></i>
                                            Tanggal Lahir
                                        </label>
                                        <input wire:model="editTanggalLahir" type="date" id="editTanggalLahir"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                                        @error('editTanggalLahir') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Password Baru -->
                                    <div class="space-y-2">
                                        <label for="editPassword" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-lock text-blue-500 w-4"></i>
                                            Password Baru
                                        </label>
                                        <div class="relative">
                                            <input wire:model="editPassword" type="password" id="editPassword"
                                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base placeholder:text-sm"
                                                placeholder="Kosongkan jika tidak ingin mengubah password">
                                            <button type="button" onclick="togglePasswordVisibility('editPassword')"
                                                class="absolute w-fit justify-center p-3 h-full right-0 top-0 flex items-center pr-3 text-gray-500 group-focus-within:text-blue-500">
                                                <i id="togglePasswordIcon_editPassword" class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('editPassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                                    </div>

                                    <!-- Alamat - Full Width -->
                                    <div class="lg:col-span-2 space-y-2">
                                        <label for="editAlamat" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-map-marker-alt text-blue-500 w-4"></i>
                                            Alamat
                                        </label>
                                        <textarea wire:model="editAlamat" id="editAlamat" rows="3"
                                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base resize-none"
                                            placeholder="Masukkan alamat lengkap"></textarea>
                                        @error('editAlamat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Role Display (Non-editable) -->
                                    <div class="lg:col-span-2 space-y-2">
                                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <i class="fas fa-user-cog text-blue-500 w-4"></i>
                                            Role
                                        </label>
                                        <div class="px-3 sm:px-4 py-2.5 sm:py-3 bg-gray-50 border border-gray-300 rounded-lg">
                                            @if ($userToEdit->role == 'kepsek')
                                                <span class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg">
                                                    <i class="fas fa-crown text-xs"></i>
                                                    Kepala Sekolah
                                                </span>
                                            @elseif($userToEdit->role == 'guru')
                                                <span class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg">
                                                    <i class="fas fa-chalkboard-teacher text-xs"></i>
                                                    Guru
                                                </span>
                                            @endif
                                            <p class="text-xs text-gray-500 mt-1">Role tidak dapat diubah</p>
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
                        <button wire:click="updatePengajar" type="button" wire:loading.attr="disabled"
                                class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm sm:text-base disabled:opacity-50">
                            <i class="fas fa-save text-sm" wire:loading.remove wire:target="updatePengajar"></i>
                            <i class="fas fa-spinner fa-spin text-sm" wire:loading wire:target="updatePengajar"></i>
                            <span wire:loading.remove wire:target="updatePengajar">Update</span>
                            <span wire:loading wire:target="updatePengajar">Memperbarui...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
            `;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            `;
        }
    }

    function togglePasswordVisibility(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById('togglePasswordIcon_' + fieldId);
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>