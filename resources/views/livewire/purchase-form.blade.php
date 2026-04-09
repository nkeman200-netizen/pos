<?php

use App\Models\{Purchase, Product, Supplier};
use Illuminate\Support\Facades\{DB, Auth};
use Livewire\Volt\Component;

new class extends Component {
    // State form
    public $supplier_id;
    public $purchase_number;
    public $purchase_date;
    public $items = [];
    public $purchaseId; // Penanda jika sedang Edit

    public function mount($purchase = null)
    {
        if ($purchase) {
            // Mode Edit: Ambil data dari model Purchase
            $this->purchaseId = $purchase->id;
            $this->supplier_id = $purchase->supplier_id;
            $this->purchase_number = $purchase->purchase_number;
            $this->purchase_date = $purchase->purchase_date;
            $this->items = $purchase->details->map(fn($item) => [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'cost_price' => $item->cost_price,
            ])->toArray();
        } else {
            // Mode Create: Inisialisasi awal
            $this->purchase_date = date('Y-m-d');
            $this->items = [['product_id' => '', 'quantity' => 1, 'cost_price' => 0]];
        }
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'cost_price' => 0];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items); // Reset index array
        }
    }

    // Properti Computed untuk menghitung total belanja secara real-time
    public function getTotalProperty()
    {
        return collect($this->items)->sum(fn($item) => $item['quantity'] * $item['cost_price']);
    }

    public function save()
    {
        // Ambil aturan validasi dari StorePurchaseRequest
        $rules = [
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_number' => 'required|unique:purchases,purchase_number,' . ($this->purchaseId ?? 'NULL'),
            'purchase_date' => 'required|date',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            // 1. Logika simpan/update Purchase
            $purchase = Purchase::updateOrCreate(
                ['id' => $this->purchaseId],
                [
                    'supplier_id' => $this->supplier_id,
                    'purchase_number' => $this->purchase_number,
                    'purchase_date' => $this->purchase_date,
                    'user_id' => Auth::id(),
                    'total_cost' => $this->total,
                ]
            );

            // 2. Jika Edit, kembalikan stok lama sebelum dihapus
            if ($this->purchaseId) {
                foreach ($purchase->details as $oldItem) {
                    $oldItem->product->decrement('stock', $oldItem->quantity);
                }
                $purchase->details()->delete();
            }

            // 3. Simpan item baru dan update stok
            foreach ($this->items as $item) {
                $purchase->details()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                    'subtotal' => $item['quantity'] * $item['cost_price'],
                ]);

                Product::find($item['product_id'])->increment('stock', $item['quantity']);
            }
        });

        session()->flash('success', 'Transaksi berhasil disimpan!');
        return redirect()->route('purchase.index');
    }

    public function with()
    {
        return [
            'suppliers' => Supplier::all(),
            'products' => Product::all(),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-2">Supplier</label>
            <select wire:model="supplier_id" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Pilih Supplier</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-2">No. Faktur</label>
            <input type="text" wire:model="purchase_number" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-2">Tanggal</label>
            <input type="date" wire:model="purchase_date" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase">
                <tr>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4 text-center">Qty</th>
                    <th class="px-6 py-4 text-right">Harga Beli</th>
                    <th class="px-6 py-4 text-right">Subtotal</th>
                    <th class="px-6 py-4 text-center"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($items as $index => $item)
                <tr>
                    <td class="px-6 py-4">
                        <select wire:model="items.{{ $index }}.product_id" class="w-full p-2 border-b outline-none">
                            <option value="">Pilih Obat</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" wire:model.live="items.{{ $index }}.quantity" class="w-full text-center outline-none">
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" wire:model.live="items.{{ $index }}.cost_price" class="w-full text-right outline-none">
                    </td>
                    <td class="px-6 py-4 text-right font-bold">
                        Rp{{ number_format($item['quantity'] * $item['cost_price']) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button type="button" wire:click="removeItem({{ $index }})" class="text-red-500"><i data-lucide="trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 bg-gray-50 flex justify-between items-center">
            <button type="button" wire:click="addItem" class="text-indigo-600 font-bold">+ Tambah Baris</button>
            <div class="text-right">
                <span class="text-gray-500">Total Biaya:</span>
                <span class="text-2xl font-black text-indigo-600 ml-2">Rp{{ number_format($this->total) }}</span>
            </div>
        </div>
    </div>

    <div class="flex justify-end">
        <button wire:click="save" class="bg-indigo-600 text-white px-10 py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">
            Simpan Transaksi
        </button>
    </div>
</div>