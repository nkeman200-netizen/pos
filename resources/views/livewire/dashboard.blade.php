<div class="p-6 lg:p-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    
    <div class="relative z-30 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600 dark:text-indigo-400"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="15" rx="1"/></svg>
                Dashboard Eksekutif
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Pusat pantauan operasional apotek hari ini.</p>
        </div>
        <div class="bg-white dark:bg-slate-800 px-4 py-2 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center gap-3">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</span>
        </div>
    </div>

    <div class="relative z-20 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center gap-4 relative overflow-hidden group hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-colors">
            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Omzet Hari Ini</p>
                <p class="text-xl font-black text-gray-800 dark:text-gray-100 font-mono">Rp{{ number_format($omzetHariIni) }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center gap-4 relative overflow-hidden group hover:border-emerald-200 dark:hover:border-emerald-500/30 transition-colors">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Est. Profit Hari Ini</p>
                <p class="text-xl font-black text-gray-800 dark:text-gray-100 font-mono">Rp{{ number_format($profitHariIni) }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center gap-4 relative overflow-hidden group hover:border-orange-200 dark:hover:border-orange-500/30 transition-colors">
            <div class="w-12 h-12 bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Transaksi Hari Ini</p>
                <p class="text-xl font-black text-gray-800 dark:text-gray-100 font-mono">{{ $transaksiHariIni }} <span class="text-sm font-medium text-gray-400">Struk</span></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center gap-4 relative overflow-hidden group hover:border-blue-200 dark:hover:border-blue-500/30 transition-colors">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Total Nilai Aset</p>
                <p class="text-lg xl:text-xl truncate font-black text-gray-800 dark:text-gray-100 font-mono" title="Rp{{ number_format($totalAset) }}">Rp{{ number_format($totalAset) }}</p>
            </div>
        </div>

    </div>

    <div class="relative z-10 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm mb-8 overflow-hidden w-full">
        <h3 class="text-sm font-black text-gray-800 dark:text-gray-100 uppercase tracking-widest mb-4">Tren Omzet 7 Hari Terakhir</h3>
        
        <div id="revenueChart" class="w-full h-[300px]" 
                x-data="chartData()" 
                x-init="setTimeout(() => renderChart('revenueChart'), 100)">
        </div>
    </div>

    <div class="relative z-20 grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-red-100 dark:border-red-500/20 shadow-sm overflow-hidden relative">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            
            <div class="p-5 bg-red-50 dark:bg-red-500/10 border-b border-red-100 dark:border-red-500/20 flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 rounded-lg flex items-center justify-center animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="font-black text-red-800 dark:text-red-400">Peringatan Kadaluarsa!</h3>
                    <p class="text-xs font-bold text-red-600 dark:text-red-500/80 mt-0.5">Obat dengan ED < 3 Bulan (Batas Retur)</p>
                </div>
            </div>

            <div class="p-4">
                @if($obatHampirExpired->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-sm font-bold text-gray-400 dark:text-gray-500">Kondisi aman. Tidak ada obat hampir expired.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($obatHampirExpired as $batch)
                            @php
                                // Ratakan ke awal hari (00:00:00) agar hitungannya bulat
                                $now = \Carbon\Carbon::now()->startOfDay();
                                $ed = \Carbon\Carbon::parse($batch->expired_date)->startOfDay();
                                
                                $sisaHari = $now->diffInDays($ed, false); // false agar sisa hari bisa minus
                                $isBasi = $sisaHari < 0;

                                // Logika dinamis: Jika < 30 hari tampilkan Hari, jika lebih tampilkan Bulan
                                if ($isBasi) {
                                    $teksSisa = 'KADALUARSA!';
                                } elseif ($sisaHari < 30) {
                                    $teksSisa = 'Sisa ' . $sisaHari . ' Hari';
                                } else {
                                    $teksSisa = 'Sisa ' . floor($sisaHari / 30) . ' Bulan';
                                }
                            @endphp
                            
                            <div class="flex justify-between items-center p-3 rounded-xl border {{ $isBasi ? 'border-red-500 bg-red-50 dark:bg-red-500/10' : 'border-orange-200 bg-orange-50 dark:bg-orange-500/10' }}">
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $batch->product->name }}</p>
                                    <p class="text-[10px] font-bold text-gray-500 mt-0.5">Batch: {{ $batch->batch_number }} • Stok: {{ $batch->stock }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-2 py-1 text-[10px] font-black rounded-md {{ $isBasi ? 'bg-red-600 text-white' : 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400' }}">
                                        {{ $teksSisa }}
                                    </span>
                                    <p class="text-[10px] font-bold text-gray-600 dark:text-gray-400 mt-1">ED: {{ date('d M Y', strtotime($batch->expired_date)) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-amber-100 dark:border-amber-500/20 shadow-sm overflow-hidden relative">
            
            <div class="p-5 bg-amber-50 dark:bg-amber-500/10 border-b border-amber-100 dark:border-amber-500/20 flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>
                <div>
                    <h3 class="font-black text-amber-800 dark:text-amber-400">Buku Defecta (Stok Kritis)</h3>
                    <p class="text-xs font-bold text-amber-600 dark:text-amber-500/80 mt-0.5">Segera lakukan PO (Stok < 10 Pcs)</p>
                </div>
            </div>

            <div class="p-4">
                @if($obatKritis->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-sm font-bold text-gray-400 dark:text-gray-500">Kondisi aman. Seluruh stok mencukupi.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($obatKritis as $obat)
                            <div class="flex justify-between items-center p-3 rounded-xl border border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $obat->name }}</p>
                                    <p class="text-[10px] font-bold text-gray-500 mt-0.5">{{ $obat->category->name }}</p>
                                </div>
                                <div class="text-right flex items-center gap-3">
                                    <div class="text-right">
                                        <p class="text-sm font-black {{ $obat->total_stock <= 0 ? 'text-red-500' : 'text-amber-500' }}">{{ $obat->total_stock ?? 0 }}</p>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase">{{ $obat->unit->short_name ?? 'Pcs' }}</p>
                                    </div>
                                    <a href="{{ route('purchase-orders.create') }}" class="p-2 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 rounded-lg transition" title="Buat PO Baru">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chartData', () => ({
                categories: {!! $chartCategories !!},
                seriesData: {!! $chartData !!},
                
                renderChart(elementId) {
                    const isDark = document.documentElement.classList.contains('dark');
                    const textColor = isDark ? '#94a3b8' : '#64748b'; 
                    const gridColor = isDark ? '#334155' : '#f1f5f9';

                    var options = {
                        series: [{
                            name: 'Omzet',
                            data: this.seriesData
                        }],
                        chart: {
                            type: 'area',
                            width: '100%',
                            height: 300,
                            toolbar: { show: false },
                            fontFamily: 'inherit',
                            background: 'transparent'
                        },
                        colors: ['#4f46e5'],
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.4,
                                opacityTo: 0.05,
                                stops: [0, 90, 100]
                            }
                        },
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 3 },
                        xaxis: {
                            categories: this.categories,
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            labels: { style: { colors: textColor, fontWeight: 600 } }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: textColor, fontWeight: 600 },
                                formatter: function (value) {
                                    return "Rp " + (value/1000) + "k";
                                }
                            }
                        },
                        grid: {
                            borderColor: gridColor,
                            strokeDashArray: 4,
                            yaxis: { lines: { show: true } }
                        },
                        theme: {
                            mode: isDark ? 'dark' : 'light'
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#" + elementId), options);
                    chart.render();

                    window.addEventListener('theme-changed', (e) => {
                        const newIsDark = e.detail;
                        chart.updateOptions({
                            theme: { mode: newIsDark ? 'dark' : 'light' },
                            xaxis: { labels: { style: { colors: newIsDark ? '#94a3b8' : '#64748b' } } },
                            yaxis: { labels: { style: { colors: newIsDark ? '#94a3b8' : '#64748b' } } },
                            grid: { borderColor: newIsDark ? '#334155' : '#f1f5f9' }
                        });
                    });
                }
            }));
        });
    </script>
    @endpush

</div>