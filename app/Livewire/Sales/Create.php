<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
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

    public function getSearchResultsProperty() 
    {
        if (strlen($this->searchQuery) < 2) return collect(); 
        
        return Product::where('name', 'like', '%' . $this->searchQuery . '%')
                        ->where('stock', '>', 0)
                        ->take(5)
                        ->get();
    }

    // 3. ACTIONS -> Menjadi Public Functions
    public function addToCart($idProduct) 
    {
        if (!$idProduct) return; 

        $product = Product::find($idProduct); 
        $index = collect($this->cart)->search(fn($item) => $item['product_id'] == $product->id); 
        $totalQty = ($index !== false ? $this->cart[$index]['quantity'] : 0) + 1; 
        
        if ($product->stock < $totalQty) { 
            session()->flash('error', 'Stok ' . $product->name . ' tidak cukup! Tersisa: ' . $product->stock);
            return; 
        }

        if ($index !== false) { 
            $this->cart[$index]['quantity'] += (int)$this->qty;   
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
        } else {
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
        if (empty($this->cart) || $this->pembayaranMurni < $this->total) {
            session()->flash('error', 'Pastikan keranjang tidak kosong dan pembayaran mencukupi!');
            return;
        }

        DB::transaction(function ()  { 
            $sale = Sale::create([
                'invoice_number' => 'INV-' . date('YmdHis'),
                'customer_id'    => $this->customerId ?: null,
                'user_id'        => Auth::id() ?? 1, 
                'total_price'    => $this->total, 
                'pembayaran'     => $this->pembayaranMurni, 
                'kembalian'      => $this->kembalian 
            ]);

            foreach ($this->cart as $item) { 
                $sale->details()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'selling_price' => $item['unit_price'], // Pastikan nama kolom sesuai tabel sale_items
                    'subtotal' => $item['subtotal']
                ]);
                Product::find($item['product_id'])->decrement('stock', $item['quantity']);
            }
        });

        session()->flash('success', 'Transaksi Lunas!');
        return redirect()->route('sales.index');
    }

    public function render()
    {
        // $products sudah tidak wajib dikirim semua karena kita pakai $searchResults
        $customers = Customer::all(); 
        return view('livewire.sales.create', compact('customers'));
    }
}