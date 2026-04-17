<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseController extends Controller
{
    public function print($id)
    {
        // Load purchase dengan relasi details, product, supplier, dan user
        $purchase = Purchase::with(['supplier', 'user', 'details.product'])->findOrFail($id);
        
        $pdf = Pdf::loadView('purchases.pdf', compact('purchase'));
        
        return $pdf->stream('Faktur-Masuk-' . $purchase->purchase_number . '.pdf');
    }
}