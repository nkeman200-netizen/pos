<?php

namespace App\Livewire\Settings;

use App\Models\PharmacyProfile;
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $address;
    public $phone;
    public $apoteker_name;
    public $sipa_number;
    public $qris_string; 
    
    public $logo;
    public $existingLogo;
    
    public function mount()
    {
        $profile = PharmacyProfile::first();
        
        if ($profile) {
            $this->name = $profile->name;
            $this->address = $profile->address;
            $this->phone = $profile->phone;
            $this->apoteker_name = $profile->apoteker_name;
            $this->sipa_number = $profile->sipa_number;
            $this->qris_string = $profile->qris_string; 
            $this->existingLogo = $profile->logo;
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'apoteker_name' => 'nullable|string|max:255',
            'sipa_number' => 'nullable|string|max:255',
            'qris_string' => 'nullable|string', 
            'logo' => 'nullable|image|max:2048', 
        ]);

        if ($this->logo) {
            $path = $this->logo->store('logos', 'public');
            $validated['logo'] = $path;
            $this->existingLogo = $path; 
        } else {
            unset($validated['logo']);
        }

        PharmacyProfile::updateOrCreate(
            ['id' => 1],
            $validated
        );

        session()->flash('success', 'Profil Apotek berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.settings.profile');
    }
}