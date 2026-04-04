@extends('layouts.app')

@section('title','Data master pelanggan')
    
@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <p class="text-gray-500">Kelola data pelanggan di sini.</p>
            <a href="{{ route('customers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition shadow-md">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Pelanggan
            </a>
        </div>

        <!-- Tabel Pelanggan -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-3 font-medium text-gray-600">Name</th>
                        <th class="px-4 py-3 font-medium text-gray-600">Phone</th>
                        <th class="px-4 py-3 font-medium text-gray-600">address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 font-mono text-xs text-indigo-600 font-bold uppercase tracking-wider">
                            {{ $customer->name }}
                        </td>
                        <td class="px-4 py-4 text-gray-800 font-medium">
                            {{ $customer->phone }}
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold">
                                {{ $customer->address }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('customers.edit',$customer) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Yakin mau hapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-400">
                            <i data-lucide="package-search" class="w-12 h-12 mx-auto mb-2 opacity-20"></i>
                            Belum ada data customer. Yuk tambah dulu!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    </div>
    
@endsection