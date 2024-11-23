@extends('layouts.template')
@section('title', 'Dashboard | RRHH')
@section('content')
    <div class="flex flex-col items-center justify-center">
        <div class="mt-8 text-center">
            <h2 class="mt-4 font-roboto text-xl font-semibold uppercase text-white">
                Lista de empleados
            </h2>
        </div>
        <div class="mb-4 mt-10 w-3/4 overflow-x-auto font-roboto text-white">
            <table id="table-rrhh" class="divide-y divide-gray-200">
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
                            Centro de trabajo
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
                            <td class="px-6 py-4 text-sm text-zinc-800">{{ $user->cod_user }}</td>
                            <td class="px-6 py-4 text-sm text-zinc-800">{{ $user->username }}</td>
                            <td class="px-6 py-4 text-sm text-zinc-800">
                                {{ $user->workplace->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-white">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('colaboradores.show', $user->id) }}"
                                        class="add-observation rounded-md bg-blue-500 px-2 py-1 font-poppins text-xs uppercase text-white hover:bg-blue-600">
                                        Ver asistencias
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    @vite('resources/js/rrhh.js')
@endpush
