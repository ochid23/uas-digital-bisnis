<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - naazhi.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom scrollbar untuk dark mode */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #18181b; }
        ::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #52525b; }
    </style>
</head>

<body class="bg-zinc-950 text-zinc-100 flex min-h-screen antialiased">
    <!-- Sidebar Dark Mode -->
    <aside class="w-64 bg-zinc-900 border-r border-zinc-800 text-zinc-300 flex flex-col p-6 space-y-8 sticky top-0 h-screen">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-500/20 border border-indigo-500/30 rounded-xl flex items-center justify-center text-indigo-400 font-bold text-xl">nz</div>
            <span class="text-xl font-bold text-white tracking-tight">naazhi.</span>
        </div>

        <nav class="flex-1 space-y-2">
            <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-4 px-2">Main Menu</p>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'hover:bg-zinc-800 hover:text-white' }} rounded-xl font-bold transition">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-zinc-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.events.*') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'hover:bg-zinc-800 hover:text-white' }} rounded-xl font-bold transition">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.events.*') ? 'text-indigo-400' : 'text-zinc-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Kelola Event
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'hover:bg-zinc-800 hover:text-white' }} rounded-xl font-bold transition">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.categories.*') ? 'text-indigo-400' : 'text-zinc-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
                Kelola Kategori
            </a>
            <a href="{{ route('admin.partners.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.partners.*') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'hover:bg-zinc-800 hover:text-white' }} rounded-xl font-bold transition">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.partners.*') ? 'text-indigo-400' : 'text-zinc-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Kelola Partner
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'hover:bg-zinc-800 hover:text-white' }} rounded-xl font-bold transition">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-indigo-400' : 'text-zinc-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Kelola Pengguna
            </a>
            
            <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.transactions.*') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'hover:bg-zinc-800 hover:text-white' }} rounded-xl font-bold transition">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.transactions.*') ? 'text-indigo-400' : 'text-zinc-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Laporan Transaksi
            </a>
        </nav>

        <div class="pt-6 border-t border-zinc-800">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 rounded-xl transition font-bold text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-10 overflow-y-auto w-full">
        <header class="flex justify-between items-center mb-10 w-full col-span-full">
            <div>
                <h1 class="text-3xl font-black text-white">@yield('page_title', 'Dashboard')</h1>
                <p class="text-zinc-400 font-medium mt-1">@yield('page_subtitle', 'Selamat datang kembali, Admin!')</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="font-bold text-white">Admin</p>
                    <p class="text-xs text-zinc-500 font-bold uppercase tracking-wider">Superadmin</p>
                </div>
                <div class="w-12 h-12 bg-zinc-800 rounded-2xl shadow-sm border border-zinc-700 flex items-center justify-center p-1">
                    <img src="https://ui-avatars.com/api/?name=admin&background=6366f1&color=fff" class="rounded-xl">
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl mb-6 font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>