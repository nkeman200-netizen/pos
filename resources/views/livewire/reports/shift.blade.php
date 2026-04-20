<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    
    <div class="mb-8">
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

        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Filter Kasir</label>
                <select wire:model.live="userId" class="p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold min-w-[200px]">
                    <option value="">Semua Kasir</option>
                    @foreach($kasirs as $k)
                        <option value="{{ $k->id }}">{{ $k->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Periode Shift</label>
                <div class="flex items-center gap-3 max-w-md">
                    <input type="date" wire:model.live="startDate" class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold [color-scheme:light] dark:[color-scheme:dark]">
                    <span class="text-gray-300 font-bold">s/d</span>
                    <input type="date" wire:model.live="endDate" class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold [color-scheme:light] dark:[color-scheme:dark]">
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
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
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
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
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 rounded text-xs font-bold">Balance</span>
                            @elseif($selisih > 0)
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 rounded text-xs font-bold">+ Rp{{ number_format($selisih) }}</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 rounded text-xs font-bold">- Rp{{ number_format(abs($selisih)) }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($isClosed)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-gray-300 text-xs font-bold">
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-500"></div> Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400 text-xs font-bold">
                                    <div class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></div> Aktif
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 mb-3 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <span class="font-bold">Belum ada data shift untuk periode ini.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>