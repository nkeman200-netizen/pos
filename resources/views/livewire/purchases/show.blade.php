<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300 print:bg-white print:p-0">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 print:hidden">
        <div class="flex items-center gap-4">
            <a href="{{ route('purchases.index') }}" class="p-2.5 bg-white dark:bg-slate-800 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Detail Faktur: {{ $purchase->purchase_number }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium italic">Data penerimaan barang dari Supplier</p>
            </div>
        </div>
        
        <a href="{{ route('purchases.print', $purchase->id) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
            Cetak PDF
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden print:shadow-none print:border-none print:bg-white">
        
        <div class="p-6 sm:p-8 bg-indigo-50/50 dark:bg-indigo-900/20 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center print:bg-white print:border-b-2 print:border-gray-800">
            <div>
                <p class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest print:text-gray-600">Supplier</p>
                <p class="text-xl font-black text-gray-800 dark:text-gray-100 mt-1 print:text-black">{{ $purchase->supplier->name ?? 'Umum' }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest print:text-gray-600">Tanggal Masuk</p>
                <p class="text-xl font-black text-gray-800 dark:text-gray-100 mt-1 print:text-black">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d F Y') }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/80 dark:bg-slate-900/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-200 dark:border-slate-700 print:bg-gray-100 print:text-black">
                    <tr>
                        <th class="px-6 py-4 font-bold">Obat</th>
                        <th class="px-6 py-4 font-bold text-center">Qty</th>
                        <th class="px-6 py-4 font-bold text-right">Harga Beli</th>
                        <th class="px-6 py-4 font-bold text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 print:text-black">
                    @foreach($purchase->details as $item)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors print:hover:bg-white">
                        <td class="px-6 py-4 font-bold text-gray-800 dark:text-gray-100 text-sm print:text-black">{{ $item->product->name }}</td>
                        <td class="px-6 py-4 text-center font-bold text-gray-800 dark:text-gray-100 text-sm print:text-black">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800 dark:text-gray-100 text-sm print:text-black">Rp{{ number_format($item->purchase_price) }}</td>
                        <td class="px-6 py-4 text-right font-black text-indigo-600 dark:text-indigo-400 text-sm print:text-black">Rp{{ number_format($item->subtotal) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-gray-50 dark:bg-slate-900/50 flex justify-between items-center border-t border-gray-100 dark:border-slate-700 print:bg-white print:border-t-2 print:border-gray-800">
            <span class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-sm print:text-black">Total Keseluruhan</span>
            <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400 print:text-black">Rp{{ number_format($purchase->total_cost) }}</span>
        </div>
    </div>
</div>