@extends('layouts.template')
@section('title', 'Dashboard | Colaboradores')
@section('content')
    <div class="mt-4 flex items-center justify-center">
        <div class="mx-4 flex w-full flex-col gap-4 rounded-lg bg-white p-4 md:w-[500px]">
            <div class="flex items-center justify-center">
                <h1 class="font-roboto text-3xl font-bold uppercase text-primary">Registrar marca</h1>
            </div>
            <div class="flex flex-col items-center justify-center gap-4">
                @if (!$hasAllMarks)
                    <form action="{{ route('register.marking') }}" enctype="multipart/form-data" class="w-full p-4"
                        id="form-capture" method="POST">
                        @csrf
                        @if (!$markings || !$markings->entry_time)
                            <p class="text-center text-zinc-800">Registrar marca de inicio de operaciones</p>
                            <input type="hidden" value="start" name="type_marking">
                        @elseif (!$markings->lunch_time_start)
                            <p class="text-center text-zinc-800">Registrar marca de inicio de almuerzo</p>
                            <input type="hidden" value="lunch_start" name="type_marking">
                        @elseif (!$markings->lunch_time_end)
                            <p class="text-center text-zinc-800">Registrar marca de finalización de almuerzo</p>
                            <input type="hidden" value="lunch_end" name="type_marking">
                        @elseif (!$markings->exit_time)
                            <p class="text-center text-zinc-800">Registrar marca de finalización de operaciones</p>
                            <input type="hidden" value="end" name="type_marking">
                        @endif
                        <div
                            class="mt-4 flex h-80 w-full flex-col items-center justify-center rounded-lg border-2 border-dashed border-zinc-400 p-4">
                            <span id="icon-photo">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-zinc-400" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-camera">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                    <path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                </svg>
                            </span>
                            <input type="file" name="photo" id="photo" class="hidden">
                            <video id="video-capture" autoplay class="hidden h-60 w-full rounded-lg"></video>
                            <canvas id="canvas-capture" class="m-4 mx-auto hidden h-60 w-72 rounded-lg"></canvas>
                            <div class="mt-4 flex justify-center">
                                <button id="btn-capture" type="button"
                                    class="hidden rounded-md border-none bg-secondary px-4 py-2 font-poppins text-primary hover:bg-yellow-300">Capturar
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <button id="btn-take-photo" type="button"
                                class="mt-4 rounded-md border-none bg-secondary px-4 py-2 font-poppins text-primary hover:bg-yellow-300">
                                Tomar foto
                            </button>
                        </div>
                        <div class="flex items-center justify-center">
                            <button id="btn-marking" type="submit"
                                class="mt-4 hidden rounded-md border-none bg-secondary px-4 py-2 font-poppins text-primary hover:bg-yellow-300">
                                Registrar marca
                            </button>
                        </div>
                    </form>
                @else
                    <p
                        class="flex flex-col items-center gap-2 rounded-lg bg-green-100 p-6 text-center text-green-800 sm:flex-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-mood-happy">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-2 9.66h-6a1 1 0 0 0 -1 1v.05a3.975 3.975 0 0 0 3.777 3.97l.227 .005a4.026 4.026 0 0 0 3.99 -3.79l.006 -.206a1 1 0 0 0 -1 -1.029zm-5.99 -5l-.127 .007a1 1 0 0 0 .117 1.993l.127 -.007a1 1 0 0 0 -.117 -1.993zm6 0l-.127 .007a1 1 0 0 0 .117 1.993l.127 -.007a1 1 0 0 0 -.117 -1.993z" />
                        </svg>
                        Todas las marcas para hoy han sido registradas
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const lat_empresa = @json($lat_empresa);
        const lng_empresa = @json($lng_empresa);
    </script>
    @vite('resources/js/colaborator.js')
@endpush
