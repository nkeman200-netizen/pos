<div x-data="purchaseForm()">
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Pilih Supplier</label>
                <select name="supplier_id" required class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($supplier as $s)
                        <option value="{{ $s->id }}" {{ (isset($purchase) && $purchase->supplier_id == $s->id) ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">No. Faktur</label>
                <input type="text" name="purchase_number" required value="{{ $purchase->purchase_number ?? '' }}" placeholder="INV-XXXX" 
                    class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Tanggal</label>
                <input type="date" name="purchase_date" value="{{ $purchase->purchase_date ?? date('Y-m-d') }}" required 
                    class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Daftar Barang Masuk</h3>
                <button type="button" @click="addItem" class="text-sm bg-indigo-50 text-indigo-600 px-4 py-2 rounded-lg font-bold hover:bg-indigo-100 transition">
                    + Tambah Baris
                </button>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase font-bold">
                    <tr>
                        <th class="px-6 py-3 text-left">Produk</th>
                        <th class="px-6 py-3 text-center w-24">Qty</th>
                        <th class="px-6 py-3 text-right w-48">Harga Beli</th>
                        <th class="px-6 py-3 text-right w-48">Subtotal</th>
                        <th class="px-6 py-3 text-center w-16"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <select :name="`items[${index}][product_id]`" x-model="item.product_id" required class="w-full p-2 border-b focus:border-indigo-500 outline-none bg-transparent">
                                    <option value="">Cari Obat...</option>
                                    @foreach($product as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" :name="`items[${index}][quantity]`" x-model.number="item.quantity" min="1" required 
                                    class="w-full text-center p-2 border-b focus:border-indigo-500 outline-none bg-transparent">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" :name="`items[${index}][cost_price]`" x-model.number="item.cost_price" min="0" required 
                                    class="w-full text-right p-2 border-b focus:border-indigo-500 outline-none bg-transparent">
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800">
                                Rp<span x-text="formatNumber(item.quantity * item.cost_price)"></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button type="button" @click="removeItem(index)" class="text-red-400 hover:text-red-600 transition">
                                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr class="bg-indigo-50/50">
                        <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-600 uppercase">Total Biaya</td>
                        <td class="px-6 py-4 text-right font-black text-indigo-600 text-xl">
                            Rp<span x-text="formatNumber(totalCost)"></span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@php
    $initialItems = isset($purchase) 
        ? $purchase->details->map(fn($d) => [
            'product_id' => $d->product_id,
            'quantity'   => $d->quantity,
            'cost_price' => $d->cost_price,
        ]) 
        : [['product_id' => '', 'quantity' => 1, 'cost_price' => 0]];
@endphp
<script>
    function purchaseForm() {
        return {
            // Jika ada data purchase (Edit), ambil detailnya. Jika tidak (Create), mulai dengan 1 baris kosong.
            items: @json($initialItems),
            
            addItem() {
                this.items.push({ product_id: '', quantity: 1, cost_price: 0 });
                setTimeout(() => lucide.createIcons(), 50);
            },
            removeItem(index) {
                if(this.items.length > 1) this.items.splice(index, 1);
            },
            get totalCost() {
                return this.items.reduce((total, item) => total + (item.quantity * item.cost_price), 0);
            },
            formatNumber(n) {
                return new Intl.NumberFormat('id-ID').format(n);
            }
        }
    }
</script>