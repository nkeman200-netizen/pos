@extends('layouts.app') @section('content')
<div class="container">
    <h2>Input Barang Masuk (Purchase Order)</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 20px;">
            <label>Pilih Supplier:</label>
            <select name="supplier_id" required>
                <option value="">-- Pilih Supplier --</option>
                @foreach($supplier as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>

            <label>Nomor Nota:</label>
            <input type="text" name="purchase_number" required>

            <label>Tanggal Masuk:</label>
            <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" required>
        </div>

        <hr>

        <h3>Daftar Obat/Barang</h3>
        <table border="1" width="100%" style="margin-bottom: 10px;">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah (Qty)</th>
                    <th>Harga Beli Satuan (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="item-list">
                <tr id="row-0">
                    <td>
                        <select name="items[0][product_id]" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($product as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[0][quantity]" min="1" required>
                    </td>
                    <td>
                        <input type="number" name="items[0][cost_price]" min="0" required>
                    </td>
                    <td>
                        -
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" onclick="addRow()">+ Tambah Barang Lagi</button>

        <br><br>
        <button type="submit" style="background-color: green; color: white;">Simpan Transaksi</button>
    </form>
</div>

<script>
    // Variabel untuk melacak nomor urut index array
    let itemIndex = 1; 

    // Simpan daftar produk dalam bentuk string HTML supaya gampang di-copy ke baris baru
    const productOptions = `
        <option value="">-- Pilih Produk --</option>
        @foreach($product as $p)
            <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach
    `;

    // Fungsi nambah baris
    function addRow() {
        const tbody = document.getElementById('item-list');
        const tr = document.createElement('tr');
        tr.id = `row-${itemIndex}`;

        tr.innerHTML = `
            <td>
                <select name="items[${itemIndex}][product_id]" required>
                    ${productOptions}
                </select>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][quantity]" min="1" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][cost_price]" min="0" required>
            </td>
            <td>
                <button type="button" onclick="removeRow(${itemIndex})" style="color: red;">Hapus</button>
            </td>
        `;

        tbody.appendChild(tr);
        itemIndex++; // Naikkan index untuk baris selanjutnya
    }

    // Fungsi hapus baris
    function removeRow(index) {
        const row = document.getElementById(`row-${index}`);
        row.remove();
    }
</script>
@endsection