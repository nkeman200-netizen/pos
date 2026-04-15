<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    #[Layout('layouts.app')]
    #[Title('Daftar penjualan')]
    
    public $search='';

    function updatingSearch(){
        $this->resetPage();
    }
    
    
    public function render()
    {
        $sales=Sale::with('user','customer')->where('invoice_number','like','%'.$this->search.'%')->latest()->paginate(10);
        return view('livewire.sales.index', compact('sales'));
    }
}
