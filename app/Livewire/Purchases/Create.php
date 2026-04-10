<?php
namespace App\Livewire\Purchases;

use App\Models\{Purchase, Product, Supplier};
use Illuminate\Support\Facades\{DB, Auth};
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Layout('layouts.app')]
    #[Title('Pembelian baru')]
    public $supplier_id, $purchase_number, $purchase_date;
    public $items = []; // Ini rahasia "sat-set" kita

    public function mount()
    {
        $this->purchase_date = date('Y-m-d');

        // AUTO-GENERATE NOMOR FAKTUR
        // Format: PUR-YYYYMMDD-0001
        $today = date('Ymd'); 
        $latestPurchase = Purchase::latest()->first();
        $nextId = $latestPurchase ? $latestPurchase->id + 1 : 1;
        
        $this->purchase_number = 'PUR-' . $today . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        
        // Mulai dengan satu baris kosong
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'cost_price' => 0];
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'cost_price' => 0];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    // Hitung total otomatis (Computed Property)
    public function getTotalProperty()
    {
        return collect($this->items)->sum(function ($item) {
            return ($item['quantity'] ?? 0) * ($item['cost_price'] ?? 0);
        });
    }

    public function save()
    {
        $this->validate([
            'supplier_id' => 'required',
            'purchase_number' => 'required|unique:purchases,purchase_number',
            'items.*.product_id' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () {
            $purchase = Purchase::create([
                'supplier_id' => $this->supplier_id,
                'purchase_number' => $this->purchase_number,
                'purchase_date' => $this->purchase_date,
                'user_id' => Auth::id()??1,
                'total_cost' => $this->total,
            ]);

            foreach ($this->items as $item) {
                $purchase->details()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                    'subtotal' => $item['quantity'] * $item['cost_price'],
                ]);

                // Update Stok!
                Product::find($item['product_id'])->increment('stock', $item['quantity']);
            }
        });

        session()->flash('success', 'Stok masuk berhasil dicatat!');
        return redirect()->route('purchases.index');
    }

    public function render()
    {
        return view('livewire.purchases.create', [
            'suppliers' => Supplier::all(),
            'products' => Product::all(),
        ]);
    }
}