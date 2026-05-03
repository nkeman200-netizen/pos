<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300 print:bg-white print:p-0">
    
    <style>
        @media print {
            @page { size: A4 portrait; margin: 1cm; }
            
            body, html, main { 
                background-color: #ffffff !important; 
                margin: 0 !important; 
                padding: 0 !important; 
                position: static !important;
            }
            body * { visibility: hidden; }
            
            #area-laporan, #area-laporan * { 
                visibility: visible; 
                color: #000000 !important; 
            }
            
            #area-laporan {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .no-print { display: none !important; }
            
            table { border-collapse: collapse !important; width: 100% !important; }
            th, td { border: 1px solid #94a3b8 !important; padding: 10px !important; }
            th { background-color: #f1f5f9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>

    <div class="mb-8 no-print">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Laporan Shift Kasir</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau histori buka/tutup laci dan selisih uang fisik.</p>
            </div>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition shadow-md font-bold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
                Cetak Laporan
            </button>
        </div>

        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col md:flex-row items-start md:items-end gap-4">
            
            <div class="w-full md:w-auto">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Filter Kasir</label>
                <select wire:model.live="userId" class="w-full md:w-48 p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold">
                    <option value="">Semua Kasir</option>
                    @foreach($kasirs as $k)
                        <option value="{{ $k->id }}">{{ $k->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-auto">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Periode Shift</label>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <input type="date" wire:model.live="startDate" class="w-full sm:w-auto p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold [color-scheme:light] dark:[color-scheme:dark]">
                    <span class="text-gray-300 font-bold hidden sm:block">s/d</span>
                    <input type="date" wire:model.live="endDate" class="w-full sm:w-auto p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold [color-scheme:light] dark:[color-scheme:dark]">
                </div>
            </div>

        </div>
    </div>

    <div id="area-laporan">
        
        <div class="hidden print:block text-center mb-8 pb-4 border-b-4 border-double border-gray-800">
            <h1 class="text-2xl font-black uppercase">LAPORAN SHIFT KASIR</h1>
            <p class="text-sm font-bold italic mt-1">Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</p>
            @if($userId)
                <p class="text-xs font-bold mt-1">Filter Kasir: {{ $kasirs->firstWhere('id', $userId)->name ?? 'Semua' }}</p>
            @endif
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden print:border-none print:shadow-none print:rounded-none">
            <div class="overflow-x-auto print:overflow-visible">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-slate-900/50 text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest border-b border-gray-200 dark:border-slate-700 font-black">
                        <tr>
                            <th class="px-5 py-4 w-40">Tanggal & Jam</th>
                            <th class="px-5 py-4">Kasir</th>
                            <th class="px-5 py-4 text-right">Modal Awal</th>
                            <th class="px-5 py-4 text-right">Est. Sistem</th>
                            <th class="px-5 py-4 text-right">Fisik Laci</th>
                            <th class="px-5 py-4 text-center">Selisih</th>
                            <th class="px-5 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                        @forelse($shifts as $shift)
                            @php
                                $selisih = $shift->actual_cash - $shift->expected_cash;
                                $isClosed = $shift->status === 'closed';
                            @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors print:break-inside-avoid">
                            <td class="px-5 py-4">
                                <div class="text-xs font-bold text-gray-800 dark:text-gray-100">{{ $shift->start_time->format('d M Y') }}</div>
                                <div class="text-[11px] font-mono text-gray-500 mt-1">
                                    {{ $shift->start_time->format('H:i') }} - {{ $isClosed ? $shift->end_time->format('H:i') : 'Sekarang' }}
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">{{ $shift->user->name }}</span>
                            </td>
                            <td class="px-5 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-400 font-mono">
                                Rp{{ number_format($shift->starting_cash) }}
                            </td>
                            <td class="px-5 py-4 text-right text-sm font-black text-gray-800 dark:text-gray-100 font-mono">
                                Rp{{ number_format($shift->expected_cash) }}
                            </td>
                            <td class="px-5 py-4 text-right text-sm font-black text-blue-600 dark:text-blue-400 font-mono">
                                {{ $isClosed ? 'Rp' . number_format($shift->actual_cash) : '-' }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if(!$isClosed)
                                    <span class="text-gray-400 text-xs italic">-</span>
                                @elseif($selisih == 0)
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 rounded text-xs font-bold print:bg-transparent print:p-0">Balance</span>
                                @elseif($selisih > 0)
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 rounded text-xs font-bold print:bg-transparent print:p-0">+ Rp{{ number_format($selisih) }}</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 rounded text-xs font-bold print:bg-transparent print:p-0">- Rp{{ number_format(abs($selisih)) }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($isClosed)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-gray-300 text-xs font-bold print:bg-transparent print:p-0">
                                        <div class="w-1.5 h-1.5 rounded-full bg-gray-500 print:hidden"></div> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400 text-xs font-bold print:bg-transparent print:p-0">
                                        <div class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse print:hidden"></div> Aktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                <span class="font-bold">Belum ada data shift untuk periode ini.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>