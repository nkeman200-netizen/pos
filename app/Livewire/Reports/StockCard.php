<?php

namespace App\Livewire\Reports;

use App\Models\Product;
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

    public $productId;
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $mutasi = collect();
        $product = null;
        $stokAwal = 0;

        if ($this->productId) {
            $product = Product::with('unit')->find($this->productId);

            // 1. Stok Masuk
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
                        'referensi' => $item->purchase->purchase_number,
                        'batch' => $item->batch_number,
                        'masuk' => $item->quantity,
                        'keluar' => 0,
                        'tipe' => 'masuk'
                    ];
                });

            // 2. Stok Keluar (Pastikan status transaksinya bukan VOID)
            $keluar = SaleItem::with('sale')
                ->where('product_id', $this->productId)
                ->whereHas('sale', function($query) {
                    $query->where('status', '!=', 'void'); // <-- KUNCI PENTING
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
                        'referensi' => $item->sale->invoice_number,
                        'batch' => '-', 
                        'masuk' => 0,
                        'keluar' => $item->quantity,
                        'tipe' => 'keluar'
                    ];
                });

            $mutasi = $masuk->concat($keluar)->sortBy('tanggal');

            // Hitung Stok Awal (Pastikan juga tidak menghitung void)
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
            'products' => Product::orderBy('name')->get(),
            'product' => $product,
            'mutasi' => $mutasi,
            'stokAwal' => $stokAwal
        ]);
    }
}