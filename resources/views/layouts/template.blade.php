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
    <div class="flex h-24 items-center justify-between">
        <div>
            <img src="{{ asset('images/diana.png') }}" alt="bg" class="h-14 w-full object-cover">
        </div>
        <div class="flex items-center gap-4">
            @if (Auth::check() && Auth::user())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="rounded-lg bg-white px-4 py-2 text-primary transition-all hover:bg-zinc-200">
                        Cerrar sesión
                    </button>
                </form>
            @endif
            <img src="{{ asset('images/logo-esen.png') }}" alt="bg" class="h-20 w-20 object-contain">
        </div>
    </div>

    @yield('content')
</body>

@vite('resources/js/app.js')

@stack('scripts')

</html>
