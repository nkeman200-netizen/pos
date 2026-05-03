<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen flex justify-center transition-colors duration-300"
     x-data="{ showVoidModal: false }"
     x-init="setTimeout(() => window.print(), 800)"
     @keydown.window.escape="if(showVoidModal) { showVoidModal = false; } else { window.location.href = '{{ route('sales.create') }}' }"
     @keydown.window.enter="if(!showVoidModal) { window.print() }"
     @close-void-modal.window="showVoidModal = false">

    <style>
        @media print {
            @page { margin: 0; }
            body, html, main, div { position: static !important; margin: 0 !important; padding: 0 !important; transform: none !important; }
            body * { visibility: hidden; }
            #area-struk, #area-struk * { visibility: visible; color: black !important; }
            #area-struk {
                position: absolute !important; top: 0 !important; left: 0 !important; right: 0 !important;
                margin: 0 auto !important; width: 80mm !important; max-width: 100% !important; padding: 5mm !important;
                border: none !important; box-shadow: none !important; background-color: white !important;
            }
            .no-print { display: none !important; }
        }
    </style>

    <div class="w-full max-w-2xl">
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-black text-center no-print">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-wrap justify-between items-center mb-6 gap-4 no-print">
            <a href="{{ route('sales.index') }}" class="text-gray-500 font-bold hover:text-indigo-600 flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg> Riwayat
            </a>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('sales.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl font-bold shadow-md flex items-center gap-2 transition text-sm">
                    Kembali Kasir (ESC)
                </a>
                
                @if($sale->status === 'completed' && in_array(auth()->user()->role, ['admin', 'owner']))
                <button @click="showVoidModal = true" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl font-bold border border-red-200 hover:bg-red-100 transition text-sm">Void</button>
                @endif
                
                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl font-bold shadow-md transition flex items-center gap-2 text-sm">
                    Cetak Ulang
                </button>
            </div>
        </div>

        <div id="area-struk" class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden">
            @if($sale->status === 'void')
                <div class="absolute inset-0 flex items-center justify-center opacity-20 pointer-events-none z-0">
                    <h1 class="text-6xl font-black text-red-600 -rotate-45 uppercase border-4 border-red-600 p-4 rounded-xl">VOID</h1>
                </div>
            @endif

            <div class="text-center mb-6 relative z-10">
                @php $apotek = \App\Models\PharmacyProfile::first(); @endphp
                <h2 class="text-2xl font-black text-indigo-700 dark:text-indigo-400 uppercase">{{ $apotek->name ?? 'APOTEK SOFYA' }}</h2>
                <p class="text-xs text-gray-500">{{ $apotek->address ?? 'Alamat Apotek' }}</p>
            </div>

            <div class="flex justify-between text-xs border-b-2 border-dashed pb-4 mb-4 relative z-10">
                <div>
                    <p class="font-bold text-indigo-600 font-mono">{{ $sale->invoice_number }}</p>
                    <p>{{ $sale->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <p>Kasir: {{ $sale->user->name ?? '-' }}</p>
                    <p>Plg: {{ $sale->customer->name ?? 'Umum' }}</p>
                </div>
            </div>

            <table class="w-full text-sm mb-4 relative z-10">
                <tbody class="divide-y divide-dashed">
                    @foreach($sale->details as $item)
                    <tr>
                        <td class="py-2">
                            {{ $item->product->name }}
                            <br>
                            <span class="text-[10px] text-gray-400">
                                Batch: {{ $item->batch->batch_number }} | {{ $item->quantity }} x Rp{{ number_format($item->unit_price) }}
                            </span>
                        </td>
                        <td class="py-2 text-right font-bold">Rp{{ number_format($item->subtotal) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="border-t-2 border-dashed pt-4 space-y-1 text-sm relative z-10">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-bold uppercase text-xs text-gray-400">Total Belanja</span>
                    <span class="text-xl font-black text-gray-800 dark:text-gray-100 font-mono">Rp{{ number_format($sale->total_price) }}</span>
                </div>

                @if($sale->payment_method === 'cash' || empty($sale->payment_method))
                    <div class="flex justify-between">
                        <span>Tunai</span>
                        <span class="font-bold">Rp{{ number_format($sale->pembayaran) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kembalian</span>
                        <span class="font-bold">Rp{{ number_format($sale->kembalian) }}</span>
                    </div>
                @else
                    <div class="flex justify-between">
                        <span>Metode Pembayaran</span>
                        <span class="font-bold uppercase text-indigo-600">{{ $sale->payment_method }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>No. Ref/Trace</span>
                        <span class="font-bold font-mono">{{ $sale->payment_reference ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between mt-2 pt-2 border-t border-gray-100">
                        <span class="font-bold">Status</span>
                        <span class="font-black text-emerald-600">LUNAS</span>
                    </div>
                @endif
            </div>

            <div class="text-center mt-8 pt-4 border-t-2 border-dashed text-xs text-gray-400 relative z-10">
                <p>Terima Kasih Atas Kunjungan Anda</p>
                <p>Semoga Lekas Sembuh</p>
            </div>
        </div>
    </div>

    <div x-show="showVoidModal" x-cloak class="no-print fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-2xl w-full max-w-md">
            <h2 class="text-xl font-black mb-4">Batalkan Transaksi?</h2>
            @if (session()->has('void_error'))
                <div class="mb-4 p-3 bg-red-50 text-red-600 rounded-xl text-sm font-bold border border-red-200">
                    {{ session('void_error') }}
                </div>
            @endif
            <input type="text" wire:model="voidReason" class="w-full p-4 bg-gray-50 font-bold text-gray-700 border rounded-xl mb-6" placeholder="Alasan pembatalan...">
            <div class="flex gap-3">
                <button @click="showVoidModal = false" class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-xl font-bold">Batal</button>
                <button wire:click="voidTransaction" class="flex-1 py-3 bg-red-600 text-white rounded-xl font-black">Eksekusi Void</button>
            </div>
        </div>
    </div>
</div>