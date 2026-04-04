{{-- Input stok --}}
<div>
    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama pelanggan</label>
    <input type="text" 
        name="name" 
        id="name" 
        value="{{ old('name',$customer->name) }}"
        placeholder="Masukkan nama pelanggan"
        class="w-full px-4 py-3 rounded-xl border @error('name') border-red-500 @else border-gray-200 @enderror focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
    @error('name')
        <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
            <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
        </p>
    @enderror
</div>

    <div class="">
        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">No. telephon</label>
        <input type="text" 
            name="phone" 
            id="phone" 
            value="{{ old('phone',$customer->phone) }}"
            placeholder="Masukkan no telephon"
            class="w-full px-4 py-3 rounded-xl border @error('phone') border-red-500 @else border-gray-200 @enderror focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
        @error('phone')
            <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
            </p>
        @enderror
    </div>

    <div class="">
        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">address</label>
        <textarea name="address"
            id="address"
            placeholder="Masukkan alamat pelanggan"
            class="w-full px-4 py-3 rounded-xl border @error('address') border-red-500 @else border-gray-200 @enderror focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
        > {{ old('address',$customer->address) }} </textarea>
        @error('address')
            <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
            </p>
        @enderror
    </div>
