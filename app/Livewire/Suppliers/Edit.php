<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Edit extends Component
{
    #[Layout('layouts.app')]
    #[Title('Edit Supplier')]

    public $supplierId, $name, $phone, $address;

    public function mount($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $supplier->id;
        $this->name = $supplier->name;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'phone' => 'required|numeric',
            'address' => 'required',
        ]);

        $supplier = Supplier::find($this->supplierId);
        $supplier->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        session()->flash('success', 'Data supplier diperbarui.');
        return redirect()->route('suppliers.index');
    }

    public function render() { return view('livewire.suppliers.edit'); }
}