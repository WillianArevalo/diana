@if (Session::has('success') || Session::has('error') || Session::has('info'))
    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                title: "{{ $message }}",
                icon: "success",
            });
        </script>
    @elseif($message = Session::get('error'))
        <script>
            Swal.fire({
                title: "{{ $message }}",
                icon: "error",
                button: "Aceptar",
                confirmButtonColor: "#941212",
                confirmButtonText: "Aceptar",
            });
        </script>
    @elseif($message = Session::get('info'))
        <script>
            Swal.fire({
                title: "{{ $message }}",
                icon: "info",
            });
        </script>
    @endif
@endif
