<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
{
    #[Layout('layouts.app')]
    #[Title('Edit sale')]
    public $sale_id,$invoice_number,$cart=[],$customerId,$pembayaran,$selectedProductId,$qty=1,$stokLama=[];
    public $searchQuery='';

    public function mount($id){
        $sale = Sale::with('details.product')->findOrFail($id);
        $this->sale_id = $sale->id; //isi state
        $this->invoice_number = $sale->invoice_number;
        $this->customerId = $sale->customer_id;
        $this->pembayaran = $sale->pembayaran; 
        $this->stokLama = $sale->details->pluck('quantity', 'product_id')->toArray(); // Simpan stok lama dalam format [product_id => quantity]
        //buat array asosiatif sederhana. stokLama=[10=>5, 12=>2, 13=>4]

        // WAJIB: Load relasi detail beserta produknya supaya 'name' bisa kebaca!
        $sale->load('details.product');// Eager load untuk menghindari N+1 problem saat akses $item->product->name

        // Masukkan detail belanjaan lama ke array $cart
        foreach ($sale->details as $item) {
            $this->cart[] = [
                'product_id' => $item->product_id,
                'name'       => $item->product->name, // Sekarang ini aman karena relasi product sudah diload
                'unit_price' => $item->unit_price,
                'quantity'   => $item->quantity,
                'subtotal'   => $item->subtotal
            ];
        }
    }
    
    public function getPembayaranMurniProperty(){
        return (int) preg_replace('/[^0-9]/', '', (string)$this->pembayaran);
    }
    public function getTotalProperty(){
        return collect($this->cart)->sum('subtotal');
    }

    public function getKembalianProperty(){
        return $this->pembayaranMurni - $this->total;
    }

    public function getSearchResultsProperty(){
        if(strlen($this->searchQuery)<2) return collect();
        return Product::where('name','like','%'.$this->searchQuery.'%')->take(5)->get();
    }
    
    public function addToCart($idProduct) 
    {
        if (!$idProduct) return; 

        $product = Product::find($idProduct); 
        if ($product->stock < $this->qty) return session()->flash('error', 'Stok tidak cukup!');
        if (!$product) return; // Guard tambahan kalau produk tidak ketemu

        // Cari manual pakai foreach (Lebih aman dari bug scope Collection)
        $index = false;
        foreach ($this->cart as $key => $item) {
            if ($item['product_id'] == $product->id) {
                $index = $key;
                break;
            }
        }
        if ($index !== false) {
            $this->cart[$index]['quantity'] += (int)$this->qty;
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'unit_price' => $product->selling_price,
                'quantity'   => (int)$this->qty,
                'subtotal'   => (int)$this->qty * $product->selling_price
            ];
        }
        $this->selectedProductId = '';
        $this->qty = 1;
    }

    function removeFromCart($index){
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    function updateTransaction(){
        if (empty($this->cart)) return session()->flash('error', 'Keranjang tidak boleh kosong!');
        if ($this->pembayaranMurni < $this->total) return session()->flash('error', 'Uang bayar kurang!');

        // Panggil ulang data Sale asli dari database menggunakan ID yang disimpan
        $sale = Sale::findOrFail($this->sale_id);

        DB::transaction(function () use ($sale) {
            
            // TAHAP 1: KEMBALIKAN STOK LAMA
            foreach ($sale->details as $oldItem) {
                $oldItem->product->increment('stock', $oldItem->quantity);
            }

            // TAHAP 2: HAPUS DETAIL LAMA
            $sale->details()->delete();

            // TAHAP 3: INPUT DETAIL BARU & POTONG STOK
            foreach ($this->cart as $item) {
                $sale->details()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal'   => $item['subtotal']
                ]);
                
                Product::find($item['product_id'])->decrement('stock', $item['quantity']);
            }

            // TAHAP 4: UPDATE HEADER
            $sale->update([
                'customer_id' => $this->customerId ?: null,
                'total_price' => $this->total,
                'pembayaran'  => $this->pembayaranMurni,
                'kembalian'   => $this->kembalian
            ]);
        });

        return redirect()->route('sales.index')->with('success', 'Transaksi Berhasil Diupdate!');
    }
    
    public function syncCart($index)
    {
        $item = $this->cart[$index];
        $qty = (int) $item['quantity'];
        
        if ($qty < 1) $qty = 1;

        $product = Product::find($item['product_id']);
        
        // HITUNG STOK AMAN:
        // Stok Aman = Stok di Etalase (Database) + Stok barang INI yang sudah terlanjur masuk faktur lama
        // Gunakan array_search untuk mencari QTY lama dari database (Bukan dari Livewire State)
        $sale = Sale::with('details')->find($this->sale_id);
        $qtyLama = 0;
        if ($sale) {
            $detailLama = $sale->details->where('product_id', $product->id)->first();
            if ($detailLama) {
                $qtyLama = $detailLama->quantity;
            }
        }

        $stokTersedia = $product->stock + $qtyLama;

        if ($qty > $stokTersedia) {
            $qty = $stokTersedia;
            session()->flash('error', "Stok {$product->name} tidak cukup! Maksimal: {$stokTersedia}");
        }

        $this->cart[$index]['quantity'] = $qty;
        $this->cart[$index]['subtotal'] = $qty * $item['unit_price'];
    }
    
    function incrementQty($index){
        $this->cart[$index]['quantity']++;
        $this->syncCart($index);
    }

    function decrementQty($index){
        if ($this->cart[$index]['quantity']>1) {
            $this->cart[$index]['quantity']--;
            $this->syncCart($index);
        }
    }
    
    public function render()
    {
        $customers=Customer::all();
        return view('livewire.sales.edit',compact('customers'));
    }
}
