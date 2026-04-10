<?php
namespace App\Livewire\Purchases;

use App\Models\Purchase;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    #[Layout('layouts.app')]
    #[Title('Daftar pembelian')]

    public $search = '';

    // Reset halaman saat mengetik pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $purchase = Purchase::findOrFail($id);
        
        // Logika decrement stok saat purchase dihapus
        foreach ($purchase->details as $item) {
            $item->product->decrement('stock', $item->quantity);
        }
        
        $purchase->delete(); // Ini otomatis memicu Auditable Trait
        session()->flash('success', 'Data berhasil dihapus dan stok dikoreksi.');
    }

    public function render()
    {
        $purchases = Purchase::with(['supplier', 'user'])
            ->where('purchase_number', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.purchases.index', compact('purchases'));
    }
}