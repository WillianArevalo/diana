@extends('layouts.template')
@section('title', 'Dashboard | RRHH')
@section('content')
    <div class="flex flex-col items-center justify-center">
        <div class="mt-8 text-center">
            <h2 class="mt-4 font-roboto text-xl font-semibold uppercase text-white">
                Asistencias de {{ $user->username }}
            </h2>
        </div>
        <div class="mb-4 mt-10 w-[90%] overflow-x-auto font-roboto text-white">
            <table class="divide-y divide-gray-200">
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
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white font-poppins">
                    @if ($user->marks->isEmpty())
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-sm text-zinc-800">
                                No hay asistencias registradas
                            </td>
                        </tr>
                    @endif
                    @foreach ($user->marks as $mark)
                        <tr>
                            <td class="px-6 py-4 text-sm text-zinc-800">{{ $user->cod_user }}</td>
                            <td class="px-6 py-4 text-sm text-zinc-800">{{ $user->username }}</td>
                            <td class="px-6 py-4 text-sm uppercase text-zinc-800">
                                {{ \Carbon\Carbon::parse($mark->date)->locale('es')->shortDayName }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-800">
                                {{ \Carbon\Carbon::parse($mark->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-800">
                                {{ \Carbon\Carbon::parse($mark->entry_time)->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-800">
                                {{ \Carbon\Carbon::parse($mark->luch_time_start)->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-800">
                                {{ \Carbon\Carbon::parse($mark->luch_time_end)->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-800">
                                {{ \Carbon\Carbon::parse($mark->exit_time)->format('h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

{{-- @push('scripts')
    @vite('resources/js/rrhh.js')
@endpush --}}
