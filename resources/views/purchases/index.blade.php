@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-2xl shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Stok Masuk (Purchases)</h2>
        <a href="{{ route('purchases.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold transition">
            + Tambah Stok Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                    <th class="px-4 py-4 border-b">No. Faktur</th>
                    <th class="px-4 py-4 border-b">Supplier</th>
                    <th class="px-4 py-4 border-b text-center">Tanggal</th>
                    <th class="px-4 py-4 border-b text-right">Total Biaya</th>
                    <th class="px-4 py-4 border-b text-center">Admin</th>
                    <th class="px-4 py-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @foreach($purchases as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-4 font-medium text-indigo-600">{{ $item->purchase_number }}</td>
                    <td class="px-4 py-4">{{ $item->supplier->name }}</td>
                    <td class="px-4 py-4 text-center text-sm text-gray-500">{{ $item->purchase_date }}</td>
                    <td class="px-4 py-4 text-right font-bold text-gray-900">Rp{{ number_format($item->total_cost) }}</td>
                    <td class="px-4 py-4 text-center">
                        <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full text-xs font-semibold uppercase">
                            {{ $item->user->name }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('purchases.show', $item->id) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition" title="Detail">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                            <form action="{{ route('purchases.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini? Stok akan dikurangi kembali.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $purchases->links() }}
    </div>
</div>
@endsection