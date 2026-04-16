<div class="p-6 lg:p-8 bg-gray-50 min-h-screen">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}" class="p-2.5 bg-white dark:bg-slate-800 text-gray-500 hover:text-indigo-600 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-100 transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Master Obat</h2>
                <p class="text-sm text-gray-500">Form input profil dasar obat baru di apotek</p>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto bg-white  dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-indigo-600 p-4 flex items-start gap-3">
            <i data-lucide="info" class="w-5 h-5 text-indigo-200 mt-0.5"></i>
            <p class="text-indigo-100 text-sm font-medium leading-relaxed">
                <strong class="text-white">Info Penting:</strong> Di form ini Anda hanya mendaftarkan profil obat. <br>
                Untuk menambahkan <strong class="text-white">Stok, No. Batch, dan Expired Date</strong>, silakan lakukan melalui menu <b>Stok Masuk (Purchases)</b> setelah obat ini disimpan.
            </p>
        </div>

        <form wire:submit="save" class="p-6 sm:p-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sku" class="block text-sm font-semibold text-gray-700 mb-1.5">SKU / Barcode <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="barcode" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" id="sku" wire:model="sku" placeholder="Scan barcode kemasan..." 
                            class="w-full pl-10 p-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors text-sm font-mono">
                    </div>
                    @error('sku') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Obat <span class="text-red-500">*</span></label>
                    <input type="text" id="name" wire:model="name" placeholder="Contoh: Paracetamol 500mg" 
                        class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors text-sm">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Golongan Obat <span class="text-red-500">*</span></label>
                    <select id="category_id" wire:model="category_id" 
                        class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors text-sm">
                        <option value="">-- Pilih Golongan Obat --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="unit_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Satuan Kemasan <span class="text-red-500">*</span></label>
                    <select id="unit_id" wire:model="unit_id" 
                        class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors text-sm">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->short_name }})</option>
                        @endforeach
                    </select>
                    @error('unit_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="selling_price" class="block text-sm font-semibold text-gray-700 mb-1.5">Harga Jual / Satuan <span class="text-red-500">*</span></label>
                <div class="relative w-full md:w-1/2">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-500 text-sm font-bold">Rp</span>
                    </div>
                    <input type="number" id="selling_price" wire:model="selling_price" min="0" placeholder="0"
                        class="w-full pl-12 p-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors text-sm text-right font-mono font-bold text-gray-800">
                </div>
                <p class="text-xs text-gray-400 mt-1.5">Harga yang akan dikenakan ke pelanggan di kasir.</p>
                @error('selling_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-6 mt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all flex justify-center items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i> Simpan Master Obat
                </button>
                <button type="button" wire:click="resetForm" class="px-8 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl transition-colors">
                    Reset Form
                </button>
            </div>
            
        </form>
    </div>
</div>