<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Layout('layouts.app')]
    #[Title('Pelanggan Baru')] 

    public $name, $phone, $address;

    public function save()
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        $this->validate([
            'name' => 'required|min:3',
            'phone' => 'required|numeric|unique:customers,phone',
            'address' => 'required',
        ]);

        Customer::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address
        ]);

        session()->flash('success', 'Pelanggan baru berhasil ditambahkan!');
        return redirect()->route('customers.index');
    }

    public function resetForm()
    {
        $this->reset(['name', 'phone', 'address']);
    }

    public function render()
    {
        return view('livewire.customers.create');
    }
}