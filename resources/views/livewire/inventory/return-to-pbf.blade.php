<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300 print:bg-white print:p-0"
     x-data
     @trigger-print.window="setTimeout(() => window.print(), 300)">
    
    @push('scripts')
    <style>
        @media print {
            @page { size: A4 portrait; margin: 0cm; }
            body, html, main { background-color: #ffffff !important; margin: 0 !important; padding: 0 !important; position: static !important; }
            body * { visibility: hidden; }
            #area-print, #area-print * { visibility: visible; color: #000 !important; }
            #area-print { position: absolute; left: 0; top: 0; width: 100% !important; margin: 0 !important; }
            .no-print { display: none !important; }
            table { border-collapse: collapse !important; width: 100% !important; }
            th, td { border: 1px solid #000 !important; padding: 8px !important; }
            th { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
    @endpush

    <div class="no-print">
        <div class="mb-8">
            <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                Retur Barang (Return to Vendor)
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Kembalikan obat rusak/ED ke PBF dan cetak Tanda Terima Retur Barang (TTRB).</p>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 rounded-r-xl font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-500/10 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded-r-xl font-bold shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            
            @if(auth()->user()->role === 'admin')
            <div class="xl:col-span-5">
                <div class="bg-white dark:bg-slate-800 p-6 lg:p-8 rounded-3xl shadow-sm border border-red-100 dark:border-red-500/20">
                    <h3 class="font-black text-red-800 dark:text-red-400 mb-6 uppercase tracking-widest text-xs border-b border-gray-100 dark:border-slate-700 pb-3">Form Dokumen Retur</h3>
                    
                    <form wire:submit.prevent="save" class="space-y-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">PBF / Supplier Tujuan</label>
                            <select wire:model="supplierId" class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-red-500 dark:text-white text-sm font-bold">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('supplierId') <span class="text-xs font-bold text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Pilih Obat</label>
                            
                            @if($productId)
                                <div class="flex items-center justify-between p-2.5 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-xl">
                                    <div class="font-bold text-red-700 dark:text-red-400 text-sm">{{ $productName }}</div>
                                    <button type="button" wire:click="clearProduct" class="p-1 bg-white dark:bg-slate-800 text-gray-400 hover:text-red-500 rounded-md transition-colors shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            @else
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                    </div>
                                    <input type="text" wire:model.live.debounce.300ms="searchProduct" placeholder="Ketik nama atau SKU obat..." 
                                        class="w-full pl-10 p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-red-500 dark:text-white text-sm font-medium transition-all shadow-inner">
                                    
                                    @if(!empty($searchResults))
                                        <div class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl shadow-2xl overflow-hidden">
                                            @foreach($searchResults as $res)
                                                <div wire:click="selectProduct({{ $res->id }}, '{{ addslashes($res->name) }}')" class="p-3 hover:bg-red-50 dark:hover:bg-red-500/20 cursor-pointer border-b border-gray-50 dark:border-slate-700/50 last:border-0 transition-colors group">
                                                    <div class="font-bold text-gray-800 dark:text-gray-200 text-sm group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">{{ $res->name }}</div>
                                                    <div class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $res->sku }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif(strlen($searchProduct) >= 2)
                                        <div class="absolute z-50 w-full mt-1 p-3 text-center text-xs font-bold text-gray-400 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl shadow-lg">
                                            Obat tidak ditemukan / Stok kosong.
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            @error('productId') <span class="text-xs font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Batch / ED</label>
                                <select wire:model.live="batchId" class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-red-500 dark:text-white text-xs font-bold" {{ empty($batches) ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Batch --</option>
                                    @foreach($batches as $b)
                                        <option value="{{ $b->id }}">{{ $b->batch_number }} (Stok: {{ $b->stock }})</option>
                                    @endforeach
                                </select>
                                @error('batchId') <span class="text-xs font-bold text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Qty Diretur <span class="text-red-500">(Max: {{ $maxQty }})</span></label>
                                <input type="number" wire:model="qty" class="w-full p-2.5 bg-white dark:bg-slate-800 border border-red-200 dark:border-red-500/50 rounded-xl outline-none focus:ring-2 focus:ring-red-500 dark:text-white text-lg font-black font-mono text-red-600" min="1" max="{{ $maxQty }}" {{ !$batchId ? 'disabled' : '' }}>
                                @error('qty') <span class="text-xs font-bold text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Alasan Retur (Sesuai CDOB)</label>
                            <select wire:model="reason" class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-red-500 dark:text-white text-sm font-bold">
                                <option value="">-- Alasan BPOM --</option>
                                <option value="Kedaluwarsa (ED)">Kedaluwarsa (ED)</option>
                                <option value="Hampir Kedaluwarsa (< 3 Bulan)">Hampir Kedaluwarsa (< 3 Bulan)</option>
                                <option value="Rusak / Cacat Fisik">Rusak / Cacat Fisik</option>
                                <option value="Recall Pabrik / BPOM">Recall Pabrik / BPOM</option>
                                <option value="Lainnya">Lainnya...</option>
                            </select>
                            @error('reason') <span class="text-xs font-bold text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 mt-4 bg-red-600 hover:bg-red-700 text-white font-black text-sm uppercase tracking-wider rounded-xl shadow-lg transition-all flex justify-center items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                            Proses & Cetak TTRB
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <div class="{{ (auth()->user()->role === 'admin') ?  'xl:col-span-7' : 'xl:col-span-12'}}">
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden h-full flex flex-col">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50">
                        <h3 class="font-black text-gray-800 dark:text-gray-100 uppercase tracking-widest text-xs">Riwayat Retur Terakhir</h3>
                    </div>
                    
                    <div class="flex-1 overflow-x-auto p-2">
                        <table class="w-full text-left border-collapse">
                            <thead class="text-[10px] text-gray-400 uppercase tracking-widest font-black border-b border-gray-100 dark:border-slate-700/50">
                                <tr>
                                    <th class="px-4 py-3">No. Retur</th>
                                    <th class="px-4 py-3">Supplier</th>
                                    <th class="px-4 py-3">Nilai (Rp)</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-slate-700/30">
                                @forelse($recentReturns as $ret)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/20">
                                    <td class="px-4 py-3">
                                        <div class="text-xs font-bold text-red-600 dark:text-red-400 font-mono">{{ $ret->return_number }}</div>
                                        <div class="text-[10px] text-gray-500 font-bold mt-0.5">{{ $ret->return_date }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-800 dark:text-gray-200">{{ $ret->supplier->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm font-black text-gray-700 dark:text-gray-300 font-mono">{{ number_format($ret->total_return_value) }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <button wire:click="setPrint({{ $ret->id }})" class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition" title="Cetak Ulang TTRB">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-sm font-bold text-gray-400">Belum ada data retur.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($printData)
    <div id="area-print" class="hidden print:block font-sans text-black">
        
        @php $apotek = \App\Models\PharmacyProfile::first(); @endphp

        <div style="text-align: center; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 20px;">
            @php $apotek = \App\Models\PharmacyProfile::first(); @endphp
            
            @if($apotek && $apotek->logo)
                <img src="{{ asset('storage/' . $apotek->logo) }}" alt="Logo Apotek" style="max-height: 70px; margin-bottom: 10px;">
            @else
                <h1 style="margin: 0; font-size: 24px; font-weight: 900; text-transform: uppercase;">{{ $apotek->name ?? 'APOTEK SOFYA FARMA' }}</h1>
            @endif
            
            <p style="margin: 5px 0 0 0; font-size: 12px;">
                SIPA: {{ $apotek->sipa_number ?? '-' }} 
                @if($apotek->phone) | Telp/WA: {{ $apotek->phone }} @endif
            </p>
            <p style="margin: 0; font-size: 12px;">{{ $apotek->address ?? 'Alamat Apotek' }}</p>
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="margin: 0; font-size: 18px; font-weight: bold; text-decoration: underline;">TANDA TERIMA RETUR BARANG (TTRB)</h2>
            <p style="margin: 5px 0 0 0; font-size: 12px;">No. Dokumen: <b>{{ $printData->return_number }}</b></p>
        </div>

        <table style="width: 100%; margin-bottom: 20px; border: none !important;">
            <tr>
                <td style="border: none !important; width: 15%; padding: 2px !important; font-size: 12px;"><b>Kepada (PBF)</b></td>
                <td style="border: none !important; width: 35%; padding: 2px !important; font-size: 12px;">: {{ $printData->supplier->name ?? '-' }}</td>
                <td style="border: none !important; width: 15%; padding: 2px !important; font-size: 12px;"><b>Tanggal Retur</b></td>
                <td style="border: none !important; width: 35%; padding: 2px !important; font-size: 12px;">: {{ date('d F Y', strtotime($printData->return_date)) }}</td>
            </tr>
            <tr>
                <td style="border: none !important; padding: 2px !important; font-size: 12px;"><b>Admin / APJ</b></td>
                <td style="border: none !important; padding: 2px !important; font-size: 12px;">: {{ $printData->user->name ?? '-' }}</td>
                <td style="border: none !important; padding: 2px !important; font-size: 12px;"><b>Status</b></td>
                <td style="border: none !important; padding: 2px !important; font-size: 12px;">: Menunggu Penjemputan / Ganti Barang</td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 12px;">
            <thead>
                <tr>
                    <th style="padding: 8px; border: 1px solid black; background-color: #f0f0f0;">No.</th>
                    <th style="padding: 8px; border: 1px solid black; background-color: #f0f0f0;">Nama Produk (Obat)</th>
                    <th style="padding: 8px; border: 1px solid black; background-color: #f0f0f0;">No. Batch</th>
                    <th style="padding: 8px; border: 1px solid black; background-color: #f0f0f0;">Qty</th>
                    <th style="padding: 8px; border: 1px solid black; background-color: #f0f0f0;">Alasan Retur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($printData->items as $index => $item)
                <tr>
                    <td style="padding: 8px; border: 1px solid black; text-align: center;">{{ $index + 1 }}</td>
                    <td style="padding: 8px; border: 1px solid black;">{{ $item->product->name ?? '-' }}</td>
                    <td style="padding: 8px; border: 1px solid black; text-align: center;">{{ $item->batch->batch_number ?? '-' }}</td>
                    <td style="padding: 8px; border: 1px solid black; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                    <td style="padding: 8px; border: 1px solid black;">{{ $item->reason }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p style="font-size: 11px; font-style: italic; margin-bottom: 40px;">* Dokumen ini sah sebagai bukti serah terima barang retur dari Apotek kepada perwakilan PBF (Sales/Kurir) sesuai dengan ketentuan CDOB BPOM.</p>

        <table style="width: 100%; border: none !important; text-align: center; font-size: 12px;">
            <tr>
                <td style="border: none !important; width: 33%;">
                    Yang Menyerahkan,<br>Apoteker Penanggung Jawab (APJ)<br><br><br><br><br>
                    ( <b>{{ $apotek->apoteker_name ?? '.....................................' }}</b> )<br>
                    SIPA: {{ $apotek->sipa_number ?? '.................................' }}
                </td>
                <td style="border: none !important; width: 33%;"></td>
                <td style="border: none !important; width: 33%;">
                    Yang Menerima,<br>Perwakilan PBF / Sales<br><br><br><br><br>
                    (...................................................)<br>
                    Nama Jelas & TTD
                </td>
            </tr>
        </table>

    </div>
    @endif

</div>