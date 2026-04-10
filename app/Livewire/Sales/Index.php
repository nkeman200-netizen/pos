<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $search='';

    function updatingSearch(){
        $this->resetPage();
    }
    
    function delete($id){
        $sale=Sale::findOrFail($id);
        foreach ($sale->details as $detail) {
            $detail->product->increment('stock',$detail->quantity);
        }
        $sale->delete();
        session()->flash('success','data telah dihapus');
    }
    
    public function render()
    {
        $sales=Sale::with('user','customer')->where('invoice_number','like','%'.$this->search.'%')->latest()->paginate(10);
        return view('livewire.sales.index', compact('sales'));
    }
}
