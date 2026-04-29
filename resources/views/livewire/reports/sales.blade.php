<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300 print:bg-white print:p-0">
    
    <style>
        @media print {
            /* 1. Atur ukuran kertas wajib A4 dan beri jarak aman (margin) */
            @page { size: A4 portrait; margin: 1cm; }
            
            /* 2. HANCURKAN margin sidebar yang bikin gepeng ke kanan! */
            body, html, main { 
                background-color: #ffffff !important; 
                margin: 0 !important; 
                padding: 0 !important; 
                position: static !important;
            }
            
            /* 3. Sembunyikan UI Web */
            body * { visibility: hidden; }
            
            /* 4. Tampilkan HANYA Laporan */
            #area-laporan, #area-laporan * { 
                visibility: visible; 
                color: #000000 !important; /* Paksa tinta printer jadi hitam pekat */
            }
            
            /* 5. Paksa Laporan merapat ke kiri atas layar kertas */
            #area-laporan {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            
            /* 6. Hilangkan elemen yg punya class no-print */
            .no-print { display: none !important; }
            
            /* 7. Pertegas garis tabel agar rapi saat dicetak */
            table { border-collapse: collapse !important; width: 100% !important; }
            th, td { border: 1px solid #94a3b8 !important; padding: 10px !important; }
            th { background-color: #f1f5f9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>

    <div class="mb-8 no-print">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Laporan Analisis Penjualan</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Data performa omzet dan profitabilitas apotek</p>
            </div>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition shadow-md font-bold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
                Export PDF (A4)
            </button>
        </div>

        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col md:flex-row items-start md:items-center gap-4">
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full md:w-auto">
                <span class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest hidden sm:block">Periode</span>
                <input type="date" wire:model.live="startDate" class="w-full sm:w-auto [color-scheme:light] dark:[color-scheme:dark] p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold">
                <span class="text-gray-300 hidden sm:block">s/d</span>
                <input type="date" wire:model.live="endDate" class="w-full sm:w-auto [color-scheme:light] dark:[color-scheme:dark] p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold">
            </div>
            
            <div class="hidden md:block w-px h-8 bg-gray-200 dark:bg-slate-700"></div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
                <span class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest hidden sm:block">Tampilan</span>
                <select wire:model.live="frequency" class="w-full sm:w-auto p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold">
                    <option value="detail">Struk per Struk (Detail)</option>
                    <option value="daily">Rekap Harian</option>
                    <option value="monthly">Rekap Bulanan</option>
                </select>
            </div>
        </div>
    </div>

    <div id="area-laporan" class="space-y-6">
        
        <div class="hidden print:block text-center mb-8 pb-4 border-b-4 border-double border-gray-800">
            <h1 class="text-2xl font-black uppercase">LAPORAN ANALISIS PENJUALAN & LABA</h1>
            <p class="text-sm font-bold italic mt-1">Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</p>
            <p class="text-xs font-bold mt-1">Format: {{ $frequency == 'detail' ? 'Detail Transaksi' : ($frequency == 'daily' ? 'Rekapitulasi Harian' : 'Rekapitulasi Bulanan') }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Omzet</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-gray-100 font-mono">Rp{{ number_format($totalOmzet) }}</h3>
            </div>
            <div class="bg-emerald-50 dark:bg-emerald-500/10 p-6 rounded-2xl shadow-sm border border-emerald-100 dark:border-emerald-500/20">
                <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1">Estimasi Laba Kotor</p>
                <h3 class="text-2xl font-black text-emerald-700 dark:text-emerald-300 font-mono">Rp{{ number_format($totalLaba) }}</h3>
                <p class="text-[10px] font-bold text-emerald-500 mt-1 uppercase">Margin: {{ number_format($marginPersen, 1) }}%</p>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Transaksi</p>
                <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 font-mono">{{ number_format($totalTransaksi) }} <span class="text-sm font-medium">Struk</span></h3>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Obat Terjual</p>
                <h3 class="text-2xl font-black text-blue-600 dark:text-blue-400 font-mono">{{ number_format($totalItemTerjual) }} <span class="text-sm font-medium">Pcs</span></h3>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center print:hidden">
                        <h3 class="font-bold text-gray-800 dark:text-gray-100">
                            @if($frequency === 'detail') Riwayat Penjualan Detail @endif
                            @if($frequency === 'daily') Rekapitulasi Pendapatan Harian @endif
                            @if($frequency === 'monthly') Rekapitulasi Pendapatan Bulanan @endif
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50/80 dark:bg-slate-900/50 text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-tighter border-b border-gray-200 dark:border-slate-700 font-black">
                                <tr>
                                    @if($frequency === 'detail')
                                        <th class="px-5 py-4">Tgl / Invoice</th>
                                        <th class="px-5 py-4">Kasir</th>
                                    @else
                                        <th class="px-5 py-4">Waktu / Periode</th>
                                        <th class="px-5 py-4 text-center">Jml Transaksi</th>
                                    @endif
                                    <th class="px-5 py-4 text-right">Total (Rp)</th>
                                    <th class="px-5 py-4 text-right">Est. Laba (Rp)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                                
                                @if($frequency === 'detail')
                                    {{-- TABEL MODE DETAIL (STRUK) --}}
                                    @forelse($reportTable as $sale)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
                                        <td class="px-5 py-4">
                                            <div class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $sale->created_at->format('d/m/y H:i') }}</div>
                                            <div class="text-[10px] font-mono font-bold text-indigo-600">{{ $sale->invoice_number }}</div>
                                        </td>
                                        <td class="px-5 py-4 text-xs font-bold text-gray-600 dark:text-gray-400">{{ $sale->user->name ?? 'System' }}</td>
                                        <td class="px-5 py-4 text-right font-black text-sm text-gray-800 dark:text-gray-100 font-mono">{{ number_format($sale->total_price) }}</td>
                                        
                                        @php
                                            $labaInvoice = 0;
                                            foreach($sale->details as $d) {
                                                $modal = $d->product->batches->avg('purchase_price') ?? 0;
                                                $labaInvoice += ($d->unit_price - $modal) * $d->quantity;
                                            }
                                        @endphp
                                        <td class="px-5 py-4 text-right font-bold text-sm text-emerald-600 dark:text-emerald-400 font-mono">{{ number_format($labaInvoice) }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="p-10 text-center text-gray-400 italic">Tidak ada transaksi pada periode ini.</td></tr>
                                    @endforelse
                                
                                @else
                                    {{-- TABEL MODE REKAP (HARIAN / BULANAN) --}}
                                    @foreach($reportTable as $row)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors {{ $row['transaksi'] == 0 ? 'opacity-50 bg-gray-50/30 dark:bg-slate-800/30' : '' }}">
                                        <td class="px-5 py-4">
                                            <div class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $row['label'] }}</div>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            @if($row['transaksi'] > 0)
                                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-indigo-100 bg-indigo-600 rounded-full">{{ $row['transaksi'] }} struk</span>
                                            @else
                                                <span class="text-xs text-gray-400 font-bold">-</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-right font-black text-sm {{ $row['omzet'] > 0 ? 'text-gray-800 dark:text-gray-100' : 'text-gray-400' }} font-mono">{{ number_format($row['omzet']) }}</td>
                                        <td class="px-5 py-4 text-right font-bold text-sm {{ $row['laba'] > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400' }} font-mono">{{ number_format($row['laba']) }}</td>
                                    </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-1 space-y-6">
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm">
                    <h3 class="font-black text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2 uppercase tracking-widest text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        Top 5 Produk Terlaris
                    </h3>
                    <div class="space-y-4">
                        @forelse($topProducts as $index => $product)
                        <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50 dark:bg-slate-900/50 border border-gray-100 dark:border-slate-700">
                            <div class="w-8 h-8 flex items-center justify-center bg-indigo-600 text-white rounded-lg font-black text-xs">{{ $index + 1 }}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-100 truncate">{{ $product->name }}</p>
                                <p class="text-[10px] text-gray-500 font-bold uppercase">{{ number_format($product->total_qty) }} Pcs Terjual</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-gray-400 italic text-center py-4">Belum ada data penjualan.</p>
                        @endforelse
                    </div>
                </div>

                <div class="p-6 rounded-2xl bg-indigo-600 text-white shadow-xl relative overflow-hidden print:hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest opacity-80 mb-1">Total Kas Masuk</p>
                    <h2 class="text-3xl font-black font-mono">Rp{{ number_format($totalOmzet) }}</h2>
                    <p class="text-[10px] mt-4 opacity-80 leading-relaxed font-medium">Uang tunai/non-tunai yang seharusnya ada di laci kasir berdasarkan data transaksi sistem.</p>
                </div>
            </div>
        </div>
    </div>
</div>