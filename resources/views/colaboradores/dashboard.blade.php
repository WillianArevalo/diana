@extends('layouts.template')
@section('title', 'Dashboard | Colaboradores')
@section('content')
    <div class="flex h-screen w-full items-center justify-center">
        <div class="flex w-[500px] flex-col gap-4 rounded-lg bg-white p-6">
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
                            <canvas id="canvas-capture" class="m-4 mx-auto hidden h-96 w-full rounded-lg"></canvas>
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
                        <div class="mt-4 flex items-center justify-center">
                            <button id="btn-marking" type="submit"
                                class="hidden rounded-md border-none bg-secondary px-4 py-2 font-poppins text-primary hover:bg-yellow-300">
                                Registrar marca
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-center text-zinc-800">
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
