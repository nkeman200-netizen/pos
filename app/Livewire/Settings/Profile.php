<?php

namespace App\Livewire\Settings;

use App\Models\PharmacyProfile;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads; // Wajib untuk fitur upload logo

    public $name = 'Apotek Default';
    public $address;
    public $phone;
    public $apoteker_name;
    public $sipa_number;
    
    public $logo; // Untuk nampung file gambar yang baru diupload
    public $existingLogo; // Untuk nampilin gambar lama dari database

    public function mount()
    {
        // Tarik data profil pertama (karena datanya cuma 1 baris)
        $profile = PharmacyProfile::first();
        
        if ($profile) {
            $this->name = $profile->name;
            $this->address = $profile->address;
            $this->phone = $profile->phone;
            $this->apoteker_name = $profile->apoteker_name;
            $this->sipa_number = $profile->sipa_number;
            $this->existingLogo = $profile->logo;
        }
    }

    public function save()
    {
        // 1. Validasi Data
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'apoteker_name' => 'nullable|string|max:255',
            'sipa_number' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048', 
        ]);

        // 2. Logika Gambar (Jika ada gambar baru)
        if ($this->logo) {
            $path = $this->logo->store('logos', 'public');
            $validated['logo'] = $path; // Masukkan path ke array tervalidasi
            $this->existingLogo = $path; 
        } else {
            // Jika tidak ada gambar baru, hapus kunci 'logo' dari array 
            // agar logo lama di database tidak terhapus menjadi null
            unset($validated['logo']);
        }

        // 3. Eksekusi Database (Cari ID 1. Jika ada, Update. Jika tidak, Create)
        PharmacyProfile::updateOrCreate(
            ['id' => 1], // Patokan pencarian
            $validated   // Data yang dimasukkan
        );

        // 4. Bersihkan memori file sementara Livewire & kirim notifikasi
        $this->logo = null; 
        session()->flash('success', 'Profil Apotek berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.settings.profile');
    }
}