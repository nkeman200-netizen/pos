<?php

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use function Livewire\Volt\{state, mount, computed};

// 1. STATE (Variabel)
// KITA UBAH: Jangan simpan seluruh object 'sale', cukup simpan ID dan Invoice-nya saja biar Livewire gak bingung!
state([ //state yang disimpen di memori
    'sale_id' => '', 
    'invoice_number' => '',
    'cart' => [],
    'customerId' => '',
    'pembayaran' => '',
    'selectedProductId' => '',
    'qty' => 1
]);

// 2. MOUNT (Dijalankan sekali saat halaman dibuka)
mount(function (Sale $sale) { //tangkep sale yang dikirim dari blade edit
    $this->sale_id = $sale->id; //isi state
    $this->invoice_number = $sale->invoice_number;
    $this->customerId = $sale->customer_id;
    $this->pembayaran = $sale->pembayaran; 

    // WAJIB: Load relasi detail beserta produknya supaya 'name' bisa kebaca!
    $sale->load('details.product');// Eager load untuk menghindari N+1 problem saat akses $item->product->name

    // Masukkan detail belanjaan lama ke array $cart
    foreach ($sale->details as $item) {
        $this->cart[] = [
            'product_id' => $item->product_id,
            'name'       => $item->product->name, // Sekarang ini aman karena relasi product sudah diload
            'unit_price' => $item->unit_price,
            'quantity'   => $item->quantity,
            'subtotal'   => $item->subtotal
        ];
    }
});

// 3. COMPUTED (Otomatis menghitung total)
$pembayaranMurni = computed(fn () => (int) preg_replace('/[^0-9]/', '', (string)$this->pembayaran));
$total = computed(fn () => collect($this->cart)->sum('subtotal'));
$kembalian = computed(fn () => $this->pembayaranMurni - $this->total);

// 4. ACTIONS (Logika)
$addToCart = function () {
    if (!$this->selectedProductId) return;

    $product = Product::find($this->selectedProductId);
    if ($product->stock < $this->qty) return session()->flash('error', 'Stok tidak cukup!');

    $index = collect($this->cart)->search(fn($item) => $item['product_id'] == $product->id);

    if ($index !== false) {
        $this->cart[$index]['quantity'] += (int)$this->qty;
        $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
    } else {
        $this->cart[] = [
            'product_id' => $product->id,
            'name'       => $product->name,
            'unit_price' => $product->selling_price,
            'quantity'   => (int)$this->qty,
            'subtotal'   => (int)$this->qty * $product->selling_price
        ];
    }
    $this->selectedProductId = '';
    $this->qty = 1;
};

$removeFromCart = function ($index) {
    unset($this->cart[$index]);
    $this->cart = array_values($this->cart);
};

// LOGIKA UPDATE YG SUDAH ANTI-BUG
$updateTransaction = function () {
    if (empty($this->cart)) return session()->flash('error', 'Keranjang tidak boleh kosong!');
    if ($this->pembayaran < $this->total) return session()->flash('error', 'Uang bayar kurang!');

    // Panggil ulang data Sale asli dari database menggunakan ID yang disimpan
    $sale = Sale::findOrFail($this->sale_id);

    DB::transaction(function () use ($sale) {
        
        // TAHAP 1: KEMBALIKAN STOK LAMA
        foreach ($sale->details as $oldItem) {
            $oldItem->product->increment('stock', $oldItem->quantity);
        }

        // TAHAP 2: HAPUS DETAIL LAMA
        $sale->details()->delete();

        // TAHAP 3: INPUT DETAIL BARU & POTONG STOK
        foreach ($this->cart as $item) {
            $sale->details()->create([
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal'   => $item['subtotal']
            ]);
            
            Product::find($item['product_id'])->decrement('stock', $item['quantity']);
        }

        // TAHAP 4: UPDATE HEADER
        $sale->update([
            'customer_id' => $this->customerId ?: null,
            'total_price' => $this->total,
            'pembayaran'  => $this->pembayaran,
            'kembalian'   => $this->kembalian
        ]);
    });

    return redirect()->route('sales.index')->with('success', 'Transaksi Berhasil Diupdate!');
};

?>

<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('sales.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-indigo-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 19-7-7 7-7"/>
                <path d="M19 12H5"/>
            </svg>
            Kembali ke Riwayat
        </a>
    </div>
    <!-- Alert Error -->
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl font-bold">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Input Produk -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-700">Edit Keranjang</h3>
                    <span class="text-xs font-mono bg-indigo-100 text-indigo-700 px-2 py-1 rounded">
                        {{ $invoice_number }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <select wire:model="selectedProductId" class="p-3 border rounded-xl outline-none">
                        <option value="">-- Tambah Barang Baru --</option>
                        @foreach(App\Models\Product::where('stock', '>', 0)->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (Sisa: {{ $p->stock }})</option>
                        @endforeach
                    </select>
                    <div class="flex gap-2">
                        <input type="number" wire:model="qty" min="1" class="w-20 p-3 border rounded-xl">
                        <button wire:click="addToCart" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition">Tambah</button>
                    </div>
                </div>
            </div>

            <!-- Tabel Keranjang -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-sm font-bold text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3 text-right">Harga</th>
                            <th class="px-4 py-3 text-center">Qty</th>
                            <th class="px-4 py-3 text-right">Subtotal</th>
                            <th class="px-4 py-3 text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cart as $index => $item) 
                        {{-- //foreach yg punya fitur empty kalau array kosong --}}
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $item['name'] }}</td>
                            <td class="px-4 py-3 text-right">Rp{{ number_format($item['unit_price']) }}</td>
                            <td class="px-4 py-3 text-center font-bold text-indigo-600">{{ $item['quantity'] }}</td>
                            <td class="px-4 py-3 text-right font-bold">Rp{{ number_format($item['subtotal']) }}</td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="removeFromCart({{ $index }})" class="text-red-500 hover:bg-red-50 p-2 rounded-lg">x</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-red-400 italic">Keranjang kosong! Hati-hati, transaksi tidak bisa disimpan kalau kosong.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Checkout Edit -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4 sticky top-6">
                <h3 class="font-bold text-gray-700 border-b pb-2">Informasi Pembayaran</h3>
                
                <select wire:model="customerId" class="w-full p-3 border rounded-xl">
                    <option value="">Umum (Bukan Member)</option>
                    @foreach(App\Models\Customer::all() as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                
                <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                    <span class="text-xs text-yellow-600 uppercase font-bold tracking-wider">Total Baru</span>
                    <div class="text-3xl font-black text-yellow-700">Rp{{ number_format($this->total) }}</div>
                </div>

                <input 
                    type="text" 
                    x-mask:dynamic="$money($input, '.', ',')" 
                    wire:model.live.debounce.300ms="pembayaran" 
                    placeholder="Uang Bayar" 
                    class="w-full p-4 text-2xl border-2 rounded-xl"
                >  
                <div class="flex justify-between font-bold px-2">
                    <span class="text-gray-500">Kembalian:</span>
                    <span class="{{ $this->kembalian < 0 ? 'text-red-500' : 'text-green-600' }} text-xl">Rp{{ number_format($this->kembalian) }}</span>
                </div>

                <button wire:click="updateTransaction" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-4 rounded-xl shadow-lg transition uppercase tracking-wider">
                    Update Transaksi
                </button>
            </div>
        </div>
    </div>
</div>