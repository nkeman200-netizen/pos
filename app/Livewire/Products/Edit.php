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
    public $min_stock; 

    public function mount($id)
    {
        $product = Product::findOrFail($id);
        
        $this->product_id = $product->id;
        $this->sku = $product->sku;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->unit_id = $product->unit_id;
        $this->selling_price = number_format($product->selling_price, 0, ',', '.');
        $this->min_stock = $product->min_stock; 
    }

    public function getSellingMurniProperty() 
    {
        return (int) preg_replace('/[^0-9]/', '', (string)$this->selling_price);
    }

    public function update()
    {
        $this->validate([
            'sku' => 'required|unique:products,sku,' . $this->product_id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'min_stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($this->product_id);
        $product->update([
            'sku' => $this->sku,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'selling_price' => $this->sellingMurni,
            'min_stock' => $this->min_stock, 
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