<div> 
    <div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen font-sans transition-colors duration-300"
         x-data="{ 
            showModal: false, 
            showHoldModal: false,
            showRecallModal: false,
            showCloseShiftModal: false,
            kembalian: 0, 
            highlightIndex: 0,
            timeout: null,
            resetTimer() {
                clearTimeout(this.timeout);
                if ($wire.hasOpenShift && !$wire.isLocked && !this.showModal && !this.showHoldModal && !this.showRecallModal && !this.showCloseShiftModal) {
                    this.timeout = setTimeout(() => { $wire.lockCashier() }, 180000);
                }
            }
         }"
         x-init="
            $nextTick(() => { $refs.searchInput.focus() });
            resetTimer();
         "
         @mousemove.window="resetTimer()"
         @keypress.window="resetTimer()"
         @click.window="resetTimer()"
         @item-added.window="if(!showCloseShiftModal) { $refs.searchInput.focus(); highlightIndex = 0; }"
         @reset-highlight.window="highlightIndex = 0"
         @open-hold-modal.window="showHoldModal = true; setTimeout(() => $refs.holdNoteInput.focus(), 100);"
         @open-recall-modal.window="showRecallModal = true;"
         @open-close-shift-modal.window="showCloseShiftModal = true; setTimeout(() => $refs.actualCashInput.focus(), 100);"
         @close-modals.window="showHoldModal = false; showRecallModal = false; showCloseShiftModal = false; $refs.searchInput.focus();"
         @transaction-success.window="
            showModal = true;
            kembalian = $event.detail[0].kembalian;
            document.getElementById('printFrame').src = $event.detail[0].printUrl;
         "
         @keydown.f2.window.prevent="if($wire.hasOpenShift && !$wire.isLocked && !showHoldModal && !showRecallModal && !showCloseShiftModal) $refs.searchInput.focus()"
         @keydown.f4.window.prevent="if($wire.hasOpenShift && !$wire.isLocked && !showHoldModal && !showRecallModal && !showCloseShiftModal) $refs.paymentInput.focus()"
         @keydown.f6.window.prevent="if($wire.hasOpenShift && !$wire.isLocked && !showModal && !showRecallModal && !showCloseShiftModal) $wire.confirmHold()"
         @keydown.f7.window.prevent="if($wire.hasOpenShift && !$wire.isLocked && !showModal && !showHoldModal && !showCloseShiftModal) $wire.openRecall()"
         @keydown.f9.window.prevent="if($wire.hasOpenShift && !showModal && !showHoldModal && !showRecallModal && !showCloseShiftModal) $wire.lockCashier()"
         @keydown.f10.window.prevent="if($wire.hasOpenShift && !$wire.isLocked && !showModal && !showHoldModal && !showRecallModal) $dispatch('open-close-shift-modal')"
         @keydown.enter.window="if(showModal) { showModal = false; $refs.searchInput.focus(); }"
         @keydown.escape.window="
            if(showModal) { showModal = false; $refs.searchInput.focus(); }
            if(showHoldModal) { showHoldModal = false; $refs.searchInput.focus(); }
            if(showRecallModal) { showRecallModal = false; $refs.searchInput.focus(); }
            if(showCloseShiftModal) { showCloseShiftModal = false; $refs.searchInput.focus(); }
         ">
        
        <style>
            input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
            input[type=number] { -moz-appearance: textfield; }
        </style>

        <iframe id="printFrame" class="hidden"></iframe>

        @if(auth()->user()->role === 'kasir')
        <div x-show="!$wire.hasOpenShift" x-cloak
             class="fixed inset-0 z-[250] flex items-center justify-center bg-slate-900/95 backdrop-blur-md transition-opacity">
            <div class="bg-white dark:bg-slate-800 p-10 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 text-center max-w-sm w-full">
                
                @if (session()->has('shift_success'))
                    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-500/10 border-2 border-emerald-500 text-emerald-700 dark:text-emerald-400 rounded-xl shadow-sm font-bold animate-pulse">
                        {{ session('shift_success') }}
                    </div>
                @else
                    <div class="w-20 h-20 bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h.01"/><path d="M17 7h.01"/><path d="M7 12h.01"/><path d="M17 12h.01"/><path d="M7 17h.01"/><path d="M17 17h.01"/></svg>
                    </div>
                @endif
                
                <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight mb-2">Buka Shift Kasir</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Masukkan modal uang tunai (receh) di laci Anda saat ini sebelum mulai berjualan.</p>

                @if (session()->has('shift_error'))
                    <p class="text-red-500 text-sm font-bold mb-4 animate-bounce">{{ session('shift_error') }}</p>
                @endif

                <div class="relative mb-6">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-500 font-black">Rp</span>
                    </div>
                    <input type="text" 
                           wire:model="startingCash"
                           wire:keydown.enter="startShift"
                           x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                           x-effect="if(!$wire.hasOpenShift) setTimeout(() => $el.focus(), 100)"
                           class="w-full pl-12 p-4 text-right font-black text-2xl font-mono bg-gray-50 dark:bg-slate-900 border-2 border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:border-indigo-500 text-gray-800 dark:text-white" 
                           placeholder="0">
                </div>

                <button wire:click="startShift" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black shadow-md transition-all">
                    BUKA KAS (ENTER)
                </button>
            </div>
        </div>
        @endif

        <div x-show="$wire.isLocked" x-cloak
             class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/95 backdrop-blur-md transition-opacity">
            <div class="bg-white dark:bg-slate-800 p-10 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 text-center max-w-sm w-full">
                <div class="w-20 h-20 bg-red-100 dark:bg-red-500/20 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <h2 class="text-xl font-black text-gray-800 dark:text-gray-100 tracking-tight mb-1">Kasir Terkunci</h2>
                <p class="text-gray-400 dark:text-gray-500 text-sm mb-6">Masukkan PIN Anda untuk melanjutkan.</p>

                @if (session()->has('pin_error'))
                    <p class="text-red-500 text-sm font-bold mb-4 animate-bounce">{{ session('pin_error') }}</p>
                @endif

                <input type="password" 
                       autocomplete="new-password"
                       wire:model="pinInput"
                       wire:keydown.enter="unlockCashier"
                       x-effect="if($wire.isLocked) setTimeout(() => $el.focus(), 100)"
                       class="w-full p-4 text-center tracking-[1em] font-black text-3xl bg-gray-50 dark:bg-slate-900 border-2 border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:border-indigo-500 text-gray-800 dark:text-gray-100 mb-6" 
                       placeholder="••••••" maxlength="6">

                <button wire:click="unlockCashier" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black shadow-md transition-all">
                    BUKA (ENTER)
                </button>
            </div>
        </div>

        <div x-show="showModal" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm transition-opacity">
            <div @click.away="showModal = false; $refs.searchInput.focus()" class="bg-white dark:bg-slate-800 p-10 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 text-center max-w-sm w-full">
                <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                </div>
                <h2 class="text-gray-500 dark:text-gray-400 font-black tracking-widest uppercase text-sm mb-2">Transaksi Lunas</h2>
                <p class="text-gray-400 dark:text-gray-500 text-xs mb-6">Struk sedang dicetak oleh printer...</p>

                <div class="bg-gray-50 dark:bg-slate-900 rounded-2xl p-6 mb-8 border border-gray-100 dark:border-slate-700">
                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">Uang Kembalian</p>
                    <p class="text-4xl font-black text-indigo-600 dark:text-indigo-400 font-mono" x-text="'Rp' + new Intl.NumberFormat('id-ID').format(kembalian)"></p>
                </div>

                <button @click="showModal = false; $refs.searchInput.focus()" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black shadow-md transition-all">
                    TUTUP & LANJUT (ENTER)
                </button>
            </div>
        </div>

        <div x-show="showHoldModal" x-cloak
             class="fixed inset-0 z-[150] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm transition-opacity">
            <div @click.away="showHoldModal = false; $refs.searchInput.focus()" class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 w-full max-w-md">
                <h2 class="text-xl font-black text-gray-800 dark:text-gray-100 mb-2">Parkir Transaksi</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Simpan keranjang ini sementara untuk melayani pelanggan lain.</p>
                
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Referensi / Catatan</label>
                <input type="text" 
                       x-ref="holdNoteInput"
                       wire:model="holdNote"
                       wire:keydown.enter="executeHold"
                       class="w-full p-4 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-gray-800 dark:text-white font-bold mb-6" 
                       placeholder="Misal: Bapak Topi Merah">

                <div class="flex gap-3">
                    <button @click="showHoldModal = false; $refs.searchInput.focus()" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 rounded-xl font-bold transition-all">
                        Batal (ESC)
                    </button>
                    <button wire:click="executeHold" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black shadow-md transition-all">
                        SIMPAN (ENTER)
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showRecallModal" x-cloak
             class="fixed inset-0 z-[150] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm transition-opacity p-4">
            <div @click.away="showRecallModal = false; $refs.searchInput.focus()" class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 w-full max-w-3xl overflow-hidden flex flex-col max-h-[80vh]">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50/50 dark:bg-slate-800/50">
                    <div>
                        <h2 class="text-xl font-black text-gray-800 dark:text-gray-100">Panggil Transaksi (Recall)</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar keranjang yang sedang diparkir/ditahan.</p>
                    </div>
                    <button @click="showRecallModal = false; $refs.searchInput.focus()" class="p-2 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-500 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-6">
                    @if(count($heldTransactionsList) > 0)
                        <div class="space-y-4">
                            @foreach($heldTransactionsList as $held)
                                <div class="p-4 border border-gray-200 dark:border-slate-700 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:border-indigo-300 dark:hover:border-indigo-500/50 transition-colors bg-white dark:bg-slate-900/50">
                                    <div>
                                        <h3 class="font-black text-gray-800 dark:text-gray-100 text-lg">{{ $held->reference_notes }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            Waktu: {{ $held->created_at->format('H:i') }} | Item: {{ count($held->cart_data) }} Macam
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-4 w-full sm:w-auto">
                                        <div class="text-right flex-1 sm:flex-none">
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">Total</p>
                                            <p class="font-black text-indigo-600 dark:text-indigo-400 font-mono">Rp{{ number_format($held->total_price) }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <button wire:click="deleteHeld({{ $held->id }})" class="p-2.5 bg-red-50 hover:bg-red-100 dark:bg-red-500/10 dark:hover:bg-red-500/20 text-red-500 rounded-xl transition-all" title="Hapus Permanen">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            </button>
                                            <button wire:click="restoreTransaction({{ $held->id }})" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all shadow-md flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                                Panggil
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-gray-200 dark:border-slate-700 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-500 dark:text-gray-400 text-lg">Tidak ada transaksi yang ditahan.</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="showCloseShiftModal" x-cloak
             class="fixed inset-0 z-[150] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm transition-opacity">
            <div @click.away="showCloseShiftModal = false; $refs.searchInput.focus()" class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 w-full max-w-md text-center">
                <div class="w-16 h-16 bg-orange-100 dark:bg-orange-500/20 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                </div>
                <h2 class="text-xl font-black text-gray-800 dark:text-gray-100 mb-2">Tutup Kasir (Blind Drop)</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Hitung seluruh uang tunai yang ada di laci Anda saat ini, lalu masukkan totalnya di bawah.</p>
                
                @if (session()->has('close_shift_error'))
                    <p class="text-red-500 text-sm font-bold mb-4 animate-pulse">{{ session('close_shift_error') }}</p>
                @endif

                <div class="relative mb-6 text-left">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-500 font-black">Rp</span>
                    </div>
                    <input type="text" 
                           x-ref="actualCashInput"
                           wire:model="actualCash"
                           wire:keydown.enter="closeShift"
                           x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                           class="w-full pl-12 p-4 bg-gray-50 dark:bg-slate-900 border-2 border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-orange-500 text-gray-800 dark:text-white font-black text-2xl font-mono text-right" 
                           placeholder="0">
                </div>

                <div class="flex gap-3">
                    <button @click="showCloseShiftModal = false; $refs.searchInput.focus()" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 rounded-xl font-bold transition-all">
                        Batal (ESC)
                    </button>
                    <button wire:click="closeShift" class="flex-1 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-xl font-black shadow-md transition-all">
                        TUTUP SHIFT (ENTER)
                    </button>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('sales.index') }}" class="p-2.5 bg-white dark:bg-slate-800 text-gray-500 hover:text-indigo-600 rounded-xl shadow-sm border transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">Kasir (POS)</h2>
                    <div class="flex flex-wrap gap-2 text-xs font-bold text-gray-500 mt-1">
                        <span class="bg-gray-200 px-2 py-0.5 rounded text-gray-600 dark:bg-slate-700 dark:text-gray-300">F2: Cari</span>
                        <span class="bg-gray-200 px-2 py-0.5 rounded text-gray-600 dark:bg-slate-700 dark:text-gray-300">F4: Bayar</span>
                        <span class="bg-gray-200 px-2 py-0.5 rounded text-gray-600 dark:bg-slate-700 dark:text-gray-300">F6: Hold</span>
                        <span class="bg-gray-200 px-2 py-0.5 rounded text-gray-600 dark:bg-slate-700 dark:text-gray-300">F7: Recall</span>
                        <span class="bg-gray-200 px-2 py-0.5 rounded text-gray-600 dark:bg-slate-700 dark:text-gray-300">F9: Lock</span>
                        <span class="bg-orange-100 px-2 py-0.5 rounded text-orange-700 dark:bg-orange-500/20 dark:text-orange-400">F10: Tutup Kas</span>
                    </div>
                </div>
            </div>
        </div>

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-500/10 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded-r-xl shadow-sm font-bold flex items-center gap-3 animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 rounded-r-xl shadow-sm font-bold flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6">
            
            <div class="flex-1 flex flex-col gap-6">
                
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 relative z-50">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5v2"/><path d="M4 17v2"/><path d="M20 5v2"/><path d="M20 17v2"/><path d="M8 5h8"/><path d="M8 19h8"/><path d="M8 12h8"/></svg>
                        </div>
                        
                        <input type="text" 
                            x-ref="searchInput"
                            wire:model.live.debounce.300ms="searchQuery"
                            @keydown.arrow-down.prevent="highlightIndex = Math.min(highlightIndex + 1, {{ max(0, count($this->searchResults) - 1) }})"
                            @keydown.arrow-up.prevent="if(highlightIndex > 0) highlightIndex--"
                            @keydown.enter.prevent="$wire.addSelectedResult(highlightIndex)"
                            placeholder="Scan Barcode SKU atau Ketik Nama Obat..." 
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-slate-900 border-2 border-indigo-100 dark:border-slate-600 rounded-xl outline-none focus:border-indigo-500 text-gray-800 dark:text-gray-100 font-bold transition-all text-lg placeholder:text-sm shadow-inner">
                        
                        @if(!empty($searchQuery) && $this->searchResults->count() > 0)
                            <div class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-xl rounded-xl overflow-hidden max-h-60 overflow-y-auto">
                                @foreach($this->searchResults as $index => $product)
                                    <div wire:key="search-{{ $product->id }}" 
                                         wire:click="addToCart({{ $product->id }})" 
                                         :class="{
                                             'bg-indigo-100 dark:bg-indigo-500/30 border-l-4 border-indigo-600 dark:border-indigo-400': highlightIndex === {{ $index }},
                                             'hover:bg-gray-50 dark:hover:bg-slate-700/30 border-l-4 border-transparent': highlightIndex !== {{ $index }}
                                         }"
                                         class="p-3 cursor-pointer transition flex justify-between items-center border-b border-gray-50 dark:border-slate-700/50">
                                        <div>
                                            <div class="font-bold" :class="highlightIndex === {{ $index }} ? 'text-indigo-800 dark:text-indigo-300' : 'text-gray-800 dark:text-gray-100'">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Stok: <span class="font-bold {{ $product->stock < 10 ? 'text-red-500' : 'text-emerald-500' }}">{{ $product->stock }}</span> | SKU: {{ $product->sku }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-black" :class="highlightIndex === {{ $index }} ? 'text-indigo-700 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300'">Rp{{ number_format($product->selling_price) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden relative z-10 min-h-[400px]">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-xs text-gray-400 uppercase font-black tracking-wider">
                                <tr>
                                    <th class="px-5 py-4">Nama Obat</th>
                                    <th class="px-5 py-4 text-right w-32">Harga</th>
                                    <th class="px-3 py-4 text-center w-36">Qty</th>
                                    <th class="px-5 py-4 text-right w-36">Subtotal</th>
                                    <th class="px-4 py-4 w-12 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                                @forelse($cart as $key => $item)
                                <tr wire:key="cart-row-{{ $key }}" class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
                                    <td class="px-5 py-4 font-bold text-sm text-gray-800 dark:text-gray-100">{{ $item['name'] }}</td>
                                    <td class="px-5 py-4 text-right font-bold text-gray-700 dark:text-gray-300 text-sm">Rp{{ number_format($item['unit_price']) }}</td>
                                    <td class="px-3 py-4">
                                        <div class="flex items-center justify-center bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg overflow-hidden">
                                            <button wire:click="decrementQty({{ $key }})" class="px-3 py-1.5 text-gray-500 hover:bg-gray-200 font-bold">-</button>
                                            
                                            <input type="number" 
                                                wire:model.blur="cart.{{ $key }}.quantity"
                                                class="w-12 p-1.5 text-center text-sm font-bold bg-transparent outline-none dark:text-white" min="1">
                                                
                                            <button wire:click="incrementQty({{ $key }})" class="px-3 py-1.5 text-gray-500 hover:bg-gray-200 font-bold">+</button>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right font-black text-indigo-600 dark:text-indigo-400 text-sm font-mono">Rp{{ number_format($item['subtotal']) }}</td>
                                    <td class="px-4 py-4 text-center">
                                        <button wire:click="removeFromCart({{ $key }})" class="p-2 text-gray-400 hover:text-red-500 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-32 text-center text-gray-500 dark:text-gray-400 font-bold">Keranjang belanja kosong.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-96 space-y-6 relative z-10">
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pelanggan</label>
                    <select wire:model="customerId" class="w-full p-3.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-colors text-sm font-bold">
                        <option value="">-- Umum / Non Member --</option>
                        @if(isset($customers))
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 text-center">Total Tagihan</p>
                    <p class="text-4xl font-black text-indigo-600 dark:text-indigo-400 mb-6 font-mono text-center">Rp{{ number_format($this->total) }}</p>
                    
                    <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Metode Pembayaran</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button wire:click="$set('paymentMethod', 'cash')" 
                                        class="py-2.5 rounded-xl text-xs font-bold transition-all border-2 flex flex-col items-center gap-1 {{ $paymentMethod === 'cash' ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300' : 'bg-white border-gray-100 text-gray-500 hover:border-gray-200 dark:bg-slate-800 dark:border-slate-700 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
                                    Tunai
                                </button>
                                <button wire:click="$set('paymentMethod', 'qris')" 
                                        class="py-2.5 rounded-xl text-xs font-bold transition-all border-2 flex flex-col items-center gap-1 {{ $paymentMethod === 'qris' ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300' : 'bg-white border-gray-100 text-gray-500 hover:border-gray-200 dark:bg-slate-800 dark:border-slate-700 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1a2 2 0 0 1 2 2v1"/><path d="M12 21v-1"/></svg>
                                    QRIS
                                </button>
                                <button wire:click="$set('paymentMethod', 'debit')" 
                                        class="py-2.5 rounded-xl text-xs font-bold transition-all border-2 flex flex-col items-center gap-1 {{ $paymentMethod === 'debit' ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300' : 'bg-white border-gray-100 text-gray-500 hover:border-gray-200 dark:bg-slate-800 dark:border-slate-700 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                    EDC/Debit
                                </button>
                            </div>
                        </div>

                        @if($paymentMethod === 'cash')
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Uang Tunai <span class="text-indigo-500">(F4)</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-black">Rp</span>
                                    </div>
                                    <input type="text" 
                                        x-ref="paymentInput"
                                        wire:model.live.debounce.300ms="pembayaran" 
                                        wire:keydown.enter.prevent="saveTransaction"
                                        x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                        class="w-full pl-12 p-4 bg-gray-50 dark:bg-slate-900 border-2 border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 transition-colors text-right font-black text-2xl text-gray-800 dark:text-white font-mono" placeholder="0">
                                </div>
                            </div>

                            @php
                                $isLunas = $this->kembalian >= 0 && $this->total > 0;
                                $kembalianWarna = $isLunas ? 'emerald' : 'red';
                            @endphp

                            <div class="flex justify-between items-center p-4 bg-{{ $kembalianWarna }}-50 dark:bg-{{ $kembalianWarna }}-500/10 rounded-xl border border-{{ $kembalianWarna }}-200 dark:border-{{ $kembalianWarna }}-500/30">
                                <span class="font-bold text-{{ $kembalianWarna }}-700 dark:text-{{ $kembalianWarna }}-400 text-sm">
                                    {{ $isLunas ? 'Kembalian' : 'Kurang Bayar' }}
                                </span>
                                <span class="font-black text-{{ $kembalianWarna }}-700 dark:text-{{ $kembalianWarna }}-400 text-xl font-mono">
                                    Rp{{ number_format(abs($this->kembalian)) }}
                                </span>
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">No. Ref/Trace (EDC/QRIS) <span class="text-indigo-500">(F4)</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                    </div>
                                    <input type="text" 
                                        x-ref="paymentInput"
                                        wire:model="paymentReference" 
                                        wire:keydown.enter.prevent="saveTransaction"
                                        class="w-full pl-12 p-4 bg-gray-50 dark:bg-slate-900 border-2 border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 transition-colors text-left font-black text-xl text-gray-800 dark:text-white" placeholder="Contoh: 83921038">
                                </div>
                            </div>
                            
                            @php $isLunas = true; @endphp 
                            <div class="flex justify-between items-center p-4 bg-blue-50 dark:bg-blue-500/10 rounded-xl border border-blue-200 dark:border-blue-500/30">
                                <span class="font-bold text-blue-700 dark:text-blue-400 text-sm">Sistem:</span>
                                <span class="font-black text-blue-700 dark:text-blue-400 text-sm">Pembayaran Pas Otomatis</span>
                            </div>
                        @endif
                    </div>

                    <button wire:click="saveTransaction" 
                        class="mt-6 w-full py-5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-lg rounded-2xl shadow-lg transition-all flex justify-center items-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ empty($cart) || !$isLunas ? 'disabled' : '' }}>
                        PROSES BAYAR (ENTER)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>