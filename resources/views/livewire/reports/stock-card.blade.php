<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300 print:bg-white print:p-0">
    
    <style>
        @media print {
            @page { size: A4 portrait; margin: 1cm; }
            body, html, main { background-color: #ffffff !important; margin: 0 !important; padding: 0 !important; position: static !important; }
            body * { visibility: hidden; }
            #area-laporan, #area-laporan * { visibility: visible; color: #000 !important; }
            #area-laporan { position: absolute; left: 0; top: 0; width: 100% !important; }
            .no-print { display: none !important; }
            table { border-collapse: collapse !important; width: 100% !important; }
            th, td { border: 1px solid #94a3b8 !important; padding: 8px !important; font-size: 11px !important; }
        }
    </style>

    <div class="mb-8 no-print">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Kartu Stok Obat</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Lacak riwayat mutasi masuk dan keluar setiap produk</p>
            </div>
            
            <div class="flex items-center gap-2 bg-white dark:bg-slate-800 p-1.5 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700">
                <input type="date" wire:model.live="startDate" class="px-3 py-2 text-sm font-bold bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500 text-gray-700 dark:text-white transition-colors [color-scheme:light] dark:[color-scheme:dark]">
                
                <span class="text-gray-400 font-bold text-sm px-1">s/d</span>
                
                <input type="date" wire:model.live="endDate" class="px-3 py-2 text-sm font-bold bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500 text-gray-700 dark:text-white transition-colors [color-scheme:light] dark:[color-scheme:dark]">
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 relative w-full lg:w-1/2">
            <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3">Cari Master Obat</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Ketik nama obat atau scan SKU..." 
                    class="w-full pl-12 pr-4 py-3.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-gray-800 dark:text-white transition-colors text-sm shadow-inner">
            </div>

            @if(!empty($searchQuery))
                <div class="absolute z-50 w-full mt-2 left-0 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-2xl rounded-xl overflow-hidden max-h-60 overflow-y-auto">
                    @forelse($searchResults as $res)
                        <div wire:click="selectProduct({{ $res->id }})" class="p-4 border-b border-gray-50 dark:border-slate-700/50 hover:bg-indigo-50 dark:hover:bg-indigo-500/20 cursor-pointer transition flex justify-between items-center group">
                            <div>
                                <div class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">{{ $res->name }}</div>
                                <div class="text-xs text-gray-500 font-mono mt-1 bg-gray-100 dark:bg-slate-700 inline-block px-2 py-0.5 rounded">SKU: {{ $res->sku }}</div>
                            </div>
                            <span class="text-xs font-bold text-white bg-indigo-600 px-3 py-1.5 rounded-lg group-hover:bg-indigo-700 transition shadow-sm">Pilih</span>
                        </div>
                    @empty
                        <div class="p-5 text-center text-gray-500 text-sm font-medium">Obat tidak ditemukan.</div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>

    <div id="area-laporan">
        @if($product)
            <div class="mb-6 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 p-6 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-5 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest bg-indigo-50 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400">{{ $product->category->name ?? 'Obat' }}</span>
                        <span class="text-xs font-bold text-gray-400 font-mono">SKU: {{ $product->sku }}</span>
                    </div>
                    <h3 class="text-2xl font-black text-gray-800 dark:text-gray-100">{{ $product->name }}</h3>
                </div>
                
                <div class="relative z-10 text-left sm:text-right w-full sm:w-auto flex sm:block justify-between items-center">
                    <div class="no-print mb-0 sm:mb-2 order-2 sm:order-1">
                        <button onclick="window.print()" class="px-4 py-2 bg-gray-50 hover:bg-gray-100 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 font-bold rounded-lg shadow-sm border border-gray-200 dark:border-slate-600 transition flex items-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            Cetak
                        </button>
                    </div>
                    <div class="order-1 sm:order-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Stok Aktif</p>
                        <p class="text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $product->stock ?? 0 }} <span class="text-sm text-gray-500">{{ $product->unit->short_name ?? 'Pcs' }}</span></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
                
                <div class="xl:col-span-2 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden shadow-sm">
                    <div class="p-5 bg-gray-50/80 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div>
                            <h4 class="font-black text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                                Buku Mutasi Barang
                            </h4>
                        </div>
                        <div class="px-3 py-1.5 bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm flex items-center gap-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-bold">Stok Awal:</span>
                            <span class="text-sm font-black text-gray-800 dark:text-gray-100">{{ $stokAwal }}</span>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 font-black tracking-wider">
                                <tr>
                                    <th class="px-5 py-4 w-32">Tanggal</th>
                                    <th class="px-5 py-4">Referensi / Keterangan</th>
                                    <th class="px-5 py-4 text-center bg-emerald-50/50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400">Masuk</th>
                                    <th class="px-5 py-4 text-center bg-red-50/50 dark:bg-red-500/10 text-red-700 dark:text-red-400">Keluar</th>
                                    <th class="px-5 py-4 text-center text-indigo-700 dark:text-indigo-400">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                                @php $runningBalance = $stokAwal; @endphp
                                @forelse($mutasi as $m)
                                    @php
                                        $runningBalance += $m['masuk'];
                                        $runningBalance -= $m['keluar'];
                                    @endphp
                                    <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/30 transition-colors">
                                        <td class="px-5 py-4 font-bold text-gray-600 dark:text-gray-400 text-xs">
                                            {{ \Carbon\Carbon::parse($m['tanggal'])->format('d/m/Y') }}<br>
                                            <span class="font-normal text-gray-400">{{ \Carbon\Carbon::parse($m['tanggal'])->format('H:i') }}</span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="font-black text-gray-800 dark:text-gray-200 text-xs mb-0.5">{{ $m['referensi'] }}</div>
                                            <div class="text-[11px] text-gray-500 font-medium">{{ $m['keterangan'] }}</div>
                                        </td>
                                        <td class="px-5 py-4 text-center font-black text-emerald-600 dark:text-emerald-400">{{ $m['masuk'] > 0 ? '+'.$m['masuk'] : '-' }}</td>
                                        <td class="px-5 py-4 text-center font-black text-red-500 dark:text-red-400">{{ $m['keluar'] > 0 ? '-'.$m['keluar'] : '-' }}</td>
                                        <td class="px-5 py-4 text-center font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-500/5">{{ $runningBalance }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-5 py-16 text-center text-gray-400 font-bold text-sm">Tidak ada transaksi pada periode ini</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="xl:col-span-1 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden shadow-sm self-start">
                    <div class="p-5 bg-gray-50/80 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700">
                        <h4 class="font-black text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                            Stok per Batch Aktif
                        </h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead class="text-[10px] text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 font-black tracking-widest">
                                <tr>
                                    <th class="px-5 py-3">No. Batch</th>
                                    <th class="px-5 py-3 text-center">Expired</th>
                                    <th class="px-5 py-3 text-right">Stok</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                                @forelse($activeBatches as $batch)
                                    @php $isNearExp = \Carbon\Carbon::parse($batch->expired_date)->diffInDays(now()) < 90; @endphp
                                    <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/30 transition-colors {{ $isNearExp ? 'bg-red-50/30 dark:bg-red-900/10' : '' }}">
                                        <td class="px-5 py-3.5 font-mono font-bold text-gray-700 dark:text-gray-300 text-xs">{{ $batch->batch_number }}</td>
                                        <td class="px-5 py-3.5 text-center text-xs font-bold {{ $isNearExp ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                            {{ \Carbon\Carbon::parse($batch->expired_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-black text-indigo-600 dark:text-indigo-400">{{ $batch->stock }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400 font-bold text-xs uppercase tracking-widest">Stok Kosong</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        @else
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-16 text-center border-2 border-dashed border-gray-200 dark:border-slate-700">
                
                <div class="flex justify-center w-full mb-6">
                    <div class="flex items-center justify-center w-20 h-20 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-xl font-black text-gray-800 dark:text-gray-200 mb-2">Siap Menganalisa Stok?</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto font-medium">Gunakan fitur Live Search di atas untuk memantau pergerakan mutasi dan rincian expired date per-batch.</p>
            </div>
        @endif
    </div>
</div>