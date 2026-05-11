<?php

use App\Livewire\Forms\LoginForm;
use App\Models\PharmacyProfile;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;
    public string $pharmacyName = '';
    public string $logoPath = '';

    public function mount(): void
    {
        $profile = PharmacyProfile::first();
        $this->pharmacyName = $profile ? $profile->name : 'APOTEK POS';
        $this->logoPath = $profile ? $profile->logo : '';
    }

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: '/', navigate: false);
    }
}; ?>

<div class="h-screen w-full flex items-center justify-center bg-gray-50 dark:bg-slate-900 p-4 overflow-hidden select-none">
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-500/10 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-blue-500/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-[420px] z-10">
        <div class="text-center mb-8">
            @if($logoPath)
                <div class="inline-block p-1 bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700 mb-4">
                    <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" class="w-24 h-24 object-contain rounded-2xl">
                </div>
            @else
                <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-600 rounded-[2rem] shadow-lg shadow-indigo-500/30 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </div>
            @endif

            <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter uppercase italic">
                {{ $pharmacyName }}
            </h2>
            <div class="mt-1 flex items-center justify-center gap-2">
                <span class="h-px w-8 bg-indigo-500"></span>
                <p class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em]">Point of Sale System</p>
                <span class="h-px w-8 bg-indigo-500"></span>
            </div>
        </div>

        <div class="bg-white/80 dark:bg-slate-800/50 backdrop-blur-2xl border border-white dark:border-slate-700/50 rounded-[3rem] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.1)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.3)]">
            
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form wire:submit="login" class="space-y-5">
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Kredensial Akses</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <x-text-input wire:model="form.email" 
                            class="block w-full bg-gray-100/50 dark:bg-slate-900/50 border-transparent dark:border-transparent text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-4 pl-12 transition-all placeholder:text-slate-400" 
                            type="email" name="email" required autofocus placeholder="nama@email.com" />
                    </div>
                    <x-input-error :messages="$errors->get('form.email')" class="mt-1 ml-1" />
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center ml-1">
                        <label class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Kata Sandi</label>
                        
                    </div>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <x-text-input wire:model="form.password" 
                            class="block w-full bg-gray-100/50 dark:bg-slate-900/50 border-transparent dark:border-transparent text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-4 pl-12 transition-all placeholder:text-slate-400"
                            type="password" name="password" required placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('form.password')" class="mt-1 ml-1" />
                </div>

                <div class="flex items-center px-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input wire:model="form.remember" type="checkbox" class="w-5 h-5 rounded-lg border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500/20">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 group-hover:text-indigo-500 transition-colors">Biarkan saya tetap masuk</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-600/20 hover:shadow-indigo-600/40 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group relative overflow-hidden">
                    <span class="relative z-10 tracking-widest text-sm">OTENTIKASI MASUK</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative z-10 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full duration-1000 transition-transform"></div>
                </button>
            </form>
        </div>
    </div>
</div>