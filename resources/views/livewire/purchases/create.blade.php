<div class="p-6 lg:p-8 bg-gray-50 min-h-screen font-sans">
    
    <style>
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('purchases.index') }}" class="p-2.5 bg-white dark:bg-slate-800 text-gray-500 hover:text-indigo-600 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-100 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Stok Masuk (Pembelian)</h2>
            <p class="text-sm text-gray-500 font-medium">Scan barang masuk atau Import Excel dari PBF/Supplier</p>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex flex-col xl:grid xl:grid-cols-3 gap-6">
        
        <div class="xl:col-span-2 flex flex-col gap-6">
            
            <div class="bg-indigo-600 p-6 rounded-2xl shadow-lg shadow-indigo-200 relative z-50">
                <label class="block text-sm font-bold text-indigo-100 mb-2">Scan Barcode / Cari Nama Obat</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M3 5v14"/><path d="M8 5v14"/><path d="M12 5v14"/><path d="M17 5v14"/><path d="M21 5v14"/></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Mulai scan barcode kemasan..." autofocus
                        class="w-full pl-12 p-3.5 bg-white  dark:bg-slate-800 rounded-xl outline-none focus:ring-4 focus:ring-indigo-400 transition-all font-bold text-gray-800 text-lg shadow-inner">
                </div>

                @if(strlen($searchQuery) >= 2)
                    <div class="absolute w-full left-0 mt-2 bg-white dark:bg-slate-800 border border-gray-100 rounded-xl shadow-2xl overflow-hidden z-50">
                        @forelse($this->searchResults as $product)
                            <button wire:click="addToCart({{ $product->id }})" class="w-full text-left px-5 py-4 hover:bg-indigo-50 transition border-b border-gray-50 flex justify-between items-center group">
                                <div>
                                    <span class="font-bold text-gray-800 group-hover:text-indigo-700 block">{{ $product->name }}</span>
                                    <span class="text-xs text-gray-400 font-mono">SKU: {{ $product->sku }}</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-300 group-hover:text-indigo-600"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                            </button>
                        @empty
                            <div class="px-5 py-6 text-center text-gray-500 text-sm">Obat tidak ditemukan di Master Produk.</div>
                        @endforelse
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 flex-1 overflow-hidden flex flex-col">
                <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2 uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-500"><path d="M16 16h6"/><path d="M19 13v6"/><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/><path d="m7.5 4.27 9 5.15"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>
                        Detail Barang Diterima
                    </h3>
                </div>

                <div class="overflow-x-auto min-h-[300px]">
                    <table class="w-full text-left border-collapse min-w-[950px]">
                        <thead class="bg-white dark:bg-slate-800 border-b text-xs text-gray-400 uppercase">
                            <tr>
                                <th class="px-5 py-4 font-bold min-w-[200px]">Obat</th>
                                <th class="px-3 py-4 font-bold w-24 text-center">Qty</th>
                                <th class="px-3 py-4 font-bold min-w-[160px] text-right">Harga Beli</th>
                                <th class="px-3 py-4 font-bold min-w-[140px] text-red-500 bg-red-50/30">No. Batch *</th>
                                <th class="px-3 py-4 font-bold min-w-[140px] text-red-500 bg-red-50/30">Exp Date *</th>
                                <th class="px-4 py-4 font-bold text-right w-32">Subtotal</th>
                                <th class="px-3 py-4 w-12 sticky right-0 bg-white dark:bg-slate-800 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.03)]"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($items as $index => $item)
                            <tr wire:key="item-{{ $index }}" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-4 font-bold text-gray-800 text-sm">{{ $item['name'] }}</td>
                                <td class="px-3 py-4">
                                    <input type="number" wire:model.blur="items.{{ $index }}.quantity" wire:change="syncItem({{ $index }})" min="1"
                                        class="w-full p-2 text-center text-sm font-bold border border-gray-200 rounded-lg outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-gray-50">
                                </td>
                                <td class="px-3 py-4">
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs font-bold pointer-events-none">Rp</span>
                                        <input type="number" wire:model.blur="items.{{ $index }}.purchase_price" wire:change="syncItem({{ $index }})" min="0"
                                            class="w-full pl-8 p-2 text-right text-sm font-bold border border-gray-200 rounded-lg outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-gray-50">
                                    </div>
                                </td>
                                <td class="px-3 py-4 bg-red-50/10">
                                    <input type="text" wire:model.blur="items.{{ $index }}.batch_number"
                                        class="w-full p-2 text-xs border border-red-200 rounded-lg outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100 uppercase" placeholder="Ketik Batch">
                                </td>
                                <td class="px-3 py-4 bg-red-50/10">
                                    <input type="date" wire:model.blur="items.{{ $index }}.expired_date"
                                        class="w-full p-2 text-xs border border-red-200 rounded-lg outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100 text-gray-700">
                                </td>
                                <td class="px-4 py-4 text-right font-black text-gray-800 text-sm">Rp{{ number_format($item['subtotal']) }}</td>
                                <td class="px-3 py-4 text-center sticky right-0 bg-white dark:bg-slate-800 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.03)]">
                                    <button wire:click="removeItem({{ $index }})" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-24 text-center">
                                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 mb-4 border border-dashed border-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-300"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                    </div>
                                    <p class="text-gray-500 font-bold text-lg">Belum ada barang di-scan</p>
                                    <p class="text-sm text-gray-400 mt-1">Gunakan kotak biru di atas atau panel Excel di kanan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 space-y-6">
            
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Supplier / PBF *</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M10 17h4V5H2v12h3"/><path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5v8h2"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                    </div>
                    <select wire:model="supplier_id" class="w-full pl-10 p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 transition font-medium text-sm">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-emerald-500">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-bold text-gray-800">Import dari Excel</label>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/></svg>
                </div>
                <p class="text-[11px] text-gray-500 font-mono mb-4 bg-gray-50 p-2 rounded-lg border border-gray-100">
                    Format (Kolom A-E):<br>
                    SKU | Qty | Harga Beli | Batch | Exp Date
                </p>

                <div class="flex flex-col gap-3">
                    <input type="file" wire:model.live="excelFile" accept=".xlsx,.xls,.csv"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition cursor-pointer">
                    
                    <button wire:click="importExcel" 
                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-md"
                        {{ !$excelFile ? 'disabled' : '' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                        TARIK DATA EXCEL
                    </button>

                    <div wire:loading wire:target="excelFile" class="text-xs text-center text-gray-500 font-bold mt-1">
                        <span class="animate-pulse">Mengunggah file...</span>
                    </div>
                    <div wire:loading wire:target="importExcel" class="text-xs text-center text-emerald-600 font-bold mt-1">
                        <span class="animate-pulse">Membaca & Memvalidasi data...</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total Tagihan Faktur</p>
                <p class="text-4xl font-black text-indigo-600 mb-6">Rp{{ number_format($this->total) }}</p>
                
                <hr class="border-dashed border-gray-200 mb-6">

                <button wire:click="saveTransaction" 
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm rounded-xl shadow-md transition-all flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ empty($items) ? 'disabled' : '' }}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg>
                    SIMPAN STOK MASUK
                </button>
            </div>
            
        </div>

    </div>
</div>