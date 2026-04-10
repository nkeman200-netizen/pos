<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // eager loading (with), pake with agar ga kena masaalah N+1 query
        $saleItems=Sale::with('user','customer')->latest()->paginate(10);
        return view('sales.index',compact('saleItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() //kirim ke form new
    { //yang perlu dikirim adalah product dan customer. biar bisa diproses di store
        $products=Product::where('stock','>','0')->get(); //ambil dengan query where
        $customers=Customer::all(); //tidak paginate 
        return view('sales.create',compact('products','customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $storeSaleRequest) //tangkep form post
    {
        $request=$storeSaleRequest->validated();

        return DB::transaction(function () use ($request){ //pake transaction untuk keamanan, gagal ditengah, gagal semua
            //1. TABEL UTAMA
            $sale=Sale::create([
                'invoice_number'=>'INV-'. date('YmdHis'),
                'customer_id'=>$request['customer_id'],
                'user_id'=>Auth::id(), //ambil otomatis dari user login
                'total_price'=>0, //kosongin dulu
                'pembayaran'=>$request['pembayaran'],
                'kembalian'=>0
            ]);
            $total = 0;
            //2. TABEL DETAIL & OTOMATISASI STOK
            foreach ($request['items'] as $item) { //ambil satu satu produc yang dibeli
                $product=Product::findOrFail($item['product_id']); //tangkep, ambil objeknya
                if ($product->stock < $item['quantity']) { //cek apakah stoknya cukup sesuai jumlah pembelian
                    throw new \Exception("Stok {$product->name} tidak cukup!"); //pake petik dua, biar bisa baca variabel
                }

                $sale->details()->create([ //buat tabel via relasi metode (di model), tabel sale_item
                    'product_id'=>$item['product_id'],
                    'quantity'=>$item['quantity'],
                    'unit_price'=>$product->selling_price,
                    'subtotal'=>$item['quantity']*$product->selling_price
                ]);

                $product->decrement('stock',$item['quantity']); //penurunan stok tabel product dari jumlah yang dibeli 
                $total+=($item['quantity']*$product->selling_price); //jumlah total biaya belanja
            }
            //3. UPDATE TABEL UTAMA
            $sale->update([ //yang tadi masih dikosongin, diisi di sini
                'total_price'=>$total,
                'kembalian'=>($request['pembayaran'])-$total
            ]);

            return redirect()->route('sales.index')->with('success','Pembelian berhasil');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load('user','details.product','customer'); //eager loading. //ambil metode datails, ambil product. ada nested relasi
        return view('sales.show',compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale) //kirim sale (struk) nya ae, ntar di situ udh ada product dan details nya, via relasi
    {
        return view('sales.edit',compact('sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $updateSaleRequest, Sale $sale)
    {
        // 1. VALIDASI DATA FORM POST
        $request=$updateSaleRequest->validated();
        // 2. FUNGSI TRANSACTION: KEAMANAN
        return DB::transaction(function () use ($request,$sale){

            //1. KEMBALIKAN STOK PRODUK
            foreach ($sale->details as $item) { //ubah tabel produk via struk detail yang lama //jangan pake details()
                $item->product->increment('stock',$item->quantity);//Kenaikan berdasarkan jumlah yang dibeli sebelumnya
            }

            //2. hapus detailS
            $sale->details()->delete(); //setelah tebel produk dibetulin, hapus jembatannya, biar bisa bbuat baru
            
            $total = 0;
            //3. SIMPAN BARU TABEL DETAIL & OTOMATISASI STOK
            foreach ($request['items'] as $item) { //request form post yang dikirim
                $product=Product::findOrFail($item['product_id']); //ambil id produk, cari objeknya
                if ($product->stock < $item['quantity']) { //cek apakah stoknya cukup
                    throw new \Exception("Stok {$product->name} tidak cukup untuk update!"); //pake petik dua, biar bisa baca variabel
                }

                $sale->details()->create([ //buat detail baru, yang tadi dihapus
                    'product_id'=>$item['product_id'],
                    'quantity'=>$item['quantity'],
                    'unit_price'=>$product->selling_price,
                    'subtotal'=>$item['quantity']*$product->selling_price
                ]);

                $product->decrement('stock',$item['quantity']); //otomatisasi perubahan stok
                $total+=($item['quantity']*$product->selling_price); //increment total biaya
            }
            //4. UPDATE TABEL UTAMA
            $sale->update([
                'customer_id' => $request['customer_id'], // Gaya ARRAY []
                'total_price' => $total,
                'pembayaran'  => $request['pembayaran'],
                'kembalian'   => $request['pembayaran'] - $total
            ]);

            return redirect()->route('sales.index')->with('success','Berhasil diupdate');
        });

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        DB::transaction(function() use ($sale) {
            foreach ($sale->details as $item) {
                $item->product->increment('stock',$item->quantity); 
            };
            $sale->delete();
            } );
        return redirect()->route('sales.index')->with('success','Behasil dihapus');
    }
}