<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order - {{ $po->po_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .total { text-align: right; margin-top: 20px; font-weight: bold; font-size: 14px; }
        .footer { margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PURCHASE ORDER</h1>
        <p>No: {{ $po->po_number }} | Tanggal: {{ $po->order_date }}</p>
    </div>

    <table style="width: 100%">
        <tr>
            <td style="width: 50%; border: none;">
                <strong>Kepada Supplier:</strong><br>
                {{ $po->supplier->name }}<br>
                {{ $po->supplier->address }}<br>
                Telp: {{ $po->supplier->phone }}
            </td>
            <td style="width: 50%; border: none; text-align: right;">
                <strong>Dari:</strong><br>
                POS APOTEK SOFYA<br>
                Estimasi Tiba: {{ $po->expected_date }}
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Harga Est.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($po->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp{{ number_format($item->purchase_price) }}</td>
                <td>Rp{{ number_format($item->subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">Total Estimasi: Rp{{ number_format($po->total_amount) }}</div>

    @if($po->notes)
    <div style="margin-top: 20px;">
        <strong>Catatan:</strong><br>
        {{ $po->notes }}
    </div>
    @endif

    <div class="footer">
        <table style="width: 100%; text-align: center; border: none;">
            <tr>
                <td style="border: none;">Dipesan Oleh,<br><br><br><br>({{ $po->user->name }})</td>
                <td style="border: none;">Disetujui Oleh,<br><br><br><br>(________________)</td>
            </tr>
        </table>
    </div>
</body>
</html>