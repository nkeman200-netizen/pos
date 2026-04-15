<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Layout('layouts.app')]
    #[Title('Kasir')]
    // 1. STATE -> Menjadi Public Properties
    public $cart = [];
    public $customerId = '';
    public $pembayaran = '';
    public $selectedProductId = '';
    public $qty = 1;
    public $searchQuery = ''; 

    // 2. COMPUTED -> Menjadi fungsi get...Property()
    public function getPembayaranMurniProperty() 
    {
        return (int) preg_replace('/[^0-9]/', '', (string)$this->pembayaran);
    }

    public function getTotalProperty() 
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function getKembalianProperty() 
    {
        return $this->pembayaranMurni - $this->total;
    }

    // Fitur pencarian otomatis
    public function getSearchResultsProperty()
    {
        if (strlen($this->searchQuery) < 2) return collect();
        return Product::with('unit')
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->orWhere('sku', 'like', '%' . $this->searchQuery . '%')
            ->where('is_active', true)
            ->take(5)->get();
    }

    // 3. ACTIONS -> Menjadi Public Functions
    public function addToCart($idProduct) 
    {
        if (!$idProduct) return; 

        $product = Product::find($idProduct); 
        if (!$product){
            session()->flash('error','Data product tidak ditemukan');return;
        };
        $index = collect($this->cart)->search(fn($item) => $item['product_id'] == $product->id); 
        $totalQty = ($index !== false ? $this->cart[$index]['quantity'] : 0) + 1; 
        
        
        if ($index !== false) { 
            if ($product->stock < $totalQty) { 
                session()->flash('error', 'Stok ' . $product->name . ' tidak cukup! Tersisa: ' . $product->stock);
                return; 
            }
            $this->cart[$index]['quantity'] += (int)$this->qty;   
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
        } else {
            if ($product->stock <1) {
                session()->flash('error','Stok product habis');return;
            };
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'unit_price' => $product->selling_price,
                'quantity' => (int)$this->qty,
                'subtotal' => (int)$this->qty * $product->selling_price
            ];
        }
        $this->selectedProductId = '';
        $this->searchQuery = ''; // Kosongkan kolom pencarian setelah ditambah
    }

    public function removeFromCart($index) 
    {
        array_splice($this->cart, $index, 1);
    }

    public function syncCart($index) 
    {
        $item = $this->cart[$index];
        $qty = (int) $item['quantity'];
        
        if ($qty < 1) $qty = 1;

        $product = Product::find($item['product_id']);
        
        if ($qty > $product->stock) {
            $qty = $product->stock;
            session()->flash('error', "Stok {$product->name} tidak cukup!");
        }

        $this->cart[$index]['quantity'] = $qty;
        $this->cart[$index]['subtotal'] = $qty * $item['unit_price'];
    }

    public function incrementQty($index) 
    {
        $this->cart[$index]['quantity']++;
        $this->syncCart($index);
    }

    public function decrementQty($index) 
    {
        if ($this->cart[$index]['quantity'] > 1) {
            $this->cart[$index]['quantity']--;
            $this->syncCart($index);
        }
    }

    public function saveTransaction()  
    {
        // Flash Session cocok ditaruh di sini (sebelum DB::transaction)
        // karena hanya memvalidasi inputan form dari kasir
        if (empty($this->cart) || $this->pembayaranMurni < $this->total) {
            session()->flash('error', 'Pastikan keranjang tidak kosong dan pembayaran mencukupi!');
            return;
        }

        try {
            // Mulai Transaksi Database
            DB::transaction(function ()  { 
                
                // 1. Simpan Sale (Invoice digenerate persis di detik ini!)
                $sale = Sale::create([
                    'invoice_number' => 'INV-' . date('YmdHis'),
                    'customer_id'    => $this->customerId ?: null,
                    'user_id'        => Auth::id() ?? 1, 
                    'total_price'    => $this->total, 
                    'pembayaran'     => $this->pembayaranMurni, 
                    'kembalian'      => $this->kembalian 
                ]);

                foreach ($this->cart as $item) { 
                    // 2. Simpan Sale Items
                    $sale->details()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'], 
                        'subtotal' => $item['subtotal']
                    ]);
                    
                    // 3. Algoritma FEFO
                    $qtyDibutuhkan = $item['quantity'];

                    $batches = ProductBatch::where('product_id', $item['product_id'])
                        ->where('stock', '>', 0)
                        ->where('expired_date', '>=', now())
                        ->orderBy('expired_date', 'asc')
                        ->lockForUpdate() // Kunci row ini agar tidak dicolong kasir lain saat bersamaan!
                        ->get();

                    foreach ($batches as $batch) {
                        if ($qtyDibutuhkan <= 0) break;

                        if ($batch->stock >= $qtyDibutuhkan) {
                            $batch->decrement('stock', $qtyDibutuhkan);
                            $qtyDibutuhkan = 0; 
                        } else {
                            $qtyDibutuhkan -= $batch->stock; 
                            $batch->update(['stock' => 0]); 
                        }
                    }
                    // REM DARURAT! Kalau setelah ngecek semua batch ternyata stok masih kurang
                    if ($qtyDibutuhkan > 0) {
                        // Exception ini akan membatalkan SEMUA perintah create() di atas secara otomatis!
                        throw new \Exception("Gagal! Stok {$item['name']} ternyata kurang saat diproses.");
                    }
                }   
            });
            // Kalau lolos dari DB::transaction tanpa Exception, berarti sukses!
            session()->flash('success', 'Transaksi Lunas!');
            return redirect()->route('sales.index');
        } catch (\Exception $e) {
            // Tangkap pesan Exception di atas, dan tampilkan ke layar kasir pakai Flash
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        // $products sudah tidak wajib dikirim semua karena kita pakai $searchResults
        $customers = Customer::all(); 
        return view('livewire.sales.create', compact('customers'));
    }
}