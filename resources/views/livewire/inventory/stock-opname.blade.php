<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    
    <div class="mb-8">
        <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600 dark:text-indigo-400"><path d="M14 4c0-1.1.9-2 2-2"/><path d="M20 2c1.1 0 2 .9 2 2"/><path d="M22 8c0 1.1-.9 2-2 2"/><path d="M16 10c-1.1 0-2-.9-2-2"/><path d="m9.05 8.49-4.5 4.5a2 2 0 0 0 0 2.82l3.69 3.69a2 2 0 0 0 2.83 0l4.49-4.49"/><path d="M17.5 15.5 22 20"/></svg>
            Stock Opname
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Sesuaikan stok fisik di rak dengan data sistem. Tiap perubahan akan terekam sebagai jurnal audit.</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 rounded-r-xl flex items-center gap-3 shadow-sm font-bold transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-500/10 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded-r-xl flex items-center gap-3 shadow-sm font-bold transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        
        @if(auth()->user()->role === 'admin')
        <div class="xl:col-span-5 space-y-6">
            <div class="bg-white dark:bg-slate-800 p-6 lg:p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700">
                <h3 class="font-black text-gray-800 dark:text-gray-100 mb-6 uppercase tracking-widest text-xs border-b border-gray-100 dark:border-slate-700 pb-3">Form Penyesuaian</h3>
                
                <form wire:submit.prevent="save" class="space-y-5">
                    
                    <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Pilih Obat yang Akan Diopname</label>
                            
                            @if($productId)
                                <div class="flex items-center justify-between p-2.5 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/30 rounded-xl">
                                    <div class="font-bold text-indigo-700 dark:text-indigo-400 text-sm">{{ $productName }}</div>
                                    <button type="button" wire:click="clearProduct" class="p-1 bg-white dark:bg-slate-800 text-gray-400 hover:text-indigo-500 rounded-md transition-colors shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            @else
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                    </div>
                                    <input type="text" wire:model.live.debounce.300ms="searchProduct" placeholder="Ketik nama atau SKU obat..." 
                                        class="w-full pl-10 p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white text-sm font-medium transition-all shadow-inner">
                                    
                                    @if(!empty($searchResults))
                                        <div class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl shadow-2xl overflow-hidden">
                                            @foreach($searchResults as $res)
                                                <div wire:click="selectProduct({{ $res->id }}, '{{ addslashes($res->name) }}')" class="p-3 hover:bg-indigo-50 dark:hover:bg-indigo-500/20 cursor-pointer border-b border-gray-50 dark:border-slate-700/50 last:border-0 transition-colors group">
                                                    <div class="font-bold text-gray-800 dark:text-gray-200 text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $res->name }}</div>
                                                    <div class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $res->sku }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif(strlen($searchProduct) >= 2)
                                        <div class="absolute z-50 w-full mt-1 p-3 text-center text-xs font-bold text-gray-400 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl shadow-lg">
                                            Obat belum pernah memiliki riwayat batch/stok.
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            @error('productId') <span class="text-xs font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase">2. Pilih Batch (Nomor Produksi)</label>
                        <select wire:model.live="batchId" class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold" {{ empty($batches) ? 'disabled' : '' }}>
                            <option value="">-- Pilih Batch --</option>
                            @foreach($batches as $b)
                                <option value="{{ $b->id }}">Batch: {{ $b->batch_number }} | ED: {{ date('M Y', strtotime($b->expired_date)) }} | Sisa: {{ $b->stock }}</option>
                            @endforeach
                        </select>
                        @error('batchId') <span class="text-xs font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="p-5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-widest">Stok Sistem</label>
                            <div class="text-3xl font-black text-gray-500 dark:text-gray-400 font-mono">{{ $systemQty }}</div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-indigo-500 mb-1 uppercase tracking-widest">Stok Fisik (Riil)</label>
                            <input type="number" wire:model.live.debounce.300ms="physicalQty" class="w-full p-2 bg-white dark:bg-slate-800 border border-indigo-200 dark:border-indigo-500/50 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-2xl font-black font-mono text-indigo-600 dark:text-indigo-400" {{ !$batchId ? 'disabled' : '' }}>
                            @error('physicalQty') <span class="text-xs font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        @if($physicalQty !== '')
                            @php
                                $diff = intval($physicalQty) - $systemQty;
                                $diffColor = $diff < 0 ? 'text-red-500' : ($diff > 0 ? 'text-emerald-500' : 'text-gray-400');
                                $diffText = $diff > 0 ? '+'.$diff : $diff;
                            @endphp
                            <div class="col-span-2 pt-3 border-t border-gray-200 dark:border-slate-700 flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Selisih Penyesuaian:</span>
                                <span class="text-xl font-black {{ $diffColor }} font-mono">{{ $diffText }}</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase">3. Alasan Penyesuaian</label>
                        <select wire:model="reason" class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold" {{ !$batchId ? 'disabled' : '' }}>
                            <option value="">-- Pilih Alasan --</option>
                            <option value="Barang Rusak / Pecah">Barang Rusak / Pecah</option>
                            <option value="Barang Kedaluwarsa">Barang Kedaluwarsa</option>
                            <option value="Barang Hilang">Barang Hilang / Selisih</option>
                            <option value="Koreksi Salah Input">Koreksi Salah Input Sistem</option>
                            <option value="Lainnya">Lainnya...</option>
                        </select>
                        @error('reason') <span class="text-xs font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-4 mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm uppercase tracking-wider rounded-xl shadow-lg transition-all flex justify-center items-center gap-2" {{ !$batchId ? 'disabled' : '' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Penyesuaian Stok
                    </button>
                </form>
            </div>
        </div>
        @endif

        <div class="{{ (auth()->user()->role === 'admin') ?  'xl:col-span-7' : 'xl:col-span-12'}}">
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden h-full flex flex-col">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50/50 dark:bg-slate-800/50">
                    <h3 class="font-black text-gray-800 dark:text-gray-100 uppercase tracking-widest text-xs flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        Jejak Audit Opname Terakhir
                    </h3>
                </div>
                
                <div class="flex-1 overflow-x-auto p-2">
                    <table class="w-full text-left border-collapse">
                        <thead class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-widest font-black border-b border-gray-100 dark:border-slate-700/50">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Obat & Batch</th>
                                <th class="px-4 py-3 text-center">Fisik</th>
                                <th class="px-4 py-3 text-center">Selisih</th>
                                <th class="px-4 py-3">Alasan / Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700/30">
                            @forelse($recentAdjustments as $adj)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/20 transition-colors">
                                <td class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $adj->created_at->format('d/m/y') }}<br>
                                    <span class="text-[10px]">{{ $adj->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $adj->product->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] font-mono font-bold text-indigo-500 dark:text-indigo-400">Batch: {{ $adj->batch->batch_number ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3 text-center font-black text-gray-700 dark:text-gray-300">{{ $adj->physical_qty }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($adj->difference < 0)
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-[10px] font-black rounded bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 border border-red-100 dark:border-red-500/20">{{ $adj->difference }}</span>
                                    @elseif($adj->difference > 0)
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-[10px] font-black rounded bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20">+{{ $adj->difference }}</span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-[10px] font-black rounded bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $adj->reason }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 mt-0.5">Oleh: {{ $adj->user->name ?? 'Admin' }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-16 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-slate-800 mb-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-bold text-sm">Belum ada riwayat Stock Opname.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>