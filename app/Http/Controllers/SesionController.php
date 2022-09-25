<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Alert;
use App\Models\Questions;
use App\Models\Users_Questions;

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
        $questions = new Questions();
        $question1 = $questions->Where('id_padre', 10000)->pluck('question', 'id')->all();
        $question2 = $questions->Where('id_padre', 20000)->pluck('question', 'id')->all();
        $question3 = $questions->Where('id_padre', 30000)->pluck('question', 'id')->all();

        $user_questions = new Users_Questions();
        $padre1 = $user_questions->Where('id_padre', 10000)->Where('id_users', $data)->select('id_padre')->get();
        $padre2 = $user_questions->Where('id_padre', 20000)->Where('id_users', $data)->select('id_padre')->get();
        $padre3 = $user_questions->Where('id_padre', 30000)->Where('id_users', $data)->select('id_padre')->get();

        return view('sesion.index', compact('user', 'data', 'question1', 'question2', 'question3', 'padre1', 'padre2', 'padre3'));
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
