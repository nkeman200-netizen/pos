<?php

namespace App\Livewire\Sales;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Layout('layouts.app')]
    #[Title('Cetak struk')]
    public $sale;
    public function mount($sale){
        $this->sale=$sale->load('details.product','user','customer');
    }
    
    public function render()
    {
        return view('livewire.sales.show');
    }
}
