<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Profil Apotek</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola informasi dasar apotek, logo, dan metode QRIS.</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border-l-4 border-green-500 text-green-700 dark:text-green-400 rounded-r-xl shadow-sm">
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="save" class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-slate-700 p-8 max-w-4xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-400 uppercase mb-4">Logo Apotek</label>
                    <div class="flex flex-col items-center p-6 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-3xl">
                        @if ($logo)
                            <img src="{{ $logo->temporaryUrl() }}" class="h-32 w-32 object-contain mb-4">
                        @elseif ($existingLogo)
                            <img src="{{ asset('storage/' . $existingLogo) }}" class="h-32 w-32 object-contain mb-4">
                        @else
                            <div class="h-32 w-32 bg-gray-100 dark:bg-slate-900 rounded-2xl flex items-center justify-center text-gray-300 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif
                        <input type="file" wire:model="logo" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    </div>
                </div>

                <div class="p-6 bg-indigo-50 dark:bg-indigo-500/5 rounded-3xl border border-indigo-100 dark:border-indigo-500/20">
                    <label class="block text-sm font-bold text-indigo-600 mb-2">QRIS Data String</label>
                    <textarea wire:model="qris_string" rows="4" class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-indigo-200 dark:border-slate-600 rounded-2xl text-xs font-mono outline-none" placeholder="Masukkan kode teks QRIS di sini..."></textarea>
                    <p class="mt-2 text-[10px] text-indigo-400">Scan QRIS toko Anda dengan aplikasi pembaca barcode biasa, lalu copy teks panjangnya ke sini.</p>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-400 uppercase mb-2">Nama Apotek</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-4 bg-gray-50 dark:bg-slate-900 border-none rounded-2xl dark:text-white font-bold outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-400 uppercase mb-2">Alamat</label>
                    <textarea wire:model="address" rows="3" class="w-full px-4 py-4 bg-gray-50 dark:bg-slate-900 border-none rounded-2xl dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 transition"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-400 uppercase mb-2">Apoteker (APJ)</label>
                        <input type="text" wire:model="apoteker_name" class="w-full px-4 py-4 bg-gray-50 dark:bg-slate-900 border-none rounded-2xl dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-400 uppercase mb-2">SIPA</label>
                        <input type="text" wire:model="sipa_number" class="w-full px-4 py-4 bg-gray-50 dark:bg-slate-900 border-none rounded-2xl dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 pt-6 border-t dark:border-slate-700 flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-10 rounded-2xl shadow-lg shadow-indigo-200 dark:shadow-none transition-all active:scale-95">
                SIMPAN PERUBAHAN
            </button>
        </div>
    </form>
</div>