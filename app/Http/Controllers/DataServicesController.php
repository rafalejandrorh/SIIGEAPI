<?php

namespace App\Http\Controllers;

use App\Models\DataServices;
use App\Models\Token_Organismos;
use App\Models\Traza_API;
use Illuminate\Http\Request;
use soapClient;

class DataServicesController extends Controller
{
    private $servicio;
    private $token;
    private $traza_api;

    public function __construct(DataServices $servicio, Token_Organismos $token, Traza_API $traza_api)
    {
        $this->servicio = $servicio;
        $this->tokens = $token;
        $this->trazas = $traza_api;
    }

    private function validarToken($token) 
    {
        $validar_token = $this->tokens::Where('token','=',$token)->exists();
        $token = $this->tokens::join('dependencias', 'dependencias.id', '=', 'token_organismos.id_dependencias')->Where('token', '=', $token);
        if($validar_token == true)
        {
            $get = $token->get();
            $last_used = $token->update(['last_used_at' => date('Y-m-d H:i:s')]);
        }else{
            $get = array(
                0 => array(
                    "Query" => 0
                )
            );
            $last_used = $token->update(['last_used_at' => date('Y-m-d H:i:s')]);
        }
        return $get;
    }

    private function validarRequest($parametros, $tokens, $metodo, $token)
    {
        if(!isset($tokens[0]['Query']))
        {
            if(date('Y-m-d') < $tokens[0]['expires_at'] && $token == $tokens[0]['token'] && $tokens[0]['estatus'] == true)
            {
                if($parametros['ip'] != null && $parametros['mac'] != null && $parametros['ente'] != null && $parametros['usuario'] != null)
                {
                    $dataservices = $this->servicio;
                    $dataservices->setMetodo($metodo);
                    $dataservices->setParametros($parametros);
                    $datos = $dataservices->ServicioSolicitado();
                    if(!empty($datos)){
                        if(isset($datos['faultcode']))
                        {   
                            $response = $this->servicio->errorInvalidRequest();
                        }else{
                            $response = $this->servicio->okCodeService($metodo, $datos);
                        }
                    }else{
                        $response = $this->servicio->errorCodeService($metodo);
                    }
                }else{
                    $response = $this->servicio->errorCodeRequest($metodo, $parametros);
                }
            }else if(date('Y-m-d') > $tokens[0]['expires_at'] && $token == $tokens[0]['token']){
                $response = $this->servicio->errorCodeTokenExpire();
            }else if(date('Y-m-d') < $tokens[0]['expires_at'] && $token == null && $tokens[0]['estatus'] == true){
                $response = $this->servicio->errorCodeNoToken();
            }else if(date('Y-m-d') < $tokens[0]['expires_at'] && $token == $tokens[0]['token'] && $tokens[0]['estatus'] == false){
                $response = $this->servicio->errorCodeInactiveToken();
            }
        }else{
            $response = $this->servicio->errorCodeToken();
        }
        return $response;   
    }

