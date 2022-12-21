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
        $this->consultarVehiculoSolicitado = URL_ESB1.WSDL_consultarVehiculoSolicitado;
        $this->consultarPersonaSolicitada = URL_ESB1.WSDL_consultarPersonaSolicitada;
        $this->consultarDatosVehiculo = URL_ESB1.WSDL_consultarDatosVehiculoINTT;
        $this->consultarDatosPersona = URL_ESB1.WSDL_consultarDatosPersonaSAIME;
        $this->consultarArmaSolicitada = URL_ESB1.WSDL_consultarArmaSolicitada;
        $this->UsuarioInterno = URL_ESB1.WSDL_consultarDatosUsuarioInterno;
        $this->UsuarioExterno = URL_ESB1.WSDL_consultarDatosUsuarioExterno;
        $this->actualizarContrasennaUsuario = URL_ESB1.WSDL_actualizarContrasennaUsuario;
    }

    public function Servicios(){
        if($this->metodo == consultarPersonaSolicitada){
            $cliente = new nusoap_client($this->consultarPersonaSolicitada, WSDL);
        }else if($this->metodo == consultarVehiculoSolicitado){
            $cliente = new nusoap_client($this->consultarVehiculoSolicitado, WSDL);
        }else if($this->metodo == consultarArmaSolicitada){
            $cliente = new nusoap_client($this->consultarArmaSolicitada, WSDL);
        }else if($this->metodo == consultarDatosPersonaSAIME){
            $cliente = new nusoap_client($this->consultarDatosPersona, WSDL);
        }else if($this->metodo == consultarDatosVehiculoINTT){
            $cliente = new nusoap_client($this->consultarDatosVehiculo, WSDL);
        }else if($this->metodo == consultarDatosUsuarioInterno){
            $cliente = new nusoap_client($this->UsuarioInterno, WSDL);
        }else if($this->metodo == consultarDatosUsuarioExterno){
            $cliente = new nusoap_client($this->UsuarioExterno, WSDL);
        }else if($this->metodo == actualizarContrasennaUsuario){
            $cliente = new nusoap_client($this->actualizarContrasennaUsuario, WSDL);
        }
        $result = $cliente->call("$this->metodo", $this->parametros); 
        return $result;
    }

}
