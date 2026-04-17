<div class="p-6 lg:p-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('purchase-orders.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-gray-300 px-4 py-3 rounded-xl flex items-center justify-center transition-all" title="Kembali ke Daftar PO">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>

        <div>
            <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Detail PO: {{ $po->po_number }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Surat pesanan barang</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('purchase-orders.print', $po->id) }}" target="_blank" class="bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-slate-700 px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:bg-gray-50 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                Cetak PO
            </a>
    
            @if($po->status === 'pending')
                <button wire:click="updateStatus('ordered')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-indigo-500/20">
                    Tandai Sudah Dipesan
                </button>
                <button onclick="confirm('Batalkan PO ini?') || event.stopImmediatePropagation()" wire:click="updateStatus('cancelled')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-all">
                    Batalkan
                </button>
            @endif
        </div>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-4">Informasi Supplier</h3>
            <div class="space-y-2">
                <p class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $po->supplier->name ?? '-' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400"><span class="font-bold">Telepon:</span> {{ $po->supplier->phone ?? '-' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400"><span class="font-bold">Alamat:</span> {{ $po->supplier->address ?? '-' }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-4">Informasi Dokumen</h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-bold">Tanggal Pesan</span>
                    <span class="text-sm text-gray-800 dark:text-gray-100 font-bold">{{ \Carbon\Carbon::parse($po->order_date)->format('d F Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-bold">Estimasi Tiba</span>
                    <span class="text-sm text-amber-600 dark:text-amber-400 font-bold">{{ \Carbon\Carbon::parse($po->expected_date)->format('d F Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-bold">Status</span>
                    @if($po->status === 'pending')
                        <span class="bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider">Pending</span>
                    @else
                        <span class="bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider">{{ $po->status }}</span>
                    @endif
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-slate-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-bold">Dibuat Oleh</span>
                    <span class="text-sm text-gray-800 dark:text-gray-100 font-bold">{{ $po->user->name ?? 'Admin' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-50 dark:border-slate-700">
            <h3 class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Daftar Barang Pesanan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-xs text-gray-500 dark:text-gray-400 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-bold">Nama Obat / Produk</th>
                        <th class="px-6 py-4 font-bold text-center">Qty</th>
                        <th class="px-6 py-4 font-bold text-right">Harga Satuan</th>
                        <th class="px-6 py-4 font-bold text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                    @foreach($po->items as $item)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-800 dark:text-gray-100 text-sm">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                        <td class="px-6 py-4 text-center font-bold text-gray-800 dark:text-gray-100 text-sm">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800 dark:text-gray-100 text-sm">Rp{{ number_format($item->purchase_price) }}</td>
                        <td class="px-6 py-4 text-right font-black text-indigo-600 dark:text-indigo-400 text-sm">Rp{{ number_format($item->subtotal) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-gray-50 dark:bg-slate-900/50 flex justify-between items-center">
            <span class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-sm">Total Keseluruhan</span>
            <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400">Rp{{ number_format($po->total_amount) }}</span>
        </div>
    </div>
    
    @if($po->notes)
    <div class="bg-indigo-50 dark:bg-indigo-500/10 p-6 rounded-2xl border border-indigo-100 dark:border-indigo-500/20">
        <h3 class="text-xs font-black text-indigo-800 dark:text-indigo-300 uppercase tracking-widest mb-2">Catatan Tambahan</h3>
        <p class="text-sm font-medium text-indigo-900 dark:text-indigo-200">{{ $po->notes }}</p>
    </div>
    @endif
</div>