<?php

namespace App\Http\Controllers;

use App\Models\Token_Organismos;
use App\Models\Traza_Token;
use App\Models\Dependencias;
use Illuminate\Http\Request;
use Alert;
use App\Models\Token_Historial;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Firebase\JWT\JWT;
use PhpParser\Parser\Tokens;

class TokensController extends Controller
{
    function __construct()
    {

        $this->middleware('can:tokens.index')->only('index');
        $this->middleware('can:tokens.create')->only('create');
        $this->middleware('can:tokens.show')->only('show');
        $this->middleware('can:tokens.edit')->only('edit', 'update');
        $this->middleware('can:tokens.update_status')->only('update_status');
 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->all();

        if($request->tipo_busqueda == 'dependencia'){
            $tokens = Token_Organismos::join('dependencias', 'dependencias.id', '=', 'token_organismos.id_dependencias')
            ->Where('dependencias.Nombre', 'LIKE', '%'.$request->buscador.'%')->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Token::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else{
            $tokens = Token_Organismos::paginate(10);
        }
        
        return view('tokens.index', ['tokens' => $tokens]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $dependencias = Dependencias::orderBy('Nombre', 'asc')
        ->select('id', 'Nombre', 'Ministerio', 'Organismo')->get();
        $fecha_hoy = date('Y-m-d');

        return view('tokens.create', compact('fecha_hoy', 'dependencias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validar_dependencia = Token_Organismos::where('id_dependencias','=',$request->dependencia)->exists();
        if($validar_dependencia == true)
        {
            Alert()->error('Esta Dependencia ya posee un Token Asignado');
            return redirect()->route('tokens.index');
        }else{
            date_default_timezone_set('America/Caracas');
            $time = time();
            $time_expire = $time + (60*60*24*$request->duracion_token);
            $token = array(
                "iat" => $time, //Tiempo en que inicia el Token
                "exp" => $time_expire, //Tiempo de expiración del Token
                "data" => [
                    "id_dependencia" => $request->dependencia,
                ]
            );

            $JWT = JWT::encode($token, "dfhsdfg32dfhcs4xgsrrsdry46", 'HS256');
            $fecha_expire = date('Y-m-d H:i:s', $time_expire);
            $fecha_created = date('Y-m-d H:i:s', $time);

            Token_Organismos::create([
                'id_dependencias' => $request->dependencia, 
                'token' => $JWT, 
                'created_at' => $fecha_created, 
                'expires_at' => $fecha_expire,
                'duracion_token' => $request->duracion_token,
                'estatus' => 1
            ]);

            $tokens = Dependencias::Where('id', $request->dependencia)->get();
            foreach($tokens as $token)
            {
                $dependencia = $token['Nombre'];
                $organismo = $token['Organismo'];
                $ministerio = $token['Ministerio'];
            }

            $id_user = Auth::user()->id;
            $id_Accion = 1; //Registro
            $trazas = Traza_Token::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Datos del Token: Fecha de generacion:'.$fecha_created.' || Fecha de Expiración: '.$fecha_expire.
            ' || Duración del Token(días): '.$request->duracion_token.' || Token: '.$JWT.
            ' || Dependencia: '.$dependencia.' || Organismo: '.$organismo.' || Ministerio: '.$ministerio]);

            Alert()->success('Token Creado Satisfactoriamente','Su Token es: '.$JWT.'  ||  Su Token expirará el: '.$fecha_expire);
            return redirect()->route('tokens.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Token_Organismos $token)
    {
        $fecha_hoy = new DateTime();
        $generacion = new DateTime($token->created_at);
        $expiracion = new DateTime($token->expires_at);
        $ultimo = new DateTime($token->last_used_at);
        $fecha_generacion = $fecha_hoy->diff($generacion)->days;
        $fecha_expiracion = $fecha_hoy->diff($expiracion)->days;
        $ultimo_uso = $fecha_hoy->diff($ultimo)->h;
        return view('tokens.show', compact('token', 'fecha_generacion', 'fecha_expiracion', 'ultimo_uso'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Token_Organismos $token)
    {
        $dependencias = Dependencias::orderBy('Nombre', 'asc')
        ->select('id', 'Nombre', 'Ministerio', 'Organismo')->get();
        $fecha_hoy = new DateTime();
        $generacion = new DateTime($token->created_at);
        $expiracion = new DateTime($token->expires_at);
        $ultimo = new DateTime($token->last_used_at);
        $fecha_generacion = $fecha_hoy->diff($generacion)->days;
        $fecha_expiracion = $fecha_hoy->diff($expiracion)->days;
        $ultimo_uso = $fecha_hoy->diff($ultimo)->h;

        return view('tokens.edit', compact('token', 'dependencias', 'fecha_generacion', 'fecha_expiracion', 'ultimo_uso'));
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
        $token_actual = Token_Organismos::Where('id', $id)->select('id_dependencias', 'token', 'created_at', 'expires_at', 'last_used_at', 'duracion_token')->get();
        Token_Historial::create([
            'id_dependencias' => $token_actual[0]['id_dependencias'],
            'token' => $token_actual[0]['token'],
            'created_at' => $token_actual[0]['created_at'],
            'expires_at' => $token_actual[0]['expires_at'],
            'last_used_at' => $token_actual[0]['last_used_at'], 
            'duracion_token' => $token_actual[0]['duracion_token']
        ]);

        date_default_timezone_set('America/Caracas');
        $time = time();
        $time_expire = $time + (60*60*24*$request->duracion_token);
        $token = array(
            "iat" => $time, //Tiempo en que inicia el Token
            "exp" => $time_expire, //Tiempo de expiración del Token
            "data" => [
                "id_dependencia" => $token_actual[0]['id_dependencias'],
            ]);

        $JWT = JWT::encode($token, "dfhsdfg32dfhcs4xgsrrsdry46", 'HS256');
        $fecha_expire = date('Y-m-d H:i:s', $time_expire);
        $fecha_created = date('Y-m-d H:i:s', $time);

        $token = Token_Organismos::Find($id, ['id']);
        $token->update([
            'token' => $JWT,
            'duracion_token' => $request->duracion_token,
            'created_at' => $fecha_created,
            'expires_at' => $fecha_expire,
            'last_used_at' => null
        ]);

        $tokens = Dependencias::Where('id', $request->dependencia)->get();
        foreach($tokens as $token)
        {
            $dependencia = $token['Nombre'];
            $organismo = $token['Organismo'];
            $ministerio = $token['Ministerio'];
        }

        $id_user = Auth::user()->id;
        $id_Accion = 2; //Actualización
        $trazas = Traza_Token::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos del Token: Fecha de generacion:'.$fecha_created.' || Fecha de Expiración: '.$fecha_expire.
        ' || Duración del Token(días): '.$request->duracion_token.' || Token: '.$JWT.
        ' || Dependencia: '.$dependencia.' || Organismo: '.$organismo.' || Ministerio: '.$ministerio]);

        Alert()->success('Token Actualizado Satisfactoriamente','Su Token es: '.$JWT.'  ||  Su Token expirará el: '.$fecha_expire);
        return redirect()->route('tokens.index');
    }

    public function update_status($id)
    {
        $token = Token_Organismos::Where('id', $id)->get();
        $status = $token[0]['estatus'];
        $id_dependencia = $token[0]['id_dependencias'];

        if($status == true)
        {
            $estatus = false;
            $notificacion = 'Inactivo';
            $estatus_previo = 'Activo';
        }else{
            $estatus = true;
            $notificacion = 'Activo';
            $estatus_previo = 'Inactivo';
        }
        $tokens = Token_Organismos::find($id, ['id']);
        $tokens->update(['estatus' => $estatus]);

        $tokens = Dependencias::Where('id', $id_dependencia)->get();
        foreach($tokens as $token)
        {
            $dependencia = $token['Nombre'];
            $organismo = $token['Organismo'];
            $ministerio = $token['Ministerio'];
            $token = $token['token'];
        }

        $id_user = Auth::user()->id;
        $id_Accion = 2; //Actualización
        $trazas = Traza_Token::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos del Token: Estatus previo: '.$estatus_previo.' || Estatus nuevo: '.$notificacion.
        ' || Token: '.$token.' || Dependencia: '.$dependencia.' || Organismo: '.$organismo.' || Ministerio: '.$ministerio]);

        Alert()->success('Estatus de Token Actualizado', 'Nuevo Estatus: '.$notificacion);
        return redirect()->route('tokens.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
