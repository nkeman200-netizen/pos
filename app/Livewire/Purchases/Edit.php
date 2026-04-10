<?php

namespace App\Livewire\Purchases;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $purchasesId,$supplier_id,$purchase_number,$purchase_date,$total_cost,$user_id;
    public $items=[];
    function mount($id){
        $purchases= Purchase::with('details')->findOrFail($id);
        $this->purchasesId=$purchases->id;
        $this->supplier_id=$purchases->supplier_id;
        $this->purchase_number=$purchases->purchase_number;
        $this->purchase_date=$purchases->purchase_date;

        foreach ($purchases->details as $detail) {
            $this->items[]=[
                'product_id'=>$detail->product_id,
                'quantity'=>$detail->quantity,
                'cost_price'=>$detail->cost_price
            ];  
        }
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'cost_price' => 0];
    }

    function remove($index){
        if (count($this->items)>1) {
            unset($this->items[$index]);
            $this->items=array_values($this->items);
        }
    }
    public function getTotalProperty() {
        return collect($this->items)->sum(fn($i) => ($i['quantity'] ?? 0) * ($i['cost_price'] ?? 0));
    }

    public function update()
    {
        $this->validate([
            'supplier_id' => 'required',
            'purchase_number' => 'required|unique:purchases,purchase_number,'.$this->purchasesId,
            'items.*.product_id' => 'required',
        ]);

        DB::transaction(function () {
            $purchase = Purchase::findOrFail($this->purchasesId);

            // 1. REVERT STOK LAMA (Decrement)
            foreach ($purchase->details as $oldItem) {
                $oldItem->product->decrement('stock', $oldItem->quantity);
            }

            // 2. HAPUS DETAIL LAMA
            $purchase->details()->delete();

            // 3. UPDATE HEADER
            $purchase->update([
                'supplier_id' => $this->supplier_id,
                'purchase_number' => $this->purchase_number,
                'purchase_date' => $this->purchase_date,
                'total_cost' => $this->total,
            ]);

            // 4. SIMPAN DETAIL BARU & INCREMENT STOK
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

        session()->flash('success', 'Transaksi stok masuk berhasil diperbarui!');
        return redirect()->route('purchases.index');
    }

    public function render()
    {
        return view('livewire.purchases.edit', [
            'suppliers' => Supplier::all(),
            'products' => Product::all(),
        ]);
    }
}
