<form wire:submit="updatePassword">
    <div class="space-y-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password</h3>
            <p class="text-sm text-gray-600 mb-6">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>
        </div>

        <!-- Current Password -->
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                Password Saat Ini
            </label>
            <div class="relative group">
                <input type="password" id="current_password" name="current_password" wire:model.live="current_password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                <button type="button" onclick="togglePasswordVisibility('current_password')"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-blue-500">
                    <i id="togglePasswordIcon_current_password" class="fas fa-eye"></i>
                </button>
            </div>
            @error('current_password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Password Baru
            </label>
            <div class="relative group">
                <input type="password" id="password" name="password" wire:model.live="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                <button type="button" onclick="togglePasswordVisibility('password')"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-blue-500">
                    <i id="togglePasswordIcon_password" class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                Konfirmasi Password Baru
            </label>
            <div class="relative group">
                <input type="password" id="password_confirmation" name="password_confirmation"
                    wire:model.live="password_confirmation"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                <button type="button" onclick="togglePasswordVisibility('password_confirmation')"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-blue-500">
                    <i id="togglePasswordIcon_password_confirmation" class="fas fa-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Requirements -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="text-sm font-medium text-blue-900 mb-2">Persyaratan Password:</h4>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Minimal 8 karakter</li>
                <li>• Mengandung huruf besar dan kecil</li>
                <li>• Mengandung minimal satu angka</li>
            </ul>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
        <a href="{{ route('profil', ['tab' => 'overview']) }}"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
            Batal
        </a>
        <button type="submit"
            class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
            Update Password
        </button>
    </div>
</form>

<script>
    function togglePasswordVisibility(fieldId) {
        const input = document.getElementById(fieldId);
        const icon = document.getElementById('togglePasswordIcon_' + fieldId);

        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>