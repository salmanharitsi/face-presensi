<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    @vite('resources/css/app.css')
</head>

<body class="overflow-hidden">

    @include('_message')

    <!-- Background dengan gradient dan circle decorations -->
    <div class="fixed inset-0 overflow-hidden">
        <!-- Animated circles -->
        <div
            class="absolute -bottom-40 -right-40 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-float">
        </div>
        <div
            class="absolute -top-40 -left-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse-slow">
        </div>
        <div class="absolute bottom-1/4 right-1/3 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-25 animate-pulse-slow"
            style="animation-delay: -1s;"></div>
    </div>

    <!-- Main container -->
    <div class="relative min-h-screen flex items-center justify-center">
        <div class="animate-slide-up">
            <!-- Login card dengan glassmorphic effect -->
            <div class="glassmorphic rounded-2xl shadow-2xl w-full relative overflow-hidden">
                <div class="relative p-6 flex flex-col items-center justify-center gap-7 z-10 w-[350px] md:w-[500px]">
                    {{-- <div class="flex w-full items-center justify-start md:justify-center md:mb-8">
                        <img class="w-[90px]" src="{{ asset('assets/bps-logo.svg') }}" alt="BPS logo image">
                    </div> --}}
                    <div class="flex flex-col w-full items-start gap-4 text-gray-800">
                        <h1 class="font-semibold text-2xl">Registrasi
                        </h1>
                        <p class="font-normal text-sm">Input data dibawah untuk registrasi</p>
                    </div>
                    <div class="w-full rounded-lg ">
                        <form class="space-y-4" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div>
                                <label for="nama" class="block mb-2 text-sm font-normal">Nama</label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                    class="bg-transparent border border-gray-500 text-gray-900 text-sm rounded-lg focus:outline-blue-500 focus:outline-2 w-full p-2.5 placeholder:text-[12px]"
                                    placeholder="Septania Daniati" />
                                @error('nama')
                                    <span class="text-red-600 text-[11px]">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="nip" class="block mb-2 text-sm font-normal">Nomor Induk Pegawai</label>
                                <input type="number" name="nip" id="nip" value="{{ old('nip') }}"
                                    class="bg-transparent border border-gray-500 text-gray-900 text-sm rounded-lg focus:outline-blue-500 focus:outline-2 w-full p-2.5 placeholder:text-[12px]"
                                    placeholder="12321234245" />
                                @error('nip')
                                    <span class="text-red-600 text-[11px]">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block mb-2 text-sm font-normal">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="bg-transparent border border-gray-500 text-gray-900 text-sm rounded-lg focus:outline-blue-500 focus:outline-2 w-full p-2.5 placeholder:text-[12px]"
                                    placeholder="name@gmail.com" />
                                @error('email')
                                    <span class="text-red-600 text-[11px]">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="password" class="block mb-2 text-sm font-normal">Password</label>
                                <div class="relative group">
                                    <input type="password" name="password" id="password"
                                        class="bg-transparent border border-gray-500 text-gray-900 text-sm rounded-lg focus:outline-blue-500 focus:outline-2 w-full p-2.5 placeholder:text-[12px]"
                                        placeholder="••••••••" />
                                    <button type="button" onclick="togglePasswordVisibility('password')"
                                        class="absolute w-fit justify-center p-3 h-full right-0 top-0 flex items-center pr-3 text-gray-500 group-focus-within:text-blue-500">
                                        <i id="togglePasswordIcon_password" class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="text-red-600 text-[11px]">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block mb-2 text-sm font-normal">Konfirmasi Password</label>
                                <div class="relative group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="bg-transparent border border-gray-500 text-gray-900 text-sm rounded-lg focus:outline-blue-500 focus:outline-2 w-full p-2.5 placeholder:text-[12px]"
                                        placeholder="••••••••" />
                                    <button type="button" onclick="togglePasswordVisibility('password_confirmation')"
                                        class="absolute w-fit justify-center p-3 h-full right-0 top-0 flex items-center pr-3 text-gray-500 group-focus-within:text-blue-500">
                                        <i id="togglePasswordIcon_password_confirmation" class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full mt-2 text-white bg-blue-600 hover:bg-blue-700 transition duration-300 ease-in-out focus:ring-2 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center disabled:bg-blue-400 cursor-pointer">
                                Daftar Akun
                            </button>
                            <div class="text-sm text-center text-gray-700 mt-2">
                                Sudah punya akun Guru? <a href="{{ url('/') }}" class="text-blue-500 hover:underline">Masuk</a>
                            </div>
                        </form>                        
                    </div>
                </div>
            </div>
        </div>
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

        // Add floating animation to form on load
        window.addEventListener('load', function() {
            const form = document.querySelector('.glassmorphic');
            form.style.transform = 'translateY(0)';
        });

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
</body>

</html>
