<?php

namespace App\Http\Controllers;

use App\Models\Users_Questions;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Models\Traza_User;

class UsersQuestionsController extends Controller
{
    public function index(Request $request)
    {
        $question = Users_Questions::join('questions', 'questions.id', '=', 'users_questions.id_questions')
        ->where('id_users', '=', $request->id_user)->select('questions.question', 'users_questions.response', 'users_questions.id')
        ->orderByRaw("random()")->limit(1)->get();

        return view('auth.login_questions', compact('question'));
    }

    public function validation(Request $request)
    {
        $id_user = Auth::user()->id;
        $password_status = Auth::user()->password_status;
        $validation_question = Users_Questions::Where('id', $request->id_question)->get();

        if($validation_question[0]['response'] == $request->question)
        {
            if($password_status)
            {
                Alert()->warning('Atención', 'Por Razones de Seguridad, debe cambiar su contraseña.');
                return redirect()->route('sesion.index', compact('password_status'));
            }else{
                Alert()->toast('Inicio de Sesión Exitoso','success');
                return redirect()->route('home');
            }
            
        }else{
            $id_logout = 3;
            
            app(UserController::class)->update_status($id_user);
            return redirect()->route('logout', [$id_logout]);
        }
    }

    public function store(Request $request)
    {
        $questions = array(
            0 => array(
                'question' => $request->question1,
                'response' => $request->response1,
                'padre' => 10000
            ),
            1 => array(
                'question' => $request->question2,
                'response' => $request->response2,
                'padre' => 20000
            ),
            2 => array(
                'question' => $request->question3,
                'response' => $request->response3,
                'padre' => 30000
            )
        );

        $i = 0;
        while($i<count($questions))
        {
            Users_Questions::create([
                'id_users' => $request->id_user,
                'id_questions' => $questions[$i]['question'],
                'response' => $questions[$i]['response'],
                'id_padre' => $questions[$i]['padre']
            ]);
            $i++;
        }

        $id_Accion = 1; //Registro
        $trazas = Traza_User::create(['id_user' => $request->id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Se crearon las preguntas de Seguridad de este Usuario']);
        
        Alert()->success('Preguntas de Seguridad Creadas Satisfactoriamente');
        return redirect()->route('login');
    }

    public function update(Request $request, $id)
    {
        $persona = User::where('id', '=', $id)->first();
        $validacion_password = Hash::check(request('password'), $persona->password);
        if($validacion_password == true)
        {
            $questions = array(
                0 => array(
                    'question' => $request->question1,
                    'response' => $request->response1,
                    'padre'    => $request->padre1
                ),
                1 => array(
                    'question' => $request->question2,
                    'response' => $request->response2,
                    'padre'    => $request->padre2
                ),
                2 => array(
                    'question' => $request->question3,
                    'response' => $request->response3,
                    'padre'    => $request->padre3
                )
            );
    
            $i = 0;
            while($i<count($questions))
            {
                $user_question = Users_Questions::Where('id_users', $id)->Where('id_padre', $questions[$i]['padre']);
                $user_question->update([
                    'id_users' => $id,
                    'id_questions' => $questions[$i]['question'],
                    'response' => $questions[$i]['response'],
                ]);
                $i++;
            }
    
            $id_Accion = 2; //Actualización
            $trazas = Traza_User::create(['id_user' => $id, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Se actualizaron las preguntas de Seguridad de este Usuario']);

            Alert()->success('Preguntas de Seguridad Actualizadas Satisfactoriamente');
            return back();
        }else{
            Alert()->error('La Contraseña Actual indicada no coincide con nuestros registros.');
            return back();
        }
    }

    public function destroy($id)
    {
        $users_questions = Users_Questions::Where('id_users', $id);
        $users_questions->delete();

        $id_Accion = 3; //Eliminación
        $trazas = Traza_User::create(['id_user' => $id, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Se eliminaron las preguntas de Seguridad de este Usuario']);
        
        Alert()->success('Preguntas de Seguridad Eliminadas Satisfactoriamente');
        return back();
    }
}
