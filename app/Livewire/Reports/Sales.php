<?php

namespace App\Livewire\Reports;

use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Sales extends Component
{
    #[Layout('layouts.app')]
    #[Title('Laporan Penjualan Pro')]

    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        // 1. Ambil HANYA transaksi yang TIDAK VOID
        $sales = Sale::with(['user', 'customer', 'details.product.batches'])
            ->where('status', '!=', 'void') // <-- KUNCI PENTING
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->latest()
            ->get();

        $totalOmzet = $sales->sum('total_price');
        $totalTransaksi = $sales->count();
        
        $totalLaba = 0;
        $totalItemTerjual = 0;

        foreach ($sales as $sale) {
            foreach ($sale->details as $item) {
                $totalItemTerjual += $item->quantity;
                $hargaModal = $item->product->batches->avg('purchase_price') ?? 0;
                $labaPerItem = ($item->unit_price - $hargaModal) * $item->quantity;
                $totalLaba += $labaPerItem;
            }
        }

        $marginPersen = $totalOmzet > 0 ? ($totalLaba / $totalOmzet) * 100 : 0;

        // 3. Top Produk juga JANGAN hitung yang Void
        $topProducts = SaleItem::query()
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_qty'), DB::raw('SUM(sale_items.subtotal) as total_revenue'))
            ->where('sales.status', '!=', 'void') // <-- KUNCI PENTING
            ->whereBetween('sales.created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('livewire.reports.sales', [
            'sales' => $sales,
            'totalOmzet' => $totalOmzet,
            'totalTransaksi' => $totalTransaksi,
            'totalLaba' => $totalLaba,
            'totalItemTerjual' => $totalItemTerjual,
            'marginPersen' => $marginPersen,
            'topProducts' => $topProducts
        ]);
    }
}