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
        <a href="/" class="flex items-center gap-3 group">
            <div
                class="w-10 h-10 bg-indigo-500/20 border border-indigo-500/30 rounded-xl flex items-center justify-center text-indigo-400 font-bold text-xl group-hover:scale-105 transition">
                AE</div>
            <span class="text-xl font-bold tracking-tight text-white">AmikomEventHub</span>
        </a>
        <div class="flex gap-4 sm:gap-6 font-medium text-xs sm:text-sm items-center">
            <div class="flex gap-3 sm:gap-6 items-center">
                @if((Auth::check() && Auth::user()->role === 'organizer') || request()->is('organizer*') || request()->is('scanner*'))
                    <a href="{{ route('organizer.dashboard') }}" class="text-indigo-400 font-bold">Dashboard Panitia</a>
                    <a href="{{ route('organizer.events.index') }}" class="text-zinc-400 hover:text-zinc-200 transition">Acara Saya</a>
                    <a href="{{ route('organizer.scanner.index') }}" class="text-zinc-400 hover:text-zinc-200 transition flex items-center gap-1 font-bold">
                        📷 Scanner QR
                    </a>
                @elseif((Auth::check() && Auth::user()->role === 'admin') || request()->is('admin*'))
                    <a href="{{ route('admin.dashboard') }}" class="text-indigo-400 font-bold">Dashboard Admin</a>
                    <a href="{{ route('admin.events.index') }}" class="text-zinc-400 hover:text-zinc-200 transition font-bold">Kelola Event</a>
                @else
                    <a href="/" class="text-indigo-400 font-bold">Jelajahi</a>
                    <a href="/#events" class="text-zinc-400 hover:text-zinc-200 transition">Kategori</a>
                    @auth
                        <a href="{{ route('ticket') }}" class="text-zinc-400 hover:text-zinc-200 transition flex items-center gap-1.5 font-bold">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            Tiket Saya
                        </a>
                    @endauth
                @endif
            </div>

            @auth
                <!-- Tampilan User Sudah Login -->
                <div class="flex items-center gap-3 pl-4 border-l border-zinc-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-white leading-tight truncate max-w-[120px]">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider">{{ Auth::user()->role === 'admin' ? 'Superadmin' : (Auth::user()->role === 'organizer' ? 'Organizer' : 'Pembeli') }}</p>
                        </div>
                    </div>

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 bg-indigo-500/10 hover:bg-indigo-600 text-indigo-400 hover:text-white rounded-xl text-xs font-bold transition border border-indigo-500/20">
                            Dashboard Admin
                        </a>
                    @elseif(Auth::user()->role === 'organizer')
                        <a href="{{ route('organizer.dashboard') }}" class="px-3 py-1.5 bg-indigo-500/10 hover:bg-indigo-600 text-indigo-400 hover:text-white rounded-xl text-xs font-bold transition border border-indigo-500/20">
                            Dashboard Organizer
                        </a>
                    @endif

                    <form action="{{ route('admin.logout') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" title="Keluar Akun" class="p-2 text-zinc-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-xl transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            @else
                <!-- Tombol Masuk / Sign In Jika Belum Login -->
                <a href="{{ route('login') }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-600/20 transition-all text-xs font-black active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Masuk
                </a>
            @endauth
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