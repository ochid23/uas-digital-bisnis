<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-zinc-950 text-white min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-zinc-900 text-zinc-100 rounded-[2rem] p-8 shadow-2xl border border-zinc-800">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4 shadow-lg shadow-indigo-500/20">AH</div>
            <h1 class="text-2xl font-black text-white">Login</h1>
            <p class="text-zinc-400 mt-1">naazhi event hub</p>
        </div>

        @if(session('error'))
            <div class="bg-red-900/50 border border-red-800 text-red-400 p-4 rounded-xl mb-6 font-bold text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-zinc-400 mb-2 uppercase tracking-wide">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-5 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium text-white placeholder:text-zinc-600" required>
                @error('email')
                    <p class="text-red-400 text-sm mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-zinc-400 mb-2 uppercase tracking-wide">Password</label>
                <input type="password" name="password" class="w-full px-5 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium text-white placeholder:text-zinc-600" required>
            </div>
            <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 transition-all active:scale-95">Masuk</button>
        </form>

        <!-- Tambahan Fitur 1: Tombol SSO Google -->
        <div class="mt-6 flex items-center justify-between">
            <span class="border-b border-zinc-800 w-1/5 lg:w-1/4"></span>
            <span class="text-[10px] text-center text-zinc-500 uppercase font-bold tracking-wider">Atau masuk dengan</span>
            <span class="border-b border-zinc-800 w-1/5 lg:w-1/4"></span>
        </div>
        
        <a href="{{ route('google.login') }}" class="mt-6 w-full flex items-center justify-center gap-3 py-4 bg-zinc-950 border border-zinc-800 text-zinc-300 rounded-2xl font-bold text-lg hover:bg-zinc-800 transition-all shadow-sm">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-6 h-6" alt="Google">
            Continue with Google
        </a>

    </div>
</body>
</html>