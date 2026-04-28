<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('layouts.app')]
    #[Title('Dashboard Apotek')]

    public function render()
    {
        $today = Carbon::today();
        $threeMonthsFromNow = Carbon::now()->addMonths(3);

        $salesToday = Sale::whereDate('created_at', $today)->get();
        $omzetHariIni = $salesToday->sum('total_price');
        $transaksiHariIni = $salesToday->count();

        $profitHariIni = 0;
        foreach ($salesToday as $sale) {
            foreach ($sale->details as $item) {
                $modal = $item->product->batches->avg('purchase_price') ?? 0;
                $profitHariIni += ($item->unit_price - $modal) * $item->quantity;
            }
        }

        $totalAset = ProductBatch::selectRaw('SUM(stock * purchase_price) as total_aset')->value('total_aset') ?? 0;


        $last7Days = collect();
        $chartData = collect();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days->push($date->format('d M'));
            
            $omzet = Sale::whereDate('created_at', $date)->sum('total_price');
            $chartData->push((int) $omzet);
        }


        $obatKritis = Product::with(['category', 'unit'])
            ->withSum('batches as total_stock', 'stock')
            ->having('total_stock', '<', 10) 
            ->orderBy('total_stock', 'asc')
            ->take(5)
            ->get();


        $obatHampirExpired = ProductBatch::with('product')
            ->where('stock', '>', 0)
            ->where('expired_date', '<=', $threeMonthsFromNow)
            ->orderBy('expired_date', 'asc')
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'omzetHariIni' => $omzetHariIni,
            'profitHariIni' => $profitHariIni,
            'transaksiHariIni' => $transaksiHariIni,
            'totalAset' => $totalAset,
            'chartCategories' => json_encode($last7Days),
            'chartData' => json_encode($chartData),
            'obatKritis' => $obatKritis,
            'obatHampirExpired' => $obatHampirExpired,
        ]);
    }
}