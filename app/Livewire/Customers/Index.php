<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\session;

class Index extends Component
{
    use WithPagination;
    #[Layout('layouts.app')]
    #[Title('Daftar pelanggan')]
    public $search='';

    public function updatingSearch(){
        $this->resetPage();
    }

    public function delete($id){
        Customer::findOrFail($id)->deleteOrFail();
        session::flash('success','Customer berhasil dihapus');
    }
    
    public function render()
    {
        $customers=Customer::where('name','like','%'.$this->search.'%')->latest()->paginate(10);
        return view('livewire.customers.index',compact('customers'));
    }
}
