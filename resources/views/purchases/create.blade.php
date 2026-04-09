@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('purchases.index') }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 transition">
            <i data-lucide="arrow-left" class="w-6 h-6 text-gray-600"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Input Stok Masuk Baru</h2>
    </div>

    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf
        <livewire:purchase-form />

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3 rounded-xl font-bold shadow-lg transition">
                Simpan Transaksi
            </button>
        </div>
    </form>
</div>
@endsection