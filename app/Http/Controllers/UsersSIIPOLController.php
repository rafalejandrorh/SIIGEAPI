<?php

namespace App\Http\Controllers;

use App\Models\Services\DataServices;
use App\Models\Traza_User;
use App\Models\Traza_User_SIIPOL;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersSIIPOLController extends Controller
{
    function __construct()
    {
        $this->middleware('can:users_siipol.index')->only('index');
        $this->middleware('can:users_siipol.show')->only('show');
        $this->middleware('can:users_siipol.edit')->only('edit', 'update');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, DataServices $servicio)
    {
        $request->all();
        if($request->buscador == null)
        {
            $request->buscador = null;
        }else{
            $parametros = array(
                'cedpersona' => $request->buscador
            );
        }

        if($request->tipo_busqueda == 'interno'){
            $servicio->setMetodo(DatosUsuarioInterno);
            $servicio->setParametros($parametros);
            $user = $servicio->Servicios();
            if($user == null || $user == '')
            {   
                $user = null;
                alert()->error('Cédula NO Asociada a Usuario SIIPOL');
            }else{
                $user['DatosUsuario']['tipo_usuario'] = 'interno';
                $user['DatosUsuario']['primer_apellido'] = utf8_encode($user['DatosUsuario']['primer_apellido']);
                $user['DatosUsuario']['segundo_apellido'] = utf8_encode($user['DatosUsuario']['segundo_apellido']);
                $user['DatosUsuario']['division'] = utf8_encode($user['DatosUsuario']['division']);
            }

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_User_SIIPOL::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda - Funcionario: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'externo'){
            $servicio->setMetodo(DatosUsuarioExterno);
            $servicio->setParametros($parametros);
            $user = $servicio->Servicios();
            if($user == null || $user == '')
            {   
                $user = null;
                alert()->error('Cédula NO Asociada a Usuario SIIPOL');
            }else{
                $user['DatosUsuarioExterno']['tipo_usuario'] = 'externo';
                $user['DatosUsuarioExterno']['primer_apellido'] = utf8_encode($user['DatosUsuarioExterno']['primer_apellido']);
                $user['DatosUsuarioExterno']['segundo_apellido'] = utf8_encode($user['DatosUsuarioExterno']['segundo_apellido']);
            }

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_User_SIIPOL::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda - Funcionario: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);
        }else{
            $user = null;
        }
        
        return view('users_siipol.index', ['Users' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = array(
            'id' => $request->id,
            'user' => $request->user
        );
        return view('users_siipol.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, DataServices $servicio)
    {
        $password = md5($request->password);
        
        $parametros = array(
            'id' => $id,
            'contrasenna' => $password
        );
        $servicio->setMetodo(ActualizarContrasennaUsuario);
        $servicio->setParametros($parametros);
        $result = $servicio->Servicios();
        if($result['ContrasennaUsuario']['estado'] == 1)
        {
            Alert()->success('Contraseña Actualizada Satisfactoriamente');
            $id_user = Auth::user()->id;
            $id_Accion = 2; //Actualización
            $trazas = Traza_User_SIIPOL::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Contraseña Modificada. Usuario: '.$request->users]);
        }else{
            Alert()->warning('No se puedo Actualizar la Contraseña del Usuario', 'Comunícate con la División de Desarrollo de Sistemas - Coordinación de Soporte de Aplicaciones');
        }
        return view('users_siipol.index');
    }

    /**
     * Change Status the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, $id)
    {
        //
    }

}
