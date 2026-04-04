@extends('layouts.app')

@section('title', 'Edit POS')

@section('content')
    <livewire:pos-edit  :sale="$sale"/>  
    {{-- //volt ga bisa nangkep sale sendiri, jadi harus dioper lewat props dulu, baru di-mount di pos-edit untuk isi state awalnya --}}
@endsection