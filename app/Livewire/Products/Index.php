<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    #[Title('Master Data Obat')]

    public $search = '';
    public $sortColumn = 'id';
    public $sortDirection = 'desc';

    public $filterCategory = '';
    public $filterUnit = '';
    public $filterStock = '';

    public function updated($property)
    {
        if (in_array($property, ['search', 'filterCategory', 'filterUnit', 'filterStock'])) {
            $this->resetPage();
        }
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function delete($id)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        $product = Product::findOrFail($id);
        
        if ($product->batches()->count() > 0) {
            session()->flash('error', 'Gagal! Obat ini sudah memiliki riwayat stok/batch. Cukup nonaktifkan saja.');
            return;
        }

        $product->delete();
        session()->flash('success', 'Data obat berhasil dihapus.');
    }

    public function render()
    {
        $query = Product::with(['category', 'unit'])
            ->withSum('batches', 'stock')
            ->when($this->search, function ($q) {
                $q->where(function($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterCategory, function ($q) {
                $q->where('category_id', $this->filterCategory);
            })
            ->when($this->filterUnit, function ($q) {
                $q->where('unit_id', $this->filterUnit);
            })
            ->when($this->filterStock, function ($q) {
                if ($this->filterStock === 'tersedia') {
                    $q->whereHas('batches', function($batchQuery) {
                        $batchQuery->where('stock', '>', 0);
                    });
                } elseif ($this->filterStock === 'habis') {
                    $q->whereDoesntHave('batches', function($batchQuery) {
                        $batchQuery->where('stock', '>', 0);
                    });
                }
            });

        $products = $query->orderBy($this->sortColumn, $this->sortDirection)->paginate(10);

        return view('livewire.products.index', [
            'products' => $products,
            'categories' => Category::all(),
            'units' => Unit::all(),
        ]);
    }
}