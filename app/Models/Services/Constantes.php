<?php

//Timezone (Hora Local)
define('Ubicacion', 'America/Caracas');

// URL de consulta a los Data Services Server San Agustín
define('URL_ESB1', 'http://10.3.142.23:9763/services/');
define('URL_ESB2', 'http://10.3.142.24:9763/services/');

// URL de consulta a los Data Services Server Parque Carabobo
// define('URL_ESB1', 'http://10.1.49.171:9763/services/');
// define('URL_ESB2', 'http://10.1.49.172:9763/services/');

// Consultas WSDL al WebServices (Para concatenar con las URL de consulta)
define('WSDL_PersonaSolicitada', 'consultaPersonaSolicitada?wsdl');
define('WSDL_VehiculoSolicitada', 'consultaVehiculoSolicitado?wsdl');
define('WSDL_ArmaSolicitada', 'consultaArmaSolicitada?wsdl');
define('WSDL_DatosPersonaSAIME', 'consultarDatosPersonaSaime?wsdl');
define('WSDL_DatosVehiculoINTT', 'consultaPlacasVehiculos?wsdl');
define('WSDL', 'wsdl');

// Métodos del Servicio Disponibles para consultar
define('VehiculoSolicitado', 'ConsultarVehiculoSolicitado');
define('PersonaSolicitada', 'consultarPersonaSolicitada');
define('ArmaSolicitada', 'consultaArmaSolicitada');
define('DatosPersonaSAIME', 'consultarDatosPersonaSaime');
define('DatosVehiculoINTT', 'consultaplacasvehiculosQuery');

/////// Responses de API ///////

// Ok (Se realiza la consulta)
define('OK_CODE_SERVICE', 200);
define('OK_DESCRIPTION_SERVICE', 'Service Ok'); 

// Nok (Error en el servicio consultado)
define('ERROR_CODE_SERVICE', 404);
define('ERROR_DESCRIPTION_SERVICE', 'Service Nok');

// Nok (Error en el servicio consultado)
define('ERROR_CODE_REQUEST', 405);
define('ERROR_DESCRIPTION_REQUEST', 'Request Nok');

// Ok (Token Ok)
define('OK_CODE_TOKEN', 202);
define('OK_DESCRIPTION_TOKEN', 'Token Ok');

// Nok (Error por Token sin Bearer)
define('ERROR_CODE_NO_TOKEN_BEARER', 405);
define('ERROR_DESCRIPTION_NO_TOKEN_BEARER', 'No Token Bearer');

// Nok (Error por Token Expirado)
define('ERROR_CODE_TOKEN_EXPIRE', 406);
define('ERROR_DESCRIPTION_TOKEN_EXPIRE', 'Token Expire');

// Nok (Error por Token Incorrecto)
define('ERROR_CODE_TOKEN', 407);
define('ERROR_DESCRIPTION_TOKEN', 'Token Nok');

// Nok (Error por no Colocar Token)
define('ERROR_CODE_NO_TOKEN', 408);
define('ERROR_DESCRIPTION_NO_TOKEN', 'No Token');

// Nok (Token Inactivo)
define('ERROR_CODE_INACTIVE_TOKEN', 409);
define('ERROR_DESCRIPTION_INACTIVE_TOKEN', 'Inactive Token');

// Nok (Solicitud Inválida)
define('ERROR_CODE_INVALID_REQUEST', 410);
define('ERROR_DESCRIPTION_INVALID_REQUEST', 'Invalid Request');

// Nok (Servicio Inactivo)
define('ERROR_CODE_INACTIVE_SERVICE', 411);
define('ERROR_DESCRIPTION_INACTIVE_SERVICE', 'Inactive Service');

// Nok (Acción no permitida en el servicio)
define('ERROR_UNAUTHORIZED_ACTION', 500);
define('ERROR_DESCRIPTION_UNAUTHORIZED_ACTION', 'Unauthorized Action');

// Nok (Solicitud No Autorizada)
define('ERROR_CODE_UNAUTHORIZED_SERVICE', 501);
define('ERROR_DESCRIPTION_UNAUTHORIZED_SERVICE', 'Unauthorized Service');

/////// Tokens ///////

// Token del Ministerio de la Defensa
define('Token', 'PLIbWwxXce3VpvBT_O977da8XECEpJTJt');
define('Fecha_vencimiento_Token', '2022-10-09');

?>