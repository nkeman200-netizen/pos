<?php

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use function Livewire\Volt\{state, mount, computed};
use function Livewire\Volt\{updated};

// 1. STATE (Variabel)
// KITA UBAH: Jangan simpan seluruh object 'sale', cukup simpan ID dan Invoice-nya saja biar Livewire gak bingung!
state([ //state yang disimpen di memori
    'sale_id' => '', 
    'invoice_number' => '',
    'cart' => [],
    'customerId' => '',
    'pembayaran' => '',
    'selectedProductId' => '',
    'qty' => 1,
    'stokLama' => [], //state tambahan untuk menyimpan stok lama sebelum diedit, biar bisa dibandingkan saat update
    'searchQuery' => '', 
]);

// 2. MOUNT (Dijalankan sekali saat halaman dibuka)
mount(function (Sale $sale) { //tangkep sale yang dikirim dari blade edit
    $this->sale_id = $sale->id; //isi state
    $this->invoice_number = $sale->invoice_number;
    $this->customerId = $sale->customer_id;
    $this->pembayaran = $sale->pembayaran; 
    $this->stokLama = $sale->details->pluck('quantity', 'product_id')->toArray(); // Simpan stok lama dalam format [product_id => quantity]
    //buat array asosiatif sederhana. stokLama=[10=>5, 12=>2, 13=>4]

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
$searchResults = computed(function () {
    if (strlen($this->searchQuery) < 2) return collect(); // Jangan nyari kalau ketikan kurang dari 2 huruf
    
    return Product::where('name', 'like', '%' . $this->searchQuery . '%')
                    ->where('stock', '>', 0)
                    ->take(5) // Ambil 5 teratas
                    ->get();
});
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
    if ($this->pembayaranMurni < $this->total) return session()->flash('error', 'Uang bayar kurang!');

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
            'pembayaran'  => $this->pembayaranMurni,
            'kembalian'   => $this->kembalian
        ]);
    });

    return redirect()->route('sales.index')->with('success', 'Transaksi Berhasil Diupdate!');
};

// FUNGSI TAMBAH QTY DI KERANJANG

$syncCart = function ($index) {
    $item = $this->cart[$index];
    $qty = (int) $item['quantity'];
    
    // 1. Minimal 1
    if ($qty < 1) $qty = 1;

    // 2. Cek Stok (Logika Edit vs Create)
    $product = Product::find($item['product_id']);
    
    // Jika di halaman EDIT, gunakan stokLama sebagai tambahan
    $stokAwal = isset($this->stokLama) ? ($this->stokLama[$product->id] ?? 0) : 0;
    $stokTersedia = $product->stock + $stokAwal;

    if ($qty > $stokTersedia) {
        $qty = $stokTersedia;
        session()->flash('error', "Stok {$product->name} tidak cukup!");
    }

    // 3. Update data di array cart
    $this->cart[$index]['quantity'] = $qty;
    $this->cart[$index]['subtotal'] = $qty * $item['unit_price'];
};

// Fungsi tombol +
$incrementQty = function ($index) {
    $this->cart[$index]['quantity']++;
    $this->syncCart($index);
};

// Fungsi tombol -
$decrementQty = function ($index) {
    if ($this->cart[$index]['quantity'] > 1) {
        $this->cart[$index]['quantity']--;
    $this->syncCart($index);
    }
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
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Input Produk -->
        <div class="lg:col-span-2 space-y-6">
            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl font-bold">
                    {{ session('error') }}
                </div>
            @endif
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative w-full" x-data="{ open: false }">
                        {{-- xdata adalah deklarasi variabel, yg nanti dipake di hasil search--}}
                        <div class="relative">
                            <div wire:ignore class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                {{-- wire ignor, agar ga corupt DOM nya --}}
                                <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                wire:model.live.debounce.300ms="searchQuery" 
                                @focus="open = true" 
                                {{-- kalo diklik, hasil search nya dibuka --}}
                                @click.outside="open = false" 
                                {{-- kalo klik di luar input, hasil search nya ditutup --}}
                                placeholder="Ketik nama obat (min. 2 huruf)..." 
                                class="w-full pl-10 p-3 border rounded-xl outline-none focus:border-indigo-500 transition"
                            >
                            
                            <div wire:loading 
                                {{-- jalankan aksi ketika ada loading --}}
                                wire:target="searchQuery" 
                                {{-- loading mana? loading targetnya searchQuery --}}
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <i data-lucide="loader-2" class="w-5 h-5 text-indigo-500 animate-spin"></i>
                            </div>
                        </div>

                        <div 
                            x-show="open && $wire.searchQuery.length >= 2" 
                            {{-- yang tadinya display none, pake xshow open (true) jadi kebuka --}}
                            x-transition
                            class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden"
                            style="display: none;" 
                        >
                            <ul class="max-h-60 overflow-y-auto divide-y divide-gray-100">
                                @forelse($this->searchResults as $product)
                                    <li 
                                        wire:click="addToCart({{ $product->id }}); $set('searchQuery', ''); open = false"
                                        class="p-3 hover:bg-indigo-50 cursor-pointer transition flex justify-between items-center"
                                    >
                                        <div>
                                            <div class="font-bold text-gray-700">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500">Stok: {{ $product->stock }} | Rp{{ number_format($product->selling_price) }}</div>
                                        </div>
                                        <div class="text-indigo-600">
                                            <i data-lucide="plus-circle" class="w-5 h-5"></i>
                                        </div>
                                    </li>
                                @empty
                                    <li class="p-4 text-center text-gray-500 text-sm">
                                        Tidak menemukan obat: "<span class="font-bold">{{ $searchQuery }}</span>"
                                    </li>
                                @endforelse
                            </ul>
                        </div>
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
                        @foreach($cart as $index => $item)
                        {{-- WAJIB: wire:key supaya Livewire tidak bingung baris mana yang berubah --}}
                        <tr wire:key="cart-item-{{ $item['product_id'] }}" class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium">{{ $item['name'] }}</td>
                            <td class="px-4 py-3 text-right">Rp{{ number_format($item['unit_price']) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-3">
                                    <button type="button" wire:click="decrementQty({{ $index }})" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-red-100 rounded-full text-gray-600 hover:text-red-600 font-black transition">
                                        -
                                    </button>
                                    
                                    <input 
                                        type="number" 
                                        {{-- 1. Menyimpan data secara 'deferred' (ditahan dulu, tidak langsung dikirim ke server) --}}
                                        wire:model="cart.{{ $index }}.quantity"
                                        
                                        {{-- 2. Memaksa memanggil method syncCart setelah user berhenti ngetik selama 500ms --}}
                                        wire:input.debounce.300ms="syncCart({{ $index }})"
                                        class="w-12 text-center font-bold text-indigo-600 text-lg bg-transparent border-b-2 border-transparent focus:border-indigo-600 outline-none transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                    >
                                    
                                    <button type="button" wire:click="incrementQty({{ $index }})" class="w-8 h-8 flex items-center justify-center bg-indigo-50 hover:bg-indigo-100 rounded-full text-indigo-600 font-black transition">
                                        +
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-bold">Rp{{ number_format($item['subtotal']) }}</td>
                            <td class="px-4 py-3 text-center">
                                <button wire:ignore type="button" wire:click="removeFromCart({{ $index }})" class="text-red-500 hover:bg-red-50 p-2 rounded-lg">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
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