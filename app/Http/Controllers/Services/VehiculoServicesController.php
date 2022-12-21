<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\DataServicesController;
use Illuminate\Http\Request;

class VehiculoServicesController extends Controller
{
    public function __construct(DataServicesController $dataservices)
    {
        $this->dataservices = $dataservices;
    }

    public function consultaVehiculoSolicitadoSiipol($placa, $ip, $mac, $ente, $usuario)
    {
        $metodo = consultarVehiculoSolicitado;
        $parametros_servicio['placa'] = $placa;
        $parametros_servicio['ip'] = $ip;
        $parametros_servicio['mac'] = $mac;
        $parametros_servicio['ente'] = $ente;
        $parametros_servicio['usuario'] = $usuario;

        $request = $placa;
        $token = $this->dataservices->validarToken();
        if(isset($parametros_servicio['placa']))
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

    public function consultaDatosVehiculoINTTSiipol($placa, $ip, $mac, $ente, $usuario)
    {
        $metodo = consultarDatosVehiculoINTT;
        $parametros_servicio['placa'] = $placa;
        $parametros_servicio['ip'] = $ip;
        $parametros_servicio['mac'] = $mac;
        $parametros_servicio['ente'] = $ente;
        $parametros_servicio['usuario'] = $usuario;

        $request = $placa;
        $token = $this->dataservices->validarToken();
        if(isset($parametros_servicio['placa']))
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
