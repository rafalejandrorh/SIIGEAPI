<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Geografia_Venezuela;

class GeografiaVenezuelaController extends Controller
{

    public function getCombos($id, $tipo, $id_hijo)
    {
        // dd($tipo.'-'.$id);
      if($id_hijo == 9865879){
        $arreglo = Geografia_Venezuela::where('id_padre','=',$tipo)->pluck('valor','id')->get();
        foreach ($arreglo as $code => $name) {
            echo "<option value=\"$code\">$name</option>";
        }
      }else{
        $arreglo = Geografia_Venezuela::all()
        ->where('id_hijo','=',$id_hijo)
        ->where('id_padre','=',$tipo)
        ->pluck('valor','id');
        foreach ($arreglo as $code => $name) {
            echo "<option value=\"$code\">$name</option>";
        }  
      }
    } 

    public function getCombo($tipo, $id_hijo)
    {
        // dd($tipo.'-'.$id);
      if($id_hijo == 9865879){
        $arreglo = Geografia_Venezuela::where('id_padre','=',$tipo)->pluck('valor','id')->get();
        foreach ($arreglo as $code => $name) {
            echo "<option value=\"$code\">$name</option>";
        }
      }else{
        $arreglo = Geografia_Venezuela::all()
        ->where('id_hijo','=',$id_hijo)
        ->where('id_padre','=',$tipo)
        ->pluck('valor','id');
        foreach ($arreglo as $code => $name) {
            echo "<option value=\"$code\">$name</option>";
        }  
      }
    } 

}
