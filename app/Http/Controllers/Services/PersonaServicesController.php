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
    
    public function ServicioPersonaSolicitada($letra_cedula, $cedula, $ip, $mac, $ente, $usuario, $token)
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
        $tokens = $this->dataservices->validarToken($token);

        if(isset($parametros_servicio['letracedula']) && isset($parametros_servicio['cedpersona']))
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

    public function ServicioDatosPersonaSaime($letra_cedula, $cedula, $ip, $mac, $ente, $usuario, $token)
    {
        $metodo = '';
        $parametros_servicio = array(
            'letracedula'       => $letra_cedula,
            'cedpersona'        => $cedula,
            'ip'                => $ip,
            'mac'               => $mac,
            'ente'              => $ente,
            'usuario'           => $usuario,
        );
        $request = $letra_cedula.$cedula;
        $tokens = $this->dataservices->validarToken($token);
        if(isset($parametros_servicio['letracedula']) && isset($parametros_servicio['cedpersona']))
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
