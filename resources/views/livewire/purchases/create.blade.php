<div class="p-6 lg:p-8 bg-gray-50 min-h-screen">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a wire:ignore href="{{ route('purchases.index') }}" class="p-2.5 bg-white text-gray-500 hover:text-indigo-600 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-100 transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Catat Stok Masuk</h2>
                <p class="text-sm text-gray-500">Form pembelian obat dari supplier</p>
            </div>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col xl:grid xl:grid-cols-3 gap-6">
        
        <div class="xl:col-span-2 order-2 xl:order-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="package-plus" class="w-4 h-4 text-indigo-500"></i>
                        Detail Obat Masuk
                    </h3>
                    <button wire:ignore wire:click="addItem" class="text-sm font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Baris
                    </button>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full min-w-[800px] text-left border-collapse">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3 font-semibold w-12 text-center">#</th>
                                <th class="px-5 py-3 font-semibold min-w-[200px]">Produk Obat</th>
                                <th class="px-5 py-3 font-semibold w-32">Qty</th>
                                <th class="px-5 py-3 font-semibold w-40">Harga Beli</th>
                                <th class="px-5 py-3 font-semibold w-40 text-right">Subtotal</th>
                                <th class="px-5 py-3 w-16"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($items as $index => $item)
                            <tr wire:key="row-item-{{ $index }}" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-4 text-center text-sm text-gray-400 font-medium">
                                    {{ $index + 1 }}
                                </td>
                                
                                <td class="px-5 py-4">
                                    <div class="space-y-3">
                                        <select wire:model.live="items.{{ $index }}.product_id" class="w-full p-2 text-sm bg-white border border-gray-200 rounded-lg outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm">
                                            <option value="">-- Pilih Obat --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <input type="text" wire:model.live="items.{{ $index }}.batch_number" placeholder="No. Batch" class="w-full p-2 text-xs bg-white border border-red-200 rounded-lg outline-none focus:border-red-500 shadow-sm placeholder-gray-300" required>
                                            </div>
                                            <div>
                                                <input type="date" wire:model.live="items.{{ $index }}.expired_date" class="w-full p-2 text-xs bg-white border border-red-200 rounded-lg outline-none focus:border-red-500 shadow-sm" required title="Expired Date">
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 align-top pt-5">
                                    <div class="relative">
                                        <input type="number" wire:model.live="items.{{ $index }}.quantity" min="1" class="w-full p-2 text-sm text-center bg-white border border-gray-200 rounded-lg outline-none focus:border-indigo-500 shadow-sm">
                                    </div>
                                </td>

                                <td class="px-5 py-4 align-top pt-5">
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-500 text-sm pointer-events-none">Rp</span>
                                        <input type="number" wire:model.live="items.{{ $index }}.purchase_price" class="w-full pl-8 p-2 text-sm text-right bg-white border border-gray-200 rounded-lg outline-none focus:border-indigo-500 shadow-sm">
                                    </div>
                                </td>

                                <td class="px-5 py-4 align-top pt-7 text-right">
                                    <span class="font-bold text-gray-800">
                                        Rp{{ number_format(($items[$index]['quantity'] ?? 0) * ($items[$index]['purchase_price'] ?? 0)) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 align-top pt-5 text-center sticky right-0 bg-white z-10 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.03)] border-l border-gray-50">
                                    <button type="button" wire:click="removeItem({{ $index }})" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Baris">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach

                            @if(count($items) === 0)
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                        <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada obat yang ditambahkan.</p>
                                    <p class="text-sm text-gray-400 mt-1">Klik tombol "Tambah Baris" di atas untuk memulai.</p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 space-y-6 order-1 xl:order-2">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 pb-3 border-b border-gray-100">Informasi Faktur</h3>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Supplier <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="truck" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <select wire:model="supplier_id" class="w-full pl-10 p-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors text-sm">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">No. Faktur Pembelian <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="file-text" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" wire:model="purchase_number" class="w-full pl-10 p-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors text-sm font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Masuk <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="date" wire:model="purchase_date" class="w-full pl-10 p-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-600 p-6 rounded-2xl shadow-lg shadow-indigo-200 text-white {{ count($items) == 0 ? 'opacity-50 pointer-events-none' : '' }}">
                <p class="text-indigo-200 text-sm font-medium mb-1">Total Pembelian</p>
                <p class="text-4xl font-black mb-6">Rp{{ number_format($this->total) }}</p>
                
                <button wire:click="saveTransaction" class="w-full py-3.5 bg-white text-indigo-700 hover:bg-indigo-50 font-bold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Simpan Transaksi
                </button>
            </div>
        </div>

    </div>
</div>