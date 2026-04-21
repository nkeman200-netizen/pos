<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen font-sans transition-colors duration-300" 
     x-data="{ 
        showModal: false,
        confirmDelete: false
     }"
     @open-user-modal.window="showModal = true; $nextTick(() => $refs.nameInput.focus())"
     @close-user-modal.window="showModal = false"
     @keydown.escape.window="showModal = false">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Manajemen Karyawan</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Otoritas penuh Admin untuk mengelola akun, reset sandi, dan PIN kasir.</p>
        </div>
        <button type="button" @click="$wire.resetFields(); showModal = true; $nextTick(() => $refs.nameInput.focus())" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3.5 rounded-2xl font-black shadow-lg shadow-indigo-500/20 flex items-center gap-2 transition-all active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Tambah Karyawan
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-2xl font-bold border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700">
                    <tr>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">Karyawan</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Jabatan / Role</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Status PIN</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                    @foreach($users as $user)
                    <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-500/5 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-indigo-600 font-black text-lg">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-black text-gray-800 dark:text-white leading-tight">{{ $user->name }}</p>
                                    <p class="text-xs font-bold text-gray-400 mt-1">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="px-4 py-1.5 text-[10px] font-black rounded-xl uppercase tracking-widest border
                                {{ $user->role === 'owner' ? 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20' : 
                                   ($user->role === 'admin' ? 'bg-indigo-50 text-indigo-600 border-indigo-200 dark:bg-indigo-500/10 dark:border-indigo-500/20' : 
                                   'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/20') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @if($user->pin)
                                <div class="flex items-center justify-center gap-1.5 text-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                                    <span class="text-xs font-black uppercase tracking-tighter">Aktif</span>
                                </div>
                            @else
                                <span class="text-xs font-black text-gray-300 uppercase tracking-tighter">Belum Set</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <button type="button" wire:click="editUser({{ $user->id }})" 
                                    class="p-3 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 rounded-xl shadow-sm border border-gray-100 dark:border-slate-600 hover:bg-indigo-600 hover:text-white transition-all active:scale-90">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4">
        <div x-show="showModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             @click.away="showModal = false" 
             class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-slate-700 w-full max-w-lg relative">
            
            <button @click="showModal = false" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>

            <div class="mb-8">
                <h3 class="text-2xl font-black text-gray-800 dark:text-white tracking-tight">
                    {{ $isEdit ? 'Perbarui Akun' : 'Karyawan Baru' }}
                </h3>
                <p class="text-sm text-gray-500 font-medium mt-1">Lengkapi data kredensial akses di bawah ini.</p>
            </div>
            
            <form wire:submit.prevent="saveUser" class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" x-ref="nameInput" 
                               class="w-full p-4 bg-gray-50 dark:bg-slate-900 border-none rounded-2xl dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 font-bold transition">
                        @error('name') <span class="text-red-500 text-[10px] font-black mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Hak Akses (Role)</label>
                        <select wire:model="role" class="w-full p-4 bg-gray-50 dark:bg-slate-900 border-none rounded-2xl font-black dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="kasir">KASIR</option>
                            <option value="admin">ADMIN</option>
                            <option value="owner">OWNER</option>
                        </select>
                        @error('role') <span class="text-red-500 text-[10px] font-black mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Alamat Email Login</label>
                    <input type="email" wire:model="email" 
                           class="w-full p-4 bg-gray-50 dark:bg-slate-900 border-none rounded-2xl dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 font-bold transition">
                    @error('email') <span class="text-red-500 text-[10px] font-black mt-1 block uppercase">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Password Login</label>
                        <input type="password" wire:model="password" placeholder="{{ $isEdit ? 'Kosongkan jika tak diubah' : 'Min. 8 Karakter' }}" 
                               class="w-full p-4 bg-gray-50 dark:bg-slate-900 border-none rounded-2xl dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 font-bold transition text-sm">
                        @error('password') <span class="text-red-500 text-[10px] font-black mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">PIN Otorisasi (6 Digit)</label>
                        <input type="password" wire:model="pin" maxlength="6" placeholder="Angka saja" 
                               class="w-full p-4 bg-gray-50 dark:bg-slate-900 border-none rounded-2xl dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 font-mono tracking-[0.5em] text-center transition">
                        @error('pin') <span class="text-red-500 text-[10px] font-black mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-6">
                    <button type="button" @click="showModal = false" 
                            class="flex-1 py-4 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-2xl font-black text-gray-600 dark:text-gray-200 transition">
                        BATAL
                    </button>
                    <button type="submit" 
                            class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black shadow-lg shadow-indigo-500/30 transition-all active:scale-95">
                        {{ $isEdit ? 'SIMPAN PERUBAHAN' : 'BUAT AKUN' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>