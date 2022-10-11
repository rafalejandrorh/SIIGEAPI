<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\DataServicesController;
use Illuminate\Http\Request;

class ArmaServicesController extends Controller
{
    public function __construct(DataServicesController $dataservices)
    {
        $this->dataservices = $dataservices;
    }

    public function ServicioArmaSolicitada($serial, $ip, $mac, $ente, $usuario)
    {
        $metodo = ArmaSolicitada;
        $parametros_servicio = array(
            'noserialprimario'    => $serial,
            'ip'                => $ip,
            'mac'               => $mac,
            'ente'              => $ente,
            'usuario'           => $usuario,
        );
        $request = $serial;
        $token = $this->dataservices->validarToken();
        if(isset($parametros_servicio['noserialprimario']))
        {
            $response = $this->dataservices->validarRequest($parametros_servicio, $metodo, $token);
        }else{
            $response = $this->dataservices->errorInvalidRequest();
        }
        $this->dataservices->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token['data']['token'], $token['data']['Nombre'], $token['data']['Ministerio'], $token['data']['Organismo']);
        
        return response()->json($response);
    }

}
