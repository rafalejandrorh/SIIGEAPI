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

Route::get('rest/core/siipol/consulta/persona/solicitada/{letra_cedula}/{cedula}/{ip}/{mac}/{ente}/{usuario}', [PersonaServicesController::class, 'consultaPersonaSolicitadaSiipol']);
// PersonaSolicitada/V/20677724/10.3.130.124/00:00:00:00/CICPC/V27903883

Route::get('rest/core/siipol/consulta/vehiculo/solicitado/{placa}/{ip}/{mac}/{ente}/{usuario}', [VehiculoServicesController::class, 'consultaVehiculoSolicitadoSiipol']);
// VehiculoSolicitado/KAM666/10.3.130.124/00:00:00:00/CICPC/V27903883 

Route::get('rest/core/siipol/consulta/arma/solicitada/{serialprimario}/{ip}/{mac}/{ente}/{usuario}', [ArmaServicesController::class, 'consultaArmaSolicitadaSiipol']);
// ArmaSolicitada/E438858/10.3.130.124/00:00:00:00/CICPC/V27903883

Route::get('rest/core/siipol/consulta/persona/datos/{letra_cedula}/{cedula}/{ip}/{mac}/{ente}/{usuario}', [PersonaServicesController::class, 'consultaDatosPersonaSaimeSiipol']);
// DatosPersona/V/20677724/10.3.130.124/00:00:00:00/CICPC/V27903883

Route::get('rest/core/siipol/consulta/vehiculo/datos/{placa}/{ip}/{mac}/{ente}/{usuario}', [VehiculoServicesController::class, 'consultaDatosVehiculoINTTSiipol']);
// DatosVehiculo/KAM666/10.3.130.124/00:00:00:00/CICPC/V27903883

Route::get('rest/test/siipol/consulta', [TestServicesController::class, 'Test']);

// Token Desarrollo de Sistemas: 
// Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM5MzkwNDMsImV4cCI6MTY5NTQ3NTA0MywiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.kWalFZBlzCI62njbG9c_Khfyn-NOkXYBhP659H-_N38


