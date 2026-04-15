<div class="p-6 lg:p-8 bg-gray-50 min-h-screen font-sans">
    
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('sales.index') }}" class="p-2.5 bg-white text-gray-500 hover:text-indigo-600 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-100 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-icon lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Master Obat</h2>
                <p class="text-sm text-gray-500">Form input profil dasar obat baru di apotek</p>
            </div>
        </div>
        <div class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg font-bold font-mono text-sm border border-indigo-200">
            {{ date('d M Y') }}
        </div>
    </div>

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex flex-col xl:grid xl:grid-cols-3 gap-6">
        
        <div class="xl:col-span-2 flex flex-col gap-6">
            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative z-50">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pindai Barcode / Cari Nama Obat</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Ketik minimal 2 huruf..." autofocus
                        class="w-full pl-12 p-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-gray-800 font-medium text-lg">
                </div>

                @if(strlen($searchQuery) >= 2)
                    <div class="absolute w-full left-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-2xl overflow-hidden z-50">
                        @forelse($this->searchResults as $product)
                            <button wire:click="addToCart({{ $product->id }})" class="w-full text-left px-5 py-4 hover:bg-indigo-50 border-b border-gray-50 transition flex justify-between items-center group">
                                <div>
                                    <h4 class="font-bold text-gray-800 group-hover:text-indigo-700">{{ $product->name }}</h4>
                                    <p class="text-xs text-gray-500 font-mono mt-0.5">SKU: {{ $product->sku }} | Stok Aktif: {{ $product->stock }}</p>
                                </div>
                                <div class="font-black text-indigo-600">
                                    Rp{{ number_format($product->selling_price) }}
                                </div>
                            </button>
                        @empty
                            <div class="px-5 py-8 text-center text-gray-500">
                                <i data-lucide="package-x" class="w-8 h-8 mx-auto text-gray-300 mb-2"></i>
                                Obat tidak ditemukan atau stok habis.
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex-1 overflow-hidden flex flex-col">
                <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="shopping-cart" class="w-4 h-4 text-indigo-500"></i> Keranjang Belanja
                    </h3>
                </div>
                
                <div class="overflow-x-auto flex-1 min-h-75">
                    <table class="w-full text-left">
                        <thead class="bg-white border-b border-gray-100 text-xs text-gray-400 uppercase">
                            <tr>
                                <th class="px-5 py-4 font-bold">Produk</th>
                                <th class="px-5 py-4 font-bold text-right">Harga</th>
                                <th class="px-5 py-4 font-bold text-center w-40">Qty</th>
                                <th class="px-5 py-4 font-bold text-right">Subtotal</th>
                                <th class="px-4 py-4 w-12 text-center sticky right-0 bg-white shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.03)]"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($cart as $index => $item)
                            <tr wire:key="cart-item-{{ $index }}" class="hover:bg-gray-50/30 transition-colors">
                                <td class="px-5 py-4">
                                    <p class="font-bold text-gray-800">{{ $item['name'] }}</p>
                                </td>
                                <td class="px-5 py-4 text-right font-medium text-gray-600">
                                    Rp{{ number_format($item['unit_price']) }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center bg-gray-50 rounded-lg border border-gray-200 p-1">
                                        <button wire:click="decrementQty({{ $index }})" class="w-8 h-8 flex items-center justify-center rounded-md bg-white text-gray-600 hover:bg-gray-100 shadow-sm transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus-icon lucide-minus"><path d="M5 12h14"/></svg>
                                        </button>
                                        <input type="number" wire:model.blur="cart.{{ $index }}.quantity" wire:change="syncCart({{ $index }})" 
                                            class="w-12 text-center bg-transparent border-none outline-none font-bold text-gray-800 focus:ring-0 p-0 text-sm">
                                        <button wire:click="incrementQty({{ $index }})" class="w-8 h-8 flex items-center justify-center rounded-md bg-white text-indigo-600 hover:bg-indigo-50 shadow-sm transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right font-black text-gray-800">
                                    Rp{{ number_format($item['subtotal']) }}
                                </td>
                                <td class="px-4 py-4 text-center sticky right-0 bg-white shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.03)]">
                                    <button wire:click="removeFromCart({{ $index }})" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-5 py-20 text-center">
                                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 mb-4">
                                        <i data-lucide="shopping-basket" class="w-10 h-10 text-gray-300"></i>
                                    </div>
                                    <p class="text-gray-500 font-bold text-lg">Keranjang masih kosong</p>
                                    <p class="text-sm text-gray-400 mt-1">Cari obat atau gunakan scanner barcode.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pelanggan (Opsional)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <select wire:model="customerId" class="w-full pl-10 p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 transition font-medium text-sm">
                        <option value="">-- Pelanggan Umum --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="space-y-4">
                    
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total Tagihan</p>
                        <p class="text-4xl font-black text-indigo-600">Rp{{ number_format($this->total) }}</p>
                    </div>

                    <hr class="border-dashed border-gray-200">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Uang Diterima</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold text-lg pointer-events-none">Rp</span>
                            <input type="text" 
                                wire:model.live.debounce.300ms="pembayaran" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');"
                                placeholder="0" 
                                class="w-full pl-12 p-4 bg-white border-2 border-gray-200 rounded-xl outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition text-right font-black text-2xl text-gray-800">
                        </div>
                    </div>

                    <div class="flex justify-between items-center p-4 bg-{{ $this->kembalian >= 0 ? 'green' : 'red' }}-50 rounded-xl border border-{{ $this->kembalian >= 0 ? 'green' : 'red' }}-100">
                        <span class="font-bold text-{{ $this->kembalian >= 0 ? 'green' : 'red' }}-700 text-sm">
                            {{ $this->kembalian >= 0 ? 'Kembalian' : 'Kurang Bayar' }}
                        </span>
                        <span class="font-black text-{{ $this->kembalian >= 0 ? 'green' : 'red' }}-700 text-xl">
                            Rp{{ number_format(abs($this->kembalian)) }}
                        </span>
                    </div>

                </div>
            </div>

            <button wire:click="saveTransaction" 
                class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-lg rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex justify-center items-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed"
                {{ empty($cart) || $this->kembalian < 0 ? 'disabled' : '' }}>
                <i data-lucide="check-circle" class="w-6 h-6"></i>
                PROSES PEMBAYARAN
            </button>

        </div>
    </div>
</div>