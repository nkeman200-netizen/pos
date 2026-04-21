<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Katalog Obat</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola master data obat, harga, dan pantau total stok aktif.</p>
        </div>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2 whitespace-nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Tambah Obat
        </a>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border-l-4 border-green-500 text-green-700 dark:text-green-400 rounded-r-xl flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-icon lucide-circle-check"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-500/10 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded-r-xl flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        
        <div class="p-5 border-b border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex flex-col sm:flex-row justify-between gap-4">
            <div class="relative w-full sm:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama obat atau SKU..." 
                    class="w-full pl-10 p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 dark:text-white transition-colors text-sm">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left border-collapse">
                <thead class="bg-gray-50/80 dark:bg-slate-900/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-semibold">SKU / Barcode</th>
                        <th class="px-6 py-4 font-semibold">Nama Obat</th>
                        <th class="px-6 py-4 font-semibold">Golongan</th>
                        <th class="px-6 py-4 font-semibold text-right">Harga Jual</th>
                        <th class="px-6 py-4 font-semibold text-center">Stok Total</th>
                        @if(auth()->user()->role === 'admin')
                        <th class="px-6 py-4 font-semibold text-center w-24">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
                        
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded-md border border-gray-200 dark:border-slate-600">
                                {{ $product->sku }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800 dark:text-gray-100">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Satuan: {{ $product->unit->name }}</div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full whitespace-nowrap border 
                                {{ $product->category->name == 'Obat Bebas' ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-500/10 dark:text-green-400 dark:border-green-500/20' : 
                                ($product->category->name == 'Obat Bebas Terbatas' ? 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20' : 
                                ($product->category->name == 'Obat Keras' ? 'bg-red-50 text-red-700 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20' : 
                                'bg-gray-50 text-gray-700 border-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600')) }}">
                                {{ $product->category->name }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-gray-800 dark:text-gray-100">Rp{{ number_format($product->selling_price) }}</span>
                        </td>

                        
                        <td class="px-6 py-4 text-center">
                            @if($product->stock > 10)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-green-50 text-green-700 font-bold text-sm border border-green-200 dark:bg-green-500/10 dark:text-green-400 dark:border-green-500/20">
                                    {{ $product->stock }} <span class="font-normal text-xs">{{ $product->unit->short_name }}</span>
                                </span>
                            @elseif($product->stock > 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-yellow-50 text-yellow-700 font-bold text-sm border border-yellow-200 dark:bg-yellow-500/10 dark:text-yellow-400 dark:border-yellow-500/20">
                                    {{ $product->stock }} <span class="font-normal text-xs">{{ $product->unit->short_name }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-red-50 text-red-700 font-bold text-sm border border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20">
                                    Habis
                                </span>
                            @endif
                        </td>
                        @if(auth()->user()->role === 'admin')
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/20 rounded-lg transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </a>
                                <button wire:click="delete({{ $product->id }})" wire:confirm="Hapus data obat ini?" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/20 rounded-lg transition-colors" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-search-icon lucide-package-search"><path d="M12 22V12"/><path d="M20.27 18.27 22 20"/><path d="M21 10.498V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.729l7 4a2 2 0 0 0 2 .001l.98-.559"/><path d="M3.29 7 12 12l8.71-5"/><path d="m7.5 4.27 8.997 5.148"/><circle cx="18.5" cy="16.5" r="2.5"/></svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-lg">Belum ada data obat.</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Klik tombol "Tambah Obat" untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-5 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800">
            {{ $products->links() }}
        </div>
        @endif
        
    </div>
</div>