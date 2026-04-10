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
                        <div class="flex justify-center items-center gap-3">
                            <a href="{{ route('purchases.show', $p->id) }}" class="text-blue-500 hover:text-blue-700 tooltip" title="Lihat Detail">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                            
                            <a href="{{ route('purchases.edit', $p->id) }}" class="text-amber-500 hover:text-amber-700 tooltip" title="Edit Faktur">
                                <i data-lucide="edit" class="w-5 h-5"></i>
                            </a>

                            <button wire:click="delete({{ $p->id }})" wire:confirm="Hapus data ini? Stok obat akan otomatis dikurangi kembali." class="text-red-500 hover:text-red-700" title="Hapus Data">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $purchases->links() }}</div>
</div>