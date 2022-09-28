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

    public function ServicioArmaSolicitada($serial, $ip, $mac, $ente, $usuario, $token)
    {
        $metodo = ArmaSolicitada;
        $parametros_servicio = array(
            'NOSERIALPRIMARIO'  => $serial,
            'ip'                => $ip,
            'mac'               => $mac,
            'ente'              => $ente,
            'usuario'           => $usuario,
        );
        $request = $serial;
        $tokens = $this->dataservices->validarToken($token);
        if(isset($parametros_servicio['NOSERIALPRIMARIO']))
        {
            if($tokens['response']['Code'] == 407){
                $response = $tokens['response'];
            }else{
                $response = $this->dataservices->validarRequest($parametros_servicio, $metodo);
            }
        }else{
            $response = $this->dataservices->errorInvalidRequest();
        }
        $this->dataservices->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $tokens['token'][0]['Nombre'], $tokens['token'][0]['Ministerio'], $tokens['token'][0]['Organismo']);
        
        return response()->json($response);
    }

}
