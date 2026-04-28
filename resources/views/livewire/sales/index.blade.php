<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Riwayat Penjualan</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola dan lihat riwayat penjualan tokomu di sini.</p>
        </div>
        <a href="{{ route('sales.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition shadow-md font-bold text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            Kasir Baru
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800">
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No. Invoice..." 
                    class="w-full pl-10 p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-medium">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/80 dark:bg-slate-900/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-200 dark:border-slate-700 font-bold">
                    <tr>
                        <th class="px-6 py-4">No. Invoice</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4 text-right">Total Transaksi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-sm text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-2.5 py-1 rounded-md border border-indigo-100 dark:border-indigo-500/20">
                                {{ $sale->invoice_number }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $sale->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $sale->created_at->format('H:i') }} | Kasir: {{ $sale->user->name ?? 'Sistem' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-sm text-gray-800 dark:text-gray-100">{{ $sale->customer->name ?? 'Umum' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-black text-gray-800 dark:text-gray-100 text-sm font-mono">Rp{{ number_format($sale->total_price) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($sale->status === 'void')
                                <span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 rounded-full border border-red-200 dark:border-red-500/30 inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    VOID
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 rounded-full border border-emerald-200 dark:border-emerald-500/30">
                                    Lunas
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('sales.show', $sale) }}" class="inline-flex p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 rounded-lg transition-colors border border-indigo-100 dark:border-indigo-500/20 shadow-sm" title="Lihat Struk">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 mb-4 border-2 border-dashed border-gray-200 dark:border-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 dark:text-gray-500"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 font-bold text-lg">Belum ada riwayat penjualan.</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Buka menu kasir untuk memulai transaksi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($sales, 'hasPages') && $sales->hasPages())
        <div class="p-5 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</div>