<div> <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <p class="text-gray-500">Kelola data pelanggan di sini.</p>
            <a href="{{ route('customers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition shadow-md">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Pelanggan
            </a>
        </div>

        <div class="mb-4">
            <input type="text" wire:model.live="search" placeholder="Cari nama pelanggan..." 
                class="w-full md:w-64 p-2 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        
        <div class="overflow-x-auto bg-white dark:bg-slate-800 rounded-xl border border-gray-100 shadow-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-4 font-bold text-gray-600 text-sm">Nama</th>
                        <th class="px-4 py-4 font-bold text-gray-600 text-sm">No. HP</th>
                        <th class="px-4 py-4 font-bold text-gray-600 text-sm">Alamat</th>
                        <th class="px-4 py-4 font-bold text-gray-600 text-sm text-center">Aksi</th> 
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($customers as $customer)
                    <tr wire:key="customer-{{ $customer->id }}" class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 font-mono text-sm text-indigo-600 font-bold uppercase tracking-wider">
                            {{ $customer->name }}
                        </td>
                        <td class="px-4 py-4 text-gray-800 font-medium">
                            {{ $customer->phone }}
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                {{ $customer->address }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div wire:ignore class="flex justify-center gap-2">
                                <a href="{{ route('customers.edit', $customer) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <button wire:click="delete({{ $customer->id }})" wire:confirm="Yakin ingin menghapus pelanggan ini?" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus Data">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center text-gray-400">
                            <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                            <p class="text-lg font-medium">Belum ada data pelanggan.</p>
                            <p class="text-sm">Klik tombol "Tambah Pelanggan" di atas untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    </div>
</div>