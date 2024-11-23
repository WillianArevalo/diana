@extends('layouts.template')
@section('title', 'Error al registrar tu marca')
@section('content')
    <div class="container">
        <div class="flex h-screen items-center justify-center">
            <div class="flex flex-col items-center gap-4 text-center">
                <h1 class="font-roboto text-3xl font-bold text-secondary">¡Atención!</h1>
                <p class="mb-4 font-poppins text-lg text-white">Para registrar tu marca, debes estar en la empresa</p>
                <a href="{{ route('colaboradores.dashboard') }}"
                    class="rounded-md border-none bg-secondary px-4 py-2 font-poppins text-primary hover:bg-yellow-300">
                    Volver al dashboard
                </a>
            </div>
        </div>
    </div>
@endsection