    private function GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $dependencia, $organismo, $ministerio)
    {
        $this->trazas->ip = $ip;
        $this->trazas->mac = $mac;
        $this->trazas->usuario = $usuario;
        $this->trazas->ente = $ente;
        $this->trazas->fecha_request = date('Y-m-d H:i:s');
        $this->trazas->action = $metodo;
        $this->trazas->response = json_encode($response, true);
        $this->trazas->request = $request;
        $this->trazas->token = $token;
        $this->trazas->dependencia = $dependencia;
        $this->trazas->organismo = $organismo;
        $this->trazas->ministerio = $ministerio;
        $this->trazas->save();
    }

    public function TestPersonaSolicitada()
    {
        $metodo = 'consultarPersonaSolicitada';
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjI2ODE5NjYsImV4cCI6MTY2MzExMzk2NiwiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.2kPvoVm_nGook34f1f-yE3vYfSZQ5ROnaidLFQgnNiI';
        $parametros = array(
            'letracedula' => 'V',
            'cedpersona'  => '20677724',
            'ip'          => '10.3.130.124',
            'mac'         => 'MAC',
            'ente'        => 'CICPC',
            'usuario'     => 'V27903883',
        );
        $request = $parametros['letracedula'].$parametros['cedpersona'];
        $tokens = $this->validarToken($token);
        if(!isset($parametros['letracedula']) && !isset($parametros['cedpersona']))
        {
            $response = $this->servicio->errorInvalidRequest();
        }else{
            $response = $this->validarRequest($parametros, $tokens, $metodo, $token);
        }
        $this->GuardarTrazas($parametros['ip'], $parametros['mac'], $parametros['usuario'], $parametros['ente'], $metodo, $response, $request, $token, $tokens[0]['Nombre'], $tokens[0]['Organismo'], $tokens[0]['Ministerio']);
        return response()->json($response);
    }

    public function TestVehiculoSolicitado()
    {
        $metodo = 'ConsultarVehiculoSolicitado';
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjI2ODE5NjYsImV4cCI6MTY2MzExMzk2NiwiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.2kPvoVm_nGook34f1f-yE3vYfSZQ5ROnaidLFQgnNiI';
        $parametros = array(
            'placa' => 'KAM666',
            'ip'          => '10.3.130.124',
            'mac'         => 'MAC',
            'ente'        => 'CICPC',
            'usuario'     => 'V27903883',
        );
        $request = $parametros['placa'];
        $tokens = $this->validarToken($token);
        if(!isset($parametros['placa']))
        {
            $response = $this->servicio->errorInvalidRequest();
        }else{
            $response = $this->validarRequest($parametros, $tokens, $metodo, $token);
        }
        $this->GuardarTrazas($parametros['ip'], $parametros['mac'], $parametros['usuario'], $parametros['ente'], $metodo, $response, $request, $token, $tokens[0]['Nombre'], $tokens[0]['Organismo'], $tokens[0]['Ministerio']);
        return response()->json($response);
    }

    public function TestArmaSolicitada()
    {
        $metodo = 'consultaArmaSolicitada';
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjI2ODE5NjYsImV4cCI6MTY2MzExMzk2NiwiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.2kPvoVm_nGook34f1f-yE3vYfSZQ5ROnaidLFQgnNiI';
        $parametros = array(
            'NOSERIALPRIMARIO' => 'E438858',
            'ip'          => '10.3.130.124',
            'mac'         => 'MAC',
            'ente'        => 'CICPC',
            'usuario'     => 'V27903883',
        );
        $request = $parametros['NOSERIALPRIMARIO'];
        $tokens = $this->validarToken($token);
        if(!isset($parametros['NOSERIALPRIMARIO']))
        {
            $response = $this->servicio->errorInvalidRequest();
        }else{
            $response = $this->validarRequest($parametros, $tokens, $metodo, $token);
        }
        $this->GuardarTrazas($parametros['ip'], $parametros['mac'], $parametros['usuario'], $parametros['ente'], $metodo, $response, $request, $token, $tokens[0]['Nombre'], $tokens[0]['Organismo'], $tokens[0]['Ministerio']);
        return response()->json($response);
    }

    public function ServicioPersonaSolicitada($letra_cedula, $cedula, $ip, $mac, $ente, $usuario, $token)
    {
        $metodo = 'consultarPersonaSolicitada';
        $parametros_servicio = array(
            'letracedula' => $letra_cedula,
            'cedpersona'  => $cedula,
            'ip'          => $ip,
            'mac'         => $mac,
            'ente'        => $ente,
            'usuario'     => $usuario,
        );
        $request = $letra_cedula.$cedula;
        $tokens = $this->validarToken($token);
        if(!isset($parametros_servicio['letracedula']) && !isset($parametros_servicio['cedpersona']))
        {
            $response = $this->servicio->errorInvalidRequest();
        }else{
            $response = $this->validarRequest($parametros_servicio, $tokens, $metodo, $token);
        }
        $this->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $tokens[0]['Nombre'], $tokens[0]['Ministerio'], $tokens[0]['Organismo']);
        return response()->json($response);
    }

    public function ServicioVehiculoSolicitado($placa, $ip, $mac, $ente, $usuario, $token)
    {
        $metodo = 'ConsultarVehiculoSolicitado';
        $parametros_servicio = array(
            'placa'       => $placa,
            'ip'          => $ip,
            'mac'         => $mac,
            'ente'        => $ente,
            'usuario'     => $usuario,
        );
        $request = $placa;
        $tokens = $this->validarToken($token);
        if(!isset($parametros_servicio['placa']))
        {
            $response = $this->servicio->errorInvalidRequest();
        }else{
            $response = $this->validarRequest($parametros_servicio, $tokens, $metodo, $token);
        }
        $this->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $tokens[0]['Nombre'], $tokens[0]['Ministerio'], $tokens[0]['Organismo']);
        return response()->json($response);
    }

    public function ServicioArmaSolicitada($serial, $ip, $mac, $ente, $usuario, $token)
    {
        $metodo = 'consultaArmaSolicitada';
        $parametros_servicio = array(
            'NOSERIALPRIMARIO'  => $serial,
            'ip'                => $ip,
            'mac'               => $mac,
            'ente'              => $ente,
            'usuario'           => $usuario,
        );
        $request = $serial;
        $tokens = $this->validarToken($token);
        if(!isset($parametros_servicio['NOSERIALPRIMARIO']))
        {
            $response = $this->servicio->errorInvalidRequest();
        }else{
            $response = $this->validarRequest($parametros_servicio, $tokens, $metodo, $token);
        }
        $this->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $tokens[0]['Nombre'], $tokens[0]['Ministerio'], $tokens[0]['Organismo']);
        return response()->json($response);
    }

}
