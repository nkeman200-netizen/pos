<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockAdjustment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class StockOpname extends Component
{
    #[Layout('layouts.app')]
    #[Title('Stock Opname')]

    public $productId = '';
    public $batchId = '';
    public $systemQty = 0;
    public $physicalQty = '';
    public $reason = '';
    public $searchProduct = '';
    public $searchResults = [];
    public $productName = '';
    public function updatedProductId($value)
    {
        $this->batchId = '';
        $this->systemQty = 0;
        $this->physicalQty = '';
    }

    public function updatedBatchId($value)
    {
        if ($value) {
            $batch = ProductBatch::find($value);
            $this->systemQty = $batch ? $batch->stock : 0;
            $this->physicalQty = $this->systemQty; 
        } else {
            $this->systemQty = 0;
            $this->physicalQty = '';
        }
    }

    public function updatedSearchProduct($value)
    {
        if (strlen($value) >= 2) {
            $this->searchResults = Product::whereHas('batches')
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
        $this->systemQty = 0;
        $this->physicalQty = '';
    }

    public function clearProduct()
    {
        $this->productId = '';
        $this->productName = '';
        $this->batchId = '';
        $this->systemQty = 0;
        $this->physicalQty = '';
    }
    public function save()
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        $this->validate([
            'productId' => 'required',
            'batchId' => 'required',
            'physicalQty' => 'required|numeric|min:0',
            'reason' => 'required',
        ], [
            'productId.required' => 'Pilih obat terlebih dahulu.',
            'batchId.required' => 'Pilih batch obat yang akan diopname.',
            'physicalQty.required' => 'Masukkan stok fisik yang sebenarnya.',
            'reason.required' => 'Pilih alasan penyesuaian stok.',
        ]);

        $difference = $this->physicalQty - $this->systemQty;

        if ($difference == 0) {
            session()->flash('error', 'Stok fisik sama persis dengan sistem. Tidak ada yang perlu disesuaikan.');
            return;
        }

        try {
            DB::transaction(function () use ($difference) {
                $batch = ProductBatch::lockForUpdate()->findOrFail($this->batchId);

                $actualSystemQty = $batch->stock;
                $actualDifference = $this->physicalQty - $actualSystemQty;

                StockAdjustment::create([
                    'product_id' => $this->productId,
                    'product_batch_id' => $this->batchId,
                    'user_id' => Auth::id() ?? 1,
                    'system_qty' => $actualSystemQty,
                    'physical_qty' => $this->physicalQty,
                    'difference' => $actualDifference,
                    'reason' => $this->reason,
                ]);

                $batch->update(['stock' => $this->physicalQty]);
            });

            session()->flash('success', 'Stock Opname berhasil dicatat & stok telah disesuaikan!');
            $this->reset(['productId', 'batchId', 'systemQty', 'physicalQty', 'reason']);

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses Opname: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $batches = $this->productId ? ProductBatch::where('product_id', $this->productId)->orderBy('expired_date')->get() : [];
        $recentAdjustments = StockAdjustment::with(['product', 'batch', 'user'])->latest()->take(6)->get();

        return view('livewire.inventory.stock-opname', compact('batches', 'recentAdjustments'));
    }
}