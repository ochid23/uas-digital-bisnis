@extends('layouts.admin')
@section('title', 'Kelola Pengguna - naazhi.')
@section('page_title', 'Kelola Pengguna')
@section('page_subtitle', 'Atur hak akses dan role pengguna naazhi. di sini.')

@section('content')
<div class="bg-zinc-900 rounded-[2.5rem] border border-zinc-800 shadow-lg shadow-black/20 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-zinc-950/50 text-zinc-500 uppercase text-[10px] font-black tracking-widest border-b border-zinc-800">
                <tr>
                    <th class="px-8 py-5 w-16">No</th>
                    <th class="px-8 py-5">Pengguna</th>
                    <th class="px-8 py-5">Role Saat Ini</th>
                    <th class="px-8 py-5">Ubah Role</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @forelse($users as $user)
                <tr class="hover:bg-zinc-800/30 transition-all duration-200">
                    {{-- Menggunakan $loop->iteration agar aman dari error jika controller tidak memakai paginate() --}}
                    <td class="px-8 py-6 font-bold text-zinc-500">{{ $loop->iteration }}</td>
                    
                    <td class="px-8 py-6">
                        <p class="font-black text-white text-base mb-0.5">{{ $user->name }}</p>
                        <p class="text-xs text-zinc-500 font-medium">{{ $user->email }}</p>
                    </td>
                    
                    <td class="px-8 py-6">
                        @if($user->role === 'admin')
                            <span class="inline-block px-3 py-1.5 bg-rose-500/10 text-rose-400 rounded-full text-[10px] font-bold uppercase tracking-widest ring-1 ring-rose-500/20 shadow-sm shadow-rose-500/10">
                                Admin
                            </span>
                        @elseif($user->role === 'organizer')
                            <span class="inline-block px-3 py-1.5 bg-indigo-500/10 text-indigo-400 rounded-full text-[10px] font-bold uppercase tracking-widest ring-1 ring-indigo-500/20 shadow-sm shadow-indigo-500/10">
                                Organizer
                            </span>
                        @else
                            <span class="inline-block px-3 py-1.5 bg-zinc-800/50 text-zinc-400 rounded-full text-[10px] font-bold uppercase tracking-widest ring-1 ring-zinc-700/50 shadow-sm">
                                User
                            </span>
                        @endif
                    </td>
                    
                    <td class="px-8 py-6">
                        <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            @method('PUT')
                            
                            {{-- Dropdown elegan bergaya Tailwind Dark Mode --}}
                            <select name="role" class="bg-zinc-950 border-none ring-1 ring-zinc-800 text-zinc-300 text-sm rounded-xl focus:ring-1 focus:ring-indigo-500 focus:outline-none block p-2.5 font-medium transition-all cursor-pointer shadow-sm">
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                <option value="organizer" {{ $user->role == 'organizer' ? 'selected' : '' }}>Organizer</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            
                            {{-- Tombol simpan yang senada dengan tombol edit di halaman lain --}}
                            <button type="submit" class="px-5 py-2.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm font-bold text-sm">
                                Simpan
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-16 text-center">
                        <div class="w-16 h-16 bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-4 border border-zinc-700/50">
                            <svg class="w-8 h-8 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <p class="text-zinc-500 font-medium">Belum ada pengguna yang terdaftar.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mengecek apakah pagination aktif di controller agar tidak error --}}
    @if(method_exists($users, 'links'))
    <div class="px-8 py-5 bg-zinc-950/30 border-t border-zinc-800 items-center">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection