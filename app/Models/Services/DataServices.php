<?php

namespace App\Models\Services;

require_once('Constantes.php');

use Illuminate\Database\Eloquent\Model;
use nusoap_client;

class DataServices extends Model
{
    private $metodo;
    private $parametros = array();

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
        $this->vehiculoSolicitado = URL_ESB1.WSDL_VehiculoSolicitada;
        $this->personaSolicitada = URL_ESB1.WSDL_PersonaSolicitada;
        $this->armaSolicitada = URL_ESB1.WSDL_ArmaSolicitada;
        $this->datosvehiculo = URL_ESB1;
        $this->datospersona = URL_ESB1;
    }

    public function Servicios() {
        if($this->metodo == VehiculoSolicitado){
            $cliente = new nusoap_client($this->personaSolicitada, WSDL);
        }else if($this->metodo == PersonaSolicitada){
            $cliente = new nusoap_client($this->vehiculoSolicitado, WSDL);
        }else if($this->metodo == ArmaSolicitada){
            $cliente = new nusoap_client($this->armaSolicitada, WSDL);
        }else if($this->metodo == ''){
            $cliente = new nusoap_client($this->datospersona, WSDL);
        }else if($this->metodo == ''){
            $cliente = new nusoap_client($this->datosvehiculo, WSDL);
        }
        $result = $cliente->call("$this->metodo", $this->parametros); 
        return $result;
    }

}