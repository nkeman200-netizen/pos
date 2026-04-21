<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Layout('layouts.app')]
    #[Title('Data Obat Baru')]

    // Properti yang sesuai dengan tabel products baru
    public $category_id = '';
    public $unit_id = '';
    public $sku = '';
    public $name = '';
    public $selling_price = '';

    public function getSellingMurniProperty() 
    {
        return (int) preg_replace('/[^0-9]/', '', (string)$this->selling_price);
    }
    public function save()
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        $this->validate([
            'category_id' => 'required',
            'unit_id' => 'required',
            'sku' => 'required|unique:products,sku', // SKU tidak boleh kembar
            'name' => 'required|min:3',
        ]);

        Product::create([
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'sku' => $this->sku,
            'name' => $this->name,
            'selling_price' => $this->sellingMurni,
            'is_active' => true // Default aktif
        ]);

        session()->flash('success', 'Master Obat berhasil ditambahkan! Silakan catat stok masuk di menu Purchases.');
        return redirect()->route('products.index');
    }

    public function resetForm()
    {
        $this->reset(['category_id', 'unit_id', 'sku', 'name', 'selling_price']);
    }

    public function render()
    {
        // Kirim data master ke view untuk dijadikan pilihan Dropdown
        return view('livewire.products.create', [
            'categories' => Category::all(),
            'units' => Unit::all()
        ]);
    }
}