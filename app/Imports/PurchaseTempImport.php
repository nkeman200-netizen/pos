<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PurchaseTempImport implements ToCollection, WithStartRow
{
    public function collection(Collection $rows)
    {
        // Kita hanya butuh class ini untuk membaca baris excel menjadi Collection, 
        // eksekusi pencarian produknya akan kita lakukan di dalam Livewire Component.
        return $rows;
    }

    // Lewati baris 1 karena itu adalah Header (Judul Kolom)
    public function startRow(): int
    {
        return 2;
    }
}