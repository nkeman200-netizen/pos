<?php

namespace App\Livewire\Purchases;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads; // WAJIB DIPANGGIL
use Maatwebsite\Excel\Facades\Excel; // WAJIB DIPANGGIL
use App\Imports\PurchaseTempImport; 
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

        // Validasi ekstra: Pastikan Batch, Exp Date, dan Harga Beli tidak kosong
        foreach ($this->items as $item) {
            if (empty($item['batch_number']) || empty($item['expired_date']) || $item['purchase_price'] <= 0) {
                session()->flash('error', "Lengkapi Harga Beli, No. Batch, dan Expired Date untuk obat {$item['name']}!");
                return;
            }
        }

        try {
            DB::transaction(function () {
                $purchase = Purchase::create([
                    'purchase_number' => 'PRC-' . date('YmdHis'), // Auto generate
                    'purchase_date'=>now(),
                    'supplier_id' => $this->supplier_id,
                    'user_id' => Auth::id() ?? 1,
                    'total_cost' => $this->total,
                ]);

                foreach ($this->items as $item) {
                    // 1. Simpan ke Riwayat Purchase
                    $purchase->details()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'purchase_price' => $item['purchase_price'],
                        'subtotal' => $item['subtotal']
                    ]);

                    // 2. Tambah Stok Nyata ke Tabel ProductBatches
                    ProductBatch::create([
                        'product_id' => $item['product_id'],
                        'batch_number' => $item['batch_number'],
                        'expired_date' => $item['expired_date'],
                        'purchase_price' => $item['purchase_price'],
                        'stock' => $item['quantity']
                    ]);
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
        return view('livewire.purchases.create', compact('suppliers'));
    }
}