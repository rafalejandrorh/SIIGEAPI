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

    public function __construct(DataServices $servicio, Token_Organismos $token)
    {
        $this->servicio = $servicio;
        $this->tokens = $token;
        $this->trazas = new Traza_API();
    }

    private function validarToken($token) 
    {
        $validar_token = $this->tokens::Where('token','=',$token)->exists();
        if($validar_token == true)
        {
            $token = $this->tokens::join('dependencias', 'dependencias.id', '=', 'token_organismos.id_dependencias')

            ->Where('token', '=', $token)->get();
        }else{
            $token = array(
                0 => array(
                    "Query" => 0
                )
            );
        }
        return $token;
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

        if(!isset($tokens[0]['Query']))
        {
            if(date('Y-m-d') < $tokens[0]['expires_at'] && $token == $tokens[0]['token'])
            {
                if($parametros['letracedula'] != null && $parametros['cedpersona'] != null && $parametros['ip'] != null && $parametros['mac'] != null && $parametros['ente'] != null && $parametros['usuario'] != null)
                {
                    $dataservices = $this->servicio;
                    $dataservices->setMetodo($metodo);
                    $dataservices->setParametros($parametros);
                    $datos = $dataservices->PersonaSolicitada();
                    if(!empty($datos['persona'])){
                        $response = $this->servicio->okCodeService($metodo, $datos);
                    }else{
                        $response = $this->servicio->errorCodeService($metodo);
                    }
                }else{
                    $response = $this->servicio->errorCodeRequest($metodo, $parametros);
                }
            }else if(date('Y-m-d') > $token[0]['expires_at'] && $token == $token[0]['token']){
                $response = $this->servicio->errorCodeTokenExpire();
            }else if(date('Y-m-d') > $token[0]['expires_at'] && $token == $token[0]['token']){
                $response = $this->servicio->errorNoToken();
            }
        }else{
            $response = $this->servicio->errorCodeToken();
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
        
        if(!isset($tokens[0]['Query']))
        {
            if(date('Y-m-d') < $tokens[0]['expires_at'] && $token == $tokens[0]['token'])
            {
                if($parametros['placa'] != null && $parametros['ip'] != null && $parametros['mac'] != null && $parametros['ente'] != null && $parametros['usuario'] != null)
                {
                    $dataservices = $this->servicio;
                    $dataservices->setMetodo($metodo);
                    $dataservices->setParametros($parametros);
                    $datos = $dataservices->VehiculoSolicitado();
                    if(!empty($datos['vehiculo'])){
                        $response = $this->servicio->okCodeService($metodo, $datos);
                    }else{
                        $response = $this->servicio->errorCodeService($metodo);
                    }
                }else{
                    $response = $this->servicio->errorCodeRequest($metodo, $parametros);
                }
            }else if(date('Y-m-d') > $token[0]['expires_at'] && $token == $token[0]['token']){
                $response = $this->servicio->errorCodeTokenExpire();
            }else if(date('Y-m-d') > $token[0]['expires_at'] && $token == $token[0]['token']){
                $response = $this->servicio->errorNoToken();
            }
        }else{
            $response = $this->servicio->errorCodeToken();
        }   
        $this->GuardarTrazas($parametros['ip'], $parametros['mac'], $parametros['usuario'], $parametros['ente'], $metodo, $response, $request, $token, $tokens[0]['Nombre'], $tokens[0]['Ministerio'], $tokens[0]['Organismo']);

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
        
        if(!isset($tokens[0]['Query']))
        {
            if(date('Y-m-d') < $tokens[0]['expires_at'] && $token == $tokens[0]['token'])
            {
                if($parametros['NOSERIALPRIMARIO'] != null && $parametros['ip'] != null && $parametros['mac'] != null && $parametros['ente'] != null && $parametros['usuario'] != null)
                {
                    $dataservices = $this->servicio;
                    $dataservices->setMetodo($metodo);
                    $dataservices->setParametros($parametros);
                    $datos = $dataservices->ArmaSolicitada();
                    if(!empty($datos['arma'])){
                        $response = $this->servicio->okCodeService($metodo, $datos);
                    }else{
                        $response = $this->servicio->errorCodeService($metodo);
                    }
                }else{
                    $response = $this->servicio->errorCodeRequest($metodo, $parametros);
                }
            }else if(date('Y-m-d') > $token[0]['expires_at'] && $token == $token[0]['token']){
                $response = $this->servicio->errorCodeTokenExpire();
            }else if(date('Y-m-d') > $token[0]['expires_at'] && $token == $token[0]['token']){
                $response = $this->servicio->errorNoToken();
            }
        }else{
            $response = $this->servicio->errorCodeToken();
        }   
        $this->GuardarTrazas($parametros['ip'], $parametros['mac'], $parametros['usuario'], $parametros['ente'], $metodo, $response, $request, $token, $tokens[0]['Nombre'], $tokens[0]['Ministerio'], $tokens[0]['Organismo']);

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
        if(!isset($tokens[0]['Query']))
        {
            if(date('Y-m-d') < $tokens[0]['expires_at'] && $token == $tokens[0]['token'])
            {
                if($letra_cedula != null && $cedula != null && $ip != null && $mac != null && $ente != null && $usuario != null)
                {
                    $dataservices = $this->servicio;
                    $dataservices->setMetodo($metodo);
                    $dataservices->setParametros($parametros_servicio);
                    $datos = $dataservices->PersonaSolicitada();
                    if(isset($datos['persona'])){
                        $response = $this->servicio->okCodeService($metodo, $datos);
                    }else{
                        $response = $this->servicio->errorCodeService($metodo);
                    }
                    $response;
                }else{
                    $response = $this->servicio->errorCodeRequest($metodo, $parametros_servicio);
                }
            }else if(date('Y-m-d') > $tokens[0]['expires_at'] && $token == $tokens[0]['token']){
                $response = $this->servicio->errorCodeTokenExpire();
            }else if(date('Y-m-d') > $tokens[0]['expires_at'] && $token == $tokens[0]['token']){
                $response = $this->servicio->errorNoToken();
            }
        }else{
            $response = $this->servicio->errorCodeToken();
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
        
        if(!isset($tokens[0]['Query']))
        {
            if(date('Y-m-d') < $tokens[0]['expires_at'] && $token == $tokens[0]['token'])
            {
                if($placa != null && $ip != null && $ip != null && $mac != null && $ente != null && $usuario != null)
                {
                    $dataservices = $this->servicio;
                    $dataservices->setMetodo($metodo);
                    $dataservices->setParametros($parametros_servicio);
                    $datos = $dataservices->VehiculoSolicitado();
                    if(!empty($datos['vehiculo'])){
                        $response = $this->servicio->okCodeService($metodo, $datos);
                    }else{
                        $response = $this->servicio->errorCodeService($metodo);
                    }
                }else{
                    $response = $this->servicio->errorCodeRequest($metodo, $parametros_servicio);
                }
            }else if(date('Y-m-d') > $token[0]['expires_at'] && $token == $token[0]['token']){
                $response = $this->servicio->errorCodeTokenExpire();
            }else if(date('Y-m-d') > $token[0]['expires_at'] && $token == $token[0]['token']){
                $response = $this->servicio->errorNoToken();
            }
        }else{
            $response = $this->servicio->errorCodeToken();
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
        
        if(!isset($tokens[0]['Query']))
        {
            if(date('Y-m-d') < $tokens[0]['expires_at'] && $token == $tokens[0]['token'])
            {
                if($serial != null && $ip != null && $ip != null && $mac != null && $ente != null && $usuario != null)
                {
                    $dataservices = $this->servicio;
                    $dataservices->setMetodo($metodo);
                    $dataservices->setParametros($parametros_servicio);
                    $datos = $dataservices->ArmaSolicitada();
                    if(!empty($datos['arma'])){
                        $response = $this->servicio->okCodeService($metodo, $datos);
                    }else{
                        $response = $this->servicio->errorCodeService($metodo);
                    }
            }else{
                $response = $this->servicio->errorCodeRequest($metodo, $parametros_servicio);
            }
        }else if(date('Y-m-d') > $token[0]['expires_at'] && $token == $token[0]['token']){
            $response = $this->servicio->errorCodeTokenExpire();
        }else if(date('Y-m-d') > $token[0]['expires_at'] && $token == $token[0]['token']){
            $response = $this->servicio->errorNoToken();
        }
        }else{
            $response = $this->servicio->errorCodeToken();
        }   
        $this->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $tokens[0]['Nombre'], $tokens[0]['Ministerio'], $tokens[0]['Organismo']);

        return response()->json($response);
    }

}
