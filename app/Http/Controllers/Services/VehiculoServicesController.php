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

    public function ServicioVehiculoSolicitado($placa, $ip, $mac, $ente, $usuario, $token)
    {
        $metodo = VehiculoSolicitado;
        $parametros_servicio = array(
            'placa'       => $placa,
            'ip'          => $ip,
            'mac'         => $mac,
            'ente'        => $ente,
            'usuario'     => $usuario,
        );
        $request = $placa;
        $tokens = $this->dataservices->validarToken($token);
        if(isset($parametros_servicio['placa']))
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

    public function ServicioDatosVehiculoINTT($placa, $ip, $mac, $ente, $usuario, $token)
    {
        $metodo = '';
        $parametros_servicio = array(
            
            'ip'          => $ip,
            'mac'         => $mac,
            'ente'        => $ente,
            'usuario'     => $usuario,
        );
        $request = $placa;
        $tokens = $this->dataservices->validarToken($token);
        if(isset($parametros_servicio['placa']))
        {
            if($tokens['response']['Code'] == 407){
                $response = $tokens['response'];
            }else{
                $response = $this->dataservices->validarRequest($parametros_servicio, $metodo);
            }
        }else{
            $response = $this->dataservices->servicio->errorInvalidRequest();
        }
        $this->dataservices->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token, $tokens['token'][0]['Nombre'], $tokens['token'][0]['Ministerio'], $tokens['token'][0]['Organismo']);
        
        return response()->json($response);
    }
}
