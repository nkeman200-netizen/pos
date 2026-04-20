<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('customers.index') }}" class="p-2.5 bg-white dark:bg-slate-800 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 hover:border-indigo-100 dark:hover:border-indigo-500/50 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Data Pelanggan</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Form update profil pelanggan apotek</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm rounded-2xl overflow-hidden transition-colors">
        <div class="bg-indigo-600 p-4">
            <p class="text-indigo-100 text-sm font-medium">Perbarui detail informasi pelanggan di bawah ini.</p>
        </div>

        <form wire:submit="update" class="p-6 sm:p-8 space-y-5">
            
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Pelanggan</label>
                <input type="text" id="name" wire:model="name"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white outline-none transition text-sm"> 
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">No. HP / WhatsApp</label>
                <input type="text" id="phone" wire:model="phone"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white outline-none transition text-sm"> 
                @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                <textarea id="address" wire:model="address" rows="3"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white outline-none transition text-sm"></textarea>
                @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 mt-2 border-t border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row gap-3">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all flex justify-center items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i> Update Pelanggan
                </button>
            </div>
        </form>
    </div>
</div>