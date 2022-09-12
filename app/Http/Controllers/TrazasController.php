<?php

namespace App\Http\Controllers;

use App\Models\Historial_Sesion;
use Illuminate\Http\Request;
use Alert;
use App\Models\Traza_Acciones;
use App\Models\Traza_Dependencias;
use App\Models\Traza_Funcionarios;
use App\Models\Traza_Roles;
use App\Models\Traza_User;
use App\Models\User;
use Spatie\Permission\Models\Role;

class TrazasController extends Controller
{
    function __construct()
    {

        $this->middleware('can:trazas.index')->only('index', 
        'index_usuarios', 'index_dependencias', 'index_funcionarios', 'index_historial_sesion', 'index_roles',
        'show_dependencias', 'show_usuarios', 'show_funcionarios', 'show_roles');
 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('trazas.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

}
