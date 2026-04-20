<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
{
    #[Layout('layouts.app')]
    #[Title('Edit data pelanggan')]
    public $name,$phone,$address,$customerId;
    public function mount($id) {
        $customer=Customer::findOrFail($id);
        $this->name=$customer->name;
        $this->phone=$customer->phone;
        $this->address=$customer->address;
        $this->customerId=$id;
    }

    public function update(){
        $this->validate([
            'name' => 'required|min:3',
            'phone' => 'required|numeric|unique:customers,phone,'.$this->customerId,
            'address' => 'required',
        ]);

        // 2. Simpan ke Database
        Customer::findOrFail($this->customerId)->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address
        ]);

        // 3. Beri notifikasi dan kembalikan ke halaman daftar
        session()->flash('success', 'Pelanggan berhasil diperbarui!');
        return redirect()->route('customers.index');
    }

    public function resetForm(){
        // Tinggal panggil ulang fungsi mount! Praktis kan?
        $this->mount($this->customerId);
    }
    
    public function render()
    {
        return view('livewire.customers.edit');
    }
}
