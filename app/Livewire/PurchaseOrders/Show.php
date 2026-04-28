<?php
namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Layout('layouts.app')]
    #[Title('Detail Purchase Order')]

    public $po; 

    public function mount($id)
    {
        $this->po = PurchaseOrder::with(['supplier', 'user', 'items.product'])->findOrFail($id);
    }

    public function updateStatus($newStatus)
    {
        $poId = is_array($this->po) ? $this->po['id'] : $this->po->id;

        $poModel = PurchaseOrder::findOrFail($poId);

        $poModel->update(['status' => $newStatus]);
        
        $this->po = PurchaseOrder::with(['supplier', 'user', 'items.product'])->findOrFail($poId);
        
        session()->flash('success', 'Status PO berhasil diperbarui ke: ' . strtoupper($newStatus));
    }

    public function render()
    {
        return view('livewire.purchase-orders.show');
    }
}