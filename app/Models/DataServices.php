<?php

namespace App\Models;

require_once('Constantes.php');

use Illuminate\Database\Eloquent\Model;
use nusoap_client;

class DataServices extends Model
{
    private $wsdl;
    private $metodo;
    private $parametros = array();

    public function getWsdl(){
        return $this->wsdl;
    }

    public function setWsdl($wsdl){
        
        $this->wsdl = $wsdl;
        return $this;
    }

	public function getMetodo() {
        return $this->metodo;
    }
    
    public function setMetodo($metodo) {
        $this->metodo = $metodo;
        return $this;
    }

    public function getParametros() {
        return $this->parametros;
    }
    
    public function setParametros($parametros) {
        $this->parametros = $parametros;
        return $this;
    }

    function __construct()
    {
        $this->wsdl = 'http://10.1.49.171:9763/services/consultaVehiculoSolicitado?wsdl';
        $this->wsdl2 = 'http://10.1.49.171:9763/services/consultaPersonaSolicitada?wsdl';
        $this->wsdl3 = 'http://10.1.49.171:9763/services/consultaArmaSolicitada?wsdl';
    }

    public function okCodeService($servicio, $datos)
    {
        $response = [
            'Code' => OK_CODE_SERVICE,
            'Status' => OK_DESCRIPTION_SERVICE,
            'Services' => $servicio,
            'Data' => $datos
        ];
        return $response;
    }

    public function errorCodeService($servicio)
    {
        $response = [
            'Code' => ERROR_CODE_SERVICE,
            'Status' => ERROR_DESCRIPTION_SERVICE,
            'Services' => $servicio,
            'Description' => 'El Servicio  que intenta consultar no existe'
        ];
        return $response;
    }

    public function errorCodeRequest($servicio, $data)
    {
        $response = [
            'Code' => ERROR_CODE_REQUEST,
            'Status' => ERROR_DESCRIPTION_REQUEST,
            'Services' => $servicio,
            'Request' => $data
        ];
        return $response;
    }

    public function errorCodeToken()
    {
        $response = [
            'Code' => ERROR_CODE_TOKEN,
            'Status' => ERROR_DESCRIPTION_TOKEN,
        ];
        return $response;
    }

    public function errorCodeTokenExpire()
    {
        $response = [
            'Code' => ERROR_CODE_TOKEN_EXPIRE,
            'Status' => ERROR_DESCRIPTION_TOKEN_EXPIRE,
        ];
        return $response;
    }

    public function errorNoToken()
    {
        $response = [
            'Code' => ERROR_NO_TOKEN,
            'Status' => ERROR_DESCRIPTION_NO_TOKEN,
        ];
        return $response;
    }

    public function errorCodeInactiveToken()
    {
        $response = [
            'Code' => ERROR_CODE_INACTIVE_TOKEN,
            'Status' => ERROR_DESCRIPTION_INACTIVE_TOKEN,
        ];
        return $response;  
    }

    public function errorUnauthorizedAction()
    {
        $response = [
            'Code' => ERROR_UNAUTHORIZED_ACTION,
            'Status' => ERROR_DESCRIPTION_UNAUTHORIZED_ACTION,
            'Description' => 'La Accion que pretende realizar no se encuentra permitida en este servicio. El incidente sera reportado.'
        ];
        return $response;
    }

    public function okWelcome()
    {
        $response = [
            'Code' => OK_CODE_SERVICE,
            'Status' => OK_DESCRIPTION_SERVICE,
            'Description' => 'Revisa la Documentacion para utilizar el Servicio.'
        ];
        return $response;
    }

    public function VehiculoSolicitado() {
		$cliente = new nusoap_client($this->wsdl, 'wsdl');
		$result  = $cliente->call("$this->metodo", $this->parametros);
		return $result;
	}

    public function PersonaSolicitada() {
        $cliente = new nusoap_client($this->wsdl2, 'wsdl');
        $result = $cliente->call("$this->metodo", $this->parametros); 
        return $result;
    }

    public function ArmaSolicitada() {
        $cliente = new nusoap_client($this->wsdl3, 'wsdl');
        $result = $cliente->call("$this->metodo", $this->parametros); 
        return $result;
    }
}