<?php

namespace App\Livewire\Sales;

use App\Models\CashierShift;
use App\Models\Customer;
use App\Models\HeldTransaction;
use App\Models\PharmacyProfile;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Sale;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Layout('layouts.app')]
    #[Title('Kasir Penjualan')]
    
    public $cart = [];
    public $customerId = '';
    public $pembayaran = '';
    public $qty = 1;
    public $searchQuery = ''; 

    public $isLocked = false;
    public $pinInput = '';
    public $hasOpenShift = false;
    public $startingCash = '';
    public $actualCash='';
    
    public $holdNote = '';
    public $heldTransactionsList = [];

    public $paymentMethod = 'cash'; 
    public $paymentReference = '';  
    public $showQrisModal = false; 
    public $qrisString = '';

    public function mount()
    {
        $this->isLocked = session('kasir_locked', false);
        $this->hasOpenShift = CashierShift::where('user_id', Auth::id())->where('status', 'open')->exists();
        $this->loadHeldTransactions();
    }

    #[Computed]
    public function total()
    {
        return collect($this->cart)->sum('subtotal');
    }

    #[Computed]
    public function pembayaranMurni()
    {
        return (int) str_replace('.', '', $this->pembayaran);
    }

    #[Computed]
    public function kembalian()
    {
        return $this->pembayaranMurni - $this->total;
    }

    public function getSearchResultsProperty()
    {
        if (strlen($this->searchQuery) < 2) return collect();
        return Product::with(['unit', 'batches'])
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->orWhere('sku', 'like', '%' . $this->searchQuery . '%')
            ->take(5)
            ->get();
    }

    public function addHighlightedToCart($index)
    {
        $results = $this->searchResults->values();
        if (isset($results[$index])) {
            $this->addToCart($results[$index]);
        }
    }

    public function addToCart($product)
    {
        $totalStock = collect($product['batches'])->sum('stock');
        if ($totalStock <= 0) {
            session()->flash('error', 'Stok obat ini sedang kosong!');
            return;
        }

        $existingIndex = collect($this->cart)->search(fn($item) => $item['product_id'] == $product['id']);

        if ($existingIndex !== false) {
            if ($this->cart[$existingIndex]['quantity'] + $this->qty > $totalStock) {
                session()->flash('error', 'Stok tidak mencukupi untuk ditambah lagi!');
                return;
            }
            
            $item = $this->cart[$existingIndex];
            $item['quantity'] += $this->qty;
            $item['subtotal'] = $item['quantity'] * $item['unit_price'];
            
            unset($this->cart[$existingIndex]);
            $this->cart = array_values($this->cart);
            array_unshift($this->cart, $item);

        } else {
            $newItem = [
                'product_id' => $product['id'],
                'name' => $product['name'],
                'unit_price' => $product['selling_price'],
                'quantity' => $this->qty,
                'subtotal' => $product['selling_price'] * $this->qty,
            ];
            array_unshift($this->cart, $newItem);
        }
        
        $this->qty = 1;
        $this->searchQuery = '';
        $this->dispatch('item-added'); 
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function incrementQuantity($index)
    {
        $product = Product::with('batches')->find($this->cart[$index]['product_id']);
        $totalStock = collect($product->batches)->sum('stock');
        
        if ($this->cart[$index]['quantity'] < $totalStock) {
            $this->cart[$index]['quantity']++;
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
        } else {
            session()->flash('error', 'Maksimal stok tercapai!');
        }
    }

    public function decrementQuantity($index)
    {
        if ($this->cart[$index]['quantity'] > 1) {
            $this->cart[$index]['quantity']--;
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
        } else {
            $this->removeFromCart($index);
        }
    }

    public function updateQuantity($index, $qty)
    {
        $qty = (int) $qty;
        if ($qty < 1) {
            $this->removeFromCart($index);
            return;
        }

        $product = Product::with('batches')->find($this->cart[$index]['product_id']);
        $totalStock = collect($product->batches)->sum('stock');

        if ($qty <= $totalStock) {
            $this->cart[$index]['quantity'] = $qty;
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
        } else {
            session()->flash('error', 'Stok tidak mencukupi!');
            $this->cart[$index]['quantity'] = $totalStock; 
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
        }
    }

    public function saveTransaction()  
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja masih kosong!');
            return;
        }

        if ($this->paymentMethod === 'qris') {
            $profile = PharmacyProfile::first();
            if (!$profile || !$profile->qris_string) {
                session()->flash('error', 'Data QRIS belum diatur di Profil Apotek!');
                return;
            }
            $this->qrisString = $this->makeDynamicQRIS($profile->qris_string, $this->total);
            $this->showQrisModal = true;
            return;
        }

        $this->executeCheckout();
    }

    public function confirmQrisPaymentAndCheckout()
    {
        $this->paymentReference = 'QR' . strtoupper(uniqid()); 
        $this->executeCheckout();
    }

    private function executeCheckout()
    {
        $nominalDibayar = $this->paymentMethod === 'cash' ? $this->pembayaranMurni : $this->total;
        $kembalianFinal = $this->paymentMethod === 'cash' ? $this->kembalian : 0;

        if ($this->paymentMethod === 'cash' && $nominalDibayar < $this->total) {
            session()->flash('error', 'Pembayaran tunai kurang!');
            return;
        }

        try {
            $createdSaleId = null;

            DB::transaction(function () use (&$createdSaleId, $nominalDibayar, $kembalianFinal) {
                $sale = Sale::create([
                    'invoice_number'    => 'INV-' . date('ymdHis'), 
                    'customer_id'       => $this->customerId ?: null,
                    'user_id'           => Auth::id(), 
                    'total_price'       => $this->total, 
                    'pembayaran'        => $nominalDibayar, 
                    'kembalian'         => $kembalianFinal,
                    'payment_method'    => $this->paymentMethod,
                    'payment_reference' => $this->paymentReference ?: null,
                    'status'            => 'completed'
                ]);

                $createdSaleId = $sale->id;

                if ($this->paymentMethod === 'cash') {
                    $openShift = CashierShift::where('user_id', Auth::id())->where('status', 'open')->first();
                    if ($openShift) {
                        $openShift->increment('expected_cash', $this->total);
                    }
                }

                foreach ($this->cart as $item) {
                    
                    
                    $qtyDibutuhkan = $item['quantity'];
                    $batches = ProductBatch::where('product_id', $item['product_id'])
                        ->where('stock', '>', 0)
                        ->where('expired_date', '>=', now())
                        ->orderBy('expired_date', 'asc')
                        ->get();

                    foreach ($batches as $batch) {
                        if ($qtyDibutuhkan <= 0) break;
                        $qtyDiambil=0;
                        if ($batch->stock >= $qtyDibutuhkan) {
                            $batch->decrement('stock', $qtyDibutuhkan);
                            $qtyDiambil=$qtyDibutuhkan;
                            $qtyDibutuhkan = 0; 
                        } else {
                            $qtyDiambil=$batch->stock;
                            $qtyDibutuhkan -= $batch->stock; 
                            $batch->update(['stock' => 0]); 
                        }
                        $sale->details()->create([
                            'product_id' => $item['product_id'],
                            'product_batch_id' => $batch->id,
                            'quantity' => $qtyDiambil,
                            'unit_price' => $item['unit_price'], 
                            'subtotal' => $item['unit_price']*$qtyDiambil
                        ]);
                    }
                    if($qtyDibutuhkan>0){
                        throw new \Exception("stok tidak mencukup ".$item['name']);
                    }
                }
            });

            return redirect()->route('sales.show', $createdSaleId);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    private function makeDynamicQRIS($fullPayload, $amount)
    {
        $payload = substr($fullPayload, 0, -8); 
        
        $payload = str_replace('010211', '010212', $payload); 
        
        $valAmount = (string) $amount;
        $lenAmount = str_pad(strlen($valAmount), 2, '0', STR_PAD_LEFT);
        $payload .= "54" . $lenAmount . $valAmount;
        
        $payload .= "6304"; 
        
        $crc = $this->crc16($payload); 
        return $payload . $crc;
    }

    private function crc16($data)
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $x = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
            $x ^= $x >> 4;
            $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
        }
        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    public function lockCashier() {
        $this->isLocked = true;
        session(['kasir_locked' => true]);
    }

    public function unlockCashier() {
        if ($this->pinInput == Auth::user()->pin) { 
            $this->isLocked = false;
            $this->pinInput = '';
            session(['kasir_locked' => false]);
        } else {
            session()->flash('pin_error', 'PIN Keamanan Salah!');
        }
    }

    public function openShift() {
        $startingCash = (int) str_replace('.', '', $this->startingCash);
        CashierShift::create([
            'user_id' => Auth::id(),
            'starting_cash' => $startingCash,
            'expected_cash' => $startingCash,
            'status' => 'open',
            'start_time' => now()
        ]);
        $this->hasOpenShift = true;
    }

    public function closeShift()
    {
        $nominalFisik = (int) preg_replace('/[^0-9]/', '', (string) $this->actualCash);

        if ($this->actualCash === '' || $nominalFisik < 0) {
            session()->flash('close_shift_error', 'Hitung fisik laci dan masukkan nominalnya!');
            return;
        }

        $openShift = CashierShift::where('user_id', Auth::id())->where('status', 'open')->first();

        if ($openShift) {
            $openShift->update([
                'actual_cash' => $nominalFisik,
                'end_time' => now(),
                'status' => 'closed',
            ]);
        }

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    }
    public function holdTransaction() {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang kosong, tidak ada yang ditahan.');
            return;
        }
        
        HeldTransaction::create([
            'user_id' => Auth::id(),
            'customer_id' => $this->customerId ?: null,
            'cart_data' => $this->cart,
            'reference_notes' => $this->holdNote,
        ]);
        
        $this->reset(['cart', 'customerId', 'holdNote', 'pembayaran']);
        $this->loadHeldTransactions();
    }

    public function loadHeldTransactions() {
        $this->heldTransactionsList = HeldTransaction::where('user_id', Auth::id())->latest()->get();
    }

    public function recallTransaction($id) {
        $held = HeldTransaction::findOrFail($id);
        $this->cart = $held->cart_data;
        $this->customerId = $held->customer_id;
        $held->delete();
        $this->loadHeldTransactions();
    }

    public function render()
    {
        $customers = Customer::orderBy('name')->get(); 
        $apotek=PharmacyProfile::first();
        return view('livewire.sales.create', compact('customers','apotek'));
    }
}