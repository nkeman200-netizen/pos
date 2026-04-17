<div class="p-6 lg:p-8 bg-gray-50 min-h-screen">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Katalog Obat</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola master data obat, harga, dan pantau total stok aktif.</p>
        </div>
        <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2 whitespace-nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Tambah Obat
        </a>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="p-5 border-b border-gray-100 bg-white dark:bg-slate-800 flex flex-col sm:flex-row justify-between gap-4">
            <div class="relative w-full sm:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama obat atau SKU..." 
                    class="w-full pl-10 p-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors text-sm">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left border-collapse">
                <thead class="bg-gray-50/80 text-xs text-gray-500 uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">SKU / Barcode</th>
                        <th class="px-6 py-4 font-semibold">Nama Obat</th>
                        <th class="px-6 py-4 font-semibold">Golongan</th>
                        <th class="px-6 py-4 font-semibold text-right">Harga Jual</th>
                        <th class="px-6 py-4 font-semibold text-center">Stok Total</th>
                        <th class="px-6 py-4 font-semibold text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded-md border border-gray-200">
                                {{ $product->sku }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800 dark:text-gray-100">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">Satuan: {{ $product->unit->name }}</div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full whitespace-nowrap border 
                                {{ $product->category->name == 'Obat Bebas' ? 'bg-green-50 text-green-700 border-green-200' : 
                                ($product->category->name == 'Obat Bebas Terbatas' ? 'bg-blue-50 text-blue-700 border-blue-200' : 
                                ($product->category->name == 'Obat Keras' ? 'bg-red-50 text-red-700 border-red-200' : 
                                'bg-gray-50 text-gray-700 border-gray-200')) }}">
                                {{ $product->category->name }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-gray-800 dark:text-gray-100">Rp{{ number_format($product->selling_price) }}</span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($product->stock > 10)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-green-50 text-green-700 font-bold text-sm border border-green-200">
                                    {{ $product->stock }} <span class="font-normal text-xs">{{ $product->unit->short_name }}</span>
                                </span>
                            @elseif($product->stock > 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-yellow-50 text-yellow-700 font-bold text-sm border border-yellow-200">
                                    {{ $product->stock }} <span class="font-normal text-xs">{{ $product->unit->short_name }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-red-50 text-red-700 font-bold text-sm border border-red-200">
                                    Habis
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </a>
                                <button wire:click="delete({{ $product->id }})" wire:confirm="Hapus data obat ini?" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                <i data-lucide="package-search" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 font-medium text-lg">Belum ada data obat.</p>
                            <p class="text-sm text-gray-400 mt-1">Klik tombol "Tambah Obat" untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-5 border-t border-gray-100 bg-gray-50/50">
            {{ $products->links() }}
        </div>
        @endif
        
    </div>
</div>