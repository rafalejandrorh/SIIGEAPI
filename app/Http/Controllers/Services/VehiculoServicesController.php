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

    public function ServicioVehiculoSolicitado($placa, $ip, $mac, $ente, $usuario)
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
        $token = $this->dataservices->validarToken();
        if(isset($parametros_servicio['placa']))
        {
            if($token['response']['Code'] == 408){
                $response = $token['response'];
            }else if ($token['response']['Code'] == 407){
                $response = $token['response'];
            }else if ($token['response']['Code'] == 405){
                $response = $token['response'];
            }else{
                $response = $this->dataservices->validarRequest($parametros_servicio, $metodo);
            }
        }else{
            $response = $this->dataservices->errorInvalidRequest();
        }
        $this->dataservices->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token['data'][0]['token'], $token['data'][0]['Nombre'], $token['data'][0]['Ministerio'], $token['data'][0]['Organismo']);
        
        return response()->json($response);
    }

    public function ServicioDatosVehiculoINTT($placa, $ip, $mac, $ente, $usuario)
    {
        $metodo = DatosVehiculoINTT;
        $parametros_servicio = array(
            'placa'       => $placa,
            'ip'          => $ip,
            'mac'         => $mac,
            'ente'        => $ente,
            'usuario'     => $usuario,
        );
        $request = $placa;
        $token = $this->dataservices->validarToken();
        if(isset($parametros_servicio['placa']))
        {
            if($token['response']['Code'] == 408){
                $response = $token['response'];
            }else if ($token['response']['Code'] == 407){
                $response = $token['response'];
            }else if ($token['response']['Code'] == 405){
                $response = $token['response'];
            }else{
                $response = $this->dataservices->validarRequest($parametros_servicio, $metodo);
            }
        }else{
            $response = $this->dataservices->servicio->errorInvalidRequest();
        }
        $this->dataservices->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token['data'][0]['token'], $token['data'][0]['Nombre'], $token['data'][0]['Ministerio'], $token['data'][0]['Organismo']);
        
        return response()->json($response);
    }
}
