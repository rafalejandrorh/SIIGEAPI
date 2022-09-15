<li class="side-menus {{ Request::is('home') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('home') }}">
        <i class=" fas fa-home"></i><span>Inicio</span>
    </a>
</li>
@can('tokens.index') 
<li class="side-menus {{ Request::is('tokens') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('tokens.index') }}">
        <i class=" fas fa-key"></i><span>Tokens</span>
    </a>
</li>
@endcan
@can('dependencias.index') 
<li class="side-menus {{ Request::is('dependencias') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('dependencias.index') }}">
        <i class=" fas fa-building"></i><span>Dependencias</span>
    </a>
</li>
@endcan
@can('funcionarios.index') 
<li class="side-menus {{ Request::is('funcionarios') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('funcionarios.index') }}">
        <i class=" fas fa-users"></i><span>Funcionarios</span>
    </a>
</li>
@endcan
@can('users.index') 
<li class="side-menus {{ Request::is('users') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('users.index') }}">
        <i class=" fas fa-user"></i><span>Usuarios</span>
    </a>
</li>
@endcan
@can('roles.index') 
<li class="side-menus {{ Request::is('roles') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('roles.index') }}">
        <i class=" fas fa-key"></i><span>Roles</span>
    </a>
</li>
@endcan
@can('trazas.index') 
<li class="side-menus {{ Request::is('trazas') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('trazas.index') }}">
        <i class=" fas fa-save"></i>
        <span>Trazas</span>
    </a>
</li>
@endcan

