<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\DataServicesController;
use Illuminate\Http\Request;

class TestServicesController extends Controller
{
    public function __construct(DataServicesController $dataservices)
    {
        $this->dataservices = $dataservices;
    }

    public function Test()
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM5MzkwNDMsImV4cCI6MTY5NTQ3NTA0MywiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.kWalFZBlzCI62njbG9c_Khfyn-NOkXYBhP659H-_N38';
        $metodo_persona_solicitada = PersonaSolicitada;
        $metodo_vehiculo_solicitado = VehiculoSolicitado;
        $metodo_arma_solicitada = ArmaSolicitada;
        $parametros_persona = array(
            'letracedula' => 'V',
            'cedpersona'  => '20677724',
            'ip'          => '10.3.142.59',
            'mac'         => '00:00:00:00',
            'ente'        => 'CICPC',
            'usuario'     => 'superadmin'
        );
        $parametros_vehiculo = array(
            'placa'       => 'KAM666',
            'ip'          => '10.3.142.59',
            'mac'         => '00:00:00:00',
            'ente'        => 'CICPC',
            'usuario'     => 'superadmin'
        );
        $parametros_arma = array(
            'noserialprimario' => 'E438858',
            'ip'          => '10.3.142.59',
            'mac'         => '00:00:00:00',
            'ente'        => 'CICPC',
            'usuario'     => 'superadmin'
        );

        $token = $this->dataservices->validarToken();

        $metodo = array(
            'Persona' => $metodo_persona_solicitada,
            'Vehiculo' => $metodo_vehiculo_solicitado,
            'Arma' => $metodo_arma_solicitada
        );
        $request = array(
            'Persona' => $parametros_persona['letracedula'].$parametros_persona['cedpersona'],
            'Vehiculo' => $parametros_vehiculo['placa'],
            'Arma' => $parametros_arma['noserialprimario']
        );

        $response_persona_solicitada = $this->dataservices->validarRequest($parametros_persona, $metodo_persona_solicitada, $token);
        $response_vehiculo_solicitado = $this->dataservices->validarRequest($parametros_vehiculo, $metodo_vehiculo_solicitado, $token);
        $response_arma_solicitada = $this->dataservices->validarRequest($parametros_arma, $metodo_arma_solicitada, $token);

        $response = array(
            'Persona Solicitada' => $response_persona_solicitada,
            'Arma Solicitada' => $response_arma_solicitada,
            'Vehiculo Solicitado' => $response_vehiculo_solicitado
        );

        $this->dataservices->GuardarTrazas($parametros_persona['ip'], $parametros_persona['mac'], $parametros_persona['usuario'], $parametros_persona['ente'], $metodo, $response, $request, $token['data']['token'], $token['data']['Nombre'], $token['data']['Ministerio'], $token['data']['Organismo']);
        unset($_SERVER['HTTP_AUTHORIZATION']);
        return response()->json($response);
    }
}
