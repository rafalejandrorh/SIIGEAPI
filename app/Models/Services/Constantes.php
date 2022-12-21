<?php

//Timezone (Hora Local)
define('Ubicacion', 'America/Caracas');

// URL de consulta a los Data Services Server de San Agustín
define('URL_ESB1', 'http://10.3.142.23:9763/services/');
define('URL_ESB2', 'http://10.3.142.24:9763/services/');

// Consultas WSDL al WebServices (Para concatenar con las URL de consulta)
define('WSDL_consultarPersonaSolicitada', 'consultaPersonaSolicitada?wsdl');
define('WSDL_consultarVehiculoSolicitado', 'consultaVehiculoSolicitado?wsdl');
define('WSDL_consultarArmaSolicitada', 'consultaArmaSolicitada?wsdl');
define('WSDL_consultarDatosPersonaSAIME', 'consultarDatosPersonaSaime?wsdl');
define('WSDL_consultarDatosVehiculoINTT', 'consultaPlacasVehiculos?wsdl');
define('WSDL_consultarDatosUsuarioInterno', 'ConsultaDatosUsuario?wsdl');
define('WSDL_consultarDatosUsuarioExterno', 'ConsultaDatosUsuarioExterno?wsdl');
define('WSDL_actualizarContrasennaUsuario', 'ActualizarContrasennaUsuario?wsdl'); //POR DEFINIR
define('WSDL', 'wsdl');

// Métodos del Servicio Disponibles para consultar
define('consultarVehiculoSolicitado', 'ConsultarVehiculoSolicitado');
define('consultarPersonaSolicitada', 'consultarPersonaSolicitada');
define('consultarArmaSolicitada', 'consultaArmaSolicitada');
define('consultarDatosPersonaSAIME', 'consultarDatosPersonaSaime');
define('consultarDatosVehiculoINTT', 'consultaplacasvehiculosQuery');
define('consultarDatosUsuarioInterno', 'ConsultaDatosUsuario');
define('consultarDatosUsuarioExterno', 'ConsultaDatosUsuarioExterno');
define('actualizarContrasennaUsuario', 'actualizarContrasennaUsuario');

/////// Responses de API ///////

// Ok (Se realiza la consulta)
define('OK_CODE_SERVICE', '500');
define('OK_DESCRIPTION_SERVICE', 'Service Ok'); 

// Ok (Token Ok)
define('OK_CODE_TOKEN', '502');
define('OK_DESCRIPTION_TOKEN', 'Token Ok');

// Nok (Error en la solicitud enviada)
define('ERROR_CODE_BAD_REQUEST', '600');
define('ERROR_DESCRIPTION_BAD_REQUEST', 'Bad Request');

// Nok (Solicitud No Autorizada)
define('ERROR_CODE_UNAUTHORIZED_SERVICE', '601');
define('ERROR_DESCRIPTION_UNAUTHORIZED_SERVICE', 'Unauthorized Service');

// Nok (Error en la solicitud al servicio)
define('ERROR_CODE_REQUEST', '602');
define('ERROR_DESCRIPTION_REQUEST', 'Request Nok');

// Nok (Error en el servicio consultado)
define('ERROR_CODE_SERVICE', '603');
define('ERROR_DESCRIPTION_SERVICE', 'Service Nok');

// Nok (Acción no permitida en el servicio)
define('ERROR_UNAUTHORIZED_ACTION', '604');
define('ERROR_DESCRIPTION_UNAUTHORIZED_ACTION', 'Unauthorized Action');

// Nok (Error por Token sin Bearer)
define('ERROR_CODE_NO_TOKEN_BEARER', '605');
define('ERROR_DESCRIPTION_NO_TOKEN_BEARER', 'No Token Bearer');

// Nok (Error por Token Expirado)
define('ERROR_CODE_TOKEN_EXPIRE', '606');
define('ERROR_DESCRIPTION_TOKEN_EXPIRE', 'Token Expire');

// Nok (Error por Token Incorrecto)
define('ERROR_CODE_TOKEN', '607');
define('ERROR_DESCRIPTION_TOKEN', 'Token Nok');

// Nok (Error por no Colocar Token)
define('ERROR_CODE_NO_TOKEN', '608');
define('ERROR_DESCRIPTION_NO_TOKEN', 'No Token');

// Nok (Error por Token Inactivo)
define('ERROR_CODE_INACTIVE_TOKEN', '609');
define('ERROR_DESCRIPTION_INACTIVE_TOKEN', 'Inactive Token');

// Nok (Servicio Inactivo)
define('ERROR_CODE_INACTIVE_SERVICE', '610');
define('ERROR_DESCRIPTION_INACTIVE_SERVICE', 'Inactive Service');

/////// Tokens ///////

// Token del Ministerio de la Defensa
define('Token', 'PLIbWwxXce3VpvBT_O977da8XECEpJTJt');
define('Fecha_vencimiento_Token', '2022-10-09');

?>
