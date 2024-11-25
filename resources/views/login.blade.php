@extends('layouts.template')
@section('title', 'Iniciar sesión')
@section('content')
    <div class="absolute top-0 flex h-screen w-full items-center justify-center">
        <div class="mx-4 w-full rounded-lg bg-white p-6 shadow-md md:w-96">
            <h1 class="mb-4 text-center font-roboto text-2xl font-bold uppercase text-primary">
                Bienvenido/a
            </h1>
            <form class="mt-4 px-4" action="{{ route('login.validate') }}" method="POST">
                @csrf
                <div class="flex flex-col gap-2">
                    <label for="username" class="font-roboto text-sm">
                        Código empleado
                    </label>
                    <input type="text" id="cod_empleado" name="cod_empleado"
                        class="rounded-lg border-2 border-gray-300 px-4 py-2 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-primary focus:outline-none"
                        placeholder="Ingresa tu código de empleado">
                    @error('cod_empleado')
                        <span class="font-poppins text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-4 flex flex-col gap-2">
                    <label for="password" class="font-roboto text-sm">
                        Contraseña
                    </label>
                    <input type="password" id="password" name="password"
                        class="rounded-lg border-2 border-gray-300 px-4 py-2 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-primary focus:outline-none"
                        placeholder="Ingresa tu contraseña">
                    @error('password')
                        <span class="font-poppins text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="submit"
                        class="w-full rounded-lg bg-primary px-4 py-2 font-roboto font-bold uppercase text-white hover:bg-red-900">
                        Ingresar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
