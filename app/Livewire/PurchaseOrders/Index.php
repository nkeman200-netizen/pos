<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    #[Title('Daftar Purchase Order')]

    public $search = '';

    // Reset pagination ketika user mengetik di kotak pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        // Opsional: Cuma PO yang masih 'pending' yang bisa dihapus
        if ($po->status === 'pending') {
            $po->items()->delete(); // Hapus detail itemnya dulu
            $po->delete();          // Baru hapus PO-nya
            session()->flash('success', 'Purchase Order berhasil dihapus!');
        } else {
            session()->flash('error', 'Hanya PO Pending yang bisa dihapus!');
        }
    }

    public function render()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')
            ->when($this->search, function ($query) {
                $query->where('po_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('supplier', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.purchase-orders.index', [
            'purchaseOrders' => $purchaseOrders
        ]);
    }
}