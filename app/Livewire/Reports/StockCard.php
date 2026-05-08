<?php

namespace App\Livewire\Reports;

use App\Models\Product;
use App\Models\ProductBatch; 
use App\Models\StockCard as StockCardModel;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
class StockCard extends Component
{
    #[Layout('layouts.app')]
    #[Title('Kartu Stok Obat')]

    public $searchQuery = '';
    public $searchResults = [];
    
    public $productId;
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->searchResults = Product::with('unit')
                ->where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('sku', 'like', '%' . $this->searchQuery . '%')
                ->where('is_active', true)
                ->take(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct($id)
    {
        $this->productId = $id;
        $this->searchQuery = ''; 
        $this->searchResults = [];
    }

    public function render()
    {
        $mutasi = collect();
        $stokAwal = 0;
        $product = null;
        $activeBatches = collect();

        if ($this->productId) {
            $product = Product::with('unit')->find($this->productId);
            $activeBatches = ProductBatch::where('product_id', $this->productId)
                                ->where('stock', '>', 0)
                                ->get();

            $lastCardBefore = StockCardModel::where('product_id', $this->productId)
                ->where('created_at', '<', Carbon::parse($this->startDate)->startOfDay())
                ->latest('id')
                ->first();

            $stokAwal = $lastCardBefore ? $lastCardBefore->stock_balance : 0;

            $mutasi = StockCardModel::with(['user', 'batch'])
                ->where('product_id', $this->productId)
                ->whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ])
                ->orderBy('id', 'asc') 
                ->get();
        }

        return view('livewire.reports.stock-card', [
            'product' => $product,
            'mutasi' => $mutasi,
            'stokAwal' => $stokAwal,
            'activeBatches' => $activeBatches 
        ]);
    }
}