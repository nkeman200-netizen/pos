<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; 
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    #[Layout('layouts.app')]
    #[Title('Data Obat Baru')]

    public $category_id = '';
    public $unit_id = '';
    public $sku = '';
    public $name = '';
    public $selling_price = '';
    public $csvFile; 

    public function getSellingMurniProperty() 
    {
        return (int) preg_replace('/[^0-9]/', '', (string)$this->selling_price);
    }

    public function save()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $this->validate([
            'category_id' => 'required',
            'unit_id' => 'required',
            'sku' => 'required|unique:products,sku',
            'name' => 'required|min:3',
        ]);

        Product::create([
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'sku' => $this->sku,
            'name' => $this->name,
            'selling_price' => $this->sellingMurni,
            'is_active' => true
        ]);

        session()->flash('success', 'Master Obat berhasil ditambahkan! Silakan catat stok masuk di menu Purchases.');
        return redirect()->route('products.index');
    }

    public function resetForm()
    {
        $this->reset(['category_id', 'unit_id', 'sku', 'name', 'selling_price', 'csvFile']);
    }

    public function downloadTemplate()
    {
        $csvData = "SKU/Barcode,Nama Obat,Harga Jual,ID Golongan,ID Satuan\n";
        $csvData .= "APTK-001,Paracetamol 500mg,5000,1,1\n";
        $csvData .= "APTK-002,Amoxicillin 500mg,12000,2,1\n";

        return response()->streamDownload(function () use ($csvData) {
            echo $csvData;
        }, 'Template_Import_Obat.csv');
    }

    public function importCsv()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        try {
            $file = fopen($this->csvFile->getRealPath(), 'r');
            $header = fgetcsv($file); 
            
            $importedCount = 0;
            DB::beginTransaction();
            
            while (($row = fgetcsv($file)) !== false) {
                if (count($row) >= 5) {
                    $sku = trim($row[0]);
                    $name = trim($row[1]);
                    $price = preg_replace('/[^0-9]/', '', $row[2]); 
                    $catId = (int) trim($row[3]);
                    $unitId = (int) trim($row[4]);
                    
                    if (!empty($sku) && !empty($name)) {
                        Product::updateOrCreate(
                            ['sku' => $sku],
                            [
                                'name' => $name,
                                'selling_price' => $price ?: 0,
                                'category_id' => $catId ?: null, 
                                'unit_id' => $unitId ?: null,    
                                'is_active' => true,
                            ]
                        );
                        $importedCount++;
                    }
                }
            }
            
            fclose($file);
            DB::commit();
            
            session()->flash('success', "$importedCount data obat berhasil diimport massal ke dalam sistem!");
            return redirect()->route('products.index');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal import! Detail: ' . $e->getMessage());
        }
    }

    public function syncFromCloud()
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        try {
            $response = Http::timeout(60)->get('http://obat-api-center.test/api/v1/medicines');
            
            if ($response->successful()) {
                $medicines = $response->json(); 
                
                if (!is_array($medicines)) {
                    session()->flash('error', 'Format JSON dari server tidak valid.');
                    return;
                }

                $existingCategories = Category::pluck('id', 'name')->toArray();
                $existingUnits = Unit::pluck('id', 'name')->toArray();

                $productsToInsert = [];
                $now = now();

                DB::beginTransaction();

                foreach ($medicines as $med) {
                    if (!isset($existingCategories[$med['category']])) {
                        $newCat = Category::create(['name' => $med['category']]);
                        $existingCategories[$med['category']] = $newCat->id;
                    }

                    if (!isset($existingUnits[$med['unit']])) {
                        $newUnit = Unit::create([
                            'name' => $med['unit'],
                            'short_name' => substr($med['unit'], 0, 3)
                        ]);
                        $existingUnits[$med['unit']] = $newUnit->id;
                    }

                    $productsToInsert[] = [
                        'sku' => $med['sku'],
                        'name' => $med['name'],
                        'selling_price' => $med['selling_price'],
                        'category_id' => $existingCategories[$med['category']],
                        'unit_id' => $existingUnits[$med['unit']],
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                foreach (array_chunk($productsToInsert, 1000) as $chunk) {
                    Product::upsert(
                        $chunk,
                        ['sku'], 
                        ['name', 'selling_price', 'category_id', 'unit_id', 'is_active', 'updated_at'] 
                    );
                }

                DB::commit();
                
                $total = count($productsToInsert);
                session()->flash('success', "Sukses Brutal! $total data Master Obat berhasil ditarik dengan teknik Bulk Insert dalam hitungan detik.");
                return redirect()->route('products.index');

            } else {
                session()->flash('error', 'Gagal menarik data. Status: ' . $response->status());
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Proses terhenti. Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.products.create', [
            'categories' => Category::all(),
            'units' => Unit::all()
        ]);
    }
}