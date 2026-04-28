<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Index extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    #[Title('Daftar Supplier')]

    public $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function delete($id)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        $supplier = Supplier::findOrFail($id);
        if ($supplier->purchases()->count() > 0) {
            session()->flash('error', 'Supplier tidak bisa dihapus karena sudah memiliki riwayat transaksi.');
            return;
        }
        $supplier->delete();
        session()->flash('success', 'Supplier berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.suppliers.index', [
            'suppliers' => Supplier::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10)
        ]);
    }
}