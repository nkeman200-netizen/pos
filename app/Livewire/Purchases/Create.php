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
use Illuminate\Support\Facades\Http; 
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads; 
use Maatwebsite\Excel\Facades\Excel; 

class Create extends Component
{
    use WithFileUploads; 
    #[Layout('layouts.app')]
    #[Title('Catat Stok Masuk (Purchase)')]

    public $items = []; 
    public $supplier_id = '';
    public $searchQuery = ''; 
    public $excelFile;
    public $receiptImage; 
    public $purchase_order_id = ''; 
    public $purchase_date = '';     
    public $rowSearchQuery = '';    
    public $rowSearchResults = []; 
    public $activeRowIndex = null; 
    public $tax = 0; 

    public function mount()
    {
        $this->purchase_date = now()->format('Y-m-d');
    }

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
        return collect($this->items)->sum('subtotal') + (int)$this->tax;
    }

    public function updatedItems($value, $key)
    {
        $parts = explode('.', $key);
        if (count($parts) == 2) {
            $index = $parts[0]; 
            $this->syncItem($index); 
        }
    }
    
    public function addToCart($idProduct)
    {
        $product = Product::find($idProduct);
        if (!$product) {
            session()->flash('error', 'Produk tidak ditemukan!');
            return;
        }

        $this->items[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'unit_name' => $product->unit->short_name ?? '-',
            'quantity' => 1,
            'purchase_price' => 0, 
            'discount' => 0, 
            'batch_number' => '',  
            'expired_date' => '',  
            'subtotal' => 0
        ];

        $this->searchQuery = ''; 
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function syncItem($index)
    {
        $qty = (int) $this->items[$index]['quantity'];
        $price = (int) $this->items[$index]['purchase_price'];
        $discount = (int) ($this->items[$index]['discount'] ?? 0);
        
        if ($qty < 1) $qty = 1;
        if ($discount < 0) $discount = 0;

        $subtotal = ($qty * $price) - $discount;

        $this->items[$index]['quantity'] = $qty;
        $this->items[$index]['discount'] = $discount;
        $this->items[$index]['subtotal'] = max(0, $subtotal); 
    }

    public function updatedReceiptImage()
    {
        $this->scanReceipt();
    }

    public function updatedExcelFile()
    {
        $this->importExcel();
    }
    public function scanReceipt()
    {
        set_time_limit(180); 
        $this->validate([
            'receiptImage' => 'required|image|max:5120', 
        ]);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            session()->flash('error', 'API Key Gemini belum diatur di file .env');
            return;
        }

        try {
            $imagePath = $this->receiptImage->getRealPath();
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = $this->receiptImage->getMimeType();

            $prompt = "Anda adalah AI asisten apotek. Baca struk/faktur pembelian barang ini. 
            Kembalikan JSON dengan format OBJECT persis seperti ini: 
            {
                \"tax_total\": 17087,
                \"items\": [
                    {\"name\": \"Nama Obat\", \"quantity\": 10, \"purchase_price\": 15000, \"discount\": 5000, \"batch_number\": \"BCH-AI-123\", \"expired_date\": \"2027-12-31\"}
                ]
            }
            PENTING: 
            1. Bersihkan nama obat dari kata 'NO RETUR', 'NO RET', 'besar', dll. Ambil murni nama, dosis, dan merknya.
            2. Kolom DISC I / DISC II pada faktur adalah PERSENTASE (%). Hitung nominal diskonnya: (quantity * purchase_price) * (angka_diskon / 100). Lakukan perhitungan matematika dengan SANGAT TELITI.
            3. Jika nomor batch tidak ada, buat acak awalan BCH-. 
            4. Jika expired date tidak ada, set 2 tahun dari sekarang (YYYY-MM-DD). 
            5. Cari nominal PPN / Pajak (biasanya 11%). Masukkan angkanya ke dalam 'tax_total'. Jika tidak ada, isi 0.
            HANYA KELUARKAN JSON OBJECT MURNI TANPA MARKDOWN ATAU BASA BASI.";

            $response = Http::connectTimeout(120)->timeout(120)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                                ['inlineData' => ['mimeType' => $mimeType, 'data' => $imageData]]
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $textResponse = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

                $textResponse = str_replace(['```json', '```'], '', $textResponse);
                $aiParsed = json_decode(trim($textResponse), true);

                if (is_array($aiParsed)) {
                    $itemsToProcess = isset($aiParsed['items']) ? $aiParsed['items'] : $aiParsed;
                    
                    if (isset($aiParsed['tax_total'])) {
                        $this->tax = (int) $aiParsed['tax_total'];
                    }

                    if (is_array($itemsToProcess) && count($itemsToProcess) > 0) {
                        foreach ($itemsToProcess as $ai) {
                            
                            if (isset($ai['tax_total'])) {
                                $this->tax = (int) $ai['tax_total'];
                                continue;
                            }

                            if (!isset($ai['name'])) {
                                continue;
                            }

                            $product = Product::where('name', 'like', '%' . substr($ai['name'], 0, 5) . '%')->first();
                            
                            $qty = (int) ($ai['quantity'] ?? 1);
                            $price = (int) ($ai['purchase_price'] ?? 0);
                            $discount = (int) ($ai['discount'] ?? 0);

                            $this->items[] = [
                                'product_id' => $product ? $product->id : null,
                                'name' => $product ? $product->name : $ai['name'] . ' (Belum Terdaftar)',
                                'unit_name' => $product ? ($product->unit->short_name ?? '') : '-',
                                'quantity' => $qty,
                                'purchase_price' => $price,
                                'discount' => $discount,
                                'batch_number' => $ai['batch_number'] ?? '',
                                'expired_date' => $ai['expired_date'] ?? '',
                                'subtotal' => max(0, ($qty * $price) - $discount)
                            ];
                        }
                        session()->flash('success', 'AI OCR berhasil membaca faktur & PPN! Silakan cek kembali.');
                        $this->reset('receiptImage');
                    } else {
                        session()->flash('error', 'AI gagal mendeteksi daftar obat dari faktur.');
                    }
                } else {
                    session()->flash('error', 'Format JSON dari AI rusak atau tidak dikenali.');
                }
            } else {
                session()->flash('error', 'Gagal memproses gambar. Pesan Google: ' . $response->body());
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error AI Scanner: ' . $e->getMessage());
        }
    }

    public function saveTransaction()
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        $this->validate(['supplier_id' => 'required']);

        if (empty($this->items)) {
            session()->flash('error', 'Keranjang stok masuk tidak boleh kosong!');
            return;
        }

        try {
            DB::transaction(function () {
                $purchase = Purchase::create([
                    'purchase_number' => 'PRC-' . date('YmdHis'),
                    'purchase_date' => now(),
                    'supplier_id' => $this->supplier_id,
                    'user_id' => Auth::id() ?? 1,
                    'total_cost' => $this->total, 
                ]);

                foreach ($this->items as $item) {
                    $purchase->details()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'purchase_price' => $item['purchase_price'],
                        'discount' => $item['discount'] ?? 0, 
                        'subtotal' => $item['subtotal']
                    ]);

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
                            'purchase_price' => max(0, $item['purchase_price'] - (($item['discount'] ?? 0) / $item['quantity'])),
                            'stock' => $item['quantity']
                        ]);
                    }
                }

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

    public function downloadTemplate()
    {
        $csvData = "SKU/Barcode,Jumlah Qty,Harga Beli,Diskon (Rp),Nomor Batch,Tanggal Expired\n";
        $csvData .= "APTK-0001,50,15000,5000,BCH-001,2028-12-31\n";
        return response()->streamDownload(function () use ($csvData) { echo $csvData; }, 'Template_Import_Stok_Masuk.csv');
    }

    public function importExcel()
    {
        $this->validate(['excelFile' => 'required|mimes:xlsx,xls,csv|max:2048']);
        try {
            $data = Excel::toCollection(new PurchaseTempImport, $this->excelFile)[0];
            $notFound = [];

            foreach ($data as $row) {
                $sku = $row[0];
                if(empty($sku)) continue;

                $product = Product::with('unit')->where('sku', $sku)->first();

                if ($product) {
                    $expDate = is_numeric($row[5]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5])->format('Y-m-d') : \Carbon\Carbon::parse($row[5])->format('Y-m-d');
                    
                    $qty = (int) $row[1];
                    $price = (int) $row[2];
                    $discount = (int) ($row[3] ?? 0);

                    $this->items[] = [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'unit_name' => $product->unit->short_name ?? '',
                        'quantity' => $qty,
                        'purchase_price' => $price,
                        'discount' => $discount,
                        'batch_number' => (string) $row[4],
                        'expired_date' => $expDate,
                        'subtotal' => max(0, ($qty * $price) - $discount)
                    ];
                } else {
                    $notFound[] = $sku;
                }
            }

            if(count($notFound) > 0) {
                session()->flash('error', 'Berhasil dimuat, TAPI ada SKU yang tidak terdaftar: ' . implode(', ', $notFound));
            } else {
                session()->flash('success', 'Semua data Excel berhasil dimuat ke keranjang!');
            }
            $this->reset('excelFile'); 
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }
    
    public function openRowSearch($index)
    {
        $this->activeRowIndex = $index;
        $this->rowSearchQuery = '';
        $this->rowSearchResults = [];
    }

    public function updatedRowSearchQuery()
    {
        if (strlen($this->rowSearchQuery) >= 2) {
            $this->rowSearchResults = Product::with('unit')
                ->where('name', 'like', '%' . $this->rowSearchQuery . '%')
                ->orWhere('sku', 'like', '%' . $this->rowSearchQuery . '%')
                ->where('is_active', true)
                ->take(5)->get();
        } else {
            $this->rowSearchResults = [];
        }
    }

    public function updateProductMapping($index, $productId)
    {
        $product = Product::with('unit')->find($productId);
        if ($product) {
            $this->items[$index]['product_id'] = $product->id; 
            $this->items[$index]['name'] = $product->name;     
            $this->items[$index]['unit_name'] = $product->unit->short_name ?? '-';
            
            $this->activeRowIndex = null;
            $this->rowSearchQuery = '';
            $this->rowSearchResults = [];
        }
    }
    
    public function render()
    {
        $suppliers = Supplier::all();
        $purchaseOrders = PurchaseOrder::where('status', 'ordered')->latest()->get(); 
        return view('livewire.purchases.create', compact('suppliers', 'purchaseOrders'));
    }
}