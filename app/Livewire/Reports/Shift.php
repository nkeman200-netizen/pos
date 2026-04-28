<?php

namespace App\Livewire\Reports;

use App\Models\CashierShift;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Shift extends Component
{
    #[Layout('layouts.app')]
    #[Title('Laporan Shift Kasir')]

    public $startDate;
    public $endDate;
    public $userId = '';

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $query = CashierShift::with('user')
            ->whereBetween('start_time', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        $shifts = $query->latest('start_time')->get();
        
        $kasirs = User::whereIn('role', ['kasir', 'admin'])->orderBy('name')->get();

        return view('livewire.reports.shift', [
            'shifts' => $shifts,
            'kasirs' => $kasirs
        ]);
    }
}