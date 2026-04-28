<?php

namespace App\Livewire\Reports;

use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
    public $frequency = 'daily'; 

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $sales = Sale::with(['user', 'customer', 'details.product.batches'])
            ->where('status', '!=', 'void')
            ->whereBetween('created_at', [$start, $end])
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
                $totalLaba += ($item->unit_price - $hargaModal) * $item->quantity;
            }
        }

        $marginPersen = $totalOmzet > 0 ? ($totalLaba / $totalOmzet) * 100 : 0;

        $topProducts = SaleItem::query()
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_qty'))
            ->where('sales.status', '!=', 'void')
            ->whereBetween('sales.created_at', [$start, $end])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $reportTable = collect();

        if ($this->frequency === 'detail') {
            $reportTable = $sales;
        } elseif ($this->frequency === 'daily') {
            $period = CarbonPeriod::create($start, $end);
            $groupedSales = $sales->groupBy(fn($s) => $s->created_at->format('Y-m-d'));

            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                $daySales = $groupedSales->get($dateStr, collect());

                $dayOmzet = $daySales->sum('total_price');
                $dayLaba = 0;
                foreach ($daySales as $ds) {
                    foreach ($ds->details as $item) {
                        $modal = $item->product->batches->avg('purchase_price') ?? 0;
                        $dayLaba += ($item->unit_price - $modal) * $item->quantity;
                    }
                }

                $reportTable->push([
                    'label' => $date->translatedFormat('l, d M Y'),
                    'transaksi' => $daySales->count(),
                    'omzet' => $dayOmzet,
                    'laba' => $dayLaba
                ]);
            }
        } elseif ($this->frequency === 'monthly') {
            $period = CarbonPeriod::create($start->startOfMonth(), '1 month', $end->startOfMonth());
            $groupedSales = $sales->groupBy(fn($s) => $s->created_at->format('Y-m'));

            foreach ($period as $date) {
                $monthStr = $date->format('Y-m');
                $monthSales = $groupedSales->get($monthStr, collect());

                $monthOmzet = $monthSales->sum('total_price');
                $monthLaba = 0;
                foreach ($monthSales as $ms) {
                    foreach ($ms->details as $item) {
                        $modal = $item->product->batches->avg('purchase_price') ?? 0;
                        $monthLaba += ($item->unit_price - $modal) * $item->quantity;
                    }
                }

                $reportTable->push([
                    'label' => $date->translatedFormat('F Y'),
                    'transaksi' => $monthSales->count(),
                    'omzet' => $monthOmzet,
                    'laba' => $monthLaba
                ]);
            }
        }

        return view('livewire.reports.sales', [
            'reportTable' => $reportTable,
            'totalOmzet' => $totalOmzet,
            'totalTransaksi' => $totalTransaksi,
            'totalLaba' => $totalLaba,
            'totalItemTerjual' => $totalItemTerjual,
            'marginPersen' => $marginPersen,
            'topProducts' => $topProducts
        ]);
    }
}