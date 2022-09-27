<?php

//Nombre Otorgado a la API según la institución que lo consumirá
define('Titulo', 'API para Consumo del Ministerio de la Defensa');

//Timezone (Hora Local)
define('Ubicacion', 'America/Caracas');

//Conexión a la Base de Datos de Autenticación de la API
define('DB_HOST_API', '127.0.0.1');
define('DB_USER_API', 'postgres');
define('DB_PASSWORD_API', 'cicpc.2021');
define('DB_NAMEDB_API', 'SIIGEAPI');
define('DB_TYPE_API', 'pgsql');
define('DB_PORT_API', '5432');

// URL de consulta a los Data Services Server
define('CONSULTA_VEHICULOS', 'http://10.1.49.171:9763/services/consultaVehiculoSolicitado?wsdl');
define('CONSULTA_PERSONAS', 'http://10.1.49.171:9763/services/consultaPersonaSolicitada?wsdl');
define('CONSULTA_ARMAS', 'http://10.1.49.171:9763/services/consultaArmaSolicitada?wsdl');
define('WSDL', 'wsdl');

// Servicios Disponibles para consultar
define('VehiculoSolicitado', 'ConsultarVehiculoSolicitado');
define('PersonaSolicitada', 'consultarPersonaSolicitada');
define('ArmaSolicitada', 'consultaArmaSolicitada');

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

// Nok (Error por Token Expirado)
define('ERROR_CODE_TOKEN_EXPIRE', 406);
define('ERROR_DESCRIPTION_TOKEN_EXPIRE', 'Token Expire');

// Nok (Error por Token Incorrecto)
define('ERROR_CODE_TOKEN', 407);
define('ERROR_DESCRIPTION_TOKEN', 'Token Nok');

// Nok (Error por no Colocar Token)
define('ERROR_NO_TOKEN', 408);
define('ERROR_DESCRIPTION_NO_TOKEN', 'No Token');

// Nok (Token Inactivo)
define('ERROR_CODE_INACTIVE_TOKEN', 409);
define('ERROR_DESCRIPTION_INACTIVE_TOKEN', 'Inactive Token');

// Nok (Solicitud Inválida)
define('ERROR_CODE_INVALID_REQUEST', 410);
define('ERROR_DESCRIPTION_INVALID_REQUEST', 'Invalid Request');

// Nok (Acción no permitida en el servicio)
define('ERROR_UNAUTHORIZED_ACTION', 500);
define('ERROR_DESCRIPTION_UNAUTHORIZED_ACTION', 'Unauthorized');

/////// Tokens ///////

// Token del Ministerio de la Defensa
define('Token', 'PLIbWwxXce3VpvBT_O977da8XECEpJTJt');
define('Fecha_vencimiento_Token', '2022-10-09');

?>