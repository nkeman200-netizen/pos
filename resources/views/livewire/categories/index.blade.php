<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold dark:text-white">Manajemen Kategori</h2>
        <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold transition">
            <i class="fas fa-plus mr-2"></i> Kategori Baru
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Cari kategori..." class="w-full md:w-1/3 rounded-lg border-gray-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
    </div>

    <div class="bg-white dark:bg-slate-800 shadow-md rounded-xl overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-700 dark:text-slate-300 uppercase text-xs font-bold">
                <tr>
                    <th class="px-6 py-4">Nama Kategori</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($categories as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                    <td class="px-6 py-4 dark:text-slate-300 font-medium">{{ $item->name }}</td>
                    <td class="px-6 py-4 text-center">
                        <button wire:click="edit({{ $item->id }})" class="text-blue-500 hover:text-blue-700 mx-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </button>
                        <button onclick="confirm('Hapus kategori ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $item->id }})" class="text-red-500 hover:text-red-700 mx-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $categories->links() }}</div>
    </div>

    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl w-full max-w-md shadow-2xl">
            <h3 class="text-lg font-bold mb-4 dark:text-white">{{ $selected_id ? 'Edit' : 'Tambah' }} Kategori</h3>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2 dark:text-slate-400">Nama Kategori</label>
                <input type="text" wire:model="name" class="w-full rounded-lg border-gray-300 dark:bg-slate-900 dark:border-slate-700 dark:text-white">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end gap-2">
                <button wire:click="closeModal" class="px-4 py-2 text-gray-500 hover:text-gray-700">Batal</button>
                <button wire:click="store" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold">Simpan</button>
            </div>
        </div>
    </div>
    @endif
</div>