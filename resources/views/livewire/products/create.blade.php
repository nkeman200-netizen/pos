<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300" x-data="{ tab: 'manual' }">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}" class="p-2.5 bg-white dark:bg-slate-800 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 hover:border-indigo-100 dark:hover:border-indigo-500/50 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Registrasi Master Obat</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Pilih metode input profil obat ke dalam database apotek</p>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto mb-6 flex bg-white dark:bg-slate-800 rounded-xl p-1 shadow-sm border border-gray-100 dark:border-slate-700">
        <button type="button" @click="tab = 'manual'" :class="{ 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 shadow-sm': tab === 'manual', 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': tab !== 'manual' }" class="flex-1 py-3 text-sm font-bold rounded-lg transition-all focus:outline-none">
            Input Manual (Tunggal)
        </button>
        <button type="button" @click="tab = 'import'" :class="{ 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 shadow-sm': tab === 'import', 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': tab !== 'import' }" class="flex-1 py-3 text-sm font-bold rounded-lg transition-all focus:outline-none">
            Import Massal (Excel/CSV)
        </button>
        <button type="button" @click="tab = 'cloud'" :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shadow-sm': tab === 'cloud', 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': tab !== 'cloud' }" class="flex-1 py-3 text-sm font-bold rounded-lg transition-all focus:outline-none flex justify-center items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
            Tarik Cloud API
        </button>
    </div>

    <div x-show="tab === 'manual'" class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="bg-indigo-600 p-4 flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            <p class="text-indigo-100 text-sm font-medium leading-relaxed">
                <strong class="text-white">Info Penting:</strong> Di form ini Anda hanya mendaftarkan profil obat. <br>
                Untuk menambahkan <strong class="text-white">Stok, No. Batch, dan Expired Date</strong>, silakan lakukan melalui menu <b class="text-white">Stok Masuk (Purchases)</b>.
            </p>
        </div>

        <form wire:submit="save" class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sku" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">SKU / Barcode <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M3 5v14"/><path d="M8 5v14"/><path d="M12 5v14"/><path d="M17 5v14"/><path d="M21 5v14"/></svg>
                        </div>
                        <input type="text" id="sku" wire:model="sku" placeholder="Scan barcode kemasan..." 
                            class="w-full pl-10 p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white transition-colors text-sm font-mono">
                    </div>
                    @error('sku') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nama Obat <span class="text-red-500">*</span></label>
                    <input type="text" id="name" wire:model="name" placeholder="Contoh: Paracetamol 500mg" 
                        class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white transition-colors text-sm">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Golongan Obat <span class="text-red-500">*</span></label>
                    <select id="category_id" wire:model="category_id" 
                        class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white transition-colors text-sm">
                        <option value="">-- Pilih Golongan Obat --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="unit_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Satuan Kemasan <span class="text-red-500">*</span></label>
                    <select id="unit_id" wire:model="unit_id" 
                        class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white transition-colors text-sm">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->short_name }})</option>
                        @endforeach
                    </select>
                    @error('unit_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="selling_price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Harga Jual / Satuan <span class="text-red-500">*</span></label>
                <div class="relative w-full md:w-1/2">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400 text-sm font-bold">Rp</span>
                    </div>
                    <input 
                        x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                        type="text" id="selling_price" 
                        wire:model="selling_price" min="0" placeholder="0"
                        class="w-full pl-12 p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 transition-colors text-sm text-right font-mono font-bold text-gray-800 dark:text-white">
                </div>
                <p class="text-xs text-gray-400 mt-1.5">Harga yang akan dikenakan ke pelanggan di kasir.</p>
                @error('selling_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-6 mt-4 border-t border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row gap-3">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Simpan Master Obat
                </button>
                <button type="button" wire:click="resetForm" class="px-8 py-3.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-600 dark:text-gray-300 font-semibold rounded-xl transition-colors">
                    Reset Form
                </button>
            </div>
        </form>
    </div>

    <div x-show="tab === 'import'" x-cloak class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="bg-emerald-600 p-4 flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="M9 15l3-3 3 3"/></svg>
            <p class="text-emerald-100 text-sm font-medium leading-relaxed">
                <strong class="text-white">Fitur Cepat Import Data:</strong> Daftarkan ratusan hingga ribuan obat sekaligus menggunakan file CSV. Cocok untuk migrasi dari apotek lama atau data supplier.
            </p>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            
            @if (session()->has('error'))
                <div class="p-4 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl font-bold text-sm border border-red-200 dark:border-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                <div class="space-y-4">
                    <h3 class="font-black text-gray-800 dark:text-white">1. Siapkan Data CSV</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Download template ini. Buka dengan Microsoft Excel, lalu isi data obat sesuai format (jangan ubah baris pertama). Lalu <b>Save As -> CSV (Comma delimited)</b>.</p>
                    <button type="button" wire:click="downloadTemplate" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-800 dark:text-white text-sm font-bold rounded-xl transition shadow-sm border border-gray-200 dark:border-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        Download Template CSV
                    </button>
                </div>

                <div class="bg-amber-50 dark:bg-amber-900/10 p-4 rounded-xl border border-amber-200 dark:border-amber-700/30 text-sm">
                    <h4 class="font-bold text-amber-800 dark:text-amber-500 mb-2 border-b border-amber-200 dark:border-amber-700/50 pb-2">Kamus ID (Wajib Diisi di CSV)</h4>
                    
                    <p class="font-bold text-gray-800 dark:text-gray-300 text-[10px] uppercase tracking-widest mt-2">KODE GOLONGAN:</p>
                    <div class="h-16 overflow-y-auto custom-scrollbar mt-1 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                        @foreach($categories as $c)
                            <div class="flex justify-between"><span>{{ $c->name }}</span> <b class="text-indigo-600 dark:text-indigo-400">{{ $c->id }}</b></div>
                        @endforeach
                    </div>

                    <p class="font-bold text-gray-800 dark:text-gray-300 text-[10px] uppercase tracking-widest mt-3">KODE SATUAN:</p>
                    <div class="h-16 overflow-y-auto custom-scrollbar mt-1 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                        @foreach($units as $u)
                            <div class="flex justify-between"><span>{{ $u->name }}</span> <b class="text-indigo-600 dark:text-indigo-400">{{ $u->id }}</b></div>
                        @endforeach
                    </div>
                </div>
            </div>

            <form wire:submit="importCsv" class="border-t border-gray-100 dark:border-slate-700 pt-6">
                <h3 class="font-black text-gray-800 dark:text-white mb-4">2. Upload File CSV</h3>
                
                <div class="mb-5">
                    <input type="file" wire:model="csvFile" accept=".csv" 
                        class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-3.5 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-slate-700 dark:file:text-white transition-all cursor-pointer border-2 border-dashed border-emerald-200 dark:border-emerald-900/50 rounded-xl p-2 bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-xs text-gray-400 mt-2 italic">Format wajib .csv (Maksimal 5MB).</p>
                    @error('csvFile') <span class="text-red-500 text-xs mt-2 block font-bold">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-md hover:shadow-lg transition-all flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="importCsv" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                        Mulai Proses Import
                    </span>
                    <span wire:loading wire:target="importCsv" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Menyuntikkan Ratusan Data ke Database...
                    </span>
                </button>
            </form>
        </div>
    </div>
    
    <div x-show="tab === 'cloud'" x-cloak class="max-w-4xl mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-blue-600 p-8 text-center text-white">
            <svg class="w-16 h-16 mx-auto mb-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <h2 class="text-2xl font-black mb-2">Cloud Master Data Center</h2>
            <p class="text-blue-100 font-medium">Tarik ribuan data obat resmi dari Server Pusat secara otomatis.</p>
        </div>
        
        <div class="p-8 text-center bg-blue-50/50">
            <button wire:click="syncFromCloud" class="inline-flex justify-center items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all hover:-translate-y-1" wire:loading.attr="disabled">
                
                <span wire:loading.remove wire:target="syncFromCloud" class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Mulai Sinkronisasi Data
                </span>

                <span wire:loading wire:target="syncFromCloud" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Menyambungkan ke Server Pusat...
                </span>
            </button>
            <p class="text-xs text-gray-500 mt-4 font-bold tracking-widest uppercase">Target API: http://obat-api-center.test</p>
        </div>
    </div>
</div>