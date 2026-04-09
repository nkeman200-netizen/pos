<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('purchases.index') }}" class="p-2 bg-gray-100 rounded-full hover:bg-gray-200">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-2xl font-bold">Detail Faktur: {{ $purchase->purchase_number }}</h2>
        </div>
        <div class="text-right">
            <span class="text-gray-500 text-sm">Admin:</span>
            <span class="font-bold">{{ $purchase->user->name }}</span>
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
                <td class="px-4 py-4 text-right">Rp{{ number_format($item->cost_price) }}</td>
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