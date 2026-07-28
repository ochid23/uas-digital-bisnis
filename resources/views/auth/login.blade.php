<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-zinc-950 text-white min-h-screen flex items-center justify-center p-6 antialiased">
    <div class="max-w-md w-full bg-zinc-900 text-zinc-100 rounded-[2.5rem] p-8 sm:p-10 shadow-2xl border border-zinc-800 my-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3 group mb-4">
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl mx-auto shadow-xl shadow-indigo-500/25 group-hover:scale-105 transition">AE</div>
            </a>
            <h1 class="text-2xl font-black text-white tracking-tight">Selamat Datang Kembali</h1>
            <p class="text-zinc-400 text-sm mt-1">Masuk ke akun AmikomEventHub Anda</p>
        </div>

        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-2xl mb-6 font-bold text-xs text-center">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl mb-6 font-bold text-xs text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Login Manual Email & Password -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-zinc-400 mb-2 uppercase tracking-wide">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@gmail.com" class="w-full px-5 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium text-white text-sm placeholder:text-zinc-600" required>
                @error('email')
                    <p class="text-rose-400 text-xs mt-1.5 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-zinc-400 mb-2 uppercase tracking-wide">Password</label>
                <input type="password" name="password" placeholder="Masukkan password Anda" class="w-full px-5 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium text-white text-sm placeholder:text-zinc-600" required>
            </div>

            <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-base shadow-lg shadow-indigo-600/25 hover:bg-indigo-500 transition-all active:scale-95">
                Masuk Akun
            </button>
        </form>

        <!-- Separator SSO -->
        <div class="mt-6 flex items-center justify-between">
            <span class="border-b border-zinc-800 w-1/4"></span>
            <span class="text-[10px] text-center text-zinc-500 uppercase font-extrabold tracking-wider">Atau masuk dengan</span>
            <span class="border-b border-zinc-800 w-1/4"></span>
        </div>
        
        <!-- Opsi Login dengan Google -->
        <a href="{{ route('google.login') }}" class="mt-6 w-full flex items-center justify-center gap-3 py-4 bg-zinc-950 border border-zinc-800 text-zinc-200 rounded-2xl font-bold text-sm hover:bg-zinc-800 transition-all shadow-sm">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
            Masuk dengan Google
        </a>

        <!-- Link ke Halaman Registrasi -->
        <div class="mt-8 pt-6 border-t border-zinc-800/80 text-center">
            <p class="text-xs text-zinc-400 font-medium">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-black transition ml-1 underline decoration-indigo-500/30">
                    Daftar Akun Baru
                </a>
            </p>
        </div>

    </div>
</body>
</html>