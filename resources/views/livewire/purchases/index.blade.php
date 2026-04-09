<div class="p-6 bg-white rounded-2xl shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Stok Masuk</h2>
        <a href="{{ route('purchases.create') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold">
            + Tambah Stok
        </a>
    </div>

    <div class="mb-4">
        <input type="text" wire:model.live="search" placeholder="Cari No. Faktur..." 
            class="w-full md:w-64 p-2 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-4">No. Faktur</th>
                    <th class="px-4 py-4">Supplier</th>
                    <th class="px-4 py-4 text-right">Total</th>
                    <th class="px-4 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($purchases as $p)
                <tr>
                    <td class="px-4 py-4 font-bold text-indigo-600">{{ $p->purchase_number }}</td>
                    <td class="px-4 py-4">{{ $p->supplier->name }}</td>
                    <td class="px-4 py-4 text-right font-bold">Rp{{ number_format($p->total_cost) }}</td>
                    <td class="px-4 py-4 text-center">
                        <button wire:click="delete({{ $p->id }})" wire:confirm="Hapus data ini? Stok akan berkurang otomatis." class="text-red-500">
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $purchases->links() }}</div>
</div>