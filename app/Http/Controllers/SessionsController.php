<?php

namespace App\Http\Controllers;

use App\Events\LogoutHistorialEvent;
use App\Events\TrazasEvent;
use App\Models\Sessions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    function __construct()
    {
        $this->middleware('can:sessions.index')->only('index');
        $this->middleware('can:sessions.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(isset($request->buscador) && is_numeric($request->buscador))
        {
            if($request->tipo_busqueda == 'cedula'){
                $sessions = Sessions::join('users', 'users.id', '=', 'sessions.user_id')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id')
                ->orderBy('last_activity', 'DESC')
                ->Where('persons.cedula', '=', $request->buscador)->paginate(10);

            }else if($request->tipo_busqueda == 'credencial'){
                $sessions = Sessions::join('users', 'users.id', '=', 'sessions.user_id')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id')
                ->orderBy('last_activity', 'DESC')
                ->Where('funcionarios.credencial', '=', $request->buscador)
                ->paginate(10);
            }else{
                Alert()->warning('Búsqueda no permitida');
                $sessions = Sessions::orderBy('last_activity', 'DESC')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id')
                ->paginate(10);
            }

        }else if(isset($request->buscador) && is_string($request->buscador)){

            if($request->tipo_busqueda == 'jerarquia'){
                $sessions = Sessions::join('users', 'users.id', '=', 'sessions.user_id')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id')
                ->orderBy('last_activity', 'DESC')
                ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                ->paginate(10);

            }else if($request->tipo_busqueda == 'usuario'){
                $sessions = Sessions::join('users', 'users.id', '=', 'sessions.user_id')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id')
                ->Where('users.users', 'ilike', '%'.$request->buscador.'%')->paginate(10);

            }else if($request->tipo_busqueda == 'nombre'){
                $sessions = Sessions::join('users', 'users.id', '=', 'sessions.user_id')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id')
                ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                ->paginate(10);

            }else if($request->tipo_busqueda == 'apellido'){
                $sessions = Sessions::join('users', 'users.id', '=', 'sessions.user_id')
                ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id')
                ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                ->paginate(10);

            }else{
                Alert()->warning('Búsqueda no permitida');
                $sessions = Sessions::orderBy('last_activity', 'DESC')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id')
                ->paginate(10);
            }
        }else{
            $sessions = Sessions::orderBy('last_activity', 'DESC')
            ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id')
            ->paginate(10);
        }

        if(isset($request->tipo_busqueda) && isset($request->buscador))
        {
            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $valores_modificados = 'Tipo de Búsqueda: '.$request->tipo_busqueda.'. Valor Buscado: '.$request->buscador;
            event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Sessions'));
        }

        return view('sessions.index', ['sessions' => $sessions]);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $session = Sessions::Where('sessions.id', $request->session)
        ->join('users', 'users.id', '=', 'sessions.user_id')
        ->select('users.users', 'sessions.ip_address')
        ->first();

        if(isset($session['users']))
        {
            $valores_modificados = 'Datos de Sesión: '.$session['users'].' || '.$session['ip_address'];
        }else{
            $valores_modificados = 'Datos de Sesión: Sin Usuario asignado - Sin Inicio de Sesión';
        }

        $id_user = Auth::user()->id;
        $id_Accion = 3; //Búsqueda
        
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Sessions'));

        $session = Sessions::Where('id', $request->session);
        $user = $session->first();

        $session = Sessions::find($request->session, ['id']);
        $session->delete();
        event(new LogoutHistorialEvent(null, 4, $user['user_id']));

        Alert()->success('La Sesión ha sido finalizada Exitosamente');
        return redirect()->route('sessions.index');
    }
}
