<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrderController extends Controller
{
    public function print($id)
    {
        $po = PurchaseOrder::with(['supplier', 'user', 'items.product'])->findOrFail($id);
        
        $pdf = Pdf::loadView('purchase-orders.pdf', compact('po'));
        
        return $pdf->stream('PO-' . $po->po_number . '.pdf');
    }
}