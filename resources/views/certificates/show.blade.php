<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Certificate - {{ $transaction->customer_name }} - {{ $transaction->event->title }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Alex+Brush&display=swap" rel="stylesheet">
    
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 0;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .cert-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                max-width: 100% !important;
            }
        }

        .font-cinzel {
            font-family: 'Cinzel', serif;
        }

        .font-signature {
            font-family: 'Alex Brush', cursive;
        }

        .gold-gradient {
            background: linear-gradient(135deg, #bf953f 0%, #fcf6ba 25%, #b38728 50%, #fbf5b7 75%, #aa771c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gold-border {
            border-image: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c) 1;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen flex flex-col items-center justify-center p-4 md:p-8 font-['Plus_Jakarta_Sans']">

    <!-- Action Bar (Hidden when printing) -->
    <div class="no-print mb-8 flex items-center justify-between w-full max-w-5xl bg-zinc-900 border border-zinc-800 p-4 rounded-2xl shadow-xl">
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 rounded-xl text-xs font-bold transition">
                ← Kembali ke Home
            </a>
            <span class="text-xs text-zinc-500 font-mono hidden sm:inline">VERIFIED CERTIFICATE ID: {{ $transaction->certificate_code }}</span>
        </div>
        
        <button onclick="window.print()" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-zinc-950 font-extrabold rounded-xl text-sm transition shadow-lg shadow-amber-500/20 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak / Unduh PDF</span>
        </button>
    </div>

    <!-- Certificate Card Container -->
    <div class="cert-container w-full max-w-5xl aspect-[1.414/1] bg-zinc-900 text-zinc-100 rounded-3xl p-8 md:p-14 border-4 border-amber-500/30 shadow-2xl relative flex flex-col justify-between overflow-hidden">
        
        <!-- Background Decorative Ornaments -->
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute inset-4 border border-amber-500/20 rounded-2xl pointer-events-none"></div>
        <div class="absolute inset-6 border border-amber-500/40 rounded-xl pointer-events-none"></div>

        <!-- Header -->
        <div class="relative z-10 text-center space-y-2 pt-4">
            <div class="flex items-center justify-center gap-2 mb-2">
                <div class="w-8 h-8 bg-amber-500/20 border border-amber-500/40 rounded-lg flex items-center justify-center text-amber-400 font-bold text-sm">
                    AE
                </div>
                <span class="font-bold text-amber-400 text-sm tracking-widest uppercase">AmikomEventHub Official Certificate</span>
            </div>
            
            <h1 class="font-cinzel text-3xl md:text-5xl font-bold tracking-wider text-amber-300">
                SERTIFIKAT KEHADIRAN
            </h1>
            <p class="text-xs md:text-sm text-zinc-400 font-medium tracking-widest uppercase">
                CERTIFICATE OF ATTENDANCE & PARTICIPATION
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent mx-auto mt-2"></div>
        </div>

        <!-- Content Body -->
        <div class="relative z-10 text-center space-y-6 my-auto py-6">
            <p class="text-xs md:text-sm text-zinc-400 font-medium uppercase tracking-wider">
                Diberikan secara resmi kepada:
            </p>

            <h2 class="font-cinzel text-3xl md:text-5xl font-extrabold gold-gradient tracking-wide uppercase px-4">
                {{ $transaction->customer_name }}
            </h2>

            <p class="text-xs md:text-base text-zinc-300 max-w-2xl mx-auto leading-relaxed">
                Atas partisipasi dan kehadirannya sebagai peserta dalam acara <br>
                <strong class="text-amber-300 font-bold text-base md:text-lg">"{{ $transaction->event->title }}"</strong>
            </p>

            <p class="text-xs text-zinc-400 font-medium">
                Diselenggarakan pada {{ $transaction->event->date->format('d F Y') }} • {{ $transaction->event->location }}
            </p>
        </div>

        <!-- Footer Seal & Signature -->
        <div class="relative z-10 flex items-end justify-between pt-6 border-t border-amber-500/20">
            <!-- Left: Verification Code & QR Stub -->
            <div class="space-y-1 text-left">
                <p class="text-[10px] text-zinc-500 uppercase tracking-wider">Nomor Sertifikat / Code:</p>
                <p class="font-mono text-xs text-amber-400 font-bold tracking-widest">{{ $transaction->certificate_code }}</p>
                <p class="text-[9px] text-zinc-600">Terdaftar di database AmikomEventHub</p>
            </div>

            <!-- Center: Gold Badge Seal -->
            <div class="hidden sm:flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-amber-600 via-amber-400 to-yellow-200 p-0.5 shadow-lg shadow-amber-500/20">
                    <div class="w-full h-full bg-zinc-950 rounded-full flex flex-col items-center justify-center text-amber-400 border border-amber-500/30">
                        <span class="text-xs font-bold tracking-tighter">OFFICIAL</span>
                        <span class="text-[8px] text-zinc-400 uppercase">SEAL</span>
                    </div>
                </div>
            </div>

            <!-- Right: Organizer Signature -->
            <div class="text-right space-y-1">
                <p class="font-signature text-3xl md:text-4xl text-amber-200">AmikomEventHub</p>
                <div class="w-32 border-b border-amber-500/40 ml-auto"></div>
                <p class="text-xs font-bold text-zinc-300">Komite Penyelenggara</p>
                <p class="text-[9px] text-zinc-500">AmikomEventHub Certification Board</p>
            </div>
        </div>

    </div>

</body>
</html>
