<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Funcionario;
use App\Models\Traza_User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Alert;

class UserController extends Controller
{
    function __construct()
    {

        // $this->middleware('can:users.index')->only('index');
        // $this->middleware('can:users.create')->only('create');
        // $this->middleware('can:users.show')->only('show');
        // $this->middleware('can:users.edit')->only('edit', 'update');
        // $this->middleware('can:users.update_status')->only('update_status');
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->all();
        if($request->buscador == null)
        {
            $request->buscador = null;
        }

        if($request->tipo_busqueda == 'cedula'){
            $user = User::join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
            ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
            ->select('users.id', 'users.id_funcionario', 'users.users', 'users.status')
            ->Where('persons.cedula', '=', $request->buscador)->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);
            
        }else if($request->tipo_busqueda == 'credencial'){
            $user = User::join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
            ->select('users.id', 'users.id_funcionario', 'users.users', 'users.status')
            ->Where('funcionarios.credencial', '=', $request->buscador)->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'jerarquia'){
            $user = User::join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
            ->join('jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
            ->select('users.id', 'users.id_funcionario', 'users.users', 'users.status')
            ->Where('jerarquia.valor', 'LIKE', '%'.$request->buscador.'%')->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'usuario'){
            $user = User::select('users.id', 'users.id_funcionario', 'users.users', 'users.status')
            ->Where('users', 'LIKE', '%'.$request->buscador.'%')
            ->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'estatus'){
            if($request->buscador == 'activo' || $request->buscador == 'Activo' || $request->buscador == 'ACTIVO'){
                $status = true;
            }else if($request->buscador == 'inactivo' || $request->buscador == 'Inactivo' || $request->buscador == 'INACTIVO'){
                $status = false;
            }
            $user = User::select('users.id', 'users.id_funcionario', 'users.users', 'users.status')
            ->Where('status', '=', $status)
            ->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'nombre'){
            $user = User::join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
            ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
            ->select('users.id', 'users.id_funcionario', 'users.users', 'users.status')
            ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')->paginate(5);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'apellido'){
            $user = User::join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
            ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
            ->select('users.id', 'users.id_funcionario', 'users.users', 'users.status')
            ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')->paginate(5);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else{
            $user = User::paginate(10);
        }
        
        return view('users.index', ['Users' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','id')->all();
        $funcionarios = Funcionario::join('persons', 'persons.id', '=', 'funcionarios.id_person')
        ->join('jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
        ->select('funcionarios.id', 'persons.primer_nombre', 'persons.primer_apellido', 'jerarquia.valor')->get();
        return view('users.create',compact('roles', 'funcionarios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $request->all();

        $user = Validator::make($request,User::returnValidations(),User::returnMessages())->validate();
        
        $usuario = new User();

        $obtener_usuario = $usuario->where('id_funcionario', $request['id_funcionario'])->get();
        $validar_usuario = $usuario->where('id_funcionario', $request['id_funcionario'])->exists();
        if($validar_usuario == true){
            Alert()->info('El funcionario ya posee un Usuario.');
            return redirect()->route('users.index');
        } 

        if($validar_usuario == false){

            $funcionario = new Funcionario();
            $obtener_funcionario = $funcionario->where('id', $request['id_funcionario'])->get();

            $id_estatus = $obtener_funcionario[0]['id_estatus'];

            if($id_estatus == 1310000 || $id_estatus == 1310005)
            {

            $request['password'] = bcrypt($request['password']);
            
            $usuario->id_funcionario = $request['id_funcionario'];
            $usuario->users = $request['users'];
            $usuario->password = $request['password'];
            $usuario->status = 'true';
            $usuario->save();

            $usuario->assignRole($request['roles']);

            $roles = Role::Where('id', $request['roles'])->get();
            $rol = $roles[0]['name'];

            $id_user = Auth::user()->id;
            $id_Accion = 1; //Registro
            $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Datos de Usuario: '.$request['users'].' || Activo || '.$rol]);

            Alert()->success('Usuario Creado Satisfactoriamente');
            return redirect()->route('users.index');

            }else{
                Alert()->error('No se creó el Usuario', 'El Funcionario no se encuentra Activo, por lo que no se puede asignar un Usuario');
                return back();
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $id_user = Auth::user()->id;
        $id_Accion = 4; //Visualización
        if($user->status == true)
        {
            $estatus = 'Activo';
        }else{
            $estatus = 'Inactivo';
        }
        $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos de Usuario: '.
        $user->funcionario->jerarquia->valor.'. '.$user->funcionario->person->primer_nombre.' '.
        $user->funcionario->person->primer_apellido.' || '.$estatus.' || '.$user->users]);

        $roles = Role::pluck('name','id')->all();
        return view('users.show', compact('user', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name','id')->all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        $user->id;
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();
        $user->roles()->sync($request->roles);

        $roles = Role::Where('id', $request['roles'])->get();
        $rol = $roles[0]['name'];

        $id_user = Auth::user()->id;
        $id_Accion = 2; //Actualización
        $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos de Usuario: '.$request['users'].' || Activo || '.$rol]);
    
        Alert()->success('Usuario Actualizado Satisfactoriamente');
        return redirect()->route('users.index');
    }

    public function ResetPassword($id){
        
        $user = User::join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
        ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
        ->select('persons.cedula', 'users.users')->Where('users.id', $id)->get();
        foreach($user as $usr)
        {
            $password = 'pm*'.$usr['cedula'].'..';
            $usuario = $usr['users'];
        }
        $bcrypt = bcrypt($password);
        $reset_password = User::find($id, ['id']);
        $reset_password->update(['password'=>$bcrypt]);

        $id_user = Auth::user()->id;
        $id_Accion = 2; //Actualización
        $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Se reseteó la contraseña del Usuario: '.
        $usuario.'. Se colocó la contraseña genérica']);

        Alert()->success('Reinicio de Contraseña realizado', 'Nueva Contraseña: '.$password);
        return back(); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_status($id)
    {
        $user = User::Where('id', $id)->get();

        $id_funcionario = $user[0]['id_funcionario'];
        $status = $user[0]['status'];
        $usuario = $user[0]['users'];

        $funcionario = new Funcionario();
        $obtener_funcionario = $funcionario->where('id', $id_funcionario)->get();

        $id_estatus = $obtener_funcionario[0]['id_estatus'];

        if($status == true)
        {
            $estatus = false;
            $notificacion = 'Inactivo';
        }else if($status == false && $id_estatus == 1310000 || $id_estatus == 1310005){
            $estatus = true;
            $notificacion = 'Activo';
        }else{
            Alert()->error('No se actualizó el Estatus del Usuario', 'El Funcionario no se encuentra Activo, por lo que no se puede activar su Usuario');
            return back();
        }
        $users = User::find($id, ['id']);
        $users->update(['status' => $estatus]);

        $id_user = Auth::user()->id;
        $id_Accion = 2; //Actualización
        $trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos de Usuario: '.
        $usuario.' || '.$notificacion]);

        Alert()->success('Estatus de Usuario Actualizado', 'Nuevo Estatus: '.$notificacion);
        return redirect()->route('users.index');
    }
}
