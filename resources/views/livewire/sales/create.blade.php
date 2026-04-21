<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen font-sans transition-colors duration-300"
     x-data="{ 
        showHoldModal: false,
        showRecallModal: false,
        showCloseShiftModal: false, 
        showQrisModal: @entangle('showQrisModal'),
        highlightIndex: 0,
        timeout: null,
        resetTimer() {
            clearTimeout(this.timeout);
            if ($wire.hasOpenShift && !$wire.isLocked && !this.showHoldModal && !this.showRecallModal && !this.showQrisModal && !this.showCloseShiftModal) {
                this.timeout = setTimeout(() => { $wire.lockCashier() }, 180000);
            }
        },
        get isLunas() {
            if ($wire.paymentMethod !== 'cash') return true;
            // Gunakan konversi String untuk menghindari error manipulasi teks
            let payStr = String($wire.pembayaran || '0');
            let pay = parseInt(payStr.replace(/[^0-9]/g, '')) || 0;
            // Gunakan injeksi Blade langsung {{ $this->total }} alih-alih $wire.total
            return pay >= {{ $this->total }};
        }
    }"
    x-init="
        $nextTick(() => { if($refs.searchInput) $refs.searchInput.focus() });
        resetTimer();
    "
    @mousemove.window="resetTimer()"
    @keypress.window="resetTimer()"
    @click.window="resetTimer()"
    @keydown.escape.window="showHoldModal = false; showRecallModal = false; showQrisModal = false; showCloseShiftModal = false; if($refs.searchInput) $refs.searchInput.focus();"
    @item-added.window="if($refs.searchInput) $refs.searchInput.focus(); highlightIndex = 0;"
    @open-hold-modal.window="showHoldModal = true; setTimeout(() => $refs.holdNoteInput.focus(), 100);"
    @open-recall-modal.window="showRecallModal = true; setTimeout(() => { let f = document.getElementById('recall-btn-0'); if(f) f.focus(); }, 100);"
    @open-close-shift-modal.window="showCloseShiftModal = true; setTimeout(() => $refs.actualCashInput.focus(), 100);"
    @keydown.shift.enter.window.prevent="if(!showHoldModal && !showRecallModal && !showQrisModal && !showCloseShiftModal && isLunas && {{ $this->total }} > 0) $wire.saveTransaction();">
    

    @if($isLocked)
    <div class="fixed inset-0 z-[1000] bg-slate-900/95 backdrop-blur-md flex items-center justify-center p-4">
        <div class="w-full max-w-sm text-center">
            <div class="w-20 h-20 bg-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h2 class="text-2xl font-black text-white mb-2 tracking-tight">KASIR TERKUNCI</h2>
            <p class="text-slate-400 text-sm mb-8 font-medium">Sistem terkunci otomatis. Masukkan PIN Anda.</p>
            <input type="password" wire:model="pinInput" wire:keydown.enter="unlockCashier" 
                   class="w-full bg-slate-800 border-2 border-slate-700 p-5 rounded-2xl text-center text-3xl tracking-[1em] text-white focus:border-indigo-500 outline-none mb-4 shadow-inner" 
                   maxlength="6" autofocus placeholder="******">
            @if(session()->has('pin_error'))
                <p class="text-red-500 font-bold animate-pulse">{{ session('pin_error') }}</p>
            @endif
        </div>
    </div>
    @endif

    @if(!$hasOpenShift && !$isLocked)
    <div class="fixed inset-0 z-[900] bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-2xl w-full max-w-md text-center border border-slate-100 dark:border-slate-700">
            <h2 class="text-2xl font-black mb-2 text-gray-800 dark:text-white uppercase tracking-tighter">Buka Shift Baru</h2>
            <p class="text-gray-500 text-sm mb-8 font-medium">Hitung modal uang pecahan di laci kasir sekarang.</p>
            <div class="mb-6">
                <label class="block text-left text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Modal Awal Tunai</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-gray-400">Rp</span>
                    <input type="text" wire:model="startingCash" x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" 
                           @keydown.enter.prevent="$wire.openShift()"
                           class="w-full pl-12 p-4 bg-gray-50 dark:bg-slate-900 border-2 border-gray-100 dark:border-slate-700 rounded-2xl text-xl font-black text-indigo-600 outline-none focus:border-indigo-500 transition-all" autofocus>
                </div>
            </div>
            <button type="button" wire:click="openShift" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-lg shadow-indigo-500/30 transition-all">
                MULAI KERJA SEKARANG
            </button>
        </div>
    </div>
    @endif

    <a wire:navigate href="{{ route('sales.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 hover:text-indigo-600 hover:border-indigo-300 dark:hover:text-indigo-400 transition-all font-bold text-sm mb-6 z-10 relative">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali ke Riwayat
    </a>

    <div class="grid grid-cols-12 gap-8 relative z-10">
        
        <div class="col-span-12 lg:col-span-8 space-y-6">
            @if (session()->has('error'))
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl font-bold border border-red-200 shadow-sm flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-700">✖</button>
                </div>
            @endif

            <div class="flex justify-between items-center bg-white dark:bg-slate-800 p-3 px-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-bold text-gray-600 dark:text-gray-300 text-sm">Kasir Aktif</span>
                </div>
                <div class="flex gap-2">
                    <button type="button" @click="$dispatch('open-hold-modal')" class="px-4 py-2 bg-amber-50 dark:bg-amber-500/10 text-amber-600 hover:bg-amber-100 rounded-xl text-xs font-bold transition flex items-center gap-2 border border-amber-200 dark:border-amber-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/></svg>
                        F6 - Tahan Antrean
                    </button>
                    <button type="button" @click="$dispatch('open-recall-modal')" class="relative px-4 py-2 bg-blue-50 dark:bg-blue-500/10 text-blue-600 hover:bg-blue-100 rounded-xl text-xs font-bold transition flex items-center gap-2 border border-blue-200 dark:border-blue-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                        F7 - Panggil ({{ count($heldTransactionsList) }})
                        @if(count($heldTransactionsList) > 0)
                            <span class="absolute -top-1 -right-1 flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span></span>
                        @endif
                    </button>
                    <button type="button" @click="$dispatch('open-close-shift-modal')"  class="px-4 py-2 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-xl text-xs font-bold transition flex items-center gap-2 border border-red-200 dark:border-red-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                        F10 - Tutup Kasir
                    </button>
                </div>
                <div x-show="showCloseShiftModal" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4">
                    <div @click.away="showCloseShiftModal = false; $refs.searchInput.focus()" class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 w-full max-w-md text-center">
                        <div class="w-16 h-16 bg-orange-100 dark:bg-orange-500/20 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        </div>
                        <h2 class="text-xl font-black text-gray-800 dark:text-white mb-2">Tutup Kasir (Blind Drop)</h2>
                        <p class="text-sm text-gray-500 mb-6">Hitung seluruh uang fisik di laci Anda saat ini tanpa terkecuali.</p>
                        
                        @if (session()->has('close_shift_error'))
                            <p class="text-red-500 text-sm font-bold mb-4 animate-pulse">{{ session('close_shift_error') }}</p>
                        @endif

                        <div class="relative mb-6 text-left">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-gray-400">Rp</span>
                            <input type="text" x-ref="actualCashInput" wire:model="actualCash" wire:keydown.enter="closeShift"
                                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                class="w-full pl-12 p-4 bg-gray-50 dark:bg-slate-900 border-2 border-gray-200 dark:border-slate-700 rounded-xl outline-none focus:border-orange-500 text-2xl font-black text-right dark:text-white" placeholder="0">
                        </div>

                        <div class="flex gap-3">
                            <button type="button" @click="showCloseShiftModal = false" class="flex-1 py-3 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 rounded-xl font-bold">Batal (Esc)</button>
                            <button type="button" wire:click="closeShift" class="flex-1 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-xl font-black shadow-md">TUTUP SHIFT</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" x-ref="searchInput" wire:model.live.debounce.300ms="searchQuery" 
                       x-on:input="highlightIndex = 0"
                       @keydown.arrow-down.prevent="highlightIndex = Math.min(highlightIndex + 1, {{ max(0, count($this->searchResults) - 1) }})"
                       @keydown.arrow-up.prevent="highlightIndex = Math.max(highlightIndex - 1, 0)"
                       @keydown.enter.prevent="if($wire.searchQuery.length >= 2) { $wire.addHighlightedToCart(highlightIndex); }"
                       placeholder="Scan Barcode atau Cari Nama... (Arrow Bawah/Atas lalu Enter)"
                       class="w-full pl-14 p-5 bg-white dark:bg-slate-800 border-2 border-transparent focus:border-indigo-500 rounded-3xl shadow-sm text-lg font-bold dark:text-white outline-none transition-all">
                
                <div x-show="$wire.searchQuery.length >= 2" class="absolute z-50 mt-3 w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 overflow-hidden">
                    @foreach($this->searchResults as $index => $product)
                        <button type="button" wire:click="addToCart({{ $product }})" 
                                :class="{ 'bg-indigo-50 dark:bg-indigo-500/20 border-l-4 border-indigo-500': highlightIndex === {{ $index }} }"
                                class="w-full flex items-center justify-between p-5 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition border-b border-gray-50 dark:border-slate-700/50">
                            <div class="flex flex-col text-left">
                                <span class="font-black text-gray-800 dark:text-gray-100">{{ $product->name }}</span>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Stok: {{ collect($product->batches)->sum('stock') }} • {{ $product->unit->name ?? 'Unit' }}</span>
                            </div>
                            <span class="font-black text-indigo-600">Rp{{ number_format($product->selling_price) }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Produk</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Qty</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Harga</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Subtotal</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                        @forelse($cart as $index => $item)
                        <tr class="hover:bg-gray-50/30 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-5 font-bold text-gray-800 dark:text-gray-100">{{ $item['name'] }}</td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" wire:click="decrementQuantity({{ $index }})" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-red-100 dark:hover:bg-red-500/20 text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 flex items-center justify-center font-black transition-colors">-</button>
                                    <input type="number" value="{{ $item['quantity'] }}" wire:change="updateQuantity({{ $index }}, $event.target.value)" 
                                           class="w-12 text-center font-black bg-transparent outline-none dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                    <button type="button" wire:click="incrementQuantity({{ $index }})" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center justify-center font-black transition-colors">+</button>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right font-bold text-gray-500">Rp{{ number_format($item['unit_price']) }}</td>
                            <td class="px-6 py-5 text-right font-black text-gray-800 dark:text-white">Rp{{ number_format($item['subtotal']) }}</td>
                            <td class="px-6 py-5 text-right">
                                <button type="button" wire:click="removeFromCart({{ $index }})" class="text-red-400 hover:text-red-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-slate-900/50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                                </div>
                                <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Keranjang masih kosong</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 sticky top-8">
                
                <div class="mb-8">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Pelanggan</label>
                    <select wire:model="customerId" class="w-full p-4 bg-gray-50 dark:bg-slate-900 border-2 border-gray-100 dark:border-slate-700 rounded-2xl font-bold dark:text-white outline-none focus:border-indigo-500 transition-all">
                        <option value="">Umum / Tanpa Nama</option>
                        @foreach($customers as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                    </select>
                </div>

                <div class="mb-8 p-6 bg-indigo-600 rounded-3xl text-white shadow-xl shadow-indigo-500/20">
                    <p class="text-xs font-black text-indigo-200 uppercase tracking-widest mb-1 text-center">Total Pembayaran</p>
                    <h2 class="text-5xl font-black text-center font-mono tracking-tighter">Rp{{ number_format($this->total) }}</h2>
                </div>

                <div class="space-y-4 mb-8">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Metode Bayar (F8)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" wire:click="$set('paymentMethod', 'cash')" 
                                class="py-3 rounded-xl font-bold transition-all border-2 focus:ring-2 focus:ring-indigo-500 {{ $paymentMethod === 'cash' ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-indigo-500/20' : 'bg-white border-gray-100 text-gray-400 dark:bg-slate-900 dark:border-slate-700' }}">
                            💵 TUNAI
                        </button>
                        <button type="button" wire:click="$set('paymentMethod', 'qris')" 
                                class="py-3 rounded-xl font-bold transition-all border-2 focus:ring-2 focus:ring-indigo-500 {{ $paymentMethod === 'qris' ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-indigo-500/20' : 'bg-white border-gray-100 text-gray-400 dark:bg-slate-900 dark:border-slate-700' }}">
                            📱 QRIS
                        </button>
                    </div>
                </div>

                @if($paymentMethod === 'cash')
                <div class="mb-6">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Uang Dibayar (F4)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-gray-400">Rp</span>
                        <input type="text" x-ref="paymentInput" wire:model.live.debounce.300ms="pembayaran" 
                               x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                               @keydown.enter.prevent="$wire.saveTransaction()"
                               class="w-full pl-12 p-4 bg-gray-50 dark:bg-slate-900 border-2 border-gray-100 dark:border-slate-700 rounded-2xl text-2xl font-black text-indigo-600 outline-none focus:border-indigo-500 transition-all text-right">
                    </div>
                </div>
                @endif

                <div class="p-5 bg-gray-50 dark:bg-slate-900/50 rounded-2xl mb-8">
                    <div class="flex justify-between items-center text-sm font-bold {{ $this->kembalian < 0 && $paymentMethod === 'cash' ? 'text-red-500' : 'text-emerald-500' }}">
                        <span>{{ $paymentMethod === 'cash' ? ($this->kembalian < 0 ? 'Kurang' : 'Kembalian') : 'Status' }}</span>
                        <span class="text-xl font-black font-mono">
                            {{ $paymentMethod === 'cash' ? 'Rp' . number_format(abs($this->kembalian)) : 'READY' }}
                        </span>
                    </div>
                </div>

                <button type="button" wire:click="saveTransaction" x-bind:disabled="!isLunas || {{ $this->total }} <= 0"
                        :class="(isLunas && {{ $this->total }} > 0) ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/30 focus:ring-emerald-300' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-500/30 focus:ring-indigo-300'"
                        class="w-full py-5 text-white font-black text-lg rounded-2xl shadow-lg transition-all disabled:opacity-30 disabled:grayscale focus:ring-4">
                    PROSES BAYAR (SHIFT + ENTER)
                </button>
            </div>
        </div>
    </div>

    <div x-show="showQrisModal" x-cloak 
        @keydown.enter.window.prevent="if(showQrisModal) { $wire.confirmQrisPaymentAndCheckout(); }"
        class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4">
        <div x-show="showQrisModal" x-transition.scale.95 class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-2xl w-full max-w-md text-center border border-gray-100 dark:border-slate-700">
            <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2 uppercase tracking-tighter">Scan & Bayar</h3>
            <p class="text-sm text-gray-500 mb-8 font-medium">Apotek Sofya - QRIS Dinamis</p>
            
            <div class="flex justify-center p-5 bg-white rounded-3xl border-2 border-gray-50 mb-8 shadow-inner">
                @if($qrisString)
                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->margin(1)->generate($qrisString) !!}
                @endif
            </div>

            <div class="bg-indigo-50 dark:bg-indigo-500/10 p-5 rounded-2xl mb-8">
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-1">Total Belanja</p>
                <p class="text-3xl font-black text-indigo-700 dark:text-indigo-400">Rp{{ number_format($this->total) }}</p>
            </div>
            
            <div class="flex gap-3">
                <button type="button" @click="showQrisModal = false" class="flex-1 py-4 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 rounded-xl font-bold transition-all focus:ring-2 focus:ring-gray-400">
                    BATAL (ESC)
                </button>
                <button type="button" wire:click="confirmQrisPaymentAndCheckout" class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black shadow-lg transition-all focus:ring-4 focus:ring-indigo-300" autofocus>
                    SUDAH LUNAS
                </button>
            </div>
        </div>
    </div>

    <div x-show="showHoldModal" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4">
        <div x-show="showHoldModal" @click.away="showHoldModal = false" class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-100 dark:border-slate-700">
            <h3 class="text-2xl font-black mb-2 text-gray-800 dark:text-white">Tahan Transaksi</h3>
            <p class="text-sm text-gray-500 mb-6 font-medium">Kosongkan meja kasir sementara untuk melayani pelanggan lain.</p>
            <div class="mb-8">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Catatan/Identitas (Opsional)</label>
                <input type="text" wire:model="holdNote" x-ref="holdNoteInput" wire:keydown.enter="holdTransaction; showHoldModal = false" 
                       class="w-full p-4 bg-gray-50 dark:bg-slate-900 border-2 border-gray-100 dark:border-slate-700 rounded-xl outline-none focus:border-amber-500 transition-all text-lg font-bold" 
                       placeholder="Misal: Bapak Baju Merah">
            </div>
            <div class="flex gap-3">
                <button type="button" @click="showHoldModal = false" class="flex-1 py-4 bg-gray-100 dark:bg-slate-700 rounded-xl font-bold focus:ring-2 focus:ring-gray-400">Batal (Esc)</button>
                <button type="button" wire:click="holdTransaction" @click="showHoldModal = false" class="flex-1 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-black shadow-lg transition-all focus:ring-4 focus:ring-amber-300">Simpan (Enter)</button>
            </div>
        </div>
    </div>

    <div x-show="showRecallModal" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4">
        <div x-show="showRecallModal" @click.away="showRecallModal = false" class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-2xl w-full max-w-lg border border-gray-100 dark:border-slate-700 flex flex-col max-h-[85vh]">
            <h3 class="text-2xl font-black mb-2 text-gray-800 dark:text-white">Panggil Transaksi</h3>
            <p class="text-sm text-gray-500 mb-6 font-medium">Gunakan tombol TAB dan ENTER untuk memilih antrean.</p>
            <div class="overflow-y-auto flex-1 space-y-3 custom-scrollbar pr-2">
                @forelse($heldTransactionsList as $index => $held)
                <button type="button" id="recall-btn-{{ $index }}" wire:click="recallTransaction({{ $held->id }})" @click="showRecallModal = false"
                        class="w-full text-left p-4 border-2 border-gray-100 dark:border-slate-700 rounded-2xl flex justify-between items-center hover:border-blue-300 dark:hover:border-blue-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition cursor-pointer group">
                    <div>
                        <p class="font-bold text-gray-800 dark:text-white text-lg group-focus:text-blue-600">{{ $held->notes ?: 'Tanpa Catatan/Nama' }}</p>
                        <p class="text-xs text-gray-500 font-medium mt-1">
                            ⏰ {{ $held->created_at->format('H:i') }} &nbsp;•&nbsp; 📦 {{ count($held->cart_data) }} item
                        </p>
                    </div>
                    <span class="px-5 py-2.5 bg-blue-600 group-hover:bg-blue-700 group-focus:bg-blue-800 text-white rounded-xl font-bold shadow-md">
                        Pilih
                    </span>
                </button>
                @empty
                <div class="text-center py-12 border-2 border-dashed border-gray-100 dark:border-slate-700 rounded-2xl focus:outline-none" id="recall-btn-0" tabindex="0">
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Tidak ada antrean</p>
                </div>
                @endforelse
            </div>
            <button type="button" @click="showRecallModal = false" class="mt-6 w-full py-4 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-white rounded-xl font-black transition-all focus:ring-2 focus:ring-gray-400">Tutup Layar (Esc)</button>
        </div>
    </div>

    <script>
        document.addEventListener('keydown', e => {
            if (e.key === 'F2') { e.preventDefault(); if(document.querySelector('[x-ref="searchInput"]')) document.querySelector('[x-ref="searchInput"]').focus(); }
            if (e.key === 'F4') { e.preventDefault(); if (document.querySelector('[x-ref="paymentInput"]')) document.querySelector('[x-ref="paymentInput"]').focus(); }
            if (e.key === 'F6') { e.preventDefault(); window.dispatchEvent(new CustomEvent('open-hold-modal')); }
            if (e.key === 'F7') { e.preventDefault(); window.dispatchEvent(new CustomEvent('open-recall-modal')); }
            if (e.key === 'F10') { e.preventDefault(); window.dispatchEvent(new CustomEvent('open-close-shift-modal')); }
            if (e.key === 'F8') { 
                e.preventDefault(); 
                @this.set('paymentMethod', @this.paymentMethod === 'cash' ? 'qris' : 'cash'); 
            }
            
        });
    </script>
</div>