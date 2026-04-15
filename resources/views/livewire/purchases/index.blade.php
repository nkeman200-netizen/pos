<div class="p-6 bg-white rounded-2xl shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <p class="text-gray-500">Kelola dan lihat riwayat stok masuk</p>
        <a href="{{ route('purchases.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition shadow-md">
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
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($purchases as $p)
                <tr>
                    <td class="px-4 py-4 font-bold text-indigo-600">{{ $p->purchase_number }}</td>
                    <td class="px-4 py-4">{{ $p->supplier->name }}</td>
                    <td class="px-4 py-4 text-right font-bold">Rp{{ number_format($p->total_cost) }}</td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('purchases.show', $p->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                            
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $purchases->links() }}</div>
</div>