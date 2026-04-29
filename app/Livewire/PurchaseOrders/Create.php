<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Layout('layouts.app')]
    #[Title('Buat Purchase Order')]

    public $items = []; 
    public $supplier_id = '';
    public $expected_date = '';
    public $notes = '';
    public $searchQuery = ''; 

    public function getSearchResultsProperty()
    {
        if (strlen($this->searchQuery) < 2) return collect();
        return Product::where('name', 'like', '%' . $this->searchQuery . '%')
            ->orWhere('sku', 'like', '%' . $this->searchQuery . '%')
            ->where('is_active', true)
            ->take(5)->get();
    }

    public function getTotalProperty()
    {
        if (empty($this->items)) return 0;
        return collect($this->items)->sum('subtotal');
    }

    public function updatedItems($value, $key)
    {
        $parts = explode('.', $key);
        if (count($parts) == 2) {
            $index = $parts[0]; 
            $this->syncItem($index); 
        }
    }
    
    public function loadKritis()
    {
        set_time_limit(120);

        $produkKritis = Product::withSum('batches', 'stock')
            ->where('min_stock', '>', 0)
            ->get()
            ->filter(function ($p) {
                $stokSekarang = $p->batches_sum_stock ?? 0;
                return $stokSekarang <= $p->min_stock;
            });

        if ($produkKritis->isEmpty()) {
            session()->flash('success', 'Semua stok obat aman sesuai batas minimal!');
            return;
        }

        $jumlahTerambil = 0;
        foreach ($produkKritis as $p) {
            if (!collect($this->items)->contains('product_id', $p->id)) {
                $stokSekarang = $p->batches_sum_stock ?? 0;
                $saranOrder = ($p->min_stock * 2) - $stokSekarang;

                $this->items[] = [
                    'product_id' => $p->id,
                    'name' => $p->name,
                    'quantity' => max(1, $saranOrder), 
                    'purchase_price' => 0, 
                    'subtotal' => 0
                ];
                $jumlahTerambil++;
            }
        }

        session()->flash('success', "Berhasil menarik {$jumlahTerambil} obat yang butuh restock!");
    }

    public function addToCart($idProduct)
    {
        $product = Product::find($idProduct);
        if (!$product) return;

        $index = collect($this->items)->search(fn($item) => $item['product_id'] === $product->id);
        
        if ($index !== false) {
            $this->items[$index]['quantity']++;
            $this->syncItem($index);
        } else {
            $this->items[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'purchase_price' => 0,
                'subtotal' => 0
            ];
        }
        $this->searchQuery = ''; 
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function syncItem($index)
    {
        $qty = (int) $this->items[$index]['quantity'];
        $price = (int) $this->items[$index]['purchase_price'];
        
        if ($qty < 1) $qty = 1;

        $this->items[$index]['quantity'] = $qty;
        $this->items[$index]['subtotal'] = $qty * $price;
    }

    public function savePO()
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        $this->validate([
            'supplier_id' => 'required',
            'expected_date' => 'required|date|after_or_equal:today',
        ], [
            'supplier_id.required' => 'Pilih Supplier dulu!',
            'expected_date.required' => 'Tanggal kedatangan wajib diisi!',
        ]);

        if (empty($this->items)) {
            session()->flash('error', 'Keranjang PO tidak boleh kosong!');
            return;
        }

        foreach ($this->items as $item) {
            if ($item['purchase_price'] <= 0) {
                session()->flash('error', "Masukkan estimasi harga beli untuk {$item['name']}!");
                return;
            }
        }

        try {
            DB::transaction(function () {
                $po = PurchaseOrder::create([
                    'po_number' => 'PO-' . date('YmdHis'),
                    'supplier_id' => $this->supplier_id,
                    'user_id' => Auth::id() ?? 1,
                    'order_date' => now()->format('Y-m-d'),
                    'expected_date' => $this->expected_date,
                    'status' => 'pending',
                    'total_amount' => $this->total,
                    'notes' => $this->notes,
                ]);

                foreach ($this->items as $item) {
                    $po->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'purchase_price' => $item['purchase_price'],
                        'subtotal' => $item['subtotal']
                    ]);
                }
            });

            session()->flash('success', 'Surat Purchase Order (PO) berhasil dibuat!');
            $this->items = []; 
            return redirect()->route('purchase-orders.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan PO: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.purchase-orders.create', [
            'suppliers' => Supplier::all()
        ]);
    }
}