<?php

namespace App\Livewire\Purchases;

use App\Imports\PurchaseTempImport; 
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads; // WAJIB DIPANGGIL
use Maatwebsite\Excel\Facades\Excel; // WAJIB DIPANGGIL

use function Symfony\Component\Clock\now;// WAJIB DIPANGGIL

class Create extends Component
{
    use WithFileUploads; // TAMBAHKAN INI
    #[Layout('layouts.app')]
    #[Title('Catat Stok Masuk (Purchase)')]

    public $items = []; // Kita pakai nama items (sama seperti cart)
    public $supplier_id = '';
    public $searchQuery = ''; 
    public $excelFile;
    public $purchase_order_id = ''; // Untuk nangkep ID PO
    public $purchase_date = '';     // Tanggal terima fisik

    public function mount()
    {
        $this->purchase_date = now()->format('Y-m-d');
    }
    // Fitur pencarian otomatis persis seperti Sales
    public function getSearchResultsProperty()
    {
        if (strlen($this->searchQuery) < 2) return collect();
        return Product::with('unit')
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->orWhere('sku', 'like', '%' . $this->searchQuery . '%')
            ->where('is_active', true)
            ->take(5)->get();
    }

    public function getTotalProperty()
    {
        if (empty($this->items)) return 0;
        return collect($this->items)->sum('subtotal');
    }

    // Fungsi canggih: Auto-add saat di-scan
    public function addToCart($idProduct)
    {
        $product = Product::find($idProduct);
        if (!$product) {
            session()->flash('error', 'Produk tidak ditemukan!');
            return;
        }

        // Langsung tambahkan ke array sebagai baris baru.
        // Di Purchases, BISA JADI 1 obat punya 2 batch berbeda, jadi kita buat baris baru terus.
        $this->items[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'unit_name' => $product->unit->short_name,
            'quantity' => 1,
            'purchase_price' => 0, // Admin harus input harga belinya
            'batch_number' => '',  // Admin harus input
            'expired_date' => '',  // Admin harus input
            'subtotal' => 0
        ];

        $this->searchQuery = ''; // Kosongkan pencarian untuk scan berikutnya
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    // Hitung subtotal dinamis saat qty atau harga beli diubah
    public function syncItem($index)
    {
        $qty = (int) $this->items[$index]['quantity'];
        $price = (int) $this->items[$index]['purchase_price'];
        
        if ($qty < 1) $qty = 1;

        $this->items[$index]['quantity'] = $qty;
        $this->items[$index]['subtotal'] = $qty * $price;
    }
// FITUR UTAMA: Tarik Data PO Otomatis


    public function updatedPurchaseOrderId($id)
    {
        if (!$id) return;

        $po = PurchaseOrder::with(['items.product', 'supplier'])->find($id);
        
        if ($po) {
            $this->supplier_id = $po->supplier_id;
            $this->items = []; // Reset keranjang

            foreach ($po->items as $item) {
                $this->items[] = [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'unit_name' => $item->product->unit->short_name ?? '',
                    'quantity' => $item->quantity,
                    'purchase_price' => $item->purchase_price,
                    'batch_number' => '', 
                    'expired_date' => '',  // <-- Sudah pakai expired_date sesuai DB kamu
                    'subtotal' => $item->subtotal
                ];
            }
        }
    }

    public function saveTransaction()
    {
        $this->validate([
            'supplier_id' => 'required',
        ], [
            'supplier_id.required' => 'Supplier wajib dipilih!'
        ]);

        if (empty($this->items)) {
            session()->flash('error', 'Keranjang stok masuk tidak boleh kosong!');
            return;
        }

        foreach ($this->items as $item) {
            if (empty($item['batch_number']) || empty($item['expired_date']) || $item['purchase_price'] <= 0) {
                session()->flash('error', "Lengkapi Harga Beli, No. Batch, dan Expired Date untuk obat {$item['name']}!");
                return;
            }
        }

        try {
            DB::transaction(function () {
                // 1. Simpan Header (Pakai total_cost sesuai fillable kamu)
                $purchase = Purchase::create([
                    'purchase_number' => 'PRC-' . date('YmdHis'),
                    'purchase_date' => now(),
                    'supplier_id' => $this->supplier_id,
                    'user_id' => Auth::id() ?? 1,
                    'total_cost' => $this->total, 
                ]);

                foreach ($this->items as $item) {
                    // 2. Simpan Item (Pakai details() sesuai relasi di model Purchase kamu)
                    $purchase->details()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'purchase_price' => $item['purchase_price'],
                        'subtotal' => $item['subtotal']
                    ]);

                    // 3. Update atau Tambah Stok di ProductBatch
                    $batch = ProductBatch::where('product_id', $item['product_id'])
                                        ->where('batch_number', $item['batch_number'])
                                        ->first();

                    if ($batch) {
                        $batch->increment('stock', $item['quantity']);
                    } else {
                        ProductBatch::create([
                            'product_id' => $item['product_id'],
                            'batch_number' => $item['batch_number'],
                            'expired_date' => $item['expired_date'],
                            'purchase_price' => $item['purchase_price'],
                            'stock' => $item['quantity']
                        ]);
                    }
                }

                // 4. Update status PO jadi received
                if ($this->purchase_order_id) {
                    PurchaseOrder::where('id', $this->purchase_order_id)->update(['status' => 'received']);
                }
            });

            session()->flash('success', 'Faktur Pembelian berhasil dicatat dan stok gudang bertambah!');
            return redirect()->route('purchases.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
    // FUNGSI BARU: MENGIMPOR EXCEL
    public function importExcel()
    {
        $this->validate([
            'excelFile' => 'required|mimes:xlsx,xls,csv|max:2048' // Maksimal 2MB
        ]);

        try {
            // Baca Excel jadikan array (Ambil sheet pertama)
            $data = Excel::toCollection(new PurchaseTempImport, $this->excelFile)[0];
            
            $notFound = [];

            foreach ($data as $row) {
                // Sesuai kesepakatan: 
                // 0: SKU, 1: Qty, 2: Harga Beli, 3: Batch, 4: Exp Date
                $sku = $row[0];
                if(empty($sku)) continue; // Lewati baris kosong

                $product = Product::with('unit')->where('sku', $sku)->first();

                if ($product) {
                    // Konversi format tanggal bawaan Excel menjadi YYYY-MM-DD
                    $expDate = $row[4];
                    if (is_numeric($expDate)) {
                        $expDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($expDate)->format('Y-m-d');
                    } else {
                        $expDate = \Carbon\Carbon::parse($expDate)->format('Y-m-d');
                    }

                    $this->items[] = [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'unit_name' => $product->unit->short_name ?? '',
                        'quantity' => (int) $row[1],
                        'purchase_price' => (int) $row[2],
                        'batch_number' => (string) $row[3],
                        'expired_date' => $expDate,
                        'subtotal' => (int) $row[1] * (int) $row[2]
                    ];
                } else {
                    $notFound[] = $sku; // Tampung SKU yang nyasar
                }
            }

            // Pesan Hasil Import
            if(count($notFound) > 0) {
                session()->flash('error', 'Berhasil dimuat, TAPI ada SKU yang tidak terdaftar: ' . implode(', ', $notFound));
            } else {
                session()->flash('success', 'Semua data Excel berhasil dimuat ke keranjang!');
            }

            // Bersihkan file dari inputan
            $this->reset('excelFile'); 

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        $suppliers = Supplier::all();
        // Cuma kirim PO yang statusnya 'ordered'
        $purchaseOrders = PurchaseOrder::where('status', 'ordered')->latest()->get(); 
        
        return view('livewire.purchases.create', compact('suppliers', 'purchaseOrders'));
    }
}