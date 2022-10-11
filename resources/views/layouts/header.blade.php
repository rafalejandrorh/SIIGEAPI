<form class="form-inline mr-auto" action="#">
    <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
</form>
<ul class="navbar-nav navbar-right">

    @if(\Illuminate\Support\Facades\Auth::user())
        <li class="dropdown">
            <a href="#" data-toggle="dropdown"
               class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('img/profile.jpg') }}"
                     class="rounded-circle mr-1 thumbnail-rounded user-thumbnail ">
                <div class="d-sm-none d-lg-inline-block">
                    {{\Illuminate\Support\Facades\Auth::user()->users}}</div>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                @can('users.password')
                <a class="dropdown-item" href="{{ route('sesion.index') }}"><i class="fa fa-lock"></i>  Ajustes</a>
                @endcan
                <a href="{{ url('logout') }}" class="dropdown-item text-danger"
                onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i>  Cerrar Sesión</a>

                <form id="logout-form" action="{{ url('/logout/1') }}" method="POST" class="d-none">
                    {{ csrf_field() }}
                </form>
            </div>
        </li>

        <a id="logout-formactivar" href="{{ url('logout') }}" 
            onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-expire').submit();">
        </a>
        <form id="logout-expire" action="{{ url('/logout/2') }}" method="POST" class="d-none">
            {{ csrf_field() }}
        </form>
    @else
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                {{--                <img alt="image" src="#" class="rounded-circle mr-1">--}}
                <div class="d-sm-none d-lg-inline-block">{{ __('Sesión Expirada. Por favor, ingrese nuevamente.') }}</div>
            </a>
        </li>
    @endif
</ul>
