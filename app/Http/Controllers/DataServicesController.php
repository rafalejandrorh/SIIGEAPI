<?php

namespace App\Http\Controllers;

use App\Models\DataServices;
use App\Models\Token_Organismos;
use App\Models\Traza_API;

class DataServicesController extends Controller
{
    
    public function __construct(DataServices $servicio, Token_Organismos $token, Traza_API $traza_api)
    {
        $this->servicio = $servicio;
        $this->tokens = $token;
        $this->trazas = $traza_api;
    }

    private function validarToken($token) 
    {
        $validar_token = $this->tokens::Where('token','=',$token)->exists();
        $token = $this->tokens::join('dependencias', 'dependencias.id', '=', 'token_dependencias.id_dependencias')->Where('token', '=', $token);
        if($validar_token == true)
        {
            $tokens = $token->get();

            if(date('Y-m-d') < $tokens[0]['expires_at'] && $tokens[0]['estatus'] == true)
            {
                $response = $this->servicio->okCodeToken();
            }else if(date('Y-m-d') > $tokens[0]['expires_at']){
                $response = $this->servicio->errorCodeTokenExpire();
            }else if(date('Y-m-d') < $tokens[0]['expires_at'] && $token == null && $tokens[0]['estatus'] == true){
                $response = $this->servicio->errorCodeNoToken();
            }else if(date('Y-m-d') < $tokens[0]['expires_at'] && $tokens[0]['estatus'] == false){
                $response = $this->servicio->errorCodeInactiveToken();
            }

            $token->update(['last_used_at' => date('Y-m-d H:i:s')]);
        }else{
            $tokens['token'][0]['Nombre'] = null;
            $tokens['token'][0]['Organismo'] = null;
            $tokens['token'][0]['Ministerio'] = null;

            $response = $this->servicio->errorCodeToken();

            $token->update(['last_used_at' => date('Y-m-d H:i:s')]);
        }

        return  array(
            'response' => $response,
            'token' => $tokens
        );
    }

    private function validarRequest($parametros, $metodo)
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
        return $response;   
    }

    private function GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $dependencia, $organismo, $ministerio)
    {
        $this->trazas->ip = $ip;
        $this->trazas->mac = $mac;
        $this->trazas->usuario = $usuario;
        $this->trazas->ente = $ente;
        $this->trazas->fecha_request = date('Y-m-d H:i:s');
        $this->trazas->action = print_r($metodo, true);
        $this->trazas->response = json_encode($response, true);
        $this->trazas->request = print_r($request, true);
        $this->trazas->token = $token;
        $this->trazas->dependencia = $dependencia;
        $this->trazas->organismo = $organismo;
        $this->trazas->ministerio = $ministerio;
        $this->trazas->save();
    }

    public function Test()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM5MzkwNDMsImV4cCI6MTY5NTQ3NTA0MywiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.kWalFZBlzCI62njbG9c_Khfyn-NOkXYBhP659H-_N38';
        $metodo_persona_solicitada = 'consultarPersonaSolicitada';
        $metodo_vehiculo_solicitado = 'ConsultarVehiculoSolicitado';
        $metodo_arma_solicitada = 'consultaArmaSolicitada';
        $parametros_persona = array(
            'letracedula' => 'V',
            'cedpersona'  => '20677724',
            'ip'          => '10.3.130.124',
            'mac'         => 'MAC',
            'ente'        => 'CICPC',
            'usuario'     => 'V27903883',
        );
        $parametros_vehiculo = array(
            'placa'       => 'KAM666',
            'ip'          => '10.3.130.124',
            'mac'         => 'MAC',
            'ente'        => 'CICPC',
            'usuario'     => 'V27903883',
        );
        $parametros_arma = array(
            'NOSERIALPRIMARIO' => 'E438858',
            'ip'          => '10.3.130.124',
            'mac'         => 'MAC',
            'ente'        => 'CICPC',
            'usuario'     => 'V27903883',
        );

        $tokens = $this->validarToken($token);

        $metodo = array(
            'Persona' => $metodo_persona_solicitada,
            'Vehiculo' => $metodo_vehiculo_solicitado,
            'Arma' => $metodo_arma_solicitada
        );
        $request = array(
            'Persona' => $parametros_persona['letracedula'].$parametros_persona['cedpersona'],
            'Vehiculo' => $parametros_vehiculo['placa'],
            'Arma' => $parametros_arma['NOSERIALPRIMARIO']
        );

        if($tokens['response']['Code'] == 407){
            $response = $tokens['response'];
        }else{
            $response_persona_solicitada = $this->validarRequest($parametros_persona, $metodo_persona_solicitada);
            $response_vehiculo_solicitado = $this->validarRequest($parametros_vehiculo, $metodo_vehiculo_solicitado);
            $response_arma_solicitada = $this->validarRequest($parametros_arma, $metodo_arma_solicitada);

            $response = array(
                'Persona Solicitada' => $response_persona_solicitada,
                'Arma Solicitada' => $response_arma_solicitada,
                'Vehiculo Solicitado' => $response_vehiculo_solicitado
            );
        }
        $this->GuardarTrazas($parametros_persona['ip'], $parametros_persona['mac'], $parametros_persona['usuario'], $parametros_persona['ente'], $metodo, $response, $request, $token, $tokens['token'][0]['Nombre'], $tokens['token'][0]['Ministerio'], $tokens['token'][0]['Organismo']);

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
            if($tokens['response']['Code'] == 407){
                $response = $tokens['response'];
            }else{
                $response = $this->validarRequest($parametros_servicio, $metodo);
            }
        }
        $this->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $tokens['token'][0]['Nombre'], $tokens['token'][0]['Ministerio'], $tokens['token'][0]['Organismo']);
        
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
            if($tokens['response']['Code'] == 407){
                $response = $tokens['response'];
            }else{
                $response = $this->validarRequest($parametros_servicio, $metodo);
            }
        }
        $this->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $tokens['token'][0]['Nombre'], $tokens['token'][0]['Ministerio'], $tokens['token'][0]['Organismo']);
        
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
            if($tokens['response']['Code'] == 407){
                $response = $tokens['response'];
            }else{
                $response = $this->validarRequest($parametros_servicio, $metodo);
            }
        }
        $this->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $tokens['token'][0]['Nombre'], $tokens['token'][0]['Ministerio'], $tokens['token'][0]['Organismo']);
        
        return response()->json($response);
    }

}
