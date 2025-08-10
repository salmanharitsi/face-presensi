<form wire:submit="update">
    <div class="space-y-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Lengkap
            </label>
            <input type="text" id="name" wire:model="name"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email
            </label>
            <input type="email" id="email" wire:model="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone -->
        <div>
            <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                Nomor Telepon
            </label>
            <input type="tel" id="no_telepon" wire:model="no_telepon" placeholder="Contoh: 081234567890"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
            @error('no_telepon')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Location -->
        <div>
            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                Alamat
            </label>
            <input type="text" id="alamat" wire:model="alamat" placeholder="Komplek, Jalan, Kota, Provinsi"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
            @error('alamat')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Birthday -->
        <div>
            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                Tanggal Lahir
            </label>
            <input type="date" id="tanggal_lahir" wire:model="tanggal_lahir"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
            @error('tanggal_lahir')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Avatar Upload -->
        <div>
            <label for="foto_profil" class="block text-sm font-medium text-gray-700 mb-2">
                Upload Foto Profil
            </label>
            <div class="flex items-center justify-center w-full">
                <label for="foto_profil"
                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500">
                            <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                        </p>
                        <p class="text-xs text-gray-500">PNG, JPG atau JPEG (MAX. 2MB)</p>
                    </div>
                    <input id="foto_profil" type="file" wire:model="foto_profil" accept="image/*" class="hidden">
                </label>
            </div>
            @error('foto_profil')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <!-- Loading indicator for file upload -->
            <div wire:loading wire:target="foto_profil" class="mt-2">
                <p class="text-sm text-blue-600">Uploading...</p>
            </div>

            <!-- Show selected file name -->
            @if ($foto_profil)
                <p class="mt-2 text-sm text-green-600">File dipilih: {{ $foto_profil->getClientOriginalName() }}</p>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
        <a href="{{ route('profil', ['tab' => 'overview']) }}"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
            Batal
        </a>
        <div class="flex space-x-3">
            <button type="submit" wire:loading.attr="disabled"
                class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-50">
                <span wire:loading.remove wire:target="update">Simpan Perubahan</span>
                <span wire:loading wire:target="update">Menyimpan...</span>
            </button>
        </div>
    </div>
</form>