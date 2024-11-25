@extends('layouts.template')
@section('title', 'Dashboard | RRHH')
@section('content')
    <div class="flex flex-col items-center justify-center">
        <div class="mt-8 text-center">
            <h2 class="my-4 font-roboto text-xl font-semibold uppercase text-white">
                Asistencias
            </h2>
            <a href="{{ route('rrhh.dashboard') }}"
                class="rounded-md bg-secondary px-4 py-2 font-poppins uppercase text-primary hover:bg-yellow-300">
                Volver al dashboard
            </a>
        </div>
        <div
            class="mb-4 mt-10 flex w-full flex-col items-center overflow-hidden overflow-x-auto px-4 font-roboto text-white">

            <div class="mb-4 flex w-full items-center justify-end">
                <a href="{{ route('rrhh.asistencias.excel') }}"
                    class="rounded-md bg-secondary px-4 py-2 font-poppins uppercase text-primary hover:bg-yellow-300">
                    Exportar a Excel
                </a>
            </div>

            <table class="w-full divide-y divide-gray-200" id="marks-table">
                <thead class="bg-red-800 font-roboto">
                    <tr>
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
                            Día
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            Fecha
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            Entrada
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            R. salida
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            R. entrada
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            Salida
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            HRD
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            HED
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            HRN
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            HEN
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-secondary">
                            Observaciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white font-poppins text-sm text-zinc-800">
                    @foreach ($users as $user)
                        @if (!$user->marks->isEmpty())
                            @foreach ($user->marks as $mark)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-zinc-800">{{ $user->cod_user }}</td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">{{ $user->username }}</td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">
                                        <div class="flex flex-wrap items-start gap-1">
                                            @if (
                                                $user->seventh &&
                                                    strtolower(
                                                        \Carbon\Carbon::parse($mark->date)->locale('es')->translatedFormat('l')) === strtolower($user->seventh->day))
                                                <span
                                                    class="text-nowrap flex items-center gap-1 rounded-md bg-yellow-100 px-1.5 py-0.5 font-poppins text-xs font-bold text-yellow-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        class="h-3 w-3 text-current" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-alert-circle">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                                        <path d="M12 8v4" />
                                                        <path d="M12 16h.01" />
                                                    </svg>
                                                    Día séptimo
                                                </span>
                                            @endif
                                            @if ($mark->is_holiday)
                                                <span
                                                    class="text-nowrap flex items-center gap-1 rounded-md bg-red-100 px-1.5 py-0.5 font-poppins text-xs font-bold text-red-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        class="h-3 w-3 text-current" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                                        <path d="M12 8v4" />
                                                        <path d="M12 16h.01" />
                                                    </svg>
                                                    Día feriado
                                                </span>
                                            @endif
                                            <p class="uppercase">
                                                @if ($mark->type_marking === 'mark')
                                                    {{ \Carbon\Carbon::parse($mark->date)->locale('es')->shortDayName }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($mark->date_start)->locale('es')->shortDayName }}
                                                    @if (
                                                        \Carbon\Carbon::parse($mark->date_end)->locale('es')->shortDayName !==
                                                            \Carbon\Carbon::parse($mark->date_start)->locale('es')->shortDayName)
                                                        -
                                                        {{ \Carbon\Carbon::parse($mark->date_end)->locale('es')->shortDayName }}
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">
                                        @if ($mark->type_marking === 'mark')
                                            {{ \Carbon\Carbon::parse($mark->date)->format('d/m/Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($mark->date_start)->format('d/m/Y') }}
                                            @if (
                                                \Carbon\Carbon::parse($mark->date_end)->locale('es')->shortDayName !==
                                                    \Carbon\Carbon::parse($mark->date_start)->locale('es')->shortDayName)
                                                {{ \Carbon\Carbon::parse($mark->date_end)->format('d/m/Y') }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">
                                        @if ($mark->entry_time)
                                            {{ \Carbon\Carbon::parse($mark->entry_time)->format('h:i A') }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">
                                        @if ($mark->lunch_time_start)
                                            {{ \Carbon\Carbon::parse($mark->lunch_time_start)->format('h:i A') }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">
                                        @if ($mark->lunch_time_end)
                                            {{ \Carbon\Carbon::parse($mark->lunch_time_end)->format('h:i A') }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">
                                        @if ($mark->exit_time)
                                            {{ \Carbon\Carbon::parse($mark->exit_time)->format('h:i A') }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">
                                        @if ($mark->HRD)
                                            {{ $mark->HRD ?? '--' }}
                                        @else
                                            {{ $user->HRD ?? '--' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">
                                        @if ($mark->HED)
                                            {{ $mark->HED ?? '--' }}
                                        @else
                                            {{ $user->HED ?? '--' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">
                                        @if ($mark->HRN)
                                            {{ $mark->HRN ?? '--' }}
                                        @else
                                            {{ $user->HRN ?? '--' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">
                                        @if ($mark->HEN)
                                            {{ $mark->HEN ?? '--' }}
                                        @else
                                            {{ $user->HEN ?? '--' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-800">
                                        @if ($mark->type_marking === 'permission')
                                            {{ $mark->type }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/rrhh.js')
@endpush
