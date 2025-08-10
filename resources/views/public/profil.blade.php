@extends($layout)

@section('title', 'Profil')

@section('content')
    <div class="min-h-screen">
        <!-- Simple Header -->
        <div class="bg-white shadow-sm border rounded-xl">
            <div class="container mx-auto px-6 py-6">
                <h1 class="text-2xl font-semibold text-gray-900">Profil Pengguna</h1>
                <p class="text-gray-600 mt-1">Kelola informasi profil dan pengaturan akun Anda</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto m-6">
            <div class="mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Profile Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-28">
                            <div class="text-center">
                                <!-- Avatar -->
                                <div class="relative inline-block mb-4">
                                    @if(Auth::user()->foto_profil)
                                        <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" 
                                             alt="foto_profil" 
                                             class="w-24 h-24 rounded-full object-cover border-4 border-gray-100">
                                    @else
                                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center border-4 border-gray-100">
                                            <span class="text-white text-2xl font-semibold">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- User Info -->
                                <h2 class="text-xl font-semibold text-gray-900 mb-1">{{ Auth::user()->name }}</h2>
                                <p class="text-gray-600 mb-2">{{ Auth::user()->email }}</p>
                                <p class="text-gray-800 mb-2 text-sm">NIP: {{ Auth::user()->nip }}</p>
                                
                                <!-- Role Badge -->
                                @if(Auth::user()->role == 'guru')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                        Guru
                                    </span>
                                @elseif(Auth::user()->role == 'kepsek')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                        Kepala Sekolah
                                    </span>
                                @elseif(Auth::user()->role == 'admin')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                        <span class="w-2 h-2 bg-amber-400 rounded-full mr-2"></span>
                                        Admin
                                    </span>
                                @endif
                                
                                <!-- Member Since -->
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <p class="text-sm text-gray-500">Bergabung sejak</p>
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->created_at->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Details -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border">
                            <!-- Tabs -->
                            <div class="border-b border-gray-200">
                                <nav class="flex space-x-8 px-6">
                                    @php
                                        $currentTab = request()->get('tab', 'overview');
                                    @endphp
                                    
                                    <a href="{{ route('profil', ['tab' => 'overview']) }}" 
                                       class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $currentTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                        Overview
                                    </a>
                                    <a href="{{ route('profil', ['tab' => 'edit']) }}" 
                                       class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $currentTab === 'edit' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                        Ubah Data
                                    </a>
                                    <a href="{{ route('profil', ['tab' => 'password']) }}" 
                                       class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $currentTab === 'password' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                        Ubah Password
                                    </a>
                                </nav>
                            </div>

                            <!-- Tab Content -->
                            <div class="p-6">
                                @if($currentTab === 'overview')
                                    <!-- Overview Tab Content -->
                                    <div class="space-y-6">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Akun</h3>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="bg-gray-50 p-4 rounded-lg">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                                    <p class="text-gray-900">{{ Auth::user()->name }}</p>
                                                </div>
                                                
                                                <div class="bg-gray-50 p-4 rounded-lg">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                    <p class="text-gray-900">{{ Auth::user()->email }}</p>
                                                </div>
                                                
                                                <div class="bg-gray-50 p-4 rounded-lg">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                                    <p class="text-gray-900">{{ Auth::user()->no_telepon ?? 'Belum diisi' }}</p>
                                                </div>
                                                
                                                <div class="bg-gray-50 p-4 rounded-lg">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                                    <p class="text-gray-900">{{ Auth::user()->tanggal_lahir ? \Carbon\Carbon::parse(Auth::user()->tanggal_lahir)->format('d F Y') : 'Belum diisi' }}</p>
                                                </div>
                                                
                                                <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                                    <p class="text-gray-900">{{ Auth::user()->alamat ?? 'Belum diisi' }}</p>
                                                </div>
                                                
                                            </div>
                                        </div>

                                        <div class="pt-6 border-t border-gray-200">
                                            <a href="{{ route('profil', ['tab' => 'edit']) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit Profil
                                            </a>
                                        </div>
                                    </div>

                                @elseif($currentTab === 'edit')
                                    <!-- Edit Profile Tab Content -->
                                    @livewire('edit-profil')

                                @elseif($currentTab === 'password')
                                    <!-- Change Password Tab Content -->
                                    @livewire('ubah-password')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
@endsection