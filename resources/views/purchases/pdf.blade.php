<!DOCTYPE html>
<html>
<head>
    <title>Faktur Stok Masuk - {{ $purchase->purchase_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        .table th { background-color: #f9f9f9; text-transform: uppercase; font-size: 10px; }
        .total { text-align: right; margin-top: 15px; font-weight: bold; font-size: 13px; }
        .footer { margin-top: 40px; }
    </style>
</head>
<body>
    <div class="header" style="text-align: center; margin-bottom: 30px;">
        @php 
            $apotek = \App\Models\PharmacyProfile::first(); 
            $logoBase64 = null;
            if($apotek && $apotek->logo && file_exists(public_path('storage/' . $apotek->logo))) {
                $type = pathinfo(public_path('storage/' . $apotek->logo), PATHINFO_EXTENSION);
                $data = file_get_contents(public_path('storage/' . $apotek->logo));
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp
        
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo Apotek" style="max-height: 80px; margin-bottom: 10px;">
        @else
            <h1 style="margin: 0;">{{ $apotek->name ?? 'POS APOTEK SOFYA' }}</h1>
        @endif
        <h2 style="margin-bottom: 5px;">BUKTI PENERIMAAN BARANG</h2>
        <p style="margin: 0;">No. Faktur: {{ $purchase->purchase_number }} | Tgl Terima: {{ $purchase->purchase_date }}</p>
    </div>  

    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td style="width: 50%;">
                <strong>Supplier:</strong><br>
                {{ $purchase->supplier->name }}<br>
                Telp: {{ $purchase->supplier->phone ?? '-' }}
            </td>
            <td style="width: 50%; text-align: right;">
                <strong>Petugas Penerima:</strong><br>
                {{ $purchase->user->name }}
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>No. Batch</th>
                <th>Exp. Date</th>
                <th>Qty</th>
                <th>Harga Beli</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase->details as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->batch_number }}</td>
                <td>{{ $item->expired_date }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">Rp{{ number_format($item->purchase_price) }}</td>
                <td style="text-align: right;">Rp{{ number_format($item->subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">Total Nilai Faktur: Rp{{ number_format($purchase->total_cost) }}</div>

    <div class="footer">
        <table style="width: 100%; text-align: center;">
            <tr>
                <td>Petugas Gudang,<br><br><br><br>( {{ $purchase->user->name }} )</td>
                <td>Saksi/Supplier,<br><br><br><br>( ________________ )</td>
            </tr>
        </table>
    </div>
</body>
</html>