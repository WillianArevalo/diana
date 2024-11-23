@extends('layouts.template')
@section('title', 'Dashboard | Facilitadores')
@section('content')

    <div class="flex flex-col items-center justify-center">
        <div class="mt-8 text-center">
            <h1 class="mt-4 font-roboto text-2xl font-bold uppercase text-secondary">
                Centro de trabajo {{ $workplace->id }}
            </h1>
            <h2 class="mt-4 font-roboto text-xl font-semibold uppercase text-white">
                Lista de empleados de {{ $workplace->name }}
            </h2>
        </div>
        <div class="mt-4">
            <h2 class="mt-4 text-center font-roboto text-base font-semibold uppercase text-white">
                Horarios de trabajo asignados:
            </h2>
            @if ($schedules)
                <table class="mt-4 divide-y divide-gray-200">
                    <thead class="bg-zinc-200 font-roboto">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-800">
                                Tipo
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-800">
                                Fecha de inicio
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-800">
                                Fecha de fin
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-800">
                                Hora de inicio
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-800">
                                Hora de inicio de descanso
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-800">
                                Hora de fin de descanso
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white font-poppins">
                        @foreach ($schedules as $schedule)
                            <tr>
                                <td class="px-6 py-4 text-sm text-zinc-800">
                                    {{ $schedule->type === 'day' ? 'Diurno' : 'Nocturno' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-zinc-800">{{ $schedule->date_start }}</td>
                                <td class="px-6 py-4 text-sm text-zinc-800">{{ $schedule->date_end }}</td>
                                <td class="px-6 py-4 text-sm text-zinc-800">
                                    {{ \Carbon\Carbon::parse($schedule->time_start)->format('h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-zinc-800">
                                    {{ \Carbon\Carbon::parse($schedule->break_start)->format('h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-zinc-800">
                                    {{ \Carbon\Carbon::parse($schedule->break_end)->format('h:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="mt-4 text-center font-poppins text-sm text-zinc-200">
                    No hay horarios asignados
                </p>
            @endif
        </div>
        <div class="mb-4 mt-10 w-3/4 overflow-x-auto font-roboto text-white">
            <div class="mb-4 flex items-center justify-end gap-4">
                @if (count($schedules) < 2)
                    <a href="{{ route('horarios.create') }}"
                        class="rounded-md border-none bg-secondary px-4 py-2 font-poppins uppercase text-primary hover:bg-yellow-300">
                        Crear horario
                    </a>
                @endif
                <button id="assign-schedule"
                    class="hidden rounded-md border-none bg-zinc-200 px-4 py-2 font-poppins uppercase text-primary hover:bg-zinc-300">
                    Asignar horario
                </button>
                <button id="assign-seventh"
                    class="hidden rounded-md border-none bg-zinc-200 px-4 py-2 font-poppins uppercase text-primary hover:bg-zinc-300">
                    Asignar septimo
                </button>
            </div>
            <table id="example" class="divide-y divide-gray-200">
                <thead class="bg-red-800 font-roboto">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            <input type="checkbox" name="selectAll" id="selectAll"
                                class="rounded-lg border-gray-300 text-primary focus:ring-primary">
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            Código
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            Nombre
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            Observaciones
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white font-poppins">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-6 py-4 text-sm text-zinc-800">
                                <input type="checkbox" name="select" value="{{ $user->id }}"
                                    class="check-items rounded-md border-gray-300 text-primary focus:ring-primary">
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-800">
                                {{ $user->cod_user }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-800">
                                <div class="flex items-center gap-2">
                                    @if ($user->schedule)
                                        @if ($user->schedule->type === 'day')
                                            <span
                                                class="rounded-md bg-yellow-100 px-2 py-1 font-poppins text-xs font-bold text-yellow-500">
                                                Horario diurno
                                            </span>
                                        @else
                                            <span
                                                class="rounded-md bg-zinc-200 px-2 py-1 font-poppins text-xs font-bold text-zinc-800">
                                                Horario nocturno
                                            </span>
                                        @endif
                                    @else
                                        <span
                                            class="rounded-md bg-red-100 px-2 py-1 font-poppins text-xs font-bold text-red-500">
                                            Sin horario asignado
                                        </span>
                                    @endif
                                    {{ $user->username }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-800">
                                @foreach ($user->marks as $mark)
                                    @php
                                        $count = 0;
                                        if ($mark->entry_time) {
                                            $count++;
                                        }
                                        if ($mark->exit_time) {
                                            $count++;
                                        }
                                        if ($mark->lunch_time_start) {
                                            $count++;
                                        }
                                        if ($mark->lunch_time_end) {
                                            $count++;
                                        }
                                    @endphp
                                    @if ($count === 4)
                                        <span
                                            class="rounded-md bg-green-100 px-2 py-1 font-poppins text-xs font-bold text-green-500">
                                            Marcas completas
                                        </span>
                                    @else
                                        <span
                                            class="rounded-md bg-red-100 px-2 py-1 font-poppins text-xs font-bold text-red-500">
                                            Marcas incompletas
                                        </span>
                                    @endif
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-sm text-white">
                                <div class="flex items-center gap-2">

                                    <button data-user-id="{{ $user->id }}"
                                        class="add-observation rounded-md bg-blue-500 px-2 py-1 font-poppins text-xs uppercase text-white hover:bg-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-current"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye-plus">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path d="M12 18c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                            <path d="M16 19h6" />
                                            <path d="M19 16v6" />
                                        </svg>
                                    </button>

                                    <button data-user-id="{{ $user->id }}"
                                        class="assign-schedule rounded-md bg-secondary px-2 py-1 font-poppins text-xs uppercase text-primary hover:bg-yellow-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            class="h-5 w-5 text-current" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-table">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                                            <path d="M3 10h18" />
                                            <path d="M10 3v18" />
                                        </svg>
                                    </button>

                                    <button data-user-id="{{ $user->id }}"
                                        class="assign-seventh rounded-md border bg-zinc-200 px-2 py-1 font-poppins text-xs uppercase text-zinc-800 hover:bg-zinc-300 hover:text-zinc-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            class="h-5 w-5 text-current" viewBox="0 0 24 24" fill="none"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                            <path d="M16 3v4" />
                                            <path d="M8 3v4" />
                                            <path d="M4 11h16" />
                                            <path d="M11 15h1" />
                                            <path d="M12 15v3" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para asignar horario -->
    <div class="modal fixed inset-0 z-50 hidden flex-col items-center justify-center bg-black bg-opacity-50">
        <div class="w-[500px] rounded-lg bg-white p-6">
            <form action="{{ route('horarios.assign') }}" method="POST">
                <h2 class="text-2xl font-bold">Asignar horario</h2>
                <div class="mt-4">
                    @csrf
                    <div class="flex items-center gap-4">
                        <input type="hidden" name="user_ids">
                        <select name="schedule_id" id="schedule_id"
                            class="w-full rounded-lg border-2 border-zinc-300 px-4 py-3 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-zinc-400 focus:outline-none">
                            <option value="" class="text-gray-400">Selecciona un horario</option>
                            @foreach ($schedules as $schedule)
                                <option value="{{ $schedule->id }}">
                                    {{ $schedule->type === 'day' ? 'Diurno' : 'Nocturno' }} -
                                    {{ $schedule->date_start }} -
                                    {{ $schedule->date_end }} -
                                    {{ \Carbon\Carbon::parse($schedule->time_start)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($schedule->time_end)->format('h:i A') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-end gap-4">
                    <button type="submit"
                        class="rounded-md bg-secondary px-4 py-2 font-poppins uppercase text-primary hover:bg-yellow-300">
                        Asignar horario
                    </button>
                    <button type="button"
                        class="close-modal rounded-md bg-zinc-100 px-4 py-2 font-poppins uppercase text-zinc-800 hover:bg-zinc-200">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para asignar septimo -->
    <div class="modal-seventh fixed inset-0 z-50 hidden flex-col items-center justify-center bg-black bg-opacity-50">
        <div class="w-[500px] rounded-lg bg-white p-6">
            <form action="{{ route('septimo.assign') }}" method="POST">
                <h2 class="text-2xl font-bold">Asignar septimo</h2>
                @csrf
                <input type="hidden" name="user_ids">
                <div class="relative flex flex-1 flex-col gap-2">
                    <label for="date_seventh" class="mb-2 block text-sm font-medium text-white">
                        Selecciona una fecha
                    </label>
                    <input type="date" name="date_seventh" id="date-input"
                        class="w-full rounded-lg border-2 border-zinc-300 px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-zinc-400 focus:outline-none">
                    <div id="calendar" class="absolute top-20 z-50 mt-2 hidden rounded-md border bg-white p-4 shadow-lg">
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-end gap-4">
                    <button type="submit"
                        class="rounded-md bg-secondary px-4 py-2 font-poppins uppercase text-primary hover:bg-yellow-300">
                        Asignar septimo
                    </button>
                    <button type="button"
                        class="close-modal rounded-md bg-zinc-100 px-4 py-2 font-poppins uppercase text-zinc-800 hover:bg-zinc-200">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para crear observaciones -->
    <div class="modal-observation fixed inset-0 z-50 hidden flex-col items-center justify-center bg-black bg-opacity-50">
        <div class="w-[500px] overflow-y-auto rounded-lg bg-white p-6">
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <h2 class="mb-4 text-2xl font-bold text-primary">Crear observación</h2>
                <input type="hidden" name="user_id" id="user_id">
                <div class="flex flex-col">
                    <label for="type" class="mb-2 block text-sm font-medium text-zinc-800">
                        Tipo de observación
                    </label>
                    <select name="type" id="type"
                        class="w-full rounded-lg border-2 border-zinc-300 px-4 py-2.5 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-zinc-400 focus:outline-none">
                        <option value="" class="text-gray-400">Selecciona un tipo</option>
                        <option value="Permison con">
                            Permison con
                        </option>
                        <option value="Permiso sin">
                            Permiso sin
                        </option>
                        <option value="Incapacidad">
                            Incapacidad
                        </option>
                        <option value="Otros">
                            Otros
                        </option>
                    </select>
                </div>
                <div class="mt-4 flex flex-1 flex-col">
                    <label for="start_date" class="mb-2 block text-sm font-medium text-zinc-800">
                        Fecha y hora de inicio del permiso
                    </label>
                    <input type="date" name="start_date" id="start_date"
                        class="w-full rounded-lg border-2 border-zinc-300 px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-zinc-400 focus:outline-none">
                </div>
                <div class="mt-4 flex flex-1 flex-col">
                    <label for="end_date" class="mb-2 block text-sm font-medium text-zinc-800">
                        Fecha y hora de fin del permiso
                    </label>
                    <input type="date" name="end_date" id="end_date"
                        class="w-full rounded-lg border-2 border-zinc-300 px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-zinc-400 focus:outline-none">
                </div>
                <div class="mt-4 flex flex-1 flex-col">
                    <label for="description" class="mb-2 block text-sm font-medium text-zinc-800">
                        Descripción del permiso
                    </label>
                    <textarea name="description" id="description" cols="10" rows="10" style="field-sizing:content"
                        placeholder="Escribe una descripción del permiso"
                        class="w-full rounded-lg border-2 border-zinc-300 px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-zinc-400 focus:outline-none"></textarea>
                </div>

                <div class="mt-4">
                    <label for="file" class="mb-2 block text-sm font-medium text-zinc-800">
                        Adjunta un archivo
                    </label>
                    <input type="file" name="file" id="file"
                        class="w-full rounded-lg border-2 border-zinc-300 px-4 py-2 text-gray-800 placeholder:font-poppins placeholder:text-sm placeholder:font-light placeholder:tracking-wide placeholder:text-gray-400 focus:border-zinc-400 focus:outline-none">
                </div>

                <div class="mt-4 flex items-center justify-end gap-4">
                    <button type="submit"
                        class="rounded-md bg-secondary px-4 py-2 font-poppins uppercase text-primary hover:bg-yellow-300">
                        Crear observación
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
    @vite('resources/js/facilitator.js')
@endpush
