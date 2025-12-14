<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox Simulasi - {{ $email }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>

<body class="p-4 md:p-8 flex items-center justify-center min-h-screen">

    <div
        class="w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden min-h-[500px] flex flex-col">

        {{-- Header Ala Gmail --}}
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0">
            <div class="flex items-center gap-2">
                <div class="flex gap-1.5 mr-4">
                    <div class="w-3 h-3 rounded-full bg-red-400 border border-red-500"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-400 border border-yellow-500"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400 border border-green-500"></div>
                </div>
                <div
                    class="flex items-center gap-2 text-gray-500 bg-white px-3 py-1 rounded-lg border border-gray-200 shadow-sm">
                    <i class="bi bi-inbox-fill"></i>
                    <span class="text-xs font-bold font-mono">Inbox Localhost</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Logged in as</p>
                <p class="text-xs font-bold text-gray-700 truncate max-w-[150px]">{{ $email }}</p>
            </div>
        </div>

        {{-- KONTEN DINAMIS --}}
        <div class="flex-1 p-0 relative">

            @if ($tokenData)
                {{-- KONDISI 1: ADA PERMINTAAN OTP (Tampilkan Email) --}}
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-200">
                                K
                            </div>
                            <div>
                                <h1 class="font-bold text-gray-900 text-lg">Permintaan Reset Password</h1>
                                <p class="text-sm text-gray-500">Dari: <span class="font-medium text-gray-900">KoopEase
                                        Security</span> <span
                                        class="text-blue-600">&lt;noreply@koopease.ac.id&gt;</span></p>
                            </div>
                        </div>
                        <span
                            class="text-xs font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded border border-gray-100">
                            {{ \Carbon\Carbon::parse($tokenData->created_at)->format('H:i') }}
                        </span>
                    </div>

                    <hr class="border-gray-100 mb-8">

                    <div class="prose prose-sm text-gray-600 max-w-none">
                        <p class="mb-4">Halo,</p>
                        <p class="mb-6">Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Gunakan
                            kode rahasia di bawah ini untuk melanjutkan:</p>

                        <div
                            class="bg-blue-50/50 border-2 border-dashed border-blue-200 rounded-2xl p-8 text-center mb-6 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-3 opacity-10">
                                <i class="bi bi-shield-lock-fill text-6xl text-blue-600"></i>
                            </div>

                            <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-2">Kode Verifikasi
                                (OTP)</p>
                            <span
                                class="text-5xl font-mono font-extrabold text-gray-900 tracking-[0.2em] group-hover:scale-110 transition duration-300 inline-block">{{ $tokenData->token }}</span>
                        </div>

                        <div class="flex items-start gap-3 bg-yellow-50 p-4 rounded-xl text-yellow-800 text-xs">
                            <i class="bi bi-exclamation-triangle-fill text-lg"></i>
                            <p class="leading-relaxed">
                                Kode ini akan kadaluarsa dalam <strong>2 menit</strong>. Jangan berikan kode ini kepada
                                siapa pun, termasuk pihak yang mengaku sebagai admin KoopEase.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                {{-- KONDISI 2: TIDAK ADA OTP (Inbox Kosong) --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-8">
                    <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                        <i class="bi bi-inbox text-6xl text-gray-300"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Semuanya Bersih! ✨</h2>
                    <p class="text-gray-500 text-sm max-w-xs mx-auto mb-8">
                        Tidak ada email baru di kotak masuk simulasi Anda saat ini.
                    </p>

                    <a href="{{ route('home') }}"
                        class="px-6 py-2 bg-white border border-gray-200 rounded-full text-sm font-bold text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition shadow-sm">
                        Kembali ke Website
                    </a>
                </div>
            @endif

        </div>

        {{-- Footer Simulasi --}}
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-center">
            <p class="text-[10px] text-gray-400 font-mono">
                ENVIRONMENT: LOCALHOST • LARAVEL {{ app()->version() }} • SIMULATION MODE
            </p>
        </div>

    </div>

</body>

</html>
