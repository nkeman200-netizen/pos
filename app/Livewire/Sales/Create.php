<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Sale;
use App\Models\HeldTransaction;
use App\Models\CashierShift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Layout('layouts.app')]
    #[Title('Kasir')]
    
    public $cart = [];
    public $customerId = '';
    public $pembayaran = '';
    public $qty = 1;
    public $searchQuery = ''; 

    public $isLocked = false;
    public $pinInput = '';

    public $holdNote = '';
    public $heldTransactionsList = [];

    // STATE MANAJEMEN SHIFT
    public $hasOpenShift = false;
    public $startingCash = '';
    public $actualCash = ''; // <-- Untuk input uang fisik saat tutup kasir

    public function mount()
    {
        $this->isLocked = session('kasir_locked', false);
        $this->hasOpenShift = CashierShift::where('user_id', Auth::id())->where('status', 'open')->exists();
    }

    public function getStartingCashMurniProperty() 
    {
        return (int) preg_replace('/[^0-9]/', '', (string)$this->startingCash);
    }

    public function getActualCashMurniProperty() 
    {
        return (int) preg_replace('/[^0-9]/', '', (string)$this->actualCash);
    }

    public function startShift()
    {
        $nominal = $this->startingCashMurni;
        
        if ($this->startingCash === '' || $nominal < 0) {
            session()->flash('shift_error', 'Nominal modal laci harus diisi!');
            return;
        }

        CashierShift::create([
            'user_id' => Auth::id(),
            'start_time' => now(),
            'starting_cash' => $nominal,
            'expected_cash' => $nominal,
            'status' => 'open',
        ]);

        $this->hasOpenShift = true;
        $this->startingCash = '';
        $this->dispatch('item-added'); 
    }

    // FUNGSI BARU: TUTUP KASIR (BLIND DROP)
    public function closeShift()
    {
        $nominalFisik = $this->actualCashMurni;

        if ($this->actualCash === '' || $nominalFisik < 0) {
            session()->flash('close_shift_error', 'Nominal uang fisik harus diisi!');
            return;
        }

        $openShift = CashierShift::where('user_id', Auth::id())->where('status', 'open')->first();

        if ($openShift) {
            $selisih = $nominalFisik - $openShift->expected_cash;
            $statusSelisih = $selisih == 0 ? 'Sesuai (Balance)' : ($selisih > 0 ? 'Lebih Rp' . number_format($selisih) : 'Minus/Kurang Rp' . number_format(abs($selisih)));

            $openShift->update([
                'actual_cash' => $nominalFisik,
                'end_time' => now(),
                'status' => 'closed',
            ]);

            // Pesan ini akan dibawa menyeberang ke halaman Login bawaan Laravel
            session()->flash('status', "Shift " . Auth::user()->name . " Berhasil Ditutup! Laporan: " . $statusSelisih);
        }

        $this->dispatch('close-modals');

        // PROSES LOGOUT PAKSA STANDAR KEAMANAN LARAVEL
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // Lempar kasir keluar ke halaman Login!
        return redirect('/login');
    }

    public function confirmHold()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang kosong, tidak ada yang bisa ditahan!');
            return;
        }
        $this->dispatch('open-hold-modal');
    }

    public function executeHold()
    {
        if (empty($this->cart)) return;

        HeldTransaction::create([
            'user_id' => Auth::id(),
            'customer_id' => $this->customerId ?: null,
            'cart_data' => $this->cart,
            'total_price' => $this->total,
            'reference_notes' => $this->holdNote ?: 'Antrean ' . date('H:i:s'),
        ]);

        $this->reset(['cart', 'customerId', 'pembayaran', 'searchQuery', 'qty', 'holdNote']);
        session()->flash('success', 'Transaksi berhasil ditahan/diparkir!');
        $this->dispatch('close-modals');
    }

    public function openRecall()
    {
        $this->heldTransactionsList = HeldTransaction::with('customer')->where('user_id', Auth::id())->latest()->get();
        $this->dispatch('open-recall-modal');
    }


    public function restoreTransaction($id)
    {
        $held = HeldTransaction::find($id);
        if ($held) {
            if (!empty($this->cart)) {
                session()->flash('error', 'Keranjang saat ini masih terisi! Selesaikan atau Tahan (F6) dulu sebelum memanggil antrean lain.');
                $this->dispatch('close-modals');
                return;
            }

            $this->cart = $held->cart_data;
            $this->customerId = $held->customer_id;
            $held->delete(); 

            session()->flash('success', 'Transaksi berhasil dipanggil kembali!');
            $this->dispatch('close-modals');
        }
    }

    public function deleteHeld($id)
    {
        HeldTransaction::find($id)?->delete();
        $this->heldTransactionsList = HeldTransaction::with('customer')->where('user_id', Auth::id())->latest()->get();
    }

    public function lockCashier()
    {
        $this->isLocked = true;
        $this->pinInput = '';
        session(['kasir_locked' => true]);
    }

    public function unlockCashier()
    {
        $user = Auth::user();
        if ($user->pin === $this->pinInput || empty($user->pin)) {
            $this->isLocked = false;
            $this->pinInput = '';
            session(['kasir_locked' => false]);
            $this->dispatch('item-added'); 
        } else {
            session()->flash('pin_error', 'PIN Salah!');
            $this->pinInput = '';
        }
    }

    public function updatedSearchQuery()
    {
        $this->dispatch('reset-highlight');
    }

    public function updatedCart($value, $name)
    {
        $parts = explode('.', $name);
        if (count($parts) == 2 && $parts[1] === 'quantity') {
            $this->updateQuantity($parts[0], $value);
        }
    }

    public function getPembayaranMurniProperty() 
    {
        return (int) preg_replace('/[^0-9]/', '', (string)$this->pembayaran);
    }

    public function getTotalProperty() 
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function getKembalianProperty() 
    {
        return $this->pembayaranMurni - $this->total;
    }

    #[Computed]
    public function searchResults()
    {
        if (strlen($this->searchQuery) < 2) return collect();
        return Product::with('unit')
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->orWhere('sku', 'like', '%' . $this->searchQuery . '%')
            ->where('is_active', true)
            ->take(5)->get();
    }

    public function addSelectedResult($index)
    {
        $results = $this->searchResults->values();
        if ($results->count() > 0 && isset($results[$index])) {
            $this->addToCart($results[$index]->id);
        } else {
            session()->flash('error', 'Obat tidak ditemukan / Barcode salah!');
            $this->searchQuery = '';
            $this->dispatch('item-added');
        }
    }

    public function addToCart($idProduct) 
    {
        if (!$idProduct) return; 
        $product = Product::find($idProduct); 
        if (!$product){
            session()->flash('error','Data obat tidak ditemukan.');
            return;
        }

        $key = $product->id; 
        $totalQty = (isset($this->cart[$key]) ? $this->cart[$key]['quantity'] : 0) + (int)$this->qty; 
        
        if ($product->stock < $totalQty) { 
            session()->flash('error', 'Stok ' . $product->name . ' tidak cukup!');
            return; 
        }

        if (isset($this->cart[$key])) { 
            $item = $this->cart[$key];
            $item['quantity'] = $totalQty;   
            $item['subtotal'] = $totalQty * $item['unit_price'];
            unset($this->cart[$key]); 
            $this->cart = [$key => $item] + $this->cart; 
        } else {
            $newItem = [
                'product_id' => $product->id,
                'name' => $product->name,
                'unit_price' => $product->selling_price,
                'quantity' => (int)$this->qty,
                'subtotal' => (int)$this->qty * $product->selling_price
            ];
            $this->cart = [$key => $newItem] + $this->cart; 
        }
        
        $this->searchQuery = ''; 
        $this->dispatch('reset-highlight'); 
        $this->dispatch('item-added');
    }

    public function removeFromCart($key) 
    {
        unset($this->cart[$key]);
        $this->dispatch('item-added');
    }

    public function updateQuantity($key, $qty)
    {
        if (!isset($this->cart[$key])) return;
        
        $qty = (int) $qty;
        if ($qty < 1) $qty = 1;

        $product = Product::find($this->cart[$key]['product_id']);
        
        if ($qty > $product->stock) {
            $qty = $product->stock;
            session()->flash('error', "Stok {$product->name} tersisa {$product->stock}!");
        }

        $this->cart[$key]['quantity'] = $qty;
        $this->cart[$key]['subtotal'] = $qty * $this->cart[$key]['unit_price'];
    }

    public function incrementQty($key) 
    {
        if (isset($this->cart[$key])) {
            $this->updateQuantity($key, $this->cart[$key]['quantity'] + 1);
        }
    }

    public function decrementQty($key) 
    {
        if (isset($this->cart[$key]) && $this->cart[$key]['quantity'] > 1) {
            $this->updateQuantity($key, $this->cart[$key]['quantity'] - 1);
        }
    }

    public function saveTransaction()  
    {
        if (!in_array(Auth::user()->role, ['admin', 'kasir'])) {
            abort(403, 'Akses ditolak');
        }

        if (empty($this->cart) || $this->pembayaranMurni < $this->total) {
            session()->flash('error', 'Keranjang kosong atau uang pembayaran kurang!');
            return;
        }

        try {
            $createdSaleId = null;
            $kembalianFinal = $this->kembalian; 

            DB::transaction(function () use (&$createdSaleId) { 
                $sale = Sale::create([
                    'invoice_number' => 'INV-' . date('ymdHis'), 
                    'customer_id'    => $this->customerId ?: null,
                    'user_id'        => Auth::id() ?? 1, 
                    'total_price'    => $this->total, 
                    'pembayaran'     => $this->pembayaranMurni, 
                    'kembalian'      => $this->kembalian 
                ]);

                $createdSaleId = $sale->id;

                $openShift = CashierShift::where('user_id', Auth::id())->where('status', 'open')->first();
                if ($openShift) {
                    $openShift->increment('expected_cash', $this->total);
                }

                foreach ($this->cart as $item) { 
                    $sale->details()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'], 
                        'subtotal' => $item['subtotal']
                    ]);
                    
                    $qtyDibutuhkan = $item['quantity'];
                    $batches = ProductBatch::where('product_id', $item['product_id'])
                        ->where('stock', '>', 0)
                        ->where('expired_date', '>=', now())
                        ->orderBy('expired_date', 'asc')
                        ->lockForUpdate() 
                        ->get();

                    foreach ($batches as $batch) {
                        if ($qtyDibutuhkan <= 0) break;
                        if ($batch->stock >= $qtyDibutuhkan) {
                            $batch->decrement('stock', $qtyDibutuhkan);
                            $qtyDibutuhkan = 0; 
                        } else {
                            $qtyDibutuhkan -= $batch->stock; 
                            $batch->update(['stock' => 0]); 
                        }
                    }
                    if ($qtyDibutuhkan > 0) {
                        throw new \Exception("Gagal! Stok fisik {$item['name']} tidak sinkron.");
                    }
                }   
            });

            $this->dispatch('transaction-success', [
                'kembalian' => $kembalianFinal, 
                'printUrl' => route('sales.show', $createdSaleId)
            ]);
            
            $this->reset(['cart', 'customerId', 'pembayaran', 'searchQuery', 'qty']);
            
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $customers = Customer::orderBy('name')->get(); 
        return view('livewire.sales.create', compact('customers'));
    }
}