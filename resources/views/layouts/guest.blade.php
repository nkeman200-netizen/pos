<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'POS Apotek') }} - Login</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 dark:bg-slate-900 selection:bg-indigo-500 selection:text-white">
        
        <div class="fixed inset-0 z-[-1] bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] dark:bg-[radial-gradient(#334155_1px,transparent_1px)] [background-size:16px_16px] opacity-50"></div>
        
        <div class="min-h-screen flex flex-col justify-center items-center py-10 px-4">
            <div class="w-full sm:max-w-md p-8 sm:p-10 bg-white dark:bg-slate-800 shadow-2xl overflow-hidden rounded-[2.5rem] border border-gray-100 dark:border-slate-700 relative">
                
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-amber-500"></div>
                
                {{ $slot }}
            </div>
            
            <p class="mt-8 text-[10px] font-black text-gray-400 dark:text-slate-500 tracking-[0.2em] uppercase text-center">
                &copy; {{ date('Y') }} {{ config('app.name', 'Sistem Manajemen Apotek') }}
            </p>
        </div>
    </body>
</html>