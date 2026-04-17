<div> <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <p class="text-gray-500">Kelola data pelanggan di sini.</p>
            <a  href="{{ route('customers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Tambah Pelanggan
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </a>
                                <button wire:click="delete({{ $customer->id }})" wire:confirm="Yakin ingin menghapus pelanggan ini?" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus Data">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
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