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

Route::get('/PersonaSolicitada/{letra_cedula}/{cedula}/{ip}/{mac}/{ente}/{usuario}', [PersonaServicesController::class, 'ServicioPersonaSolicitada']);
// PersonaSolicitada/V/20677724/10.3.130.124/00:00:00:00/CICPC/V27903883

Route::get('/VehiculoSolicitado/{placa}/{ip}/{mac}/{ente}/{usuario}', [VehiculoServicesController::class, 'ServicioVehiculoSolicitado']);
// VehiculoSolicitado/KAM666/10.3.130.124/00:00:00:00/CICPC/V27903883 

Route::get('/ArmaSolicitada/{serialprimario}/{ip}/{mac}/{ente}/{usuario}', [ArmaServicesController::class, 'ServicioArmaSolicitada']);
// ArmaSolicitada/E438858/10.3.130.124/00:00:00:00/CICPC/V27903883

Route::get('/DatosPersona/{letra_cedula}/{cedula}/{ip}/{mac}/{ente}/{usuario}', [PersonaServicesController::class, 'ServicioDa tosPersonaSaime']);
// DatosPersona/V/20677724/10.3.130.124/00:00:00:00/CICPC/V27903883

Route::get('/DatosVehiculo/{placa}/{ip}/{mac}/{ente}/{usuario}', [VehiculoServicesController::class, 'ServicioDatosVehiculoINTT']);
// DatosVehiculo/KAM666/10.3.130.124/00:00:00:00/CICPC/V27903883

Route::get('/Test', [TestServicesController::class, 'Test']);

// Token Desarrollo de Sistemas: 
// Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM5MzkwNDMsImV4cCI6MTY5NTQ3NTA0MywiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.kWalFZBlzCI62njbG9c_Khfyn-NOkXYBhP659H-_N38


