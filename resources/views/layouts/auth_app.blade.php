<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>SIIGEAPI</title>

    <!-- General CSS Files -->
    <link rel="icon" href="{{ asset('public/img/Imagen1.png')}}">
    <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('public/css/font-awesome.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/web/css/components.css')}}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/iziToast.min.css') }}">
    <link href="{{ asset('public/assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
        function callbackThen(response){
            // read HTTP status
            console.log(response.status);
            // read Promise object
            response.json().then(function(data){
            console.log(data);
            });
        }

        function callbackCatch(error){
            console.error('Error:', error)
        }
    </script>

        {!! htmlScriptTagJsApi(['callback_then' => 'callbackThen', 'callback_catch' => 'callbackCatch']) !!}
</head>

<body> {{-- background="{{ asset('img/bandera.jpg') }}" --}}
<div id="app">
    <section class="section">
        <div class="container mt-5">
            {{-- <h6 align="right">{!! $QR !!}</h6> --}}
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="login-brand">
                        <img src="{{ asset('public/img/Imagen1.png') }}" alt="CICPC" width="170" class="shadow-light">
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
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/js/popper.min.js') }}"></script>
<script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('public/assets/js/login_animacion.js')}}"></script>

<!-- JS Libraies -->

@include('sweetalert::alert')

<!-- Template JS File -->
<script src="{{ asset('public/web/js/stisla.js') }}"></script>
<script src="{{ asset('public/web/js/scripts.js') }}"></script>
<!-- Page Specific JS File -->
</body>
</html>
