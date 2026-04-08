<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB as FacadesDB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchase=Purchase::latest()->paginate(10);
        return view('purchases.index',compact('purchase'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product=Product::all();
        $supplier=Supplier::all();
        return view('purchases.create',compact('product','supplier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        $validated=$request->validated();
        return FacadesDB::transaction(function() use ($validated){
            $purchase=Purchase::create([
                'supplier_id'=>$validated['supplier_id'],
                'purchase_number'=>$validated['purchase_number'],
                'purchase_date'=>$validated['purchase_date'],
                'total_cost'=>0,
                'user_id'=>FacadesAuth::id(),
            ]);

            $total=0;
            foreach ($validated['items'] as $item ) {
                $product=Product::findOrFail($item['product_id']);
                $purchase->details()->create([
                    'product_id'=>$item['product_id'],
                    'quantity'=>$item['quantity'],
                    'cost_price'=>$item['cost_price'],
                    'subtotal'=>$item['cost_price']*$item['quantity']
                ]);

                $product->increment('stock',$item['quantity']);
                $total+=$item['cost_price']*$item['quantity'];
            }

            $purchase->update(['total_cost'=>$total]);
            return redirect()->route('purchases.index')->with('success','Berhasil menyimpan stok baru');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        // Load relasi detail dan produknya
        $purchase->load('details.product'); 
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $product=Product::all();
        return view('purchases.edit',compact('product','purchase'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        $validated=$request->validated();
        return FacadesDB::transaction(function()use ($validated,$purchase){
            foreach ($purchase->details as $item) {
                $item->product->decrement('stock',$item->quantity);
            }
            $purchase->details()->delete();

            $total=0;
            foreach ($validated['items'] as $item) {
                $purchase->details()->create([
                    'product_id'=>$item['product_id'],
                    'quantity'=>$item['quantity'],
                    'cost_price'=>$item['cost_price'],
                    'subtotal'=>$item['cost_price']*$item['quantity']
                ]);

                Product::findOrFail($item['product_id'])->increment('stock',$item['quantity']);
                $total+=$item['cost_price']*$item['quantity'];
            }
            $purchase->update([
                'total_cost'=>$total
            ]);
            return redirect()->route('purchases.index')->with('success','Berhasil diupdate');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        FacadesDB::transaction(function() use ($purchase){
            foreach ($purchase->details as $item) {
                Product::findOrFail($item['product_id'])->decrement('stock',$item['quantity']);
            }
            $purchase->delete();
            });
            return redirect()->route('purchases.index')->with('success','Purchase berhasil dihapus');
    }
}
