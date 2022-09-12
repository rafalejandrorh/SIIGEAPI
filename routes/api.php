<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataServicesController;

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

Route::get('/TestPersonaSolicitada', [DataServicesController::class, 'TestPersonaSolicitada']);

Route::get('/TestVehiculoSolicitado', [DataServicesController::class, 'TestVehiculoSolicitado']);

Route::get('/TestArmaSolicitada', [DataServicesController::class, 'TestArmaSolicitada']);

Route::get('/PersonaSolicitada/{letra_cedula}/{cedula}/{ip}/{mac}/{ente}/{usuario}/{token}', [DataServicesController::class, 'ServicioPersonaSolicitada']);
// V/20677724/10.3.130.124/00:00:00:00/CICPC/V27903883/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjI2ODE5NjYsImV4cCI6MTY2MzExMzk2NiwiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.2kPvoVm_nGook34f1f-yE3vYfSZQ5ROnaidLFQgnNiI

Route::get('/VehiculoSolicitado/{placa}/{ip}/{mac}/{ente}/{usuario}/{token}', [DataServicesController::class, 'ServicioVehiculoSolicitado']);
// E438858/10.3.130.124/00:00:00:00/CICPC/V27903883/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjI2ODE5NjYsImV4cCI6MTY2MzExMzk2NiwiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.2kPvoVm_nGook34f1f-yE3vYfSZQ5ROnaidLFQgnNiI

Route::get('/ArmaSolicitada/{serialprimario}/{ip}/{mac}/{ente}/{usuario}/{token}', [DataServicesController::class, 'ServicioArmaSolicitada']);
// KAM666/10.3.130.124/00:00:00:00/CICPC/V27903883/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjI2ODE5NjYsImV4cCI6MTY2MzExMzk2NiwiZGF0YSI6eyJpZF9kZXBlbmRlbmNpYSI6MX19.2kPvoVm_nGook34f1f-yE3vYfSZQ5ROnaidLFQgnNiI
