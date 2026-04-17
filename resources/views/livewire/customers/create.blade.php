<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('customers.index') }}" class="p-2.5 bg-white dark:bg-slate-800 text-gray-500 hover:text-indigo-600 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-100 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Data Pelanggan</h2>
                <p class="text-sm text-gray-500">Form input profil pelanggan</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto bg-white dark:bg-slate-800 border border-gray-100 shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-indigo-600 p-4">
            <p class="text-indigo-100 text-sm font-medium">Edit detail informasi pelanggan di bawah ini.</p>
        </div>

        <form wire:submit="save" class="p-6 space-y-5">
            
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Pelanggan</label>
                <input type="text" id="name" wire:model="name"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon</label>
                <input type="text" id="phone" wire:model="phone"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"> 
                @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                <textarea id="address" wire:model="address" rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"></textarea>
                @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-200 transition transform active:scale-95 flex justify-center items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i> Simpan Pelanggan
                </button>
                
                <button type="button" wire:click="resetForm" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl transition">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>