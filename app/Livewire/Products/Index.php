<?php

namespace App\Livewire\Products;

use App\Models\Product;
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

    // Reset halaman ke 1 setiap kali user mengetik di kotak pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        $product = Product::findOrFail($id);
        
        // Proteksi: Jangan hapus kalau sudah ada transaksi/batch
        if ($product->batches()->count() > 0) {
            session()->flash('error', 'Gagal! Obat ini sudah memiliki riwayat stok/batch. Cukup nonaktifkan saja.');
            return;
        }

        $product->delete();
        session()->flash('success', 'Data obat berhasil dihapus.');
    }

    public function render()
    {
        // Gunakan 'with' untuk memuat relasi (Eager Loading) agar loading halaman sangat cepat
        $products = Product::with(['category', 'unit'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.products.index', compact('products'));
    }
}