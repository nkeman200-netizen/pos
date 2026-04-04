<?php

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use function Livewire\Volt\{state, computed};

// 1. STATE (Variabel)
state([ //ingantan browser yang tidak hilang jika tidak direfresh   
    'cart' => [],
    'customerId' => '',
    'pembayaran' => '',
    'selectedProductId' => '',
    'qty' => 1
]);

// Buat fungsi internal untuk mengubah "100.000" kembali jadi 100000
$pembayaranMurni = function () {
    return (int) str_replace('.', '', $this->pembayaran);
};
// 2. COMPUTED (Data Otomatis) kaya useEfect di spring, dia akan hitung ulang ketika state nya berubah
$pembayaranMurni = computed(fn () => (int) preg_replace('/[^0-9]/', '', (string)$this->pembayaran));
$total = computed(fn () => collect($this->cart)->sum('subtotal'));
$kembalian = computed(fn () => $this->pembayaranMurni - $this->total);

// 3. ACTIONS (Logika)
$addToCart = function () {
    if (!$this->selectedProductId) return; //kalo belum select product, tendang

    $product = Product::find($this->selectedProductId); //inisiasi objek product via id
    $index = collect($this->cart)->search(fn($item) => $item['product_id'] == $product->id); //pencariannya berhasil kirim true. $item diambil dari isi setiap $this->cart
    $totalQty=($index !== false ? $this->cart[$index]['quantity'] : 0) + (int)$this->qty; //total qty yang mau dimasukkan ke cart, kalo ternyata udah ada di cart, ya tinggal ditambahin quantitynya
    
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
    if (empty($this->cart) || $this->pembayaranMurni < $this->total) return; //cart ga boleh koosng, duit bayar ga boleh kurang

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
                    <select wire:model="selectedProductId" class="p-3 border rounded-xl outline-none">
                        <option value="">-- Pilih Barang --</option>
                        @foreach(App\Models\Product::where('stock', '>', 0)->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->stock }})</option>
                        @endforeach
                    </select>
                    <div class="flex gap-2">
                        <input type="number" wire:model="qty" class="w-20 p-3 border rounded-xl">
                        <button wire:click="addToCart" class="flex-1 bg-indigo-600 text-white rounded-xl">Tambah</button>
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
                        <tr>
                            <td class="px-4 py-3">{{ $item['name'] }}</td>
                            <td class="px-4 py-3">Rp{{ number_format($item['unit_price']) }}</td>
                            <td class="px-4 py-3">{{ $item['quantity'] }}</td>
                            <td class="px-4 py-3 font-bold">Rp{{ number_format($item['subtotal']) }}</td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="removeFromCart({{ $index }})" class="text-red-500">x</button>
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

                    <button wire:click="saveTransaction" class="w-2/3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg transition">
                        BAYAR
                    </button>          
            </div>
        </div>
    </div>
</div>