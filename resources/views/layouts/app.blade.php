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
            background: rgba(24, 24, 27, 0.75);
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
        <div class="hidden md:flex gap-8 font-medium text-sm items-center">
            <a href="/" class="text-indigo-400">Jelajahi</a>
            <a href="/#events" class="text-zinc-400 hover:text-zinc-200 transition">Kategori</a>
            <a href="#" class="text-zinc-400 hover:text-zinc-200 transition">Tentang Kami</a>
        </div>
    </nav>

    @yield('content')

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

    <!-- Script Registrasi PWA Service Worker Native Chrome -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js', { scope: '/' })
                    .then(registration => {
                        console.log('PWA ServiceWorker registered:', registration.scope);
                    })
                    .catch(err => {
                        console.log('PWA ServiceWorker registration failed:', err);
                    });
            });
        }
    </script>
</body>

</html>