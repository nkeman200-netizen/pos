<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Layout('layouts.app')]
    #[Title('Cetak struk')]
    public $sale;
    public function mount($id){
        $this->sale=Sale::with('details.product')->findOrFail($id); 
    }
    
    public function render()
    {
        return view('livewire.sales.show');
    }
}
