<div class="p-6 lg:p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-black text-gray-800">Daftar Supplier</h2>
            <p class="text-sm text-gray-500">Kelola data PBF atau distributor obat</p>
        </div>
        <a href="{{ route('suppliers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition shadow-md font-bold text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Tambah Supplier
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-50 flex items-center gap-4">
            <div class="relative flex-1 max-w-xs">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </span>
                <input type="text" wire:model.live="search" placeholder="Cari supplier..." class="w-full pl-10 p-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 transition text-sm">
            </div>
        </div>

        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 text-xs font-bold text-gray-400 uppercase tracking-widest border-b">
                <tr>
                    <th class="px-6 py-4">Nama Supplier</th>
                    <th class="px-6 py-4">Telepon</th>
                    <th class="px-6 py-4">Alamat</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($suppliers as $s)
                <tr class="hover:bg-gray-50/30 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-800">{{ $s->name }}</td>
                    <td class="px-6 py-4 text-gray-600 text-sm">{{ $s->phone }}</td>
                    <td class="px-6 py-4 text-gray-500 text-sm">{{ $s->address }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('suppliers.edit', $s->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </a>
                            <button wire:click="delete({{ $s->id }})" wire:confirm="Hapus supplier ini?" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">Data supplier kosong.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-50">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>