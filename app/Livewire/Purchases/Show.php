<?php
namespace App\Livewire\Purchases;

use App\Models\Purchase;
use Livewire\Component;

class Show extends Component
{
    public $purchase;

    public function mount($id)
    {
        // Load purchase beserta relasinya
        $this->purchase = Purchase::with(['supplier', 'details.product', 'user'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.purchases.show');
    }
}