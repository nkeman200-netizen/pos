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
    public $voidReason = ''; // State untuk alasan pembatalan

    public function mount($id){
        // WAJIB: Tarik juga user dan customer agar blade tidak error
        $this->sale = Sale::with(['details.product', 'user', 'customer'])->findOrFail($id); 
    }
    
    public function voidTransaction()
    {
        // 1. Keamanan: Hanya Admin & Owner yang bisa membatalkan
        if (!in_array(Auth::user()->role, ['admin', 'owner'])) {
            abort(403, 'Akses Ditolak.');
        }

        if (empty($this->voidReason)) {
            session()->flash('void_error', 'Alasan pembatalan wajib diisi!');
            return;
        }

        if ($this->sale->status === 'void') {
            session()->flash('void_error', 'Transaksi sudah dibatalkan sebelumnya!');
            return;
        }

        try {
            DB::transaction(function () {
                // 2. Ubah status struk
                $this->sale->update([
                    'status' => 'void',
                    'void_reason' => $this->voidReason . ' (Oleh: ' . Auth::user()->name . ')',
                ]);

                // 3. Kembalikan stok ke Batch (Pilih yang masa expirednya paling panjang)
                foreach ($this->sale->details as $item) {
                    $batch = ProductBatch::where('product_id', $item->product_id)
                                ->orderBy('expired_date', 'desc')
                                ->first();

                    if ($batch) {
                        $batch->increment('stock', $item->quantity);
                    }
                }

                // 4. Koreksi Uang Laci Kasir (Jika shiftnya masih aktif hari ini)
                $shift = CashierShift::where('user_id', $this->sale->user_id)
                            ->where('status', 'open')
                            ->whereDate('created_at', clone $this->sale->created_at)
                            ->first();

                if ($shift) {
                    $shift->decrement('expected_cash', $this->sale->total_price);
                }
            });

            $this->dispatch('close-void-modal');
            session()->flash('success', 'Transaksi berhasil dibatalkan! Stok obat dan uang laci telah dikoreksi.');
            $this->sale->refresh();
            
        } catch (\Exception $e) {
            session()->flash('void_error', 'Sistem Gagal: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sales.show');
    }
}