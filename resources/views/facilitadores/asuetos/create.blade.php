@extends('layouts.template')
@section('title', 'Dashboard | Facilitadores')
@section('content')


    <div class="mb-4 flex w-full flex-col items-center justify-center px-4">
        <a href="{{ route('facilitadores.dashboard') }}"
            class="mb-10 rounded-md bg-secondary px-4 py-2 font-poppins uppercase text-primary hover:bg-yellow-300">
            Volver al dashboard
        </a>
        <div id="calendar" class="w-full md:w-full lg:w-1/2"></div>
    </div>

    <!-- Modal para agregar asueto -->
    <div class="modal-holiday fixed inset-0 z-50 hidden flex-col items-center justify-center bg-black bg-opacity-50">
        <div class="w-[500px] rounded-lg bg-white p-6">
            <form action="{{ route('asuetos.store') }}" method="POST">
                @csrf
                <h2 class="text-2xl font-bold">Agregar asueto</h2>
                <input type="hidden" name="user_ids">
                <div class="mt-4 flex flex-1 flex-col">
                    <label for="date_start" class="mb-2 block text-sm font-medium text-zinc-800">
                        Fecha de inicio del asueto
                    </label>
                    <input type="date" name="date_start" id="date_start"
                        class="w-full rounded-lg border-2 border-zinc-300 px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-zinc-400 focus:outline-none"
                        readonly>
                </div>
                <div class="mt-4 flex flex-1 flex-col">
                    <label for="date_end" class="mb-2 block text-sm font-medium text-zinc-800">
                        Fecha de fin del asueto
                    </label>
                    <input type="date" name="date_end" id="date_end"
                        class="w-full rounded-lg border-2 border-zinc-300 px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-zinc-400 focus:outline-none"
                        readonly>
                </div>

                <div class="mt-4 flex flex-1 flex-col">
                    <label for="name" class="mb-2 block text-sm font-medium text-zinc-800">
                        Nombre del asueto
                    </label>
                    <input type="text" name="name" id="name"
                        class="w-full rounded-lg border-2 border-zinc-300 px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-zinc-400 focus:outline-none"
                        placeholder="Nombre del asueto">
                </div>
                <div class="mt-4 flex items-center justify-end gap-4">
                    <button type="submit"
                        class="rounded-md bg-secondary px-4 py-2 font-poppins uppercase text-primary hover:bg-yellow-300">
                        Agregar asueto
                    </button>
                    <button type="button"
                        class="close-modal rounded-md bg-zinc-100 px-4 py-2 font-poppins uppercase text-zinc-800 hover:bg-zinc-200">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/calendar.js')
    <script>
        var holidays = @json($holidays);
    </script>
@endpush
