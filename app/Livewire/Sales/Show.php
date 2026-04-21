<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use App\Models\ProductBatch;
use App\Models\CashierShift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Layout('layouts.app')]
    #[Title('Cetak struk')]
    
    public $sale;
    public $voidReason = ''; 

    public function mount($id){
        // Load Sale beserta detail produk, kasir, dan pelanggan
        $this->sale = Sale::with(['details.product', 'user', 'customer'])->findOrFail($id); 
    }
    
    public function voidTransaction()
    {
        if (!in_array(Auth::user()->role, ['admin', 'owner'])) {
            abort(403, 'Akses Ditolak.');
        }

        if (empty($this->voidReason)) {
            session()->flash('void_error', 'Alasan pembatalan wajib diisi!');
            return;
        }

        try {
            DB::transaction(function () {
                $this->sale->update([
                    'status' => 'void',
                    'void_reason' => $this->voidReason . ' (Oleh: ' . Auth::user()->name . ')',
                ]);

                foreach ($this->sale->details as $item) {
                    $batch = ProductBatch::where('product_id', $item->product_id)
                                ->orderBy('expired_date', 'desc')
                                ->first();
                    if ($batch) {
                        $batch->increment('stock', $item->quantity);
                    }
                }

                // Koreksi uang laci HANYA jika pembayarannya CASH
                if ($this->sale->payment_method === 'cash') {
                    $shift = CashierShift::where('user_id', $this->sale->user_id)
                                ->where('status', 'open')
                                ->whereDate('created_at', $this->sale->created_at->toDateString())
                                ->first();

                    if ($shift) {
                        $shift->decrement('expected_cash', $this->sale->total_price);
                    }
                }
            });

            $this->dispatch('close-void-modal');
            session()->flash('success', 'Transaksi berhasil dibatalkan!');
            $this->sale->refresh();
            
        } catch (\Exception $e) {
            session()->flash('void_error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sales.show');
    }
}