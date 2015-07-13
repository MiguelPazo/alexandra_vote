<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alexandra</title>
    <link rel="stylesheet" href="{{ asset('/js/libs/bootstrap/dist/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/js/libs/bootstrap/dist/css/bootstrap-theme.min.css') }}"/>

    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <script src="{{ asset('/js/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>

</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
            <a href="{{ url('/auth/logout') }}" role="button" class="btn btn-danger float_right">SALIR</a>
        </div>
    </div>
</nav>

@yield('content')

</body>
</html>