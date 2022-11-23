<?php

namespace App\Http\Controllers;

use App\Models\Historial_Sesion;
use Illuminate\Http\Request;
use Alert;
use App\Models\Servicios;
use App\Models\Token_Historial;
use App\Models\Traza_Acciones;
use App\Models\Traza_Dependencias;
use App\Models\Traza_Funcionarios;
use App\Models\Traza_Roles;
use App\Models\Traza_User;
use App\Models\Traza_Token;
use App\Models\Traza_API;
use App\Models\Traza_Servicios;
use App\Models\Traza_User_SIIPOL;
use App\Models\User;
use Spatie\Permission\Models\Role;

class TrazasController extends Controller
{
    function __construct()
    {
        $this->middleware('can:trazas.index')->only('index', 
        'index_usuarios', 'index_dependencias', 'index_funcionarios', 'index_historial_sesion', 'index_roles',
        'show_dependencias', 'show_usuarios', 'show_funcionarios', 'show_roles', 'index_historial_tokens', 'show_historial_tokens');
    }

    public function index()
    {
        return view('trazas.index');
    }

    public function index_dependencias(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_Dependencias::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
                }
                if($request->id_accion != null)
                {
                    $queryBuilder->Where('id_accion', $request->id_accion);
                }
                if($request->id_usuario != null)
                {
                    $queryBuilder->Where('id_user', $request->id_usuario);
                }
                $dependencias = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'cedula'){
                $dependencias = Traza_Dependencias::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                ->Where('persons.cedula', '=', $request->buscador)->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'credencial'){
                $dependencias = Traza_Dependencias::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'usuario'){
                $dependencias = Traza_Dependencias::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                ->Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'nombre'){
                $dependencias = Traza_Dependencias::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'apellido'){
                $dependencias = Traza_Dependencias::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'accion'){
                $dependencias = Traza_Dependencias::join('traza_acciones', 'traza_acciones.id', '=', 'trazas_organismos.id_accion')
                ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                ->Where('traza_acciones.valor', 'LIKE', '%'.$request->buscador.'%')->orderBy('created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'valores_modificados'){
                $dependencias = Traza_Dependencias::select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                ->Where('valores_modificados', 'LIKE', '%'.$request->buscador.'%')->
                orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

            }else{
                $dependencias = Traza_Dependencias::orderBy('created_at', 'desc')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();

        return view('trazas.dependencias_index', compact('dependencias', 'user', 'accion'));
    }

    public function show_dependencias(Traza_Dependencias $dependencia)
    {
        return view('trazas.dependencias_show', compact('dependencia'));
    }

    public function index_usuarios(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_User::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
                }
                if($request->id_accion != null)
                {
                    $queryBuilder->Where('id_accion', $request->id_accion);
                }
                if($request->id_usuario != null)
                {
                    $queryBuilder->Where('id_user', $request->id_usuario);
                }
                $users = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'cedula'){
                $users = Traza_User::join('users', 'users.id', '=', 'traza_users.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('traza_users.id', 'traza_users.id_user', 'traza_users.id_accion', 'traza_users.valores_modificados', 'traza_users.created_at')
                ->Where('persons.cedula', '=', $request->buscador)->orderBy('traza_users.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'credencial'){
                $users = Traza_User::join('users', 'users.id', '=', 'traza_users.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->select('traza_users.id', 'traza_users.id_user', 'traza_users.id_accion', 'traza_users.valores_modificados', 'traza_users.created_at')
                ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('traza_users.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'usuario'){
                $users = Traza_User::join('users', 'users.id', '=', 'traza_users.id_user')
                ->select('traza_users.id', 'traza_users.id_user', 'traza_users.id_accion', 'traza_users.valores_modificados', 'traza_users.created_at')
                ->Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('traza_users.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'nombre'){
                $users = Traza_User::join('users', 'users.id', '=', 'traza_users.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('traza_users.id', 'traza_users.id_user', 'traza_users.id_accion', 'traza_users.valores_modificados', 'traza_users.created_at')
                ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('traza_users.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'apellido'){
                $users = Traza_User::join('users', 'users.id', '=', 'traza_users.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('traza_users.id', 'traza_users.id_user', 'traza_users.id_accion', 'traza_users.valores_modificados', 'traza_users.created_at')
                ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('traza_users.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'accion'){
                $users = Traza_User::join('traza_acciones', 'traza_acciones.id', '=', 'traza_users.id_accion')
                ->select('traza_users.id', 'traza_users.id_user', 'traza_users.id_accion', 'traza_users.valores_modificados', 'traza_users.created_at')
                ->Where('traza_acciones.valor', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('traza_users.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'valores_modificados'){
                $users = Traza_User::select('traza_users.id', 'traza_users.id_user', 'traza_users.id_accion', 'traza_users.valores_modificados', 'traza_users.created_at')
                ->Where('valores_modificados', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('traza_users.created_at', 'desc')->paginate(10);

            }else{
                $users = Traza_User::orderBy('created_at', 'desc')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $usr = User::pluck('users', 'id')->all();

        return view('trazas.users_index', compact('users', 'usr', 'accion'));
    }

    public function show_usuarios(Traza_User $user)
    {
        return view('trazas.users_show', compact('user'));
    }

    public function index_funcionarios(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_Funcionarios::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
                }
                if($request->id_accion != null)
                {
                    $queryBuilder->Where('id_accion', $request->id_accion);
                }
                if($request->id_usuario != null)
                {
                    $queryBuilder->Where('id_user', $request->id_usuario);
                }
                $funcionario = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'cedula'){
                $funcionario = Traza_Funcionarios::join('users', 'users.id', '=', 'trazas_funcionarios.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_funcionarios.id', 'trazas_funcionarios.id_user', 'trazas_funcionarios.id_accion', 'trazas_funcionarios.valores_modificados', 'trazas_funcionarios.created_at')
                ->Where('persons.cedula', '=', $request->buscador)->orderBy('trazas_funcionarios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'credencial'){
                $funcionario = Traza_Funcionarios::join('users', 'users.id', '=', 'trazas_funcionarios.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->select('trazas_funcionarios.id', 'trazas_funcionarios.id_user', 'trazas_funcionarios.id_accion', 'trazas_funcionarios.valores_modificados', 'trazas_funcionarios.created_at')
                ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('trazas_funcionarios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'usuario'){
                $funcionario = Traza_Funcionarios::join('users', 'users.id', '=', 'trazas_funcionarios.id_user')
                ->select('trazas_funcionarios.id', 'trazas_funcionarios.id_user', 'trazas_funcionarios.id_accion', 'trazas_funcionarios.valores_modificados', 'trazas_funcionarios.created_at')
                ->Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('trazas_funcionarios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'nombre'){
                $funcionario = Traza_Funcionarios::join('users', 'users.id', '=', 'trazas_funcionarios.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_funcionarios.id', 'trazas_funcionarios.id_user', 'trazas_funcionarios.id_accion', 'trazas_funcionarios.valores_modificados', 'trazas_funcionarios.created_at')
                ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_funcionarios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'apellido'){
                $funcionario = Traza_Funcionarios::join('users', 'users.id', '=', 'trazas_funcionarios.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_funcionarios.id', 'trazas_funcionarios.id_user', 'trazas_funcionarios.id_accion', 'trazas_funcionarios.valores_modificados', 'trazas_funcionarios.created_at')
                ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_funcionarios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'accion'){
                $funcionario = Traza_Funcionarios::join('traza_acciones', 'traza_acciones.id', '=', 'trazas_funcionarios.id_accion')
                ->select('trazas_funcionarios.id', 'trazas_funcionarios.id_user', 'trazas_funcionarios.id_accion', 'trazas_funcionarios.valores_modificados', 'trazas_funcionarios.created_at')
                ->Where('traza_acciones.valor', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_funcionarios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'valores_modificados'){
                $funcionario = Traza_Funcionarios::select('trazas_funcionarios.id', 'trazas_funcionarios.id_user', 'trazas_funcionarios.id_accion', 'trazas_funcionarios.valores_modificados', 'trazas_funcionarios.created_at')
                ->Where('valores_modificados', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_funcionarios.created_at', 'desc')->paginate(10);

            }else{
                $funcionario = Traza_Funcionarios::orderBy('created_at', 'desc')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();

        return view('trazas.funcionarios_index', compact('funcionario', 'user', 'accion'));
    }

    public function show_funcionarios(Traza_Funcionarios $funcionario)
    {
        return view('trazas.funcionarios_show', compact('funcionario'));
    }

    public function index_roles(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_Roles::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
                }
                if($request->id_accion != null)
                {
                    $queryBuilder->Where('id_accion', $request->id_accion);
                }
                if($request->id_usuario != null)
                {
                    $queryBuilder->Where('id_user', $request->id_usuario);
                }
                $roles = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'cedula'){
                $roles = Traza_Roles::join('users', 'users.id', '=', 'traza_roles.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->Where('persons.cedula', '=', $request->buscador)->orderBy('traza_roles.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'credencial'){
                $roles = Traza_Roles::join('users', 'users.id', '=', 'traza_roles.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('traza_roles.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'usuario'){
                $roles = Traza_Roles::join('users', 'users.id', '=', 'traza_roles.id_user')
                ->Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('traza_roles.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'nombre'){
                $roles = Traza_Roles::join('users', 'users.id', '=', 'traza_roles.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('traza_roles.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'apellido'){
                $roles = Traza_Roles::join('users', 'users.id', '=', 'traza_roles.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('traza_roles.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'accion'){
                $roles = Traza_Roles::join('traza_acciones', 'traza_acciones.id', '=', 'traza_roles.id_accion')
                ->Where('traza_acciones.valor', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('traza_roles.created_at', 'desc')->paginate(10);

            }else{
                $roles = Traza_Roles::orderBy('created_at', 'desc')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();

        return view('trazas.roles_index', compact('roles', 'user', 'accion'));
    }

    public function show_roles(Traza_Roles $role)
    {
        return view('trazas.roles_show', compact('role'));
    }

    public function index_historial_sesion(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Historial_Sesion::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    $queryBuilder->WhereBetween('login', [$inicio, $fin]);
                }
                if($request->id_usuario != null)
                {
                    $queryBuilder->Where('id_user', $request->id_usuario);
                }
                $historial_sesion = $queryBuilder->orderBy('login', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'cedula'){
                $historial_sesion = Historial_Sesion::join('users', 'users.id', '=', 'historial_sesion.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->Where('persons.cedula', '=', $request->buscador)->orderBy('login', 'DESC')->paginate(10);
            }else if($request->tipo_busqueda == 'credencial'){
                $historial_sesion = Historial_Sesion::join('users', 'users.id', '=', 'historial_sesion.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('login', 'DESC')->paginate(10);
            }else if($request->tipo_busqueda == 'jerarquia'){
                $historial_sesion = Historial_Sesion::join('users', 'users.id', '=', 'historial_sesion.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
                ->Where('jerarquia.valor', 'LIKE', '%'.$request->buscador.'%')->orderBy('login', 'DESC')->paginate(10);
            }else if($request->tipo_busqueda == 'usuario'){
                $historial_sesion = Historial_Sesion::Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('login', 'DESC')->paginate(10);
            }else if($request->tipo_busqueda == 'nombre'){
                $historial_sesion = Historial_Sesion::join('users', 'users.id', '=', 'historial_sesion.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')->orderBy('login', 'DESC')->paginate(10);

            }else if($request->tipo_busqueda == 'apellido'){
                $historial_sesion = Historial_Sesion::join('users', 'users.id', '=', 'historial_sesion.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')->orderBy('login', 'DESC')->paginate(10);

            }else{
                $historial_sesion = Historial_Sesion::orderBy('login', 'DESC')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();

        return view('trazas.historial_sesion_index', compact('historial_sesion', 'user', 'accion'));
    }

    public function index_historial_tokens(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Token_Historial::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null && $request->tipo_filtro != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    if($request->tipo_filtro == 'creacion'){
                        $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
                    }else if($request->tipo_filtro == 'expiracion'){
                        $queryBuilder->WhereBetween('expires_at', [$inicio, $fin]);
                    }else if($request->tipo_filtro == 'ultimo_uso'){
                        $queryBuilder->WhereBetween('last_used_at', [$inicio, $fin]);
                    }
                }
                if($request->id_usuario != null)
                {
                    $queryBuilder->Where('id_user', $request->id_usuario);
                }
                $historial_token = $queryBuilder->orderBy('updated_at', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'dependencia'){
                $historial_token = Token_Historial::join('dependencias', 'dependencias.id', '=', 'token_historial.id_dependencias')
                ->Where('dependencias.Nombre', 'LIKE', '%'.$request->buscador.'%')->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_dependencias', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
                'token_historial.last_used_at', 'token_historial.id')->paginate(10);

            }else if($request->tipo_busqueda == 'organismo'){
                $historial_token = Token_Historial::join('dependencias', 'dependencias.id', '=', 'token_historial.id_dependencias')
                ->Where('dependencias.Organismo', 'LIKE', '%'.$request->buscador.'%')->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_dependencias', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
                'token_historial.last_used_at', 'token_historial.id')->paginate(10);

            }else if($request->tipo_busqueda == 'ministerio'){
                $historial_token = Token_Historial::join('dependencias', 'dependencias.id', '=', 'token_historial.id_dependencias')
                ->Where('dependencias.Ministerio', 'LIKE', '%'.$request->buscador.'%')->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_dependencias', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
                'token_historial.last_used_at', 'token_historial.id')->paginate(10);

            }else if($request->tipo_busqueda == 'token'){
                $historial_token = Token_Historial::join('dependencias', 'dependencias.id', '=', 'token_historial.id_dependencias')
                ->Where('token_historial.token', '=', $request->buscador)->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_dependencias', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
                'token_historial.last_used_at', 'token_historial.id')->paginate(10);

            }else{
                $historial_token = Token_Historial::orderBy('token_historial.created_at', 'DESC')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();

        return view('trazas.historial_tokens_index', compact('historial_token', 'user', 'accion'));
    }
    
    public function show_historial_tokens(Token_Historial $historial_tokens)
    {
        return view('trazas.historial_tokens_show', compact('historial_tokens'));
    }

    public function index_tokens(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_token::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
                }
                if($request->id_accion != null)
                {
                    $queryBuilder->Where('id_accion', $request->id_accion);
                }
                if($request->id_usuario != null)
                {
                    $queryBuilder->Where('id_user', $request->id_usuario);
                }
                $tokens = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'cedula'){
                $tokens = Traza_token::join('users', 'users.id', '=', 'trazas_token.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                ->Where('persons.cedula', '=', $request->buscador)->orderBy('trazas_token.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'credencial'){
                $tokens = Traza_token::join('users', 'users.id', '=', 'trazas_token.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('trazas_token.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'usuario'){
                $tokens = Traza_token::join('users', 'users.id', '=', 'trazas_token.id_user')
                ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                ->Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('trazas_token.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'nombre'){
                $tokens = Traza_token::join('users', 'users.id', '=', 'trazas_token.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_token.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'apellido'){
                $tokens = Traza_token::join('users', 'users.id', '=', 'trazas_token.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_token.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'accion'){
                $tokens = Traza_token::join('traza_acciones', 'traza_acciones.id', '=', 'trazas_token.id_accion')
                ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                ->Where('traza_acciones.valor', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_token.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'valores_modificados'){
                $tokens = Traza_token::select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                ->Where('valores_modificados', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_token.created_at', 'desc')->paginate(10);

            }else{
                $tokens = Traza_token::orderBy('created_at', 'desc')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();

        return view('trazas.tokens_index', compact('tokens', 'user', 'accion'));
    }

    public function show_tokens(Traza_token $tokens)
    {
        return view('trazas.tokens_show', compact('tokens'));
    }

    public function index_api(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_API::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    $queryBuilder->WhereBetween('fecha_request', [$inicio, $fin]);
                }
                if($request->action != null)
                {
                    $queryBuilder->Where('action', $request->action);
                }
                if($request->usuario != null)
                {
                    $queryBuilder->Where('usuario', $request->usuario);
                }
                if($request->dependencia != null)
                {
                    $queryBuilder->Where('dependencia', $request->dependencia);
                }
                if($request->organismo != null)
                {
                    $queryBuilder->Where('organismo', $request->organismo);
                }
                if($request->ministerio != null)
                {
                    $queryBuilder->Where('ministerio', $request->ministerio);
                }
                $apis = $queryBuilder->orderBy('fecha_request', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'consulta'){
                $apis = Traza_API::Where('request', '=', $request->buscador)->orderBy('trazas_api.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'token'){
                $apis = Traza_API::Where('token', '=', $request->buscador)->orderBy('trazas_api.created_at', 'desc')->paginate(10);

            }else{
                $apis = Traza_API::orderBy('created_at', 'desc')->paginate(10);
            }

        }

        $accion = Servicios::pluck('valor', 'valor')->all();
        $user = Traza_API::pluck('usuario', 'usuario')->all();
        $dependencia = Traza_API::pluck('dependencia', 'dependencia')->all();
        $organismo = Traza_API::pluck('organismo', 'organismo')->all();
        $ministerio = Traza_API::pluck('ministerio', 'ministerio')->all();

        return view('trazas.api_index', compact('apis', 'user', 'accion', 'dependencia', 'organismo', 'ministerio'));
    }

    public function show_api(Traza_API $apis)
    {
        return view('trazas.api_show', compact('apis'));
    }

    public function index_servicios(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_Servicios::query();
            if($request->fecha_inicio != null && $request->fecha_fin != null)    
            {
                $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
            }
            if($request->id_accion != null)
            {
                $queryBuilder->Where('id_accion', $request->id_accion);
            }
            if($request->id_usuario != null)
            {
                $queryBuilder->Where('id_user', $request->id_usuario);
            }
            $servicios = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'cedula'){
                $servicios = Traza_Servicios::join('users', 'users.id', '=', 'trazas_servicios.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                ->Where('persons.cedula', '=', $request->buscador)->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'credencial'){
                $servicios = Traza_Servicios::join('users', 'users.id', '=', 'trazas_servicios.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'usuario'){
                $servicios = Traza_Servicios::join('users', 'users.id', '=', 'trazas_servicios.id_user')
                ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                ->Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'nombre'){
                $servicios = Traza_Servicios::join('users', 'users.id', '=', 'trazas_servicios.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'apellido'){
                $servicios = Traza_Servicios::join('users', 'users.id', '=', 'trazas_servicios.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'accion'){
                $servicios = Traza_Servicios::join('traza_acciones', 'traza_acciones.id', '=', 'trazas_servicios.id_accion')
                ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                ->Where('traza_acciones.valor', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'valores_modificados'){
                $servicios = Traza_Servicios::select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                ->Where('valores_modificados', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

            }else{
                $servicios = Traza_Servicios::orderBy('created_at', 'desc')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();

        return view('trazas.servicios_index', compact('servicios', 'user', 'accion'));
    }

    public function show_servicios(Traza_Servicios $servicios)
    {
        return view('trazas.servicios_show', compact('servicios'));
    }

    public function index_usuarios_siipol(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_User_SIIPOL::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
                }
                if($request->id_accion != null)
                {
                    $queryBuilder->Where('id_accion', $request->id_accion);
                }
                if($request->id_usuario != null)
                {
                    $queryBuilder->Where('id_user', $request->id_usuario);
                }
                $users = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'usuario'){
                $users = Traza_User_SIIPOL::join('users', 'users.id', '=', 'traza_users_siipol.id_user')
                ->select('traza_users_siipol.id', 'traza_users_siipol.id_user', 'traza_users_siipol.id_accion', 'traza_users_siipol.valores_modificados', 'traza_users_siipol.created_at')
                ->Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('traza_users_siipol.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'accion'){
                $users = Traza_User_SIIPOL::join('traza_acciones', 'traza_acciones.id', '=', 'traza_users_siipol.id_accion')
                ->select('traza_users_siipol.id', 'traza_users_siipol.id_user', 'traza_users_siipol.id_accion', 'traza_users_siipol.valores_modificados', 'traza_users_siipol.created_at')
                ->Where('traza_acciones.valor', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('traza_users_siipol.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'valores_modificados'){
                $users = Traza_User_SIIPOL::select('traza_users_siipol.id', 'traza_users_siipol.id_user', 'traza_users_siipol.id_accion', 'traza_users_siipol.valores_modificados', 'traza_users_siipol.created_at')
                ->Where('valores_modificados', 'LIKE', '%'.$request->buscador.'%')
                ->orderBy('traza_users_siipol.created_at', 'desc')->paginate(10);

            }else{
                $users = Traza_User_SIIPOL::orderBy('created_at', 'desc')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $usr = User::pluck('users', 'id')->all();

        return view('trazas.users_siipol_index', compact('users', 'usr', 'accion'));
    }
}
