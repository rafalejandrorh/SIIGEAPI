<?php

use App\Http\Controllers\Services\ArmaServicesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Services\PersonaServicesController;
use App\Http\Controllers\Services\TestServicesController;
use App\Http\Controllers\Services\VehiculoServicesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/Test', [TestServicesController::class, 'Test']);

Route::get('/PersonaSolicitada/{letra_cedula}/{cedula}/{ip}/{mac}/{ente}/{usuario}/{token}', [PersonaServicesController::class, 'ServicioPersonaSolicitada']);
// PersonaSolicitada/V/20677724/10.3.130.124/00:00:00:00/CICPC/V27903883/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM5MzkwNDMsImV4cCI6MTY5NTQ3NTA0MywiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.kWalFZBlzCI62njbG9c_Khfyn-NOkXYBhP659H-_N38

Route::get('/VehiculoSolicitado/{placa}/{ip}/{mac}/{ente}/{usuario}/{token}', [VehiculoServicesController::class, 'ServicioVehiculoSolicitado']);
// VehiculoSolicitado/E438858/10.3.130.124/00:00:00:00/CICPC/V27903883/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM5MzkwNDMsImV4cCI6MTY5NTQ3NTA0MywiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.kWalFZBlzCI62njbG9c_Khfyn-NOkXYBhP659H-_N38

Route::get('/ArmaSolicitada/{serialprimario}/{ip}/{mac}/{ente}/{usuario}/{token}', [ArmaServicesController::class, 'ServicioArmaSolicitada']);
// ArmaSolicitada/KAM666/10.3.130.124/00:00:00:00/CICPC/V27903883/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM5MzkwNDMsImV4cCI6MTY5NTQ3NTA0MywiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.kWalFZBlzCI62njbG9c_Khfyn-NOkXYBhP659H-_N38

Route::get('/DatosPersona/{letra_cedula}/{cedula}/{ip}/{mac}/{ente}/{usuario}/{token}', [PersonaServicesController::class, 'ServicioDatosPersonaSaime']);
// DatosPersona/KAM666/10.3.130.124/00:00:00:00/CICPC/V27903883/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM5MzkwNDMsImV4cCI6MTY5NTQ3NTA0MywiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.kWalFZBlzCI62njbG9c_Khfyn-NOkXYBhP659H-_N38

Route::get('/DatosVehiculo/{placa}/{ip}/{mac}/{ente}/{usuario}/{token}', [VehiculoServicesController::class, 'ServicioDatosVehiculoINTT']);
// DatosVehiculo/E438858/10.3.130.124/00:00:00:00/CICPC/V27903883/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM5MzkwNDMsImV4cCI6MTY5NTQ3NTA0MywiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.kWalFZBlzCI62njbG9c_Khfyn-NOkXYBhP659H-_N38