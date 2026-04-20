<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300 print:bg-white print:p-0">
    
    @push('scripts')
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
    @endpush

    <div class="mb-8 no-print">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight text-indigo-600">Kartu Stok Obat</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Lacak riwayat mutasi masuk dan keluar setiap produk</p>
            </div>
            @if($productId)
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition shadow-md font-bold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
                Cetak Kartu Stok
            </button>
            @endif
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Pilih Obat</label>
                <select wire:model.live="productId" class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold shadow-sm">
                    <option value="">-- Cari Nama Obat --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Periode</label>
                    <div class="flex items-center gap-3">
                        <input type="date" wire:model.live="startDate" class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition text-sm font-bold shadow-sm [color-scheme:light] dark:[color-scheme:dark]">
                        
                        <span class="text-gray-400 font-bold">s/d</span>
                        
                        <input type="date" wire:model.live="endDate" class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition text-sm font-bold shadow-sm [color-scheme:light] dark:[color-scheme:dark]">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="area-laporan" class="space-y-6">
        @if($productId)
            <div class="hidden print:block text-center mb-8 pb-4 border-b-4 border-double border-gray-800">
                <h1 class="text-2xl font-black uppercase">KARTU STOK PERSEDIAAN OBAT</h1>
                <p class="text-sm font-bold italic mt-1 uppercase tracking-widest">{{ $product->name }}</p>
                <p class="text-xs mt-1">Periode: {{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 no-print">
                <div class="bg-indigo-600 p-6 rounded-2xl shadow-lg text-white relative overflow-hidden">
                    <p class="text-[10px] font-bold uppercase opacity-80 mb-1">Stok Awal Periode</p>
                    <h3 class="text-2xl font-black">{{ $stokAwal }} <span class="text-sm font-medium">{{ $product->unit->short_name ?? 'Pcs' }}</span></h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute -right-2 -bottom-2 opacity-20 w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                </div>
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total Masuk</p>
                    <h3 class="text-2xl font-black text-emerald-600">{{ $mutasi->where('tipe', 'masuk')->sum('masuk') }}</h3>
                </div>
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total Keluar</p>
                    <h3 class="text-2xl font-black text-red-600">{{ $mutasi->where('tipe', 'keluar')->sum('keluar') }}</h3>
                </div>
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Saldo Akhir</p>
                    <h3 class="text-2xl font-black text-indigo-600">{{ $stokAwal + $mutasi->where('tipe', 'masuk')->sum('masuk') - $mutasi->where('tipe', 'keluar')->sum('keluar') }}</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-slate-900/50 text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest border-b border-gray-200 dark:border-slate-700 font-black">
                        <tr>
                            <th class="px-5 py-4 w-32">Tanggal</th>
                            <th class="px-5 py-4">Keterangan / Ref</th>
                            <th class="px-5 py-4 text-center">Batch</th>
                            <th class="px-5 py-4 text-center bg-emerald-50/30 dark:bg-emerald-500/5">Masuk (+)</th>
                            <th class="px-5 py-4 text-center bg-red-50/30 dark:bg-red-500/5">Keluar (-)</th>
                            <th class="px-5 py-4 text-center bg-indigo-50/30 dark:bg-indigo-500/5">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50">
                            <td class="px-5 py-3 text-xs font-bold text-gray-400 italic" colspan="5">STOK AWAL PERIODE</td>
                            <td class="px-5 py-3 text-center font-black text-gray-800 dark:text-gray-100">{{ $stokAwal }}</td>
                        </tr>

                        @php $runningBalance = $stokAwal; @endphp
                        @foreach($mutasi as $m)
                        @php $runningBalance += ($m['masuk'] - $m['keluar']); @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-5 py-4 text-xs font-bold text-gray-600 dark:text-gray-400">{{ $m['tanggal']->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4">
                                <div class="text-[11px] font-black text-gray-800 dark:text-gray-100">{{ $m['keterangan'] }}</div>
                                <div class="text-[10px] font-mono font-bold text-indigo-500">{{ $m['referensi'] }}</div>
                            </td>
                            <td class="px-5 py-4 text-center text-[11px] font-bold text-gray-500">{{ $m['batch'] }}</td>
                            <td class="px-5 py-4 text-center font-black text-emerald-600">{{ $m['masuk'] > 0 ? $m['masuk'] : '-' }}</td>
                            <td class="px-5 py-4 text-center font-black text-red-500">{{ $m['keluar'] > 0 ? $m['keluar'] : '-' }}</td>
                            <td class="px-5 py-4 text-center font-black text-indigo-700 dark:text-indigo-400 bg-indigo-50/20 dark:bg-indigo-500/5">{{ $runningBalance }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-16 text-center border-2 border-dashed border-gray-100 dark:border-slate-700">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 rounded-full mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                </div>
                <h3 class="text-xl font-black text-gray-800 dark:text-gray-100">Siap Menganalisa Stok?</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2 max-w-sm mx-auto font-medium">Silakan pilih salah satu obat dari dropdown di atas untuk melihat histori mutasi barang secara lengkap.</p>
            </div>
        @endif
    </div>
</div>