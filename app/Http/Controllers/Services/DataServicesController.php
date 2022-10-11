<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Dependencias_Servicios;
use App\Models\Services\DataServices;
use App\Models\Token_Organismos;
use App\Models\Traza_API;

class DataServicesController extends Controller
{
    
    public function __construct(DataServices $servicio, Token_Organismos $token, Traza_API $traza_api, Dependencias_Servicios $dependencias_Servicios)
    {
        $this->servicio = $servicio;
        $this->tokens = $token;
        $this->trazas = $traza_api;
        $this->dependencias_servicios = $dependencias_Servicios;
    }

    public function validarToken() 
    {
        if(isset($_SERVER['HTTP_AUTHORIZATION']))
        {
            $ex = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
            $bearer = $ex[0];
            if(isset($ex[1]))
            {
                $token = $ex[1];
            }else{
                $token = null;
            }  
        
            if(isset($bearer) && $bearer == 'Bearer')
            {
                $validar_token = $this->tokens::Where('token','=',$token)->exists();
                if($validar_token == true)
                {
                    $token = $this->tokens::join('dependencias', 'dependencias.id', '=', 'token_dependencias.id_dependencias')->Where('token', '=', $token);
                    $tokens = $token->get();

                    if(date('Y-m-d H:i:s') < $tokens[0]['expires_at'] && $tokens[0]['estatus'] == true)
                    {
                        $response = $this->okCodeToken();
                    }else if(date('Y-m-d H:i:s') > $tokens[0]['expires_at']){
                        $response = $this->errorCodeTokenExpire();
                    }else if(date('Y-m-d H:i:s') < $tokens[0]['expires_at'] && $tokens[0]['estatus'] == false){
                        $response = $this->errorCodeInactiveToken();
                    }

                    $token->update(['last_used_at' => date('Y-m-d H:i:s')]);
                }else{
                    $tokens[0]['token'] = $token;
                    $tokens[0]['Nombre'] = null;
                    $tokens[0]['Organismo'] = null;
                    $tokens[0]['Ministerio'] = null;

                    $response = $this->errorCodeToken();
                }
            }else{
                $tokens[0]['token'] = null;
                $tokens[0]['Nombre'] = null;
                $tokens[0]['Organismo'] = null;
                $tokens[0]['Ministerio'] = null;

                $response = $this->errorCodeNoTokenBearer();
            }
        }else{
            $tokens[0]['token'] = null;
            $tokens[0]['Nombre'] = null;
            $tokens[0]['Organismo'] = null;
            $tokens[0]['Ministerio'] = null;

            $response = $this->errorCodeNoToken();
        }  

        $result = array(
            'response' => $response,
            'data' => $tokens[0]
        );

        return $result;
    }

    public function validarRequest($parametros, $metodo, $token)
    {
        if($token['response']['Code'] == 202){
            $validar_metodo = $this->dependencias_servicios->join('servicios', 'servicios.id', '=', 'dependencias_servicios.id_servicios')
            ->where('id_dependencias', $token['data']['id_dependencias'])->where('servicios.valor', $metodo)->exists();
            
            if($validar_metodo == true)
            {
                if($parametros['ip'] != null && $parametros['mac'] != null && $parametros['ente'] != null && $parametros['usuario'] != null)
                {
                    $dataservices = $this->servicio;
                    $dataservices->setMetodo($metodo);
                    $dataservices->setParametros($parametros);
                    $datos = $dataservices->Servicios();
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
            }else{
                $response = $this->errorCodeUnauthorizedService($metodo, $parametros);
            }  
        }else{
            $response = $token['response'];
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
            'Description' => 'El Servicio  que intenta consultar no existe o no se encuentra disponible',
            'Services' => $servicio,
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

    private function errorCodeUnauthorizedService($servicio, $data)
    {
        $response = [
            'Code' => ERROR_CODE_UNAUTHORIZED_SERVICE,
            'Status' => ERROR_DESCRIPTION_UNAUTHORIZED_SERVICE,
            'Description' => 'No posee Autorizacion para consultar este servicio',
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

    private function errorCodeNoTokenBearer()
    {
        $response = [
            'Code' => ERROR_CODE_NO_TOKEN_BEARER,
            'Status' => ERROR_DESCRIPTION_NO_TOKEN_BEARER,
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

    private function errorCodeNoToken()
    {
        $response = [
            'Code' => ERROR_CODE_NO_TOKEN,
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

    public function errorUnauthorizedAction()
    {
        $response = [
            'Code' => ERROR_UNAUTHORIZED_ACTION,
            'Status' => ERROR_DESCRIPTION_UNAUTHORIZED_ACTION,
            'Description' => 'La Accion que pretende realizar no se encuentra permitida en este servicio. El incidente sera reportado.'
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
