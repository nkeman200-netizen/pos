<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
{
    #[Layout('layouts.app')]
    #[Title('Edit Master Obat')]

    public $product_id;
    public $sku;
    public $name;
    public $category_id;
    public $unit_id;
    public $selling_price;

    public function mount($id)
    {
        // 1. Tarik data obat berdasarkan ID yang dilempar dari URL
        $product = Product::findOrFail($id);
        
        // 2. Isi ke property agar muncul di form Blade
        $this->product_id = $product->id;
        $this->sku = $product->sku;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->unit_id = $product->unit_id;
        $this->selling_price =number_format($product->selling_price, 0, ',', '.');
    }

    public function getSellingMurniProperty() 
    {
        return (int) preg_replace('/[^0-9]/', '', (string)$this->selling_price);
    }
    public function update()
    {
        // 3. Validasi ketat. Pengecekan unique SKU mengecualikan ID obat ini sendiri
        $this->validate([
            'sku' => 'required|unique:products,sku,' . $this->product_id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
        ]);

        // 4. Eksekusi Update ke Database
        $product = Product::findOrFail($this->product_id);
        $product->update([
            'sku' => $this->sku,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'selling_price' => $this->sellingMurni,
        ]);

        session()->flash('success', 'Data Master Obat berhasil diperbarui!');
        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.products.edit', [
            'categories' => Category::all(),
            'units' => Unit::all(),
        ]);
    }
}