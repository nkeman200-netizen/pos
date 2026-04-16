<!DOCTYPE html>
<html lang="en" 
        x-data="{ isDark: localStorage.getItem('theme') === 'dark', sidebarCollapsed: false }" 
        x-init="$watch('isDark', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" 
        :class="{ 'dark': isDark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Warung - Sofya Project</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class' // Aktifkan manual dark mode via class
        }
    </script>
    @livewireStyles
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-200 antialiased transition-colors duration-300">

    <div class="flex min-h-screen">
        
        <aside 
            :class="sidebarCollapsed ? 'w-20' : 'w-64'" 
            class="bg-white dark:bg-slate-900 border-r border-gray-100 dark:border-slate-800 flex flex-col transition-[width] duration-300 ease-in-out fixed inset-y-0 left-0 z-50">
            
            <div class="p-6 flex items-center justify-between">
                <div x-show="!sidebarCollapsed" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shrink-0 shadow-md">
                        <span class="text-white font-black text-xl">S</span>
                    </div>
                    <span class="font-black text-gray-800 dark:text-white tracking-tighter text-xl whitespace-nowrap">POS SOFYA</span>
                </div>
                
                <div x-cloak x-show="sidebarCollapsed" class="w-full flex justify-center">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                        <span class="text-white font-black text-xl">S</span>
                    </div>
                </div>
            </div>

            <button 
                @click="sidebarCollapsed = !sidebarCollapsed" 
                class="absolute -right-3 top-10 bg-white dark:bg-slate-800 border-2 border-gray-50 dark:border-slate-700 rounded-full p-1.5 shadow-md text-gray-400 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-transform z-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" :class="sidebarCollapsed ? 'rotate-180' : ''" class="transition-transform duration-300"><path d="m15 18-6-6 6-6"/></svg>
            </button>

            <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto custom-scrollbar">
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <div class="shrink-0 flex justify-center" :class="sidebarCollapsed ? 'w-full' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="15" rx="1"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" class="font-bold text-sm whitespace-nowrap">Dashboard</span>
                </a>

                <p x-show="!sidebarCollapsed" class="px-3 pt-4 pb-2 text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest whitespace-nowrap">Master Data</p>

                <a href="{{ route('products.index') }}" class="flex items-center gap-4 p-3 rounded-xl transition-all {{ request()->routeIs('products.*') ? 'bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <div class="shrink-0 flex justify-center" :class="sidebarCollapsed ? 'w-full' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" class="font-bold text-sm whitespace-nowrap">Data Obat</span>
                </a>

                <a href="{{ route('suppliers.index') }}" class="flex items-center gap-4 p-3 rounded-xl transition-all {{ request()->routeIs('suppliers.*') ? 'bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <div class="shrink-0 flex justify-center" :class="sidebarCollapsed ? 'w-full' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17h4V5H2v12h3"/><path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5v8h2"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" class="font-bold text-sm whitespace-nowrap">Supplier</span>
                </a>

                <p x-show="!sidebarCollapsed" class="px-3 pt-4 pb-2 text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest whitespace-nowrap">Transaksi</p>

                <a href="{{ route('sales.create') }}" class="flex items-center gap-4 p-3 rounded-xl transition-all {{ request()->routeIs('sales.*') ? 'bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <div class="shrink-0 flex justify-center" :class="sidebarCollapsed ? 'w-full' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" class="font-bold text-sm whitespace-nowrap">Kasir (Jual)</span>
                </a>

                <a href="{{ route('purchases.index') }}" class="flex items-center gap-4 p-3 rounded-xl transition-all {{ request()->routeIs('purchases.*') ? 'bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <div class="shrink-0 flex justify-center" :class="sidebarCollapsed ? 'w-full' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 16h6"/><path d="M19 13v6"/><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/><path d="m7.5 4.27 9 5.15"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" class="font-bold text-sm whitespace-nowrap">Stok Masuk</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100 dark:border-slate-800 mt-auto">
                <button @click="isDark = !isDark" class="w-full flex items-center justify-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-300 transition-colors">
                    <template x-if="!isDark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                    </template>
                    <template x-if="isDark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                    </template>
                    <span x-show="!sidebarCollapsed" class="font-bold text-sm" x-text="isDark ? 'Light Mode' : 'Dark Mode'"></span>
                </button>
            </div>
        </aside>

        <main :class="sidebarCollapsed ? 'ml-20' : 'ml-64'" class="flex-1 transition-[margin] duration-300 ease-in-out p-6 lg:p-8 relative z-10 w-full overflow-x-hidden">
            {{ $slot }}
        </main>

    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>