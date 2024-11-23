@extends('layouts.template')
@section('title', 'Crear horario | Facilitadores')
@section('content')
    <div class="flex flex-col items-center justify-center">
        <div class="mt-8 text-center">
            <h1 class="mt-4 font-roboto text-2xl font-bold uppercase text-secondary">
                Crear horario
            </h1>
        </div>
        <div class="mb-4 mt-10 w-2/4 overflow-x-auto font-roboto text-white">
            <form action="{{ route('horarios.store') }}" method="POST">
                @csrf
                <div class="flex items-center gap-4">
                    <div class="mb-4 flex-1">
                        <label for="type" class="mb-2 block text-sm font-medium text-white">Tipo de horario</label>
                        <select name="type" id="type"
                            class="w-full rounded-lg border-2 border-white px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-white focus:outline-none">
                            <option value="">Selecciona un tipo de horario</option>
                            <option value="day">Día</option>
                            <option value="night">Noche</option>
                        </select>
                    </div>
                    <div class="mb-4 flex-[2]">
                        <label for="workplace_id" class="mb-2 block text-sm font-medium text-white">
                            Centro de trabajo
                        </label>
                        <input type="hidden" name="workplace_id" id="workplace_id" value="{{ $workplace->id }}">
                        <input type="text"
                            class="w-full rounded-lg border-2 border-white px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-white focus:outline-none"
                            value="{{ $workplace->name }}" readonly>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="mb-4 flex-1">
                        <label for="date_start" class="mb-2 block text-sm font-medium text-white">Fecha de inicio</label>
                        <input type="date" name="date_start" id="date_start"
                            class="w-full rounded-lg border-2 border-white px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-white focus:outline-none">
                    </div>
                    <div class="mb-4 flex-1">
                        <label for="date_end" class="mb-2 block text-sm font-medium text-white">Fecha de fin</label>
                        <input type="date" name="date_end" id="date_end"
                            class="w-full rounded-lg border-2 border-white px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-white focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="mb-4 flex-1">
                        <label for="time_start" class="mb-2 block text-sm font-medium text-white">Hora de inicio</label>
                        <input type="time" name="time_start" id="time_start"
                            class="w-full rounded-lg border-2 border-white px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-white focus:outline-none">
                    </div>
                    <div class="mb-4 flex-1">
                        <label for="time_end" class="mb-2 block text-sm font-medium text-white">Hora de fin</label>
                        <input type="time" name="time_end" id="time_end"
                            class="w-full rounded-lg border-2 border-white px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-white focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="mb-4 flex-1">
                        <label for="break_start" class="mb-2 block text-sm font-medium text-white">
                            Hora de inicio de descanso
                        </label>
                        <input type="time" name="break_start" id="break_start"
                            class="w-full rounded-lg border-2 border-white px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-white focus:outline-none">
                    </div>
                    <div class="mb-4 flex-1">
                        <label for="break_end" class="mb-2 block text-sm font-medium text-white">
                            Hora de fin de descanso
                        </label>
                        <input type="time" name="break_end" id="break_end"
                            class="w-full rounded-lg border-2 border-white px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-white focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="mb-4 flex-1">
                        <label for="hours_day" class="mb-2 block text-sm font-medium text-white">Horas de día</label>
                        <input type="number" name="hours_day" id="hours_day"
                            class="w-full rounded-lg border-2 border-white px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-white focus:outline-none"
                            placeholder="Ingresa la cantidad de horas de día">
                    </div>
                    <div class="mb-4 flex-1">
                        <label for="hours_night" class="mb-2 block text-sm font-medium text-white">Horas de noche</label>
                        <input type="number" name="hours_night" id="hours_night"
                            class="w-full rounded-lg border-2 border-white px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-white focus:outline-none"
                            placeholder="Ingresa la cantidad de horas de noche">
                    </div>
                </div>

                <div class="flex items-center justify-center">
                    <button type="submit"
                        class="rounded-md border-none bg-secondary px-4 py-2 font-poppins text-primary hover:bg-yellow-300">
                        Crear horario
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
