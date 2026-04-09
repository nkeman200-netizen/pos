<div class="p-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('purchases.index') }}" class="p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">
            <i data-lucide="arrow-left" class="w-6 h-6"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Input Stok Masuk</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div>
            <label class="block text-sm font-bold text-gray-600 mb-2">Supplier</label>
            <select wire:model="supplier_id" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Pilih Supplier --</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-600 mb-2">No. Faktur</label>
            <input type="text" wire:model="purchase_number" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-600 mb-2">Tanggal</label>
            <input type="date" wire:model="purchase_date" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
        <table class="w-full">
            <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase">
                <tr>
                    <th class="px-6 py-4">Produk Obat</th>
                    <th class="px-6 py-4 text-center w-32">Qty</th>
                    <th class="px-6 py-4 text-right">Harga Beli</th>
                    <th class="px-6 py-4 text-right">Subtotal</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($items as $index => $item)
                <tr>
                    <td class="px-6 py-4">
                        <select wire:model="items.{{ $index }}.product_id" class="w-full p-2 border-b outline-none">
                            <option value="">-- Pilih Obat --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" wire:model.live="items.{{ $index }}.quantity" class="w-full text-center outline-none">
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" wire:model.live="items.{{ $index }}.cost_price" class="w-full text-right outline-none">
                    </td>
                    <td class="px-6 py-4 text-right font-bold">
                        Rp{{ number_format(($items[$index]['quantity'] ?? 0) * ($items[$index]['cost_price'] ?? 0)) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button wire:click="removeItem({{ $index }})" class="text-red-400 hover:text-red-600">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 bg-gray-50 flex justify-between items-center">
            <button wire:click="addItem" class="text-indigo-600 font-bold hover:underline">+ Tambah Obat</button>
            <div class="text-right">
                <p class="text-gray-500 text-sm uppercase font-bold">Total Pembelian</p>
                <p class="text-3xl font-black text-indigo-600">Rp{{ number_format($this->total) }}</p>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <button wire:click="save" class="bg-indigo-600 text-white px-12 py-4 rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">
            Simpan Stok Masuk
        </button>
    </div>
</div>