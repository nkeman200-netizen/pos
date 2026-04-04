<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Warung - Sofya Project</title>
    <!-- Kita pake CDN Tailwind biar cepet, pas lomba nanti bisa pake Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">
        <!-- Sidebar Sederhana -->
        <aside class="w-64 bg-indigo-700 text-white p-6 hidden md:block">
            <h1 class="text-2xl font-bold mb-10 flex items-center gap-2">
                <i data-lucide="shopping-cart"></i> POS App
            </h1>
            <nav class="space-y-4">
                <a href="#" class="flex items-center gap-2 p-2 {{ request()->Is('dashboard')?"bg-indigo-800" : "hover:bg-indigo-600" }} rounded transition">
                    <i data-lucide="layout-dashboard"></i> Dashboard
                </a>
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 p-2 {{ request()->routeIs('products.*')?"bg-indigo-800" : "hover:bg-indigo-600" }} rounded transition">
                    <i data-lucide="package"></i> Produk
                </a>
                <a href="{{ route('customers.index') }}" class="flex items-center gap-2 p-2 {{ request()->routeIs('customers.*')?"bg-indigo-800" : "hover:bg-indigo-600" }} rounded transition">
                    <i data-lucide="users"></i> Pelanggan
                </a>
                <a href="{{ route('sales.index') }}" class="flex items-center gap-2 p-2 {{ request()->routeIs('sales.*')?"bg-indigo-800" : "hover:bg-indigo-600" }} rounded transition">
                    <i data-lucide="badge-dollar-sign"></i> Sales
                </a>
            </nav>
        </aside>

        <!-- Konten Utama -->
        <main class="flex-1 p-8">
            <!-- Header Halaman -->
            <header class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-semibold text-gray-700">@yield('title')</h2>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500 italic">Halo, Admin Sofya!</span>
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold border-2 border-indigo-200">
                        S
                    </div>
                </div>
            </header>

            <!-- Alert Sukses -->
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
            @endif

            <!-- Isi Konten dari Halaman Lain -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Inisialisasi icon Lucide
        lucide.createIcons();
    </script>
    
    <!-- SLOT KHUSUS: Untuk script tambahan dari tiap halaman -->
    @stack('scripts')
    @livewireScripts
</body>
</html>