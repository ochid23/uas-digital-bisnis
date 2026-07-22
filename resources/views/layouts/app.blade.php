<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Exclusive Event Experience</title>

    <!-- Progressive Web App (PWA) Meta Tags -->
    <meta name="theme-color" content="#4f46e5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AmikomEventHub">
    <meta name="application-name" content="AmikomEventHub">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(24, 24, 27, 0.75); /* Zinc 900 dengan transparansi */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="bg-zinc-950 text-zinc-100 antialiased">

    <nav
        class="glass sticky top-4 z-40 mx-4 md:mx-auto max-w-7xl mt-4 px-6 py-4 rounded-2xl border border-zinc-800 shadow-2xl shadow-black/50 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-indigo-500/20 border border-indigo-500/30 rounded-xl flex items-center justify-center text-indigo-400 font-bold text-xl">
                AE</div>
            <span class="text-xl font-bold tracking-tight text-white">AmikomEventHub</span>
        </div>
        <div class="flex items-center gap-4">
            <div class="hidden md:flex gap-8 font-medium text-sm items-center">
                <a href="/" class="text-indigo-400">Jelajahi</a>
                <a href="/#events" class="text-zinc-400 hover:text-zinc-200 transition">Kategori</a>
                <a href="#" class="text-zinc-400 hover:text-zinc-200 transition">Tentang Kami</a>
            </div>
            <!-- Tombol Instal Aplikasi PWA di Navbar -->
            <button id="nav-pwa-install-btn" type="button"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-indigo-500/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Instal Aplikasi HP</span>
            </button>
        </div>
    </nav>

    @yield('content')

    <!-- Banner Floating Install PWA Aplikasi HP (Selalu Tampil) -->
    <div id="pwa-install-banner" class="fixed bottom-6 right-6 left-6 md:left-auto md:w-96 z-50 bg-zinc-900/95 border border-indigo-500/50 p-5 rounded-2xl shadow-2xl backdrop-blur-md flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <img src="{{ asset('icons/icon-192x192.png') }}" alt="App Icon" class="w-12 h-12 rounded-xl object-cover border border-indigo-500/30">
            <div>
                <h4 class="font-bold text-sm text-white">Instal AmikomEventHub</h4>
                <p class="text-xs text-zinc-400">Gunakan sebagai Aplikasi HP murni</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button id="pwa-install-btn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-indigo-500/30 whitespace-nowrap">
                Instal
            </button>
            <button id="pwa-close-btn" class="p-1.5 text-zinc-400 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>

    <!-- Modal Panduan Cara Instal PWA di HP/PC -->
    <div id="pwa-guide-modal" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-zinc-900 border border-zinc-800 rounded-3xl max-w-md w-full p-6 shadow-2xl relative space-y-6">
            <button id="pwa-modal-close" class="absolute top-4 right-4 text-zinc-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="flex items-center gap-4 border-b border-zinc-800 pb-4">
                <img src="{{ asset('icons/icon-192x192.png') }}" class="w-14 h-14 rounded-2xl border border-indigo-500/30">
                <div>
                    <h3 class="font-extrabold text-lg text-white">Instal AmikomEventHub</h3>
                    <p class="text-xs text-indigo-400 font-semibold">Petunjuk Instalasi Aplikasi HP</p>
                </div>
            </div>

            <div class="space-y-4 text-sm text-zinc-300">
                <div class="bg-zinc-950 p-4 rounded-2xl border border-zinc-800 space-y-2">
                    <p class="font-bold text-white flex items-center gap-2">
                        🤖 Untuk Pengguna Android (Chrome):
                    </p>
                    <ol class="list-decimal list-inside text-xs text-zinc-400 space-y-1">
                        <li>Ketuk menu titik tiga (<strong>⋮</strong>) di pojok kanan atas browser.</li>
                        <li>Pilih <strong>"Tambahkan ke Layar Utama"</strong> (atau <em>Add to Home Screen / Install App</em>).</li>
                        <li>Aplikasi akan langsung terpasang di menu HP Anda!</li>
                    </ol>
                </div>

                <div class="bg-zinc-950 p-4 rounded-2xl border border-zinc-800 space-y-2">
                    <p class="font-bold text-white flex items-center gap-2">
                        🍎 Untuk Pengguna iPhone / iPad (Safari):
                    </p>
                    <ol class="list-decimal list-inside text-xs text-zinc-400 space-y-1">
                        <li>Ketuk tombol <strong>Bagikan</strong> (ikon kotak dengan panah ke atas ⎋).</li>
                        <li>Gulir ke bawah lalu pilih <strong>"Tambah ke Layar Utama"</strong> (<em>Add to Home Screen</em>).</li>
                        <li>Ketuk <strong>Tambah</strong> di pojok kanan atas.</li>
                    </ol>
                </div>
            </div>

            <button id="pwa-modal-ok" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-indigo-500/30">
                Saya Mengerti
            </button>
        </div>
    </div>

    <footer class="bg-zinc-900 border-t border-zinc-800 py-20 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="space-y-4 col-span-2">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-zinc-800 rounded-xl flex items-center justify-center text-white font-bold text-xl border border-zinc-700">
                        AE</div>
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-zinc-400 leading-relaxed">
                    Platform reservasi tiket event online eksklusif untuk pengalaman yang tak terlupakan.
                </p>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-6">Kategori Event</h4>
                <ul class="space-y-4 text-zinc-400">
                    @foreach(\App\Models\Category::all() as $cat)
                        <li>
                            <a href="{{ url('/?category=' . $cat->slug) }}#events" class="hover:text-indigo-400 transition">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-6">Navigasi</h4>
                <ul class="space-y-4 text-zinc-400">
                    <li><a href="/" class="hover:text-indigo-400 transition">Home</a></li>
                    <li><a href="/#events" class="hover:text-indigo-400 transition">Semua Event</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition">Cara Bayar</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                <ul class="space-y-4 text-zinc-400">
                    <li>support@amikomeventhub.com</li>
                    <li>+62 812 3456 7890</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-12 mt-12 border-t border-zinc-800/50 text-center text-zinc-600 text-sm">
            &copy; 2026 AmikomEventHub. PWA Mobile-Ready & Progressive Experience.
        </div>
    </footer>

    <!-- Script Registrasi PWA Service Worker & Install Handler -->
    <script>
        // 1. Registrasi Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js', { scope: '/' })
                    .then(registration => {
                        console.log('AmikomEventHub PWA ServiceWorker зарегистрирован:', registration.scope);
                    })
                    .catch(err => {
                        console.log('PWA ServiceWorker registration failed:', err);
                    });
            });
        }

        // 2. Handler PWA Install Prompt & Modal
        let deferredPrompt;
        const installBanner = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('pwa-install-btn');
        const navInstallBtn = document.getElementById('nav-pwa-install-btn');
        const closeBtn = document.getElementById('pwa-close-btn');
        const guideModal = document.getElementById('pwa-guide-modal');
        const modalClose = document.getElementById('pwa-modal-close');
        const modalOk = document.getElementById('pwa-modal-ok');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (installBanner) installBanner.classList.remove('hidden');
        });

        async function triggerPwaInstall() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User PWA install outcome: ${outcome}`);
                deferredPrompt = null;
                if (installBanner) installBanner.classList.add('hidden');
            } else {
                // Tampilkan Modal Panduan Instalasi jika prompt bawaan browser sudah terpicu / di iOS
                if (guideModal) guideModal.classList.remove('hidden');
            }
        }

        if (installBtn) installBtn.addEventListener('click', triggerPwaInstall);
        if (navInstallBtn) navInstallBtn.addEventListener('click', triggerPwaInstall);

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                if (installBanner) installBanner.classList.add('hidden');
            });
        }

        if (modalClose) modalClose.addEventListener('click', () => guideModal.classList.add('hidden'));
        if (modalOk) modalOk.addEventListener('click', () => guideModal.classList.add('hidden'));
    </script>
</body>

</html>