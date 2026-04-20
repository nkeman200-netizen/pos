<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Create extends Component
{
    #[Layout('layouts.app')]
    #[Title('Tambah Supplier')]

    public $name, $phone, $address;

    protected $rules = [
        'name' => 'required|min:3',
        'phone' => 'required|numeric',
        'address' => 'required',
    ];

    public function save()
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        $this->validate();
        Supplier::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        session()->flash('success', 'Supplier berhasil ditambahkan.');
        return redirect()->route('suppliers.index');
    }

    public function render() { return view('livewire.suppliers.create'); }
}