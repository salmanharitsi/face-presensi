@extends('layouts.app')

@section('title', 'Guru Presensi Face Recognition')

@section('content')
    <div class="min-h-screen">
        <!-- Main Container -->
        <div class="mx-auto">
            <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">

                <!-- Status Bar -->
                <div class="bg-gradient-to-r from-emerald-500 to-cyan-500 p-4">
                    <div class="flex justify-between items-center text-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                            <span class="font-medium" id="status-text">Memuat kamera...</span>
                        </div>
                        <div class="text-sm font-medium" id="time-display"></div>
                    </div>
                </div>

                <!-- Camera Section -->
                <div class="p-6">
                    <div class="relative">
                        <!-- Camera Container -->
                        <div class="webcam-capture relative mx-auto">
                            <video id="cam" autoplay muted class="rounded-xl shadow-lg bg-gray-900 object-cover"></video>

                            <!-- Loading Spinner -->
                            <div id="loading"
                                class="absolute inset-0 bg-black bg-opacity-50 rounded-xl flex items-center justify-center">
                                <div class="text-center text-white">
                                    <div
                                        class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin mx-auto mb-4">
                                    </div>
                                    <p class="text-lg font-medium">Memuat Model...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="mt-8 grid md:grid-cols-3 gap-6">
                            <div class="text-center p-4 bg-emerald-50 rounded-xl">
                                <div
                                    class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Posisi Tegak</h3>
                                <p class="text-sm text-gray-600">Pastikan kepala dalam posisi tegak dan menghadap kamera</p>
                            </div>

                            <div class="text-center p-4 bg-cyan-50 rounded-xl">
                                <div
                                    class="w-12 h-12 bg-cyan-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Pencahayaan Cukup</h3>
                                <p class="text-sm text-gray-600">Pastikan area wajah memiliki pencahayaan yang memadai</p>
                            </div>

                            <div class="text-center p-4 bg-purple-50 rounded-xl">
                                <div
                                    class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Tunggu Deteksi</h3>
                                <p class="text-sm text-gray-600">Sistem akan otomatis mendeteksi dan memverifikasi wajah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Modal -->
            <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
                <div class="bg-white rounded-xl p-6 m-6 max-w-md w-full transform scale-95 transition-transform">
                    <div class="text-center">
                        @php
                            $isPulang = $presensiHariIni;
                            $iconBg = $isPulang ? 'bg-amber-100' : 'bg-green-100';
                            $iconColor = $isPulang ? 'text-amber-500' : 'text-green-500';
                            $title = $isPulang ? 'Lapor Pulang Berhasil!' : 'Presensi Berhasil!';
                            $message = $isPulang ? 'Wajah Anda telah terverifikasi dan jam pulang telah tercatat.' : 'Wajah Anda telah terverifikasi dan presensi telah tercatat.';
                            $statusText = $isPulang ? 'Sudah lapor pulang' : 'Hadir';
                            $statusColor = $isPulang ? 'text-amber-600' : 'text-green-600';
                            $buttonGradient = $isPulang ? 'from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600' : 'from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600';
                        @endphp

                        <!-- Icon -->
                        <div class="w-16 h-16 {{ $iconBg }} rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>

                        <!-- Content -->
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $title }}</h3>
                        <p class="text-gray-600 mb-6">{{ $message }}</p>

                        <!-- Details -->
                        <div class="space-y-2 text-sm text-gray-500 mb-6">
                            <p><strong>Nama:</strong> <span id="success-name"></span></p>
                            <p><strong>Waktu:</strong> <span id="success-time"></span></p>
                            <p><strong>Status:</strong> <span
                                    class="{{ $statusColor }} font-medium">{{ $statusText }}</span></p>
                        </div>

                        <!-- Button -->
                        <button id="close-modal"
                            class="w-full bg-gradient-to-r {{ $buttonGradient }} text-white py-3 rounded-xl font-medium transition-colors">
                            Kembali ke Presensi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Error Modal -->
            <div id="error-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
                <div class="bg-white rounded-xl p-6 m-6 max-w-md w-full transform scale-95 transition-transform">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Wajah Tidak Dikenali</h3>
                        <p class="text-gray-600 mb-6">Wajah Anda tidak terdaftar dalam sistem. Silakan hubungi
                            administrator.</p>
                        <button id="close-error-modal"
                            class="w-full bg-gradient-to-r from-red-500 to-pink-500 text-white py-3 rounded-xl font-medium">
                            Coba Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Face API Script dari folder public -->
    <script src="{{ asset('assets/lib/face-api/face-api.min.js') }}"></script>

    <script>
        const loggedInUserId = @json(Auth::user()->id);
        const loggedInUserName = @json(Auth::user()->name);
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', async () => {
            const video = document.getElementById('cam');
            const videoContainer = video.parentElement;
            const loadingElement = document.getElementById('loading');
            let labeledFaceDescriptors;
            let faceMatcher;
            let isRecognized = false;
            let canvas;

            async function startVideo() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
                    video.srcObject = stream;
                } catch (err) {
                    console.error("❌ Error accessing webcam:", err);
                }
            }

            async function loadModels() {
                try {
                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri('/assets/lib/face-api/models'),
                        faceapi.nets.faceLandmark68Net.loadFromUri('/assets/lib/face-api/models'),
                        faceapi.nets.faceRecognitionNet.loadFromUri('/assets/lib/face-api/models'),
                        faceapi.nets.ssdMobilenetv1.loadFromUri('/assets/lib/face-api/models')
                    ]);
                    console.log("✅ All models loaded");
                } catch (err) {
                    console.error("❌ Failed to load models:", err);
                }
            }

            async function loadLabeledImages() {
                const label = loggedInUserName;
                const descriptions = [];

                //harus ada 20 image per label kalo gamau eror GET nya
                for (let i = 1; i <= 10; i++) {
                    try {
                        const imgUrl = `/assets/lib/face-api/labels/${label}/${i}.jpg`;
                        const img = await faceapi.fetchImage(imgUrl);
                        const detection = await faceapi
                            .detectSingleFace(img)
                            .withFaceLandmarks()
                            .withFaceDescriptor();
                        if (detection) {
                            descriptions.push(detection.descriptor);
                        } else {
                            console.warn(`⚠️ No face detected in ${imgUrl}`);
                        }
                    } catch (err) {
                        console.error(`❌ Failed to load image ${i} for ${label}:`, err);
                    }
                }

                return [new faceapi.LabeledFaceDescriptors(label, descriptions)];
            }

            function hideLoading() {
                if (loadingElement) {
                    loadingElement.style.display = 'none';
                }
            }

            function stopCamera() {
                const tracks = video.srcObject?.getTracks();
                if (tracks) tracks.forEach(track => track.stop());
                if (canvas && canvas.parentNode) canvas.remove();
            }

            function showSuccessModal() {
                const statusText = document.getElementById('status-text');
                let countdown = 7;
                statusText.textContent = `Tahan posisi wajah anda dalam ${countdown} detik...`;

                const interval = setInterval(() => {
                    countdown--;
                    if (countdown > 0) {
                        statusText.textContent = `Tahan posisi wajah anda dalam ${countdown} detik...`;
                    } else {
                        clearInterval(interval);
                        statusText.textContent = "Mengirim data presensi...";
                        stopCamera(); // ← pindahkan ke sini
                        sendPresensi();
                    }
                }, 1000);
            }

            function showErrorModal() {
                const modal = document.getElementById('error-modal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            async function sendPresensi() {
                try {
                    const response = await fetch('/api/process-presensi', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            user_id: loggedInUserId,
                            user_name: loggedInUserName,
                            attendance_time: new Date().toISOString(),
                            status: 'Hadir' // jika dibutuhkan
                        })
                    });

                    const data = await response.json();
                    console.log('✅ Presensi berhasil:', data);

                    // tampilkan modal sukses
                    const modal = document.getElementById('success-modal');
                    const timeSpan = document.getElementById('success-time');
                    const nameSpan = document.getElementById('success-name');

                    nameSpan.textContent = loggedInUserName;
                    timeSpan.textContent = new Date().toLocaleString('id-ID');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    document.getElementById('status-text').textContent = "Presensi berhasil!";
                } catch (error) {
                    console.error('❌ Gagal mengirim presensi:', error);
                    document.getElementById('status-text').textContent = "Gagal mengirim presensi.";
                    showErrorModal();
                }
            }

            video.addEventListener('play', () => {
                canvas = faceapi.createCanvasFromMedia(video);
                videoContainer.appendChild(canvas);

                canvas.style.position = 'absolute';
                canvas.style.top = '0';
                canvas.style.left = '0';
                canvas.style.zIndex = '10';

                const displaySize = {
                    width: video.videoWidth || video.offsetWidth,
                    height: video.videoHeight || video.offsetHeight
                };

                canvas.width = displaySize.width;
                canvas.height = displaySize.height;
                faceapi.matchDimensions(canvas, displaySize);

                const detect = async () => {
                    if (isRecognized || video.readyState !== 4) {
                        requestAnimationFrame(detect);
                        return;
                    }

                    const detections = await faceapi
                        .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptors();

                    const resizedDetections = faceapi.resizeResults(detections, displaySize);
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    if (!faceMatcher || resizedDetections.length === 0) {
                        document.getElementById('status-text').textContent = 'Tidak ada wajah yang terdeteksi';
                        requestAnimationFrame(detect);
                        return;
                    }

                    const results = resizedDetections.map(d =>
                        faceMatcher.findBestMatch(d.descriptor)
                    );

                    let wajahDikenali = false;

                    results.forEach((result, i) => {
                        const box = resizedDetections[i].detection.box;
                        const color = (result.label === loggedInUserName && result.distance < 0.45)
                            ? '#00ff00' : '#ff0000';

                        new faceapi.draw.DrawBox(box, {
                            label: `${result.label} (${result.distance.toFixed(2)})`,
                            boxColor: color
                        }).draw(canvas);

                        if (result.label === loggedInUserName && result.distance < 0.45) {
                            wajahDikenali = true;
                            isRecognized = true;
                            showSuccessModal();
                            console.log('✅ Wajah dikenali sebagai', result.label);
                        }
                    });

                    if (!wajahDikenali) {
                        document.getElementById('status-text').textContent = 'Wajah anda tidak cocok';
                        requestAnimationFrame(detect);
                    }
                };

                setTimeout(() => detect(), 1000);
            });

            document.getElementById('close-modal')?.addEventListener('click', () => {
                document.getElementById('success-modal').classList.add('hidden');
                document.getElementById('success-modal').classList.remove('flex');
                window.location.href = '/presensi-guru';
            });

            document.getElementById('close-error-modal')?.addEventListener('click', () => {
                document.getElementById('error-modal').classList.add('hidden');
                document.getElementById('error-modal').classList.remove('flex');
                window.location.reload();
            });

            window.addEventListener('resize', () => {
                if (canvas && video) {
                    const newDisplaySize = {
                        width: video.videoWidth || video.offsetWidth,
                        height: video.videoHeight || video.offsetHeight
                    };
                    canvas.width = newDisplaySize.width;
                    canvas.height = newDisplaySize.height;
                    faceapi.matchDimensions(canvas, newDisplaySize);
                }
            });

            // Inisialisasi
            try {
                await loadModels();
                labeledFaceDescriptors = await loadLabeledImages();
                faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);
                hideLoading();
                await startVideo();
            } catch (error) {
                console.error('❌ Initialization error:', error);
                hideLoading();
            }
        });
    </script>

    <style>
        @keyframes pulse-ring {
            0% {
                transform: scale(0.33);
            }

            40%,
            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
                transform: scale(1.33);
            }
        }

        .animate-pulse-ring {
            animation: pulse-ring 1.25s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        canvas {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: 0 auto;
            height: auto !important;
            border-radius: 10px;
        }

        @media (min-width: 505px) {
            .webcam-capture {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 50% !important;
                height: auto !important;
            }

            .webcam-capture video {
                width: 100% !important;
                margin: 0 auto;
            }
        }
    </style>
@endsection