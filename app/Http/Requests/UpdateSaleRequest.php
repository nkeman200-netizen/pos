<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id', // Pastikan customer-nya ada di database
            'pembayaran' => 'required|numeric|min:0',
            'items' => 'required|array|min:1', // Minimal beli 1 barang
            'items.*.product_id' => 'required|exists:products,id', // Pastikan product_id valid
            'items.*.quantity' => 'required|integer|min:1', // Jumlah barang minimal 1
        ];
    }
}
