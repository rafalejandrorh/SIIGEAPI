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
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM5MzkwNDMsImV4cCI6MTY5NTQ3NTA0MywiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.kWalFZBlzCI62njbG9c_Khfyn-NOkXYBhP659H-_N38';
        $metodo_persona_solicitada = PersonaSolicitada;
        $metodo_vehiculo_solicitado = VehiculoSolicitado;
        $metodo_arma_solicitada = ArmaSolicitada;
        $parametros_persona = array(
            'letracedula' => 'V',
            'cedpersona'  => '20677724',
            'ip'          => '10.3.130.124',
            'mac'         => 'MAC',
            'ente'        => 'CICPC',
            'usuario'     => 'V27903883',
        );
        $parametros_vehiculo = array(
            'placa'       => 'KAM666',
            'ip'          => '10.3.130.124',
            'mac'         => 'MAC',
            'ente'        => 'CICPC',
            'usuario'     => 'V27903883',
        );
        $parametros_arma = array(
            'NOSERIALPRIMARIO' => 'E438858',
            'ip'          => '10.3.130.124',
            'mac'         => 'MAC',
            'ente'        => 'CICPC',
            'usuario'     => 'V27903883',
        );

        $tokens = $this->dataservices->validarToken($token);

        $metodo = array(
            'Persona' => $metodo_persona_solicitada,
            'Vehiculo' => $metodo_vehiculo_solicitado,
            'Arma' => $metodo_arma_solicitada
        );
        $request = array(
            'Persona' => $parametros_persona['letracedula'].$parametros_persona['cedpersona'],
            'Vehiculo' => $parametros_vehiculo['placa'],
            'Arma' => $parametros_arma['NOSERIALPRIMARIO']
        );

        if($tokens['response']['Code'] == 407){
            $response = $tokens['response'];
        }else{
            $response_persona_solicitada = $this->dataservices->validarRequest($parametros_persona, $metodo_persona_solicitada);
            $response_vehiculo_solicitado = $this->dataservices->validarRequest($parametros_vehiculo, $metodo_vehiculo_solicitado);
            $response_arma_solicitada = $this->dataservices->validarRequest($parametros_arma, $metodo_arma_solicitada);

            $response = array(
                'Persona Solicitada' => $response_persona_solicitada,
                'Arma Solicitada' => $response_arma_solicitada,
                'Vehiculo Solicitado' => $response_vehiculo_solicitado
            );
        }
        $this->dataservices->GuardarTrazas($parametros_persona['ip'], $parametros_persona['mac'], $parametros_persona['usuario'], $parametros_persona['ente'], $metodo, $response, $request, $token, $tokens['token'][0]['Nombre'], $tokens['token'][0]['Ministerio'], $tokens['token'][0]['Organismo']);

        return response()->json($response);
    }
}
