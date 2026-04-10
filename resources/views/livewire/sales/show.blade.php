<div class="p-6 flex justify-center">
    
    <div id="area-struk" class="bg-white p-8 rounded-xl shadow-lg border border-gray-200 w-full max-w-2xl">
        
        <div class="text-center mb-6">
            <h2 class="text-3xl font-black text-indigo-700 uppercase">POS APP SOFYA</h2>
            <p class="text-gray-500 text-sm mt-1">Jl. Kenangan No. 123, Telp: 08123456789</p>
        </div>

        <div class="flex justify-between items-center border-b-2 border-dashed border-gray-300 pb-4 mb-4 text-sm">
            <div>
                <p><span class="font-bold text-gray-600">No. Nota:</span> <span class="text-indigo-600 font-mono font-bold">{{ $sale->invoice_number }}</span></p>
                <p><span class="font-bold text-gray-600">Tanggal:</span> {{ $sale->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="text-right">
                <p><span class="font-bold text-gray-600">Kasir:</span> {{ $sale->user ? $sale->user->name : 'Sistem' }}</p>
                <p><span class="font-bold text-gray-600">Pelanggan:</span> {{ $sale->customer ? $sale->customer->name : 'Umum (Guest)' }}</p>
            </div>
        </div>

        <table class="w-full text-sm mb-6">
            <thead class="border-b-2 border-gray-800 text-left">
                <tr>
                    <th class="py-2">Barang</th>
                    <th class="py-2 text-center">Qty</th>
                    <th class="py-2 text-right">Harga</th>
                    <th class="py-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="border-b border-dashed border-gray-300">
                @foreach($sale->details as $item)
                <tr>
                    <td class="py-3 font-medium">{{ $item->product->name }}</td>
                    <td class="py-3 text-center">{{ $item->quantity }}</td>
                    <td class="py-3 text-right text-gray-500">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="py-3 text-right font-bold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end text-sm">
            <div class="w-1/2 space-y-2">
                <div class="flex justify-between font-black text-lg border-b border-gray-200 pb-2">
                    <span>TOTAL:</span>
                    <span class="text-indigo-700">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Tunai:</span>
                    <span>Rp {{ number_format($sale->pembayaran, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold">
                    <span>Kembali:</span>
                    <span class="{{ $sale->kembalian < 0 ? 'text-red-500' : 'text-green-600' }}">Rp {{ number_format($sale->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="text-center mt-8 text-xs text-gray-400">
            <p>Terima kasih telah berbelanja!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
        </div>

        <div class="mt-8 flex justify-between items-center gap-4 no-print">
            <a href="{{ route('sales.index') }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2 transition font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Riwayat
            </a>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl flex items-center gap-2 transition shadow-md font-bold">
                <i data-lucide="printer" class="w-5 h-5"></i> Cetak Struk
            </button>
        </div>
    </div>
</div>

@push('scripts')
<style>
    /* CSS Sakti untuk Print: Menyembunyikan sidebar dan tombol, hanya mencetak struknya saja */
    @media print {
        body * {
            visibility: hidden;
        }
        #area-struk, #area-struk * {
            visibility: visible;
        }
        #area-struk {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
            border: none;
            padding: 0;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endpush