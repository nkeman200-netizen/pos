<?php

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use function Livewire\Volt\{state, computed};
use function Livewire\Volt\{updated};

// 1. STATE (Variabel)
state([ //ingantan browser yang tidak hilang jika tidak direfresh   
    'cart' => [],
    'customerId' => '',
    'pembayaran' => '',
    'selectedProductId' => '',
    'qty' => 1,
    'searchQuery' => '',     // BARU: Untuk nangkep ketikan pencarian
]);

// 2. COMPUTED (Data Otomatis) kaya useEfect di spring, dia akan hitung ulang ketika state nya berubah
$pembayaranMurni = computed(fn () => (int) preg_replace('/[^0-9]/', '', (string)$this->pembayaran));
$total = computed(fn () => collect($this->cart)->sum('subtotal'));
$kembalian = computed(fn () => $this->pembayaranMurni - $this->total);
// BARU: Otomatis cari produk berdasarkan ketikan (Dibatasi 5 hasil saja biar cepat)
$searchResults = computed(function () {
    if (strlen($this->searchQuery) < 2) return collect(); // Jangan nyari kalau ketikan kurang dari 2 huruf
    
    return Product::where('name', 'like', '%' . $this->searchQuery . '%')
                    ->where('stock', '>', 0)
                    ->take(5) // Ambil 5 teratas
                    ->get();
});


// 3. ACTIONS (Logika)
$addToCart = function ($idProduct) {

    if (!$idProduct) return; //kalo belum select product, tendang

    $product = Product::find($idProduct); //inisiasi objek product via id
    $index = collect($this->cart)->search(fn($item) => $item['product_id'] == $product->id); //pencariannya berhasil kirim true. $item diambil dari isi setiap $this->cart
    $totalQty=($index !== false ? $this->cart[$index]['quantity'] : 0) + 1; //total qty yang mau dimasukkan ke cart, kalo ternyata udah ada di cart, ya tinggal ditambahin quantitynya
    
    if ($product->stock < $totalQty) { //cek dulu stoknya, kalo kurang, ya kasih tau error
        session()->flash('error', 'Stok ' . $product->name . ' tidak cukup! Tersisa: ' . $product->stock);
        return; // Berhenti di sini, jangan lanjut ke bawah
    }
    // index akan berupa sebuah index dari array cart. atau berupa boolean false
    if ($index !== false) { //cek dgn strict cek, karena index 0 juga berarti false
        $this->cart[$index]['quantity'] += (int)$this->qty; //kalo ada di cart, update quantitynya dan subtoralnya  
        $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
    } else {//kalo indexnya gaada, ya tinggal buat baru masukin cart
        $this->cart[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'unit_price' => $product->selling_price,
            'quantity' => (int)$this->qty,
            'subtotal' => (int)$this->qty * $product->selling_price
        ];
    }
    $this->selectedProductId = '';
};

$removeFromCart = function ($index) {//array splice untuk menghapus data di array berdasarkan index dan otomatis menggeser index setelahnya ke kiri
    array_splice($this->cart,$index,1);//parameter 1 untuk array apa, 2 untuk index berapa, 3 berapa banyak yang mau dihapus
};

$saveTransaction = function ()  {
    if (empty($this->cart) || $this->pembayaranMurni < $this->total) {
        session()->flash('error', 'Pastikan keranjang tidak kosong dan pembayaran mencukupi!');
        return;
    }; //cart ga boleh koosng, duit bayar ga boleh kurang

    DB::transaction(function ()  { //kerjakan semua, atau batalkan semua jika gagal di tengah
        $sale = Sale::create([//buat tabel sales
            'invoice_number' => 'INV-' . date('YmdHis'),
            'customer_id'    => $this->customerId ?: null,
            'user_id'        => Auth::id() ?? 1, //ambil user yang lagi login
            'total_price'    => $this->total, //ambil dari computed
            'pembayaran'     => $this->pembayaranMurni, //dari state
            'kembalian'      => $this->kembalian //ambil dari computed
        ]);

        foreach ($this->cart as $item) { //looping buat tabel detailSale via relasi metode
            $sale->details()->create($item);
            Product::find($item['product_id'])->decrement('stock', $item['quantity']);
        }
    });

    return redirect()->route('sales.index')->with('success', 'Lunas!');
};


// FUNGSI TAMBAH QTY DI KERANJANG

$syncCart = function ($index) {
    $item = $this->cart[$index];
    $qty = (int) $item['quantity'];
    
    // 1. Minimal 1
    if ($qty < 1) $qty = 1;

    // 2. Cek Stok (Logika Edit vs Create)
    $product = Product::find($item['product_id']);
    
    if ($qty > $product->stock) {
        $qty = $product->stock;
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif  

            @if (session()->has('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span class="font-bold">{{ session('success') }}</span>
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

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-sm font-bold text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Subtotal</th>
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
                                <button type="button" wire:click="removeFromCart({{ $index }})" class="text-red-500 hover:bg-red-50 p-2 rounded-lg">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <select wire:model="customerId" class="w-full p-3 border rounded-xl">
                    <option value="">Umum</option>
                    @foreach(App\Models\Customer::all() as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                <div class="text-3xl font-black text-indigo-700">Rp{{ number_format($this->total) }}</div>
                <input 
                    type="text" 
                    x-mask:dynamic="$money($input, '.', ',')" 
                    wire:model.live.debounce.300ms="pembayaran" 
                    placeholder="Uang Bayar" 
                    class="w-full p-4 text-2xl border-2 rounded-xl"
                >                
                <div class="flex justify-between font-bold">
                    <span>Kembali:</span>
                    <span class="{{ $this->kembalian < 0 ? 'text-red-500' : 'text-green-600' }}">Rp{{ number_format($this->kembalian) }}</span>
                </div>

                    <button wire:click="saveTransaction" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg transition">
                        BAYAR
                    </button>          
            </div>
        </div>
    </div>
</div>