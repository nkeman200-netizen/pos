@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 bg-gray-50 border-b flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Detail Transaksi: {{ $purchase->purchase_number }}</h2>
            <p class="text-sm text-gray-500">Tanggal: {{ $purchase->purchase_date }}</p>
        </div>
        <a href="{{ route('purchases.index') }}" class="text-indigo-600 font-bold hover:underline">Kembali</a>
    </div>

    <div class="p-6 grid grid-cols-2 gap-8 border-b">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase">Supplier</p>
            <p class="text-lg font-bold text-gray-800">{{ $purchase->supplier->name }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-bold text-gray-400 uppercase">Admin Penerima</p>
            <p class="text-lg font-bold text-gray-800">{{ $purchase->user->name }}</p>
        </div>
    </div>

    <table class="w-full text-left">
        <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3">Nama Produk</th>
                <th class="px-6 py-3 text-center">Qty</th>
                <th class="px-6 py-3 text-right">Harga Satuan</th>
                <th class="px-6 py-3 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($purchase->details as $detail)
            <tr>
                <td class="px-6 py-4 font-medium">{{ $detail->product->name }}</td>
                <td class="px-6 py-4 text-center">{{ $detail->quantity }}</td>
                <td class="px-6 py-4 text-right text-gray-500">Rp{{ number_format($detail->cost_price) }}</td>
                <td class="px-6 py-4 text-right font-bold">Rp{{ number_format($detail->subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-indigo-600 text-white">
            <tr>
                <td colspan="3" class="px-6 py-4 text-right font-bold uppercase">Total Biaya Faktur</td>
                <td class="px-6 py-4 text-right font-black text-xl">Rp{{ number_format($purchase->total_cost) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection