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
    
    public function ServicioPersonaSolicitada($letra_cedula, $cedula, $ip, $mac, $ente, $usuario)
    {
        $metodo = PersonaSolicitada;
        $parametros_servicio = array(
            'letracedula' => $letra_cedula,
            'cedpersona'  => $cedula,
            'ip'          => $ip,
            'mac'         => $mac,
            'ente'        => $ente,
            'usuario'     => $usuario,
        );
        $request = $letra_cedula.$cedula;
        $token = $this->dataservices->validarToken();

        if(isset($parametros_servicio['letracedula']) && isset($parametros_servicio['cedpersona']))
        {
            $response = $this->dataservices->validarRequest($parametros_servicio, $metodo, $token);
        }else{    
            $response = $this->dataservices->errorInvalidRequest();
        }
        $this->dataservices->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token['data']['token'], $token['data']['Nombre'], $token['data']['Ministerio'], $token['data']['Organismo']);
        
        return response()->json($response);
    }

    public function ServicioDatosPersonaSaime($letra_cedula, $cedula, $ip, $mac, $ente, $usuario)
    {
        $metodo = DatosPersonaSAIME;
        $parametros_servicio = array(
            'letracedula'       => $letra_cedula,
            'cedpersona'        => $cedula,
            'ip'                => $ip,
            'mac'               => $mac,
            'ente'              => $ente,
            'usuario'           => $usuario,
        );
        $request = $letra_cedula.$cedula;
        $token = $this->dataservices->validarToken();
        if(isset($parametros_servicio['letracedula']) && isset($parametros_servicio['cedpersona']))
        {
            $response = $this->dataservices->validarRequest($parametros_servicio, $metodo, $token);
        }else{
            $response = $this->dataservices->servicio->errorInvalidRequest();
        }
        $this->dataservices->GuardarTrazas($ip, $mac, $usuario, $ente, $metodo, $response, $request, $token['data']['token'], $token['data']['Nombre'], $token['data']['Ministerio'], $token['data']['Organismo']);
        
        return response()->json($response);
    }
}
