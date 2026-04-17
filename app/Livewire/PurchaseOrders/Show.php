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

    // Jangan pakai type hint PurchaseOrder di sini untuk menghindari error inisialisasi
    public $po; 

    public function mount($id)
    {
        // Load data awal
        $this->po = PurchaseOrder::with(['supplier', 'user', 'items.product'])->findOrFail($id);
    }

    public function updateStatus($newStatus)
    {
        // 1. Tangkap ID-nya. Kita cek apakah dia array (hasil bolak-balik Livewire) atau masih object
        $poId = is_array($this->po) ? $this->po['id'] : $this->po->id;

        // 2. Ambil data asli dari database untuk mendapatkan "Object Model"-nya
        $poModel = PurchaseOrder::findOrFail($poId);

        // 3. Eksekusi update status ke database
        $poModel->update(['status' => $newStatus]);
        
        // 4. (Penting!) Timpa ulang properti $this->po dengan Object yang baru 
        // agar tampilan status di layar otomatis berubah tanpa perlu refresh (F5)
        $this->po = PurchaseOrder::with(['supplier', 'user', 'items.product'])->findOrFail($poId);
        
        session()->flash('success', 'Status PO berhasil diperbarui ke: ' . strtoupper($newStatus));
    }

    public function render()
    {
        return view('livewire.purchase-orders.show');
    }
}