<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ReturnToPbf extends Component
{
    #[Layout('layouts.app')]
    #[Title('Retur ke PBF')]

    public $supplierId = '';
    public $productId = '';
    public $batchId = '';
    public $qty = '';
    public $reason = '';
    public $notes = '';

    public $maxQty = 0;
    public $printData = null;
    public $searchProduct = '';
    public $searchResults = [];
    public $productName = '';

    public function updatedProductId($value)
    {
        $this->batchId = '';
        $this->qty = '';
        $this->maxQty = 0;
    }

    public function updatedBatchId($value)
    {
        if ($value) {
            $batch = ProductBatch::find($value);
            $this->maxQty = $batch ? $batch->stock : 0;
        } else {
            $this->maxQty = 0;
        }
    }

    public function updatedSearchProduct($value)
    {
        if (strlen($value) >= 2) {
            $this->searchResults = Product::whereHas('batches', function($q) {
                $q->where('stock', '>', 0);
            })
            ->where(function($q) use ($value) {
                $q->where('name', 'like', "%{$value}%")
                  ->orWhere('sku', 'like', "%{$value}%");
            })
            ->take(5)->get(); 
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct($id, $name)
    {
        $this->productId = $id;
        $this->productName = $name;
        $this->searchProduct = ''; 
        $this->searchResults = []; 
        
        $this->batchId = '';
        $this->qty = '';
        $this->maxQty = 0;
    }

    public function clearProduct()
    {
        $this->productId = '';
        $this->productName = '';
        $this->batchId = '';
        $this->qty = '';
        $this->maxQty = 0;
    }

    public function save()
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        $this->validate([
            'supplierId' => 'required',
            'productId' => 'required',
            'batchId' => 'required',
            'qty' => 'required|numeric|min:1|max:' . $this->maxQty,
            'reason' => 'required',
        ], [
            'qty.max' => 'Jumlah retur tidak boleh melebihi stok yang ada (' . $this->maxQty . ').',
        ]);

        try {
            DB::transaction(function () {
                $batch = ProductBatch::lockForUpdate()->findOrFail($this->batchId);
                
                if ($this->qty > $batch->stock) {
                    throw new \Exception('Stok tidak mencukupi saat diproses.');
                }

                $returnNumber = 'RTV-' . date('ymd') . '-' . rand(100, 999);
                $estimasiNilai = $batch->purchase_price * $this->qty;

                $retur = PurchaseReturn::create([
                    'return_number' => $returnNumber,
                    'supplier_id' => $this->supplierId,
                    'user_id' => Auth::id() ?? 1,
                    'return_date' => now(),
                    'notes' => $this->notes,
                    'total_return_value' => $estimasiNilai,
                ]);

                PurchaseReturnItem::create([
                    'purchase_return_id' => $retur->id,
                    'product_id' => $this->productId,
                    'product_batch_id' => $this->batchId,
                    'quantity' => $this->qty,
                    'reason' => $this->reason,
                    'unit_price' => $batch->purchase_price ?? 0,
                ]);

                $batch->decrement('stock', $this->qty);
                
                $this->printData = PurchaseReturn::with(['supplier', 'user', 'items.product', 'items.batch'])->find($retur->id);
            });

            session()->flash('success', 'Dokumen Retur berhasil dibuat & stok telah dipotong!');
            $this->reset(['supplierId', 'productId', 'batchId', 'qty', 'reason', 'notes', 'maxQty']);
            
            $this->dispatch('trigger-print');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses Retur: ' . $e->getMessage());
        }
    }

    public function setPrint($id)
    {
        $this->printData = PurchaseReturn::with(['supplier', 'user', 'items.product', 'items.batch'])->find($id);
        $this->dispatch('trigger-print');
    }

    public function render()
    {
        $suppliers = Supplier::orderBy('name')->get();
        
        $batches = $this->productId ? ProductBatch::where('product_id', $this->productId)->where('stock', '>', 0)->orderBy('expired_date')->get() : [];
        $recentReturns = PurchaseReturn::with(['supplier', 'user'])->latest()->take(5)->get();

        return view('livewire.inventory.return-to-pbf', compact('suppliers', 'batches', 'recentReturns'));
    }
}