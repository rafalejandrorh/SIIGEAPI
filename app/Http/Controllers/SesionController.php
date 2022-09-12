<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Alert;


class SesionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data = Auth::user()->id;
        $user = User::Where('id', $data)->get();
        return view('sesion.index', compact('user', 'data'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $persona = User::where('id', '=', $id)->first();
        $validacion_password = Hash::check(request('curr_password'), $persona->password);
        if($validacion_password == true)
        {
            $validacion_password_new = Hash::check(request('password'), $persona->password);
            if($validacion_password_new == false)
            {
                $request['password'] = bcrypt($request['password']);
                $user = User::find($id, ['id']);
                $user->update(['password' => $request['password']]);
                Alert()->success('Cambio de Contraseña Exitoso');
                return back();
            }else{
                Alert()->warning('Lo sentimos', 'La nueva Contraseña coincide con la Actual. Por favor, inserta una Contraseña distinta.');
                return back();
            }
            

        }else{
            Alert()->error('La Contraseña Actual indicada no coincide con nuestros registros.');
            return back()->with('error', 'Ok');
        }

    }

}
