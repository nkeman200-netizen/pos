<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Layout('layouts.app')]
    #[Title('Pelanggan Baru')] // Typo 'palanggan' diperbaiki

    public $name, $phone, $address;

    public function save()
    {
        // 1. Validasi Dulu (Best Practice!)
        $this->validate([
            'name' => 'required|min:3',
            'phone' => 'required|numeric',
            'address' => 'required',
        ]);

        // 2. Simpan ke Database
        Customer::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address
        ]);

        // 3. Beri notifikasi dan kembalikan ke halaman daftar
        session()->flash('success', 'Pelanggan baru berhasil ditambahkan!');
        return redirect()->route('customers.index');
    }

    // FUNGSI RESET: Akan dipanggil oleh tombol Reset di Blade
    public function resetForm()
    {
        // $this->reset() adalah fungsi bawaan Livewire untuk mengosongkan variabel
        $this->reset(['name', 'phone', 'address']);
    }

    public function render()
    {
        return view('livewire.customers.create');
    }
}