<?php

namespace App\Livewire\Reports;

use App\Models\Product;
use App\Models\ProductBatch; 
use App\Models\PurchaseItem;
use App\Models\SaleItem;
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
        $product = null;
        $stokAwal = 0;
        $activeBatches = [];

        if ($this->productId) {
            $product = Product::with('unit')->find($this->productId);

            
            $activeBatches = ProductBatch::where('product_id', $this->productId)
                ->where('stock', '>', 0)
                ->orderBy('expired_date', 'asc') 
                ->get();
            $masuk = PurchaseItem::with('purchase')
                ->where('product_id', $this->productId)
                ->whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ])
                ->get()
                ->map(function ($item) {
                    return [
                        'tanggal' => $item->created_at,
                        'keterangan' => 'Stok Masuk (Pembelian)',
                        'referensi' => $item->purchase->purchase_number ?? '-',
                        'batch' => '-', 
                        'masuk' => $item->quantity,
                        'keluar' => 0,
                        'tipe' => 'masuk'
                    ];
                });

            $keluar = SaleItem::with('sale')
                ->where('product_id', $this->productId)
                ->whereHas('sale', function($query) {
                    $query->where('status', '!=', 'void');
                })
                ->whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ])
                ->get()
                ->map(function ($item) {
                    return [
                        'tanggal' => $item->created_at,
                        'keterangan' => 'Stok Keluar (Penjualan)',
                        'referensi' => $item->sale->invoice_number ?? '-',
                        'batch' => '-', 
                        'masuk' => 0,
                        'keluar' => $item->quantity,
                        'tipe' => 'keluar'
                    ];
                });

            $mutasi = $masuk->concat($keluar)->sortBy('tanggal');

            $masukSebelum = PurchaseItem::where('product_id', $this->productId)
                ->where('created_at', '<', Carbon::parse($this->startDate)->startOfDay())
                ->sum('quantity');
                
            $keluarSebelum = SaleItem::where('product_id', $this->productId)
                ->whereHas('sale', function($query) {
                    $query->where('status', '!=', 'void');
                })
                ->where('created_at', '<', Carbon::parse($this->startDate)->startOfDay())
                ->sum('quantity');
                
            $stokAwal = $masukSebelum - $keluarSebelum;
        }

        return view('livewire.reports.stock-card', [
            'product' => $product,
            'mutasi' => $mutasi,
            'stokAwal' => $stokAwal,
            'activeBatches' => $activeBatches 
        ]);
    }
}