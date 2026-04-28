<?php
namespace App\Livewire\Purchases;

use App\Models\Purchase;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Layout('layouts.app')]
    #[Title('Cetak struk')]
    public $purchase;

    public function mount($id)
    {
        $this->purchase = Purchase::with(['supplier', 'details.product', 'user'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.purchases.show');
    }
}