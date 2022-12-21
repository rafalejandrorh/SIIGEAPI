<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\DataServicesController;
use Illuminate\Http\Request;

class PersonaServicesController extends Controller
{
    public function __construct(DataServicesController $dataservices)
    {
        $this->dataservices = $dataservices;
    }
    
    public function consultaPersonaSolicitadaSiipol($letra_cedula, $cedula, $ip, $mac, $ente, $usuario)
    {
        $metodo = consultarPersonaSolicitada;
        $parametros_servicio['letracedula'] = $letra_cedula;
        $parametros_servicio['cedpersona'] = $cedula;
        $parametros_servicio['ip'] = $ip;
        $parametros_servicio['mac'] = $mac;
        $parametros_servicio['ente'] = $ente;
        $parametros_servicio['usuario'] = $usuario;

        $request = $letra_cedula.$cedula;
        $token = $this->dataservices->validarToken();

        if(isset($parametros_servicio['letracedula']) && isset($parametros_servicio['cedpersona']))
        {
            $response = $this->dataservices->validarRequest($parametros_servicio, $metodo, $token);
        }else{    
            $response = $this->dataservices->errorInvalidRequest();
        }
        $this->dataservices->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token['data']['token'], $token['data']['Nombre'], $token['data']['Ministerio'], $token['data']['Organismo']);
        $code = $this->dataservices->HttpResponseCode($response['Code']);

        return response()->json($response, $code, [
            'Accept' => 'application/json'
        ]);
    }

    public function consultaDatosPersonaSaimeSiipol($letra_cedula, $cedula, $ip, $mac, $ente, $usuario)
    {
        $metodo = consultarDatosPersonaSAIME;
        $parametros_servicio['letracedula'] = $letra_cedula;
        $parametros_servicio['cedpersona'] = $cedula;
        $parametros_servicio['ip'] = $ip;
        $parametros_servicio['mac'] = $mac;
        $parametros_servicio['ente'] = $ente;
        $parametros_servicio['usuario'] = $usuario;

        $request = $letra_cedula.$cedula;
        $token = $this->dataservices->validarToken();
        if(isset($parametros_servicio['letracedula']) && isset($parametros_servicio['cedpersona']))
        {
            $response = $this->dataservices->validarRequest($parametros_servicio, $metodo, $token);
        }else{
            $response = $this->dataservices->servicio->errorInvalidRequest();
        }
        $this->dataservices->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token['data']['token'], $token['data']['Nombre'], $token['data']['Ministerio'], $token['data']['Organismo']);
        $code = $this->dataservices->HttpResponseCode($response['Code']);

        return response()->json($response, $code, [
            'Accept' => 'application/json'
        ]);
    }
}
