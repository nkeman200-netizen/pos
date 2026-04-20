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

    // Otomatis reset batch kalau produknya diganti
    public function updatedProductId($value)
    {
        $this->batchId = '';
        $this->systemQty = 0;
        $this->physicalQty = '';
    }

    // Otomatis tarik stok sistem saat batch dipilih
    public function updatedBatchId($value)
    {
        if ($value) {
            $batch = ProductBatch::find($value);
            $this->systemQty = $batch ? $batch->stock : 0;
            $this->physicalQty = $this->systemQty; // Default samakan dengan sistem dulu
        } else {
            $this->systemQty = 0;
            $this->physicalQty = '';
        }
    }

    public function save()
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        // 1. Validasi input
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

        // Kalau fisiknya sama persis dengan sistem, buat apa diopname? Tolak!
        if ($difference == 0) {
            session()->flash('error', 'Stok fisik sama persis dengan sistem. Tidak ada yang perlu disesuaikan.');
            return;
        }

        try {
            // 2. Bungkus dalam Database Transaction untuk keamanan kelas Enterprise
            DB::transaction(function () use ($difference) {
                // Kunci baris batch ini agar tidak ada transaksi kasir yang masuk saat kita opname
                $batch = ProductBatch::lockForUpdate()->findOrFail($this->batchId);

                // Cek ulang stok sistem saat ini (berjaga-jaga kalau pas detik ini ada kasir yang jualan)
                $actualSystemQty = $batch->stock;
                $actualDifference = $this->physicalQty - $actualSystemQty;

                // A. Buat Jurnal Penyesuaian
                StockAdjustment::create([
                    'product_id' => $this->productId,
                    'product_batch_id' => $this->batchId,
                    'user_id' => Auth::id() ?? 1,
                    'system_qty' => $actualSystemQty,
                    'physical_qty' => $this->physicalQty,
                    'difference' => $actualDifference,
                    'reason' => $this->reason,
                ]);

                // B. Timpa stok lama dengan stok fisik yang baru
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
        // Hanya ambil produk yang punya relasi batch
        $products = Product::whereHas('batches')->orderBy('name')->get();
        
        // Ambil batch dari produk yang dipilih
        $batches = $this->productId ? ProductBatch::where('product_id', $this->productId)->orderBy('expired_date')->get() : [];
        
        // Ambil histori opname terbaru
        $recentAdjustments = StockAdjustment::with(['product', 'batch', 'user'])->latest()->take(6)->get();

        return view('livewire.inventory.stock-opname', compact('products', 'batches', 'recentAdjustments'));
    }
}