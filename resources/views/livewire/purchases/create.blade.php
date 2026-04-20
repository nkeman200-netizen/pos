<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    <style>
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('purchases.index') }}" class="p-2.5 bg-white dark:bg-slate-800 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Stok Masuk (Pembelian)</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Scan barang masuk atau Import Excel dari PBF/Supplier</p>
            </div>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-500/10 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded-r-xl flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border-l-4 border-green-500 text-green-700 dark:text-green-400 rounded-r-xl flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <div class="xl:col-span-2 space-y-6">
            <div class="mb-6 bg-indigo-50 dark:bg-indigo-500/10 p-4 rounded-xl border border-indigo-100 dark:border-indigo-500/20">
                <label class="block text-sm font-bold text-indigo-800 dark:text-indigo-300 mb-2 tracking-wide">Tarik Data dari Purchase Order (Otomatis)</label>
                <select wire:model.live="purchase_order_id" class="w-full p-3 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-bold shadow-sm transition-colors">
                    <option value="">-- Ketik/Pilih Nomor PO --</option>
                    @if(isset($purchaseOrders))
                        @foreach($purchaseOrders as $po)
                            <option value="{{ $po->id }}">{{ $po->po_number }} - {{ $po->supplier->name ?? 'Tanpa Nama' }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row gap-4 relative z-50">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M3 7V5c0-1.1.9-2 2-2h2"/><path d="M17 3h2c1.1 0 2 .9 2 2v2"/><path d="M21 17v2c0 1.1-.9 2-2 2h-2"/><path d="M7 21H5c-1.1 0-2-.9-2-2v-2"/><rect width="7" height="5" x="7" y="7" rx="1"/><rect width="7" height="5" x="7" y="12" rx="1"/></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Ketik nama obat atau scan SKU..." 
                        class="w-full pl-12 p-3.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white transition-colors text-sm font-bold">
                    
                    @if(!empty($searchQuery))
                        <div class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-xl rounded-xl overflow-hidden max-h-60 overflow-y-auto">
                            @forelse($this->searchResults as $product)
                                <div wire:click="addToCart({{ $product->id }})" class="p-3 border-b border-gray-50 dark:border-slate-700/50 hover:bg-indigo-50 dark:hover:bg-indigo-500/20 cursor-pointer transition flex justify-between items-center group">
                                    <div>
                                        <div class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-indigo-700 dark:group-hover:text-indigo-300">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">SKU: {{ $product->sku }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs font-bold text-gray-400 dark:text-gray-500">Stok: {{ $product->stock }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-gray-500 dark:text-gray-400 text-sm font-medium">Obat tidak ditemukan.</div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden relative z-10">
                <div class="overflow-x-auto min-h-[300px]">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-xs text-gray-400 uppercase font-black tracking-wider">
                            <tr>
                                <th class="px-5 py-4 w-10">No</th>
                                <th class="px-5 py-4 min-w-[200px]">Obat</th>
                                <th class="px-3 py-4 w-32 text-center">Batch & ED</th>
                                <th class="px-3 py-4 w-28 text-center">Qty</th>
                                <th class="px-4 py-4 w-36 text-right">Harga Beli</th>
                                <th class="px-4 py-4 w-36 text-right">Subtotal</th>
                                <th class="px-4 py-4 w-12 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                            @forelse($items as $index => $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-5 py-4 text-sm font-bold text-gray-400 dark:text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-sm text-gray-800 dark:text-gray-100">{{ $item['name'] }}</div>
                                </td>
                                <td class="px-3 py-4 space-y-2">
                                    <input type="text" wire:model.blur="items.{{ $index }}.batch_number" placeholder="No. Batch" 
                                        class="w-full p-2 text-xs font-bold bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg outline-none focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white transition-colors text-center">
                                    <input type="date" wire:model.blur="items.{{ $index }}.expired_date" 
                                        class="w-full p-2 text-xs font-bold bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg outline-none focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white transition-colors text-center [color-scheme:light] dark:[color-scheme:dark]">
                                </td>
                                <td class="px-3 py-4">
                                    <div class="flex items-center justify-center bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg overflow-hidden">
                                        <input type="number" wire:model.live="items.{{ $index }}.quantity" class="w-12 p-2 text-center text-sm font-bold bg-transparent outline-none dark:text-white" min="1">
                                    </div>
                                </td>
                                <td class="px-4 py-4 min-w-[150px]"> <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400 dark:text-gray-500 text-xs font-bold">Rp</span>
                                    </div>
                                    <input type="number" 
                                        wire:model.live.debounce.500ms="items.{{ $index }}.purchase_price" 
                                        class="w-full pl-9 p-2.5 text-sm font-bold bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg outline-none focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white transition-colors text-right font-mono" min="0">
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right font-black text-indigo-600 dark:text-indigo-400 text-sm font-mono">Rp{{ number_format($item['subtotal']) }}</td>
                                <td class="px-4 py-4 text-center">
                                    <button wire:click="removeItem({{ $index }})" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/20 rounded-lg transition" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-24 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 dark:bg-slate-900 border-2 border-dashed border-gray-200 dark:border-slate-700 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 dark:text-gray-500"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-bold text-sm">Keranjang penerimaan kosong.</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Scan obat, cari nama, tarik data PO, atau Import Excel.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 space-y-6">
            
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M8 13h2"/><path d="M8 17h2"/><path d="M14 13h2"/><path d="M14 17h2"/></svg>
                </div>
                <h3 class="font-black text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2 relative z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600 dark:text-indigo-400"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                    Import dari Excel
                </h3>
                
                <div class="relative z-10">
                    <input type="file" wire:model="excelFile" accept=".xlsx, .xls" class="block w-full text-sm text-gray-500 dark:text-gray-400
                        file:mr-4 file:py-2.5 file:px-4
                        file:rounded-xl file:border-0
                        file:text-sm file:font-bold
                        file:bg-indigo-50 file:text-indigo-700
                        dark:file:bg-indigo-500/10 dark:file:text-indigo-400
                        hover:file:bg-indigo-100 dark:hover:file:bg-indigo-500/20 transition-colors
                        cursor-pointer mb-3"
                        wire:loading.attr="disabled"
                    />
                    
                    <div wire:loading wire:target="excelFile" class="text-xs text-center text-emerald-600 dark:text-emerald-400 font-bold mt-1 mb-2">
                        <span class="animate-pulse">Membaca & Memvalidasi data Excel...</span>
                    </div>

                    <a href="#" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium inline-block mt-1">Download Template Excel (.xlsx)</a>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 space-y-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">PBF / Supplier <span class="text-red-500">*</span></label>
                    <select wire:model="supplier_id" class="w-full p-3.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tanggal Terima <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="purchase_date" class="w-full p-3.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold [color-scheme:light] dark:[color-scheme:dark]">
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 text-center">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Total Tagihan Faktur</p>
                <p class="text-4xl font-black text-indigo-600 dark:text-indigo-400 mb-6 font-mono">Rp{{ number_format($this->total) }}</p>
                
                <hr class="border-dashed border-gray-200 dark:border-slate-700 mb-6">

                <button wire:click="saveTransaction" 
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm rounded-xl shadow-md transition-all flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ empty($items) ? 'disabled' : '' }}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11.2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg>
                    SIMPAN & UPDATE STOK
                </button>
            </div>
        </div>
    </div>
</div>