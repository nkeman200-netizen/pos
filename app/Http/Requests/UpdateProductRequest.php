<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * "Magic Method" untuk membersihkan data sebelum divalidasi
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'purchase_price' => $this->cleanCurrency($this->purchase_price),
            'selling_price'  => $this->cleanCurrency($this->selling_price),
            'stock'          => (int) $this->stock, // Pastikan stok jadi integer
        ]);
    }

    /**
     * Fungsi pembantu untuk buang "Rp" dan titik
     */
    private function cleanCurrency($value)
    {
        if (!$value) return 0;
        // Hapus semua karakter kecuali angka (seperti Rp, spasi, dan titik)
        return (int) preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Ambil ID produk yang sedang diedit dari route
        $productId = $this->route('product')->id; // sama dengan cara ambil data dari url/path

        return [
            // 2. Gunakan parameter ketiga di rule unique
            // Format: unique:nama_tabel,nama_kolom,id_yang_diabaikan
            'sku' => 'required|unique:products,sku,'. $productId, //parameter ke3 untuk pengecualian
            'name' => 'required|min:3',
            'stock' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric|gt:purchase_price', // Harga jual harus > harga beli
        ];
    }
}
