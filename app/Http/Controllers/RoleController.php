<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Caracteristicas_Resennado;
use App\Models\Traza_Roles;
Use Alert;
use App\Events\TrazasEvent;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('can:roles.index')->only('index');
        $this->middleware('can:roles.create')->only('create');
        $this->middleware('can:roles.show')->only('show');
        $this->middleware('can:roles.edit')->only('edit', 'update');
        $this->middleware('can:roles.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $roles = Role::paginate(10);
        $permission = Permission::get();
        return view('roles.index',compact('roles', 'permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('roles.create',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
    
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        $i = 0;
        $roles = null;
        while($i<count($request['permission']))
        {
            $permisos = Permission::select('description')->Where('id', $request['permission'][$i])->get();
            foreach($permisos as $perm)
            {
                $perm['description'];
                
            } 
            $roles .= $perm['description'].', '; 
            $i++;
        };

        $id_user = Auth::user()->id;
        $id_Accion = 1; //Registro
        $valores_modificados = 'Rol: '.$request['name'].' || Permisos: '.$roles;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Roles'));

        Alert()->success('Rol Creado Satisfactoriamente','Ahora puedes asignar el siguiente rol: '.$request->input('name'));
        return redirect()->route('roles.index');                        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();
    
        return view('roles.edit',compact('role','permission','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));

        $i = 0;
        $roles = null;
        while($i<count($request['permission']))
        {
            $permisos = Permission::select('description')->Where('id', $request['permission'][$i])->get();
            foreach($permisos as $perm)
            {
                $perm['description'];
                
            } 
            $roles .= $perm['description'].', '; 
            $i++;
        };

        $id_user = Auth::user()->id;
        $id_Accion = 2; //Actualización
        $valores_modificados = 'Rol: '.$request['name'].' || Permisos: '.$roles;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Roles'));

        Alert()->success('Rol de '.$request->input('name'),  'Actualizado Satisfactoriamente.');
        return redirect()->route('roles.index');                        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {

        $id_permisos = DB::table("role_has_permissions")->where('role_id',$role->id)
        ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->pluck('permission_id')->all();
        
        $i = 0;
        $roles = null;
        while($i<count($id_permisos))
        {
            $permisos = Permission::select('description')->Where('id', $id_permisos[$i])->get();

            foreach($permisos as $perm)
            {
                $perm['description'];
                
            } 
            $roles .= $perm['description'].', '; 
            $i++;
        };

        $id_user = Auth::user()->id;
        $id_Accion = 3; //Eliminación
        $valores_modificados = 'Rol: '.$role->name.' || Permisos: '.$roles;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Roles'));

        $role->delete();

        return redirect()->route('roles.index')->with('eliminar', 'Ok');                        
    }
}
