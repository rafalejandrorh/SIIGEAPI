<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Historial_Sesion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use Alert;
use App\Models\Questions;
use App\Models\User;
use App\Models\Users_Questions;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'users';
    }

    public function status()
    {
        return 'status';
    }

    public function index()
    {
        //request:: root o url para ruta de aplicación

        //$QR = QrCode::size(80)->generate($url);
        return view('auth.login');
    }

    ///////////////////////////////////////////////////////////////////////

    public function login(Request $request)
    {
        $validacion_user = User::Where('users', $request->users)->exists();
        if($validacion_user == true)
        {
            $users = User::Where('users', '=', $request->users)->get();
            $id_user = $users[0]['id'];
            $password = $users[0]['password'];

            $validacion_password = Hash::check(request('password'), $password);
            if($validacion_password == true)
            {
                $validar_question = Users_Questions::where('id_users', '=', $id_user)->exists();

                if($validar_question == true)
                {

                    $this->validateLogin($request);

                    // If the class is using the ThrottlesLogins trait, we can automatically throttle
                    // the login attempts for this application. We'll key this by the username and
                    // the IP address of the client making these requests into this application.
                    if (method_exists($this, 'hasTooManyLoginAttempts') &&
                        $this->hasTooManyLoginAttempts($request)) {
                        $this->fireLockoutEvent($request);
        
                        return $this->sendLockoutResponse($request);
                    }
        
                    if ($this->attemptLogin($request)) {
                        if ($request->hasSession()) {
                            $request->session()->put('auth.password_confirmed_at', time());
                        }

                        return $this->sendLoginResponse($request, $id_user);
                    }
        
                    // If the login attempt was unsuccessful we will increment the number of attempts
                    // to login and redirect the user back to the login form. Of course, when this
                    // user surpasses their maximum number of attempts they will get locked out.
                    $this->incrementLoginAttempts($request);
        
                    return $this->sendFailedLoginResponse($request);
                }else{
                    $questions = new Questions();
                    $question1 = $questions->Where('id_padre', 10000)->pluck('question', 'id')->all();
                    $question2 = $questions->Where('id_padre', 20000)->pluck('question', 'id')->all();
                    $question3 = $questions->Where('id_padre', 30000)->pluck('question', 'id')->all();
            
                    return view('auth.create_login_questions', compact('id_user', 'question1', 'question2', 'question3'));
                }
            }else{
                Alert()->warning('Contraseña Incorrecta');
                return back();
            }
        }else{
            Alert()->warning('Usuario Incorrecto');
            return back();
        }
    }

    public function credentials(Request $request)
    {
        $credenciales = $request->only($this->username(), 'password');
        $credenciales = Arr::add($credenciales, 'status', 'true');
        return $credenciales; 
    }

    public function sendLoginResponse(Request $request, $id_user)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        $question = Users_Questions::join('questions', 'questions.id', '=', 'users_questions.id_questions')
        ->where('id_users', '=', $id_user)->select('questions.question', 'users_questions.response', 'users_questions.id')
        ->orderByRaw("random()")->limit(1)->get();
        return view('auth.login_questions', compact('question'));
    }

    public function authenticated(Request $request, $user)
    {
        if(session('id_historial_session') != null)
        {
            $sesion = Historial_Sesion::find(session('id_historial_sesion'), ['id']);
            $sesion->logout = now();
            $sesion->save();
            session()->forget('id_historial_sesion');
        };

        $explode = explode(' ', exec('getmac'));
        $MAC = $explode[0];

        $sesion = new Historial_Sesion();
        $sesion->id_user = $user->id;
        $sesion->login = now();
        $sesion->MAC = $MAC;
        //$sesion->IP = $request->ip();
        $sesion->save();
        $id_historial_sesion = $sesion->id;
        session(['id_historial_sesion' => $id_historial_sesion]);
    }

    public function logout(Request $request)
    {
        $id_logout = $request->id;
        $sesion = Historial_Sesion::find(session('id_historial_sesion'), ['id']);
        $sesion->logout = now();
        $sesion->tipo_logout = $request->id;
        $sesion->save();
        session()->forget('id_historial_sesion');
        
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        if($request->id == 1){
            Alert()->toast('Haz cerrado sesión en el Sistema','info');
        }else if($request->id == 2){
            Alert()->toast('Cierre de Sesión por período de Inactividad','info');
        }else if($request->id == 3){
            Alert()->warning('Se ha bloqueado tu Usuario', 'Respuesta Incorrecta a tu pregunta de Seguridad');
        }
        return $request->wantsJson() ? new JsonResponse([], 204) : redirect('/');
    }

}
