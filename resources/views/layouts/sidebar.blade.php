<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <img class="navbar-brand-full app-header-logo" src="{{ asset('img/logo_cicpc.png') }}" width="65"
             alt="Logo CICPC">
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}" class="small-sidebar-text">
            <img class="navbar-brand-full" src="{{ asset('img/Imagen1.png') }}" width="45px" alt="Logo CICPC"/>
        </a>
    </div>
    @if (!isset($password_status) || $password_status == false)
        <ul class="sidebar-menu">
            @include('layouts.menu')
        </ul>
    @endif
</aside>
