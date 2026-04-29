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
                <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Stok Masuk (Purchase)</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Catat faktur pembelian dari PBF/Supplier</p>
            </div>
        </div>
    </div>
    <div class="mt-6">
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-2xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 rounded-2xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span class="text-sm font-bold">{{ session('error') }}</span>
            </div>
        @endif
    </div>
    
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        <div class="xl:col-span-3 space-y-6">
            
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col md:flex-row gap-4 items-center">
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" class="text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Cari nama obat atau scan barcode master..." 
                        class="w-full pl-11 p-3.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 font-bold dark:text-white text-sm">
                    
                    @if(!empty($this->searchResults))
                        <div class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-2xl rounded-xl overflow-hidden">
                            @foreach($this->searchResults as $res)
                                <div wire:click="addToCart({{ $res->id }})" class="p-4 border-b border-gray-50 dark:border-slate-700/50 hover:bg-indigo-50 dark:hover:bg-indigo-500/20 cursor-pointer flex justify-between items-center group">
                                    <div>
                                        <div class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-indigo-600">{{ $res->name }}</div>
                                        <div class="text-xs text-gray-400 font-mono">SKU: {{ $res->sku }}</div>
                                    </div>
                                    <button class="text-xs font-black text-indigo-600 uppercase">Tambah</button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <label class="w-full sm:w-auto cursor-pointer px-5 py-3.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 font-bold rounded-xl text-xs flex items-center justify-center gap-2 transition-all border border-emerald-100 dark:border-emerald-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Import Excel
                        <input type="file" wire:model.live="excelFile" class="hidden" accept=".csv,.xlsx,.xls">
                    </label>

                    <label class="w-full sm:w-auto cursor-pointer px-5 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        AI Scanner
                        <input type="file" wire:model.live="receiptImage" class="hidden" accept="image/*">
                    </label>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-[11px] text-gray-500 uppercase font-black tracking-wider">
                            <tr>
                                <th class="px-4 py-4 w-12 text-center">No</th>
                                <th class="px-4 py-4 min-w-[200px]">Detail Obat</th>
                                <th class="px-4 py-4 w-40 text-center">Batch & ED</th>
                                <th class="px-4 py-4 w-24 text-center">Qty</th>
                                <th class="px-4 py-4 w-36 text-right">Harga Beli</th>
                                <th class="px-4 py-4 w-32 text-right text-red-500">Disc (Rp)</th>
                                <th class="px-4 py-4 w-36 text-right">Subtotal</th>
                                <th class="px-4 py-4 w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                            @forelse($items as $index => $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors {{ $item['product_id'] == null ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                <td class="px-4 py-4 text-center text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 relative">
                                    <div class="font-bold text-sm text-gray-800 dark:text-gray-100 leading-tight">{{ $item['name'] }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-wider">{{ $item['unit_name'] }}</div>
                                    
                                    @if($item['product_id'] == null)
                                        <div class="mt-2">
                                            
                                            @if($activeRowIndex === $index)
                                                <div class="relative w-full">
                                                    <input type="text" wire:model.live.debounce.300ms="rowSearchQuery" placeholder="Ketik nama di master..." 
                                                        class="w-full p-2 text-xs font-bold bg-white dark:bg-slate-900 border-2 border-indigo-400 dark:border-indigo-500 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500 shadow-lg text-gray-800 dark:text-white transition-all">
                                                    
                                                    @if(!empty($rowSearchResults))
                                                        <div class="absolute z-[40] w-[250px] mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 shadow-xl rounded-lg overflow-hidden">
                                                            @foreach($rowSearchResults as $res)
                                                                <div wire:click="updateProductMapping({{ $index }}, {{ $res->id }})" class="p-3 border-b border-gray-50 dark:border-slate-700/50 hover:bg-indigo-50 dark:hover:bg-indigo-500/20 cursor-pointer flex justify-between items-center group transition">
                                                                    <div>
                                                                        <div class="font-bold text-gray-800 dark:text-gray-100 text-xs group-hover:text-indigo-600">{{ $res->name }}</div>
                                                                        <div class="text-[10px] text-gray-400 font-mono">{{ $res->sku }}</div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>

                                            @else
                                                <button wire:click="openRowSearch({{ $index }})" class="text-[10px] bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20 px-2.5 py-1.5 rounded-md font-bold flex items-center gap-1.5 transition-colors border border-red-200 dark:border-red-500/20 w-full justify-center shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                    Belum Terdaftar! Klik untuk Link
                                                </button>
                                            @endif
                                            
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 space-y-2">
                                    <input type="text" wire:model.blur="items.{{ $index }}.batch_number" placeholder="No. Batch" 
                                        class="w-full p-2 text-[11px] font-bold bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg outline-none focus:border-indigo-500 text-center">
                                    <input type="date" wire:model.blur="items.{{ $index }}.expired_date" 
                                        class="w-full p-2 text-[11px] font-bold bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg outline-none focus:border-indigo-500 text-center [color-scheme:light] dark:[color-scheme:dark]">
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" wire:model.live="items.{{ $index }}.quantity" 
                                        class="w-full p-2 text-center text-sm font-black bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500" min="1">
                                </td>
                                <td class="px-4 py-4"> 
                                    <input type="number" wire:model.live.debounce.500ms="items.{{ $index }}.purchase_price" 
                                        class="w-full p-2 text-sm font-bold bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500 text-right font-mono" min="0">
                                </td>
                                <td class="px-4 py-4"> 
                                    <input type="number" wire:model.live.debounce.500ms="items.{{ $index }}.discount" 
                                        class="w-full p-2 text-sm font-bold bg-red-50/30 dark:bg-red-900/10 border border-red-200 dark:border-red-900/30 text-red-600 dark:text-red-400 rounded-lg outline-none focus:border-red-500 text-right font-mono" placeholder="0" min="0">
                                </td>
                                <td class="px-4 py-4 text-right font-black text-indigo-600 dark:text-indigo-400 text-sm font-mono">
                                    Rp{{ number_format($item['subtotal']) }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <button wire:click="removeItem({{ $index }})" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/20 rounded-lg transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-5 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 dark:bg-slate-900/50 rounded-full flex items-center justify-center mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" class="text-gray-300" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Keranjang Masih Kosong</h3>
                                        <p class="text-sm text-gray-500 max-w-xs mx-auto">Gunakan kolom pencarian di atas atau Scan Nota menggunakan AI untuk mengisi daftar stok masuk.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 sticky top-8">
                
                <h3 class="font-black text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2 uppercase tracking-widest text-xs">Informasi Faktur</h3>

                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1.5 tracking-widest">Pilih Supplier (PBF)</label>
                        <select wire:model="supplier_id" class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-sm dark:text-white">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <span class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1.5 tracking-widest">Hubungkan ke PO (Opsional)</label>
                        <select wire:model="purchase_order_id" class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-sm dark:text-white italic">
                            <option value="">Tanpa Purchase Order</option>
                            @foreach($purchaseOrders as $po)
                                <option value="{{ $po->id }}">{{ $po->po_number }} - {{ $po->supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1.5 tracking-widest">Tanggal Terima Fisik</label>
                        <input type="date" wire:model="purchase_date" class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-sm dark:text-white [color-scheme:light] dark:[color-scheme:dark]">
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1.5 tracking-widest">PPN / Pajak (Rp)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-xs font-bold text-gray-400">Rp</span>
                            </div>
                            <input type="number" wire:model.live.debounce.500ms="tax" 
                                class="w-full pl-10 p-3 bg-white dark:bg-slate-900 border border-indigo-100 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 font-mono font-bold text-sm dark:text-white" 
                                placeholder="0">
                        </div>
                        <p class="text-[9px] text-gray-400 mt-1 italic">*Lihat kolom PPN di faktur fisik</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-dashed border-gray-100 dark:border-slate-700">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 text-center">Total Netto Faktur</p>
                    <p class="text-4xl font-black text-indigo-600 dark:text-indigo-400 text-center font-mono mb-8">Rp{{ number_format($this->total) }}</p>
                    
                    <button wire:click="saveTransaction" 
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm rounded-xl shadow-lg shadow-indigo-500/20 transition-all flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed group"
                        {{ empty($items) ? 'disabled' : '' }} wire:loading.attr="disabled">
                        
                        <span wire:loading.remove wire:target="saveTransaction" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" class="group-hover:scale-110 transition-transform"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            SIMPAN FAKTUR
                        </span>

                        <span wire:loading wire:target="saveTransaction" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            MEMPROSES...
                        </span>
                    </button>
                </div>
            </div>

            <div wire:loading.flex wire:target="receiptImage, scanReceipt" class="fixed inset-0 z-[9999] bg-slate-900/70 backdrop-blur-sm items-center justify-center p-6">
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-2xl max-w-sm w-full text-center border border-indigo-500/30 transform transition-all">
                    <div class="relative w-20 h-20 mx-auto mb-6">
                        <div class="absolute inset-0 bg-indigo-500/20 rounded-full animate-ping"></div>
                        <div class="relative bg-indigo-600 rounded-full w-20 h-20 flex items-center justify-center shadow-lg shadow-indigo-500/40">
                            <svg class="w-10 h-10 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 5h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2zM9 9h6v6H9V9z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-xl font-black text-gray-800 dark:text-gray-100 mb-2">AI Sedang Membaca Nota</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Mohon tunggu sebentar. Gemini sedang mengekstrak data obat, batch, dan diskon...</p>
                </div>
            </div>

        </div>
    </div>
</div>