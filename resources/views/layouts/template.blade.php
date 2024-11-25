<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield('title')
    </title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-primary">
    @include('layouts.__partials.alert')
    <header class="flex items-center justify-between p-4">
        <div class="flex-1">
            <img src="{{ asset('images/diana.png') }}" alt="bg"
                class="ms-4 h-12 w-20 object-contain sm:h-10 md:h-14">
        </div>
        <div class="flex flex-1 justify-center">
            <img src="{{ asset('images/logo-esen.png') }}" alt="bg"
                class="h-12 w-12 object-contain sm:h-14 sm:w-14 md:h-20 md:w-20">
        </div>
        <div class="flex flex-1 flex-col items-end justify-end gap-4 sm:justify-end">
            @if (Auth::check() && Auth::user())
                <div class="flex flex-col items-start gap-2">
                    <div class="hidden items-center gap-2 sm:flex">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->username }}" alt="avatar"
                            class="h-8 w-8 rounded-full object-contain sm:h-10 sm:w-10">
                        <p class="flex flex-col items-start text-white">
                            <span class="text-xs font-bold text-secondary">Hola,</span>
                            <span class="w-40 truncate text-xs">{{ Auth::user()->username }}</span>
                        </p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 rounded-lg bg-white px-2 py-1 text-sm text-primary transition-all hover:bg-zinc-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-logout h-4 w-4 text-current">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                <path d="M9 12h12l-3 -3" />
                                <path d="M18 15l3 -3" />
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </header>

    @yield('content')
</body>

@vite('resources/js/app.js')

@stack('scripts')

</html>
