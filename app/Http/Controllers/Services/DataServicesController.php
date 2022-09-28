<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Services\DataServices;
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

    public function validarToken($token) 
    {
        $validar_token = $this->tokens::Where('token','=',$token)->exists();
        $token = $this->tokens::join('dependencias', 'dependencias.id', '=', 'token_dependencias.id_dependencias')->Where('token', '=', $token);
        if($validar_token == true)
        {
            $tokens = $token->get();

            if(date('Y-m-d') < $tokens[0]['expires_at'] && $tokens[0]['estatus'] == true)
            {
                $response = $this->okCodeToken();
            }else if(date('Y-m-d') > $tokens[0]['expires_at']){
                $response = $this->errorCodeTokenExpire();
            }else if(date('Y-m-d') < $tokens[0]['expires_at'] && $token == null && $tokens[0]['estatus'] == true){
                $response = $this->errorCodeNoToken();
            }else if(date('Y-m-d') < $tokens[0]['expires_at'] && $tokens[0]['estatus'] == false){
                $response = $this->errorCodeInactiveToken();
            }

            $token->update(['last_used_at' => date('Y-m-d H:i:s')]);
        }else{
            $tokens['token'][0]['Nombre'] = null;
            $tokens['token'][0]['Organismo'] = null;
            $tokens['token'][0]['Ministerio'] = null;

            $response = $this->errorCodeToken();

            $token->update(['last_used_at' => date('Y-m-d H:i:s')]);
        }

        return  array(
            'response' => $response,
            'token' => $tokens
        );
    }

    public function validarRequest($parametros, $metodo)
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
                    $response = $this->errorInvalidRequest();
                }else{
                    $response = $this->okCodeService($metodo, $datos);
                }
            }else{
                $response = $this->errorCodeService($metodo);
            }
        }else{
            $response = $this->errorCodeRequest($metodo, $parametros);
        }  
        return $response;   
    }

    public function GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $dependencia, $ministerio, $organismo)
    {
        if(is_array($metodo) == true)
        {
            $metodo = print_r($metodo, true);
        }

        if(is_array($request) == true)
        {
            $request = print_r($request, true);
        }

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

    private function okCodeService($servicio, $datos)
    {
        $response = [
            'Code' => OK_CODE_SERVICE,
            'Status' => OK_DESCRIPTION_SERVICE,
            'Services' => $servicio,
            'Data' => $datos
        ];
        return $response;
    }

    private function errorCodeService($servicio)
    {
        $response = [
            'Code' => ERROR_CODE_SERVICE,
            'Status' => ERROR_DESCRIPTION_SERVICE,
            'Services' => $servicio,
            'Description' => 'El Servicio  que intenta consultar no existe o no se encuentra disponible'
        ];
        return $response;
    }

    private function errorCodeRequest($servicio, $data)
    {
        $response = [
            'Code' => ERROR_CODE_REQUEST,
            'Status' => ERROR_DESCRIPTION_REQUEST,
            'Services' => $servicio,
            'Request' => $data
        ];
        return $response;
    }

    private function okCodeToken()
    {
        $response = [
            'Code' => OK_CODE_TOKEN,
            'Status' => OK_DESCRIPTION_TOKEN,
        ];
        return $response;
    }

    private function errorCodeToken()
    {
        $response = [
            'Code' => ERROR_CODE_TOKEN,
            'Status' => ERROR_DESCRIPTION_TOKEN,
        ];
        return $response;
    }

    private function errorCodeTokenExpire()
    {
        $response = [
            'Code' => ERROR_CODE_TOKEN_EXPIRE,
            'Status' => ERROR_DESCRIPTION_TOKEN_EXPIRE,
        ];
        return $response;
    }

    private function errorNoToken()
    {
        $response = [
            'Code' => ERROR_NO_TOKEN,
            'Status' => ERROR_DESCRIPTION_NO_TOKEN,
        ];
        return $response;
    }

    public function errorInvalidRequest()
    {
        $response = [
            'Code' => ERROR_CODE_INVALID_REQUEST,
            'Status' => ERROR_DESCRIPTION_INVALID_REQUEST,
        ];
        return $response;
    }

    private function errorCodeInactiveToken()
    {
        $response = [
            'Code' => ERROR_CODE_INACTIVE_TOKEN,
            'Status' => ERROR_DESCRIPTION_INACTIVE_TOKEN,
        ];
        return $response;  
    }

    private function errorUnauthorizedAction()
    {
        $response = [
            'Code' => ERROR_UNAUTHORIZED_ACTION,
            'Status' => ERROR_DESCRIPTION_UNAUTHORIZED_ACTION,
            'Description' => 'La Accion que pretende realizar no se encuentra permitida en este servicio. El incidente sera reportado.'
        ];
        return $response;
    }

    private function okWelcome()
    {
        $response = [
            'Code' => OK_CODE_SERVICE,
            'Status' => OK_DESCRIPTION_SERVICE,
            'Description' => 'Revisa la Documentacion para utilizar el Servicio.'
        ];
        return $response;
    }

}
