<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold dark:text-white">Manajemen Kategori</h2>
        @if(auth()->user()->role === 'admin')
        <button wire:click="openModal" class="flex items-center  bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Kategori Baru
        </button>
        @endif
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-4">
        <div class="w-full md:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari SKU atau Nama Obat..." 
                    class="w-full pl-10 p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-medium text-gray-800 dark:text-white transition-all">
            </div>
    </div>


    <div class="bg-white dark:bg-slate-800 shadow-md rounded-xl overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-700 dark:text-slate-300 uppercase text-xs font-bold">
                <tr>
                    <th class="px-6 py-4">Nama Kategori</th>
                    @if(auth()->user()->role === 'admin')
                    <th class="px-6 py-4 text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($categories as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                    <td class="px-6 py-4 dark:text-slate-300 font-medium">{{ $item->name }}</td>
                    @if(auth()->user()->role === 'admin')
                    <td class="px-6 py-4 text-center">
                            <button wire:click="edit({{ $item->id }})" class="text-blue-500 hover:text-blue-700 mx-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </button>
                            <button onclick="confirm('Hapus kategori ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $item->id }})" class="text-red-500 hover:text-red-700 mx-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </td>
                        @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $categories->links() }}</div>
    </div>

    @if($isModalOpen)
    @if(auth()->user()->role === 'admin')
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            
            <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-slate-700">
                
                <div class="p-5 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="text-lg font-black text-gray-800 dark:text-gray-100">Tambah Kategori</h3>
                </div>
                
                <div class="p-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Kategori</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition text-sm">
                </div>
                
                <div class="p-5 bg-gray-50 dark:bg-slate-900/50 border-t border-gray-100 dark:border-slate-700 flex justify-end gap-3">
                    <button wire:click="closeModal" class="px-5 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition">Batal</button>
                    <button wire:click="save" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">Simpan</button>
                </div>
                
            </div>
        </div>
    @endif
    @endif
</div>