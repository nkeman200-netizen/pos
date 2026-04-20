<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen flex justify-center transition-colors duration-300"
     x-data="{ showVoidModal: false }"
     x-init="setTimeout(() => window.print(), 800)"
     @keydown.window.escape="if(showVoidModal) { showVoidModal = false; } else { window.location.href = '{{ route('sales.create') }}' }"
     @keydown.window.enter="if(!showVoidModal) { window.print() }"
     @close-void-modal.window="showVoidModal = false">

    @push('scripts')
    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500); 
        };
        
        window.onafterprint = function() {
            window.close();
        };
    </script>
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
            .border-dashed { border-color: #000 !important; }
            .no-print { display: none !important; }
        }
    </style>
    @endpush

    <div class="w-full max-w-2xl">
        
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-black text-center shadow-sm text-lg animate-pulse no-print">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-wrap justify-between items-center mb-6 gap-4 no-print">
            <a href="{{ route('sales.index') }}" class="flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-indigo-600 transition font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg> Riwayat
            </a>
            
            <div class="flex gap-3">
                <a href="{{ route('sales.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 font-bold text-sm shadow-md transition">
                    <span class="bg-emerald-800/50 text-[10px] px-1.5 py-0.5 rounded border border-emerald-500/30">ESC</span>
                    Transaksi Baru
                </a>
                
                @if(isset($sale->status) && $sale->status === 'completed' && in_array(auth()->user()->role, ['admin', 'owner']))
                <button @click="showVoidModal = true; setTimeout(() => $refs.reasonInput.focus(), 100)" class="bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:hover:bg-red-500/20 px-5 py-2.5 rounded-xl flex items-center gap-2 transition font-bold text-sm border border-red-200 dark:border-red-500/30">
                    Batalkan Transaksi
                </button>
                @endif

                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition shadow-md font-bold text-sm">
                    <span class="bg-indigo-800/50 text-[10px] px-1.5 py-0.5 rounded border border-indigo-500/30">ENTER</span>
                    Cetak Ulang
                </button>
            </div>
        </div>

        <div id="area-struk" class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 w-full relative overflow-hidden">

            @if(isset($sale->status) && $sale->status === 'void')
                <div class="absolute inset-0 flex items-center justify-center opacity-20 pointer-events-none z-0">
                    <h1 class="text-6xl font-black text-red-600 -rotate-45 uppercase border-4 border-red-600 p-4 rounded-xl">VOID</h1>
                </div>
            @endif

            <div class="text-center mb-6 relative z-10">
                @php $apotek = \App\Models\PharmacyProfile::first(); @endphp
                <h2 class="text-3xl font-black text-indigo-700 dark:text-indigo-400 uppercase tracking-wider">{{ $apotek->name ?? 'POS APP SOFYA' }}</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $apotek->address ?? 'Alamat Apotek' }} <br> Telp: {{ $apotek->phone ?? '-' }}</p>
            </div>

            <div class="flex justify-between items-center border-b-2 border-dashed border-gray-200 dark:border-slate-600 pb-4 mb-4 text-sm relative z-10">
                <div>
                    <p><span class="font-bold text-gray-500 dark:text-gray-400">No. Nota:</span> <span class="text-indigo-600 dark:text-indigo-400 font-mono font-bold">{{ $sale->invoice_number }}</span></p>
                    <p><span class="font-bold text-gray-500 dark:text-gray-400">Tanggal:</span> <span class="dark:text-gray-200">{{ $sale->created_at->format('d M Y H:i') }}</span></p>
                </div>
                <div class="text-right">
                    <p><span class="font-bold text-gray-500 dark:text-gray-400">Kasir:</span> <span class="dark:text-gray-200">{{ $sale->user ? $sale->user->name : 'Sistem' }}</span></p>
                    <p><span class="font-bold text-gray-500 dark:text-gray-400">Pelanggan:</span> <span class="dark:text-gray-200">{{ $sale->customer ? $sale->customer->name : 'Umum' }}</span></p>
                </div>
            </div>

            <table class="w-full mb-4 relative z-10">
                <thead class="border-b-2 border-dashed border-gray-200 dark:border-slate-600">
                    <tr class="text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">
                        <th class="py-2">Item</th>
                        <th class="py-2 text-center">Qty</th>
                        <th class="py-2 text-right">Harga</th>
                        <th class="py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dashed divide-gray-100 dark:divide-slate-700 text-sm">
                    @foreach($sale->details as $item)
                    <tr class="dark:text-gray-200">
                        <td class="py-3 font-medium">{{ $item->product->name }}</td>
                        <td class="py-3 text-center">{{ $item->quantity }}</td>
                        <td class="py-3 text-right">Rp{{ number_format($item->unit_price) }}</td>
                        <td class="py-3 text-right font-bold">Rp{{ number_format($item->subtotal) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="border-t-2 border-dashed border-gray-200 dark:border-slate-600 pt-4 space-y-2 text-sm relative z-10">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest text-xs">Total Belanja</span>
                    <span class="text-xl font-black text-gray-800 dark:text-gray-100 font-mono">Rp{{ number_format($sale->total_price) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-500 dark:text-gray-400">Tunai</span>
                    <span class="font-bold text-gray-800 dark:text-gray-200">Rp{{ number_format($sale->pembayaran) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-500 dark:text-gray-400">Kembalian</span>
                    <span class="font-bold text-gray-800 dark:text-gray-200">Rp{{ number_format($sale->kembalian) }}</span>
                </div>
            </div>

            @if(isset($sale->status) && $sale->status === 'void')
                <div class="mt-6 p-3 bg-red-50 border border-red-200 rounded-xl text-center relative z-10">
                    <p class="text-xs font-black text-red-600 uppercase tracking-widest mb-1">Struk Dibatalkan</p>
                    <p class="text-[10px] text-red-500 font-bold">{{ $sale->void_reason }}</p>
                </div>
            @endif

            <div class="text-center mt-8 pt-4 border-t-2 border-dashed border-gray-200 dark:border-slate-600 relative z-10">
                <p class="font-bold text-gray-800 dark:text-gray-200">Terima Kasih</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Semoga lekas sembuh & sehat selalu</p>
            </div>

        </div>
    </div>

    <div x-show="showVoidModal" x-cloak class="no-print fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm transition-opacity p-4">
        <div @click.away="showVoidModal = false" class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 w-full max-w-md">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-500/20 text-red-600 rounded-full flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <h2 class="text-xl font-black text-gray-800 dark:text-gray-100 mb-2">Batalkan Transaksi?</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Tindakan ini akan mengembalikan stok obat ke gudang dan memotong catatan laci kasir secara otomatis.</p>
            
            @if (session()->has('void_error'))
                <p class="text-red-500 text-sm font-bold mb-4 animate-pulse">{{ session('void_error') }}</p>
            @endif

            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Alasan Pembatalan (Wajib)</label>
            <input type="text" 
                x-ref="reasonInput"
                wire:model="voidReason"
                wire:keydown.enter="voidTransaction"
                class="w-full p-4 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-red-500 text-gray-800 dark:text-white font-bold mb-6" 
                placeholder="Contoh: Kasir salah ketik qty">

            <div class="flex gap-3">
                <button @click="showVoidModal = false" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 rounded-xl font-bold transition-all">
                    Kembali
                </button>
                <button wire:click="voidTransaction" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-black shadow-md transition-all">
                    Eksekusi Void
                </button>
            </div>
        </div>
    </div>
</div>