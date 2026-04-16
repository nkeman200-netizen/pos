<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Dashboard extends Component
{
    #[Layout('layouts.app')] // Pakai layout utama yang ada sidebar-nya
    #[Title('Dashboard')]
    public function render()
    {
        return view('livewire.dashboard');
    }
}