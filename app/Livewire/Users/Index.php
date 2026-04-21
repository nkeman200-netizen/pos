<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Layout('layouts.app')]
    #[Title('Manajemen Karyawan')]
    
    public $userId, $name, $email, $role = 'kasir', $password, $pin;
    public $isEdit = false;

    public function resetFields()
    {
        $this->reset(['userId', 'name', 'email', 'role', 'password', 'pin', 'isEdit']);
    }

    public function editUser($id)
    {
        $this->resetFields();
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->pin = $user->pin;
        $this->isEdit = true;
        
        $this->dispatch('open-user-modal');
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|in:owner,admin,kasir',
            'pin' => 'nullable|digits:6',
        ];

        // Password wajib jika tambah baru, opsional jika edit
        if (!$this->isEdit) {
            $rules['password'] = 'required|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'pin' => $this->pin,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $data);

        $this->dispatch('close-user-modal');
        session()->flash('success', $this->isEdit ? 'Akun Karyawan diperbarui!' : 'Karyawan baru ditambahkan!');
        $this->resetFields();
    }

    public function render()
    {
        // Hanya Admin dan Owner yang boleh akses halaman ini
        abort_if(!in_array(auth()->user()->role, ['admin', 'owner']), 403);
        
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('livewire.users.index', compact('users'));
    }
}