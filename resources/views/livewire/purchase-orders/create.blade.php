<div class="p-6 lg:p-8">
    <style>
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>

    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('purchase-orders.index') }}" class="p-2.5 bg-white text-gray-500 hover:text-indigo-600 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-100 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-icon lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Buat Purchase Order</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Buat surat pesanan barang ke Supplier (Stok belum bertambah)</p>
            </div>
        </div>
        
        <button wire:click="loadKritis" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-amber-500/30 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            Tarik Obat Kritis Otomatis
        </button>
    </div>

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-500/10 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded-r-xl font-bold shadow-sm">
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border-l-4 border-green-500 text-green-700 dark:text-green-400 rounded-r-xl font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col xl:grid xl:grid-cols-3 gap-6">
        
        <div class="xl:col-span-2 flex flex-col gap-6">
            
            <div class="bg-indigo-600 dark:bg-indigo-900 p-6 rounded-2xl shadow-lg relative z-30">
                <label class="block text-sm font-bold text-indigo-100 mb-2">Cari Manual Nama Obat / SKU</label>
                <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Ketik nama obat..." class="w-full p-4 bg-white dark:bg-slate-800 dark:text-white border-2 border-transparent dark:border-slate-700 rounded-xl outline-none focus:border-indigo-400 transition-all font-bold">
                
                @if(strlen($searchQuery) >= 2)
                    <div class="absolute w-full left-0 mt-2 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-2xl overflow-hidden z-50">
                        @forelse($this->searchResults as $product)
                            <button wire:click="addToCart({{ $product->id }})" class="w-full text-left px-5 py-4 hover:bg-indigo-50 dark:hover:bg-slate-700 transition border-b border-gray-50 dark:border-slate-700">
                                <span class="font-bold text-gray-800 dark:text-gray-100 block">{{ $product->name }}</span>
                            </button>
                        @empty
                            <div class="px-5 py-6 text-center text-gray-500 dark:text-gray-400 text-sm">Obat tidak ditemukan.</div>
                        @endforelse
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto min-h-[300px]">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-xs text-gray-500 dark:text-gray-400 uppercase">
                            <tr>
                                <th class="px-5 py-4 font-bold min-w-[200px]">Obat</th>
                                <th class="px-3 py-4 font-bold w-32 text-center">Qty Pesan</th>
                                <th class="px-3 py-4 font-bold min-w-[160px] text-right">Est. Harga Beli</th>
                                <th class="px-4 py-4 font-bold text-right w-40">Subtotal</th>
                                <th class="px-3 py-4 w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                            @forelse($items as $index => $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-5 py-4 font-bold text-gray-800 dark:text-gray-100 text-sm">{{ $item['name'] }}</td>
                                <td class="px-3 py-4">
                                    <input type="number" wire:model.blur="items.{{ $index }}.quantity" wire:change="syncItem({{ $index }})" class="w-full p-2.5 text-center text-sm font-bold border border-gray-200 dark:border-slate-600 rounded-lg outline-none bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-100 focus:border-indigo-500">
                                </td>
                                <td class="px-3 py-4">
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs font-bold pointer-events-none">Rp</span>
                                        <input type="number" wire:model.blur="items.{{ $index }}.purchase_price" wire:change="syncItem({{ $index }})" class="w-full pl-9 p-2.5 text-right text-sm font-bold border border-gray-200 dark:border-slate-600 rounded-lg outline-none bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-100 focus:border-indigo-500">
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right font-black text-indigo-600 dark:text-indigo-400 text-sm">Rp{{ number_format($item['subtotal']) }}</td>
                                <td class="px-3 py-4 text-center">
                                    <button wire:click="removeItem({{ $index }})" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-5 py-24 text-center">
                                    <p class="text-gray-500 dark:text-gray-400 font-bold text-lg">Belum ada obat di pesanan.</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Klik "Tarik Obat Kritis" atau cari manual.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pilih PBF/Supplier *</label>
                <select wire:model="supplier_id" class="w-full p-3.5 bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 mb-4 text-sm font-medium">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                </select>
                @error('supplier_id') <span class="text-red-500 text-xs block -mt-3 mb-4 font-bold">{{ $message }}</span> @enderror

                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Estimasi Tiba *</label>
                <input type="date" wire:model="expected_date" class="w-full p-3.5 bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 mb-4 text-sm font-medium [color-scheme:light] dark:[color-scheme:dark]">
                @error('expected_date') <span class="text-red-500 text-xs block -mt-3 mb-4 font-bold">{{ $message }}</span> @enderror

                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Catatan Pesanan</label>
                <textarea wire:model="notes" rows="3" placeholder="Misal: Tolong kirim pagi hari..." class="w-full p-3.5 bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-medium"></textarea>
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 text-center">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Total Estimasi</p>
                <p class="text-4xl font-black text-indigo-600 dark:text-indigo-400 mb-6">Rp{{ number_format($this->total) }}</p>
                
                <button wire:click="savePO" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm rounded-xl transition-all shadow-md">
                    BUAT SURAT PO SEKARANG
                </button>
            </div>
        </div>

    </div>
</div>