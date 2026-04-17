<div class="p-6 lg:p-8">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Data Purchase Order</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola riwayat pemesanan barang ke PBF/Supplier</p>
        </div>
        
        <a href="{{ route('purchase-orders.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-indigo-500/30 transition-all whitespace-nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Buat PO Baru
        </a>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border-l-4 border-green-500 text-green-700 dark:text-green-400 rounded-r-xl font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        
        <div class="p-4 border-b border-gray-50 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/20">
            <div class="relative max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari No. PO atau Supplier..." class="w-full pl-10 p-3 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-bold text-gray-800 dark:text-gray-100 transition-all">
            </div>
        </div>

        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-xs text-gray-500 dark:text-gray-400 uppercase">
                    <tr>
                        <th class="px-5 py-4 font-bold">No. PO</th>
                        <th class="px-5 py-4 font-bold">Supplier</th>
                        <th class="px-5 py-4 font-bold">Tgl Pesan</th>
                        <th class="px-5 py-4 font-bold">Estimasi Tiba</th>
                        <th class="px-5 py-4 font-bold text-right">Total</th>
                        <th class="px-5 py-4 font-bold text-center">Status</th>
                        <th class="px-5 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                    @forelse($purchaseOrders as $po)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-5 py-4 font-black text-indigo-600 dark:text-indigo-400 text-sm">{{ $po->po_number }}</td>
                        <td class="px-5 py-4 font-bold text-gray-800 dark:text-gray-100 text-sm">{{ $po->supplier->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300 text-sm">{{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</td>
                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300 text-sm">{{ \Carbon\Carbon::parse($po->expected_date)->format('d M Y') }}</td>
                        <td class="px-5 py-4 font-black text-right text-sm dark:text-gray-100">Rp{{ number_format($po->total_amount) }}</td>
                        <td class="px-5 py-4 text-center">
                            @if($po->status === 'pending')
                                <span class="bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider">Pending</span>
                            @else
                                <span class="bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider">{{ $po->status }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('purchase-orders.show', $po->id) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                @if($po->status === 'pending')
                                <button onclick="confirm('Yakin ingin membatalkan PO ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $po->id }})" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition" title="Hapus/Batal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <p class="text-gray-500 dark:text-gray-400 font-bold text-lg">Tidak ada data Purchase Order.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($purchaseOrders->hasPages())
        <div class="p-4 border-t border-gray-50 dark:border-slate-700">
            {{ $purchaseOrders->links() }}
        </div>
        @endif
    </div>
</div>