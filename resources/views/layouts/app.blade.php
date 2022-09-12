<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>SIIGEAPI</title>
    <link rel="icon" href="{{ asset('public/img/Imagen1.png')}}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 4.1.1 -->
    <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Ionicons -->
    {{-- <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet"> --}}
    <link href="{{ asset('public/assets/css/@fortawesome/fontawesome-free/css/all.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/assets/css/iziToast.min.css') }}">
    <link href="{{ asset('public/assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/assets/js/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('public/assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('public/css/jquery-confirm.min.css')}}" type="text/css">


@yield('page_css')
<!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/web/css/components.css')}}">
    @yield('page_css')

    @yield('css')
</head>
<body>

<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            @include('layouts.header')

        </nav>
        <div class="main-sidebar main-sidebar-postion">
            @include('layouts.sidebar')
        </div>
        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>
        <footer class="main-footer">
            @include('layouts.footer')
        </footer>
    </div>
</div>

</body>
<script src="{{ asset('public/assets/js/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/js/popper.min.js') }}"></script>
<script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/assets/js/iziToast.min.js') }}"></script>
<script src="{{ asset('public/assets/js/select2.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.nicescroll.js') }}"></script>

<!-- Template JS File -->
<script src="{{ asset('public/web/js/stisla.js') }}"></script>
<script src="{{ asset('public/web/js/scripts.js') }}"></script>
<script src="{{ mix('assets/js/profile.js') }}"></script>
<script src="{{ mix('assets/js/custom/custom.js') }}"></script>
<script src="{{ asset('public/js/jquery-confirm.min.js')}}"></script>
<script src="{{ asset('public/js/funcionesAjaxs.js')}}"></script>

@include('sweetalert::alert')

@yield('page_js')
@yield('scripts')
<script>
    let loggedInUser =@json(\Illuminate\Support\Facades\Auth::user());
    let loginUrl = '{{ route('login') }}';
    const userUrl = '{{url('users')}}';
    // Loading button plugin (removed from BS4)
    (function ($) {
        $.fn.button = function (action) {
            if (action === 'loading' && this.data('loading-text')) {
                this.data('original-text', this.html()).html(this.data('loading-text')).prop('disabled', true);
            }
            if (action === 'reset' && this.data('original-text')) {
                this.html(this.data('original-text')).prop('disabled', false);
            }
        };
    }(jQuery));

</script>

<script>
    function mayus(e) {
        e.value = e.value.toUpperCase();
        //e.value = e.value.toLowerCase();
    }

    var timeout;
    document.onmousemove = function(){ 
        clearTimeout(timeout); 
        contadorSesion(); //aqui cargamos la funcion de inactividad
    } 

function contadorSesion() {
   timeout = setTimeout(function () {
        $.confirm({
            title: 'Alerta de Inactividad!',
            content: 'La sesión esta a punto de expirar.',
            autoClose: 'expirar|10000',//cuanto tiempo necesitamos para cerrar la sess automaticamente
            type: 'red',
            icon: 'fa fa-spinner fa-spin',
            buttons: {
                expirar: {
                    text: 'Cerrar Sesión',
                    btnClass: 'btn-red',
                    action: function () {
                        salir();
                        
                    }
                },
                permanecer: function () {
                    contadorSesion(); //reinicia el conteo
                    $.alert('La Sesión ha sido reiniciada!'); //mensaje
                }
            }
        });
    }, 2100000);//2100000 son 35 minutos
}

function salir() {
    $("#logout-formactivar").click();
    //onclick="event.preventDefault(); document.getElementById('logout-form').submit();"  
   // window.location.href = "/login"; //esta función te saca
}
</script>
</html>
