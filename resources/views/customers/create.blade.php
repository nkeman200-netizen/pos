@extends('layouts.app')

@section('title','Tambah data costumer')
    
@section('content')
    
<div class="p-8">
    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ route('customers.index') }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-1 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="max-w-2xl mx-auto bg-white border border-gray-100 shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-indigo-600 p-4">
            <p class="text-indigo-100 text-sm font-medium">Lengkapi detail informasi pelanggan di bawah ini.</p>
        </div>

        <form action="{{ route('customers.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            @include('customers._form')

            <!-- Tombol Aksi -->
            <div class="pt-4 flex gap-3">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-200 transition transform active:scale-95 flex justify-center items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i> Simpan Pelanggan
                </button>
                <button type="reset" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl transition">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>
    
@endsection