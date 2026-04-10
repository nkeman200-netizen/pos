<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <p class="text-gray-500">Kelola dan lihat riwayat penjualan tokomu di sini.</p>
        <a href="{{ route('sales.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition shadow-md">
            <i data-lucide="shopping-bag" class="w-4 h-4"></i> Kasir / Transaksi Baru
        </a>
    </div>

    <div class="mb-4">
        <input type="text" wire:model.live="search" placeholder="Cari No. invoice..." 
            class="w-full md:w-64 p-2 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
    
    <div class="overflow-x-auto bg-white rounded-xl border border-gray-100 shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-4 py-4 font-bold text-gray-600 text-sm">No. Invoice</th>
                    <th class="px-4 py-4 font-bold text-gray-600 text-sm">Tanggal</th>
                    <th class="px-4 py-4 font-bold text-gray-600 text-sm">Pelanggan</th>
                    <th class="px-4 py-4 font-bold text-gray-600 text-sm">Total Belanja</th>
                    <th class="px-4 py-4 font-bold text-gray-600 text-sm">Kasir</th>
                    <th class="px-4 py-4 font-bold text-gray-600 text-sm text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sales as $sale)
                <tr class="hover:bg-gray-50 transition">
                    
                    <td class="px-4 py-4 font-mono text-sm font-bold text-indigo-600">
                        {{ $sale->invoice_number }}
                    </td>
                    
                    <td class="px-4 py-4 text-sm text-gray-600">
                        {{ $sale->created_at->format('d/m/Y H:i') }}
                    </td>
                    
                    <td class="px-4 py-4 text-sm font-medium text-gray-800">
                        {{ $sale->customer ? $sale->customer->name : 'Umum (Guest)' }}
                    </td>
                    
                    <td class="px-4 py-4 text-sm font-bold text-green-600">
                        Rp {{ number_format($sale->total_price, 0, ',', '.') }}
                    </td>
                    
                    <td class="px-4 py-4 text-sm text-gray-500">
                        {{ $sale->user ? $sale->user->name : 'Sistem' }}
                    </td>
                    
                    <td class="px-4 py-4">
                        <div class="flex justify-center gap-2">
                            
                            <a href="{{ route('sales.show', $sale) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Lihat Struk">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>

                            <a href="{{ route('sales.edit', $sale) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Transaksi">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Peringatan: Menghapus transaksi ini akan mengembalikan stok barang ke gudang. Lanjutkan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Batalkan Transaksi">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                        <i data-lucide="receipt" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                        <p class="text-lg font-medium">Belum ada transaksi</p>
                        <p class="text-sm">Klik tombol "Kasir / Transaksi Baru" untuk memulai penjualan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $sales->links() }}
    </div>
</div>