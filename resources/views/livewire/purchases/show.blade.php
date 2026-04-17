<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('purchases.index') }}" class="p-2.5 bg-white dark:bg-slate-800 text-gray-500 hover:text-indigo-600 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Detail Faktur: {{ $purchase->purchase_number }}</h2>
                <p class="text-sm text-gray-500 font-medium italic">Data penerimaan barang dari Supplier</p>
            </div>
            
            <a href="{{ route('purchases.print', $purchase->id) }}" target="_blank" class="ml-auto bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-slate-700 px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:bg-gray-50 transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                Cetak Faktur
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8 bg-indigo-50 p-6 rounded-2xl">
        <div>
            <p class="text-xs font-bold text-indigo-400 uppercase">Supplier</p>
            <p class="text-xl font-black text-indigo-900">{{ $purchase->supplier->name }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-bold text-indigo-400 uppercase">Tanggal Masuk</p>
            <p class="text-xl font-black text-indigo-900">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d F Y') }}</p>
        </div>
    </div>

    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr class="text-left text-xs font-bold text-gray-500 uppercase">
                <th class="px-4 py-3">Obat</th>
                <th class="px-4 py-3 text-center">Qty</th>
                <th class="px-4 py-3 text-right">Harga Beli</th>
                <th class="px-4 py-3 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($purchase->details as $item)
            <tr>
                <td class="px-4 py-4 font-medium">{{ $item->product->name }}</td>
                <td class="px-4 py-4 text-center">{{ $item->quantity }}</td>
                <td class="px-4 py-4 text-right">Rp{{ number_format($item->purchase_price) }}</td>
                <td class="px-4 py-4 text-right font-bold">Rp{{ number_format($item->subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-50">
            <tr>
                <td colspan="3" class="px-4 py-4 text-right font-bold uppercase text-gray-500">Total Keseluruhan</td>
                <td class="px-4 py-4 text-right font-black text-indigo-600 text-xl">Rp{{ number_format($purchase->total_cost) }}</td>
            </tr>
        </tfoot>
    </table>
</div>