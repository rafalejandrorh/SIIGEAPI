<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'SIIGEAPI')</title>

    <!-- General CSS Files -->
    <link rel="icon" href="{{ asset('img/Imagen1.png')}}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('web/css/components.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/iziToast.min.css') }}">
    <link href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
</head>

<body> {{-- background="{{ asset('img/bandera.jpg') }}" --}}
<div id="app">
    <section class="section">
        <div class="container mt-5">
            {{-- <h6 align="right">{!! $QR !!}</h6> --}}
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="login-brand">
                        <img src="{{ asset('img/Imagen1.png') }}" alt="CICPC" width="170" class="shadow-light">
                    </div>
                    @yield('content')
                    <div class="simple-footer">
                       Cuerpo de Investigaciones Científicas, Penales y Criminalísticas 
                       <br>
                       Desarrollado por: División de Desarrollo de Sistemas
                       <br>
                       Copyright &copy; SIIGEAPI {{ date('Y') }}
                       <br>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- General JS Scripts -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('assets/js/login_animacion.js')}}"></script>

<!-- JS Libraies -->

@include('sweetalert::alert')

<!-- Template JS File -->
<script src="{{ asset('web/js/stisla.js') }}"></script>
<script src="{{ asset('web/js/scripts.js') }}"></script>
<!-- Page Specific JS File -->
</body>
</html>
