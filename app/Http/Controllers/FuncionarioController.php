<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Genero;
use App\Models\Geografia_Venezuela;
use App\Models\Jerarquia;
use App\Models\Estatus_Funcionario;
use App\Models\Person;
use App\Models\Funcionario;
use Alert;
use App\Models\Traza_Funcionarios;

class FuncionarioController extends Controller
{
    function __construct()
    {
        $this->middleware('can:funcionarios.index')->only('index');
        $this->middleware('can:funcionarios.create')->only('create');
        $this->middleware('can:funcionarios.show')->only('show');
        $this->middleware('can:funcionarios.edit')->only('edit', 'update');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->all();

        if($request->tipo_busqueda == 'cedula'){
            $funcionarios = Funcionario::join('persons', 'persons.id', '=', 'funcionarios.id_person')
            ->select('funcionarios.id', 'funcionarios.id_jerarquia', 'funcionarios.id_estatus', 'funcionarios.credencial',
            'funcionarios.id_person')
            ->Where('persons.cedula', '=', $request->buscador)->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Funcionarios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'credencial'){
            $funcionarios = Funcionario::Where('funcionarios.credencial', '=', $request->buscador)
            ->select('funcionarios.id', 'funcionarios.id_jerarquia', 'funcionarios.id_estatus', 'funcionarios.credencial',
            'funcionarios.id_person')
            ->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Funcionarios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'jerarquia'){
            $funcionarios = Funcionario::join('jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
            ->select('funcionarios.id', 'funcionarios.id_jerarquia', 'funcionarios.id_estatus', 'funcionarios.credencial',
            'funcionarios.id_person')
            ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Funcionarios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'estatus'){
            if($request->buscador == 'activo' || $request->buscador == 'Activo' || $request->buscador == 'ACTIVO'){
                $status = true;
            }else if($request->buscador == 'inactivo' || $request->buscador == 'Inactivo' || $request->buscador == 'INACTIVO'){
                $status = false;
            }
            $funcionarios = Funcionario::Where('status', '=', $status)->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Funcionarios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'nombre'){
            $funcionarios = Funcionario::join('persons', 'persons.id', '=', 'funcionarios.id_person')
            ->select('funcionarios.id', 'funcionarios.id_jerarquia', 'funcionarios.id_estatus', 'funcionarios.credencial',
            'funcionarios.id_person')
            ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Funcionarios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'apellido'){
            $funcionarios = Funcionario::join('persons', 'persons.id', '=', 'funcionarios.id_person')
            ->select('funcionarios.id', 'funcionarios.id_jerarquia', 'funcionarios.id_estatus', 'funcionarios.credencial',
            'persons.telefono', 'funcionarios.id_person')
            ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Funcionarios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else{
            $funcionarios = Funcionario::paginate(10);
        }
        
        return view('funcionarios.index', ['funcionarios' => $funcionarios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $genero = Genero::pluck('valor', 'id')->all();
        $jerarquia = Jerarquia::pluck('valor', 'id')->all();
        $estatus = Estatus_Funcionario::pluck('valor', 'id')->all();
        return view('funcionarios.create', compact('genero', 'jerarquia', 'estatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $request->all();

        $validacion = Validator::make($request,Funcionario::returnValidations(),Funcionario::returnMessages())->validate();

        $person = new Person();
        $funcionario = new Funcionario();

        $cedula = $request['cedula'];
        $obtener_persona = $person->where('cedula','=',$cedula)->get();
        $validar_persona = $person->where('cedula','=',$cedula)->exists();
        if($validar_persona == true){
            $obtener_funcionario = $funcionario->where('id_person','=',$obtener_persona[0]['id'])->get();
            $validar_funcionario = $funcionario->where('id_person','=',$obtener_persona[0]['id'])->exists();
            if($validar_funcionario == true){
                    Alert()->warning('El funcionario ya se encuentra registrado en el Sistema.', 'No se realizó el registro de la información ingresada');
                    return redirect()->route('funcionarios.index');
            }
        }     

        if($validar_persona == false){

            $person->letra_cedula = 'V';
            $person->cedula = $request['cedula'];
            $person->primer_nombre = $request['primer_nombre'];
            $person->segundo_nombre = $request['segundo_nombre'];
            $person->primer_apellido = $request['primer_apellido'];
            $person->segundo_apellido = $request['segundo_apellido'];
            $person->id_genero = $request['id_genero'];
            $person->fecha_nacimiento = $request['fecha_nacimiento'];
            $person->telefono = $request['telefono'];
            $person->save();
            $id_person = $person->id;
            //$id_person = $person->Where('cedula', $request['cedula'])->pluck('id');

            $funcionario->credencial = $request['credencial'];
            $funcionario->id_jerarquia = $request['id_jerarquia'];
            $funcionario->id_person = $id_person;
            $funcionario->id_estatus = $request['id_estatus'];
            $funcionario->save();

            $estatus_laboral = Estatus_Funcionario::get();
            $jerarquia = Jerarquia::get();
            $generos = Genero::get();

            $genero_for = $generos->Where('id', $request['id_genero']);
            foreach($genero_for as $genero){
                $genero = $genero['valor'];
            }
            $id_jerarquia_for = $jerarquia->Where('id', $request['id_jerarquia']);
            foreach($id_jerarquia_for as $jerarquia){
                $jerarquia = $jerarquia['valor'];
            }
            $estatus_laboral_for = $estatus_laboral->Where('id', $request['id_estatus']);
            foreach($estatus_laboral_for as $estatus){
                $estatus_laboral = $estatus['valor'];
            }


            $id_user = Auth::user()->id;
            $id_Accion = 1; //Registro
            $trazas = Traza_Funcionarios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Datos de Usuario: '.
            'V'.$request['cedula'].' || '.$request['primer_nombre'].' || '.$request['segundo_nombre'].' || '.$request['primer_apellido'].' || '.
            $request['segundo_apellido'].' || '.$genero.' || '.$request['fecha_nacimiento'].' || '.$request['credencial'].' || '.
            $jerarquia.' || '.$request['telefono'].' || '.$estatus_laboral]);

            Alert()->success('Funcionario ingresado Satisfactoriamente');
            return redirect()->route('funcionarios.index');
        }

        if($validar_persona == true and $validar_funcionario == false){

            $obtener_funcionario = $funcionario->where('id_person','=',$obtener_persona[0]['id'])->get();

            $funcionario->credencial = $request['credencial'];
            $funcionario->id_jerarquia = $request['id_jerarquia'];
            $funcionario->id_person = $obtener_persona[0]['id'];
            $funcionario->id_estatus = $request['id_estatus'];
            $funcionario->save();

            $jerarquia = Jerarquia::get();
            $estatus_laboral = Estatus_Funcionario::get();
            $genero = Genero::get();
            $id_jerarquia_for = $jerarquia->Where('id', $request['id_jerarquia']);
            foreach($id_jerarquia_for as $jerarquia){
                $jerarquia = $jerarquia['valor'];
            }
            $estatus_laboral_for = $estatus_laboral->Where('id', $request['id_estatus']);
            foreach($estatus_laboral_for as $estatus){
                $estatus_laboral = $estatus['valor'];
            }
            $genero_for = $genero->Where('id', $obtener_persona[0]['id_genero']);
            foreach($genero_for as $gen){
                $genero = $gen['valor'];
            }

            $id_user = Auth::user()->id;
            $id_Accion = 1; //Registro
            $trazas = Traza_Funcionarios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Datos de Usuario: La Persona que intentas registrar ya está registrada, se agregó solamente la información del Funcionario.'.
            'V'.$obtener_persona[0]['cedula'].' || '.$obtener_persona[0]['primer_nombre'].' || '.$obtener_persona[0]['segundo_nombre'].' || '.
            $obtener_persona[0]['primer_apellido'].' || '.$obtener_persona[0]['segundo_apellido'].' || '.$genero.' || '.$obtener_persona[0]['fecha_nacimiento'].' || '.
            $request['credencial'].' || '.$jerarquia.' || '.$request['telefono'].' || '.$estatus_laboral]);

            Alert()->success('Funcionario ingresado Satisfactoriamente');
            return redirect()->route('funcionarios.index');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Funcionario $funcionario)
    {
        $id_user = Auth::user()->id;
        $id_Accion = 4; //Visualización
        $trazas = Traza_Funcionarios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos de Funcionario: '.
        $funcionario->credencial.' || '.$funcionario->person->letra_cedula.$funcionario->person->cedula.' || '.
        $funcionario->person->primer_nombre.' '.$funcionario->person->segundo_nombre.' '.$funcionario->person->primer_apellido.' '.
        $funcionario->person->segundo_apellido.' || '.$funcionario->jerarquia->valor.' || '.$funcionario->estatus->valor]);

        return view('funcionarios.show', compact('funcionario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Funcionario $funcionario)
    {
        $genero = Genero::pluck('valor', 'id')->all();
        $jerarquia = Jerarquia::pluck('valor', 'id')->all();
        $estatus = Estatus_Funcionario::pluck('valor', 'id')->all();
        return view('funcionarios.edit', compact('funcionario', 'genero', 'jerarquia', 'estatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    { 
        $funcionarios = Funcionario::Find($id, ['id']);
        $funcionarios->update($request->all());

        $id = $funcionarios->Where('id', $id)->select('id_person')->get();
        foreach($id as $person)
        {
            $id_person = $person['id_person'];
        }
        $personas = Person::Find($id_person, ['id']);
        $personas->update($request->all('primer_nombre', 'segundo_nombre', 'primer_apellido',
        'segundo_apellido', 'id_genero', 'fecha_nacimiento', 'telefono'));
        
        $jerarquia = Jerarquia::get();
        $generos = Genero::get();
        $estatus_laboral = Estatus_Funcionario::get();
        $genero_for = $generos->Where('id', $request['id_genero']);
        foreach($genero_for as $genero){
            $genero = $genero['valor'];
        }
        $id_jerarquia_for = $jerarquia->Where('id', $request['id_jerarquia']);
        foreach($id_jerarquia_for as $jerarquia){
            $jerarquia = $jerarquia['valor'];
        }
        $estatus_laboral_for = $estatus_laboral->Where('id', $request['id_estatus']);
        foreach($estatus_laboral_for as $estatus){
            $estatus_laboral = $estatus['valor'];
        }

        $id_user = Auth::user()->id;
        $id_Accion = 2; //Actualización
        $trazas = Traza_Funcionarios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos de Usuario: '.
        $request['primer_nombre'].' || '.$request['segundo_nombre'].' || '.$request['primer_apellido'].' || '.
        $request['segundo_apellido'].' || '.$genero.' || '.$request['fecha_nacimiento'].' || '.$request['credencial'].' || '.
        $jerarquia.' || '.$request['telefono'].' || '.$estatus_laboral]);
    
        Alert()->success('Usuario Actualizado Satisfactoriamente');
        return redirect()->route('funcionarios.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $funcionarios = Funcionario::join('persons', 'persons.id', '=', 'funcionarios.id_person')
        ->Where('funcionarios.id', $id)->get();

        foreach($funcionarios as $funcionario)
        {
            $cedula = $funcionario['cedula'];
            $primer_nombre = $funcionario['primer_nombre'];
            $segundo_nombre = $funcionario['segundo_nombre'];
            $primer_apellido = $funcionario['primer_apellido'];
            $segundo_apellido = $funcionario['segundo_apellido'];
            $fecha_nacimiento = $funcionario['fecha_nacimiento'];
            $id_genero = $funcionario['id_genero'];
            $credencial = $funcionario['credencial'];
            $id_jerarquia = $funcionario['id_jerarquia'];
            $id_estatus = $funcionario['id_estatus'];
            $telefono = $funcionario['telefono'];
        }
        
        $jerarquia = Jerarquia::get();
        $funcionarios = Funcionario::join('persons', 'persons.id', '=', 'funcionarios.id_person')
        ->join('jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia');
        $estatus = Estatus_Funcionario::get();
        $generos = Genero::get();

        $genero_for = $generos->Where('id', $id_genero);
        foreach($genero_for as $genero){
            $genero = $genero['valor'];
        }
        $jerarquia_for = $jerarquia->Where('id', $id_jerarquia);
        foreach($jerarquia_for as $jerarquia){
            $jerarquia = $jerarquia['valor'];
        }
        $estatus_for = $estatus->Where('id', $id_estatus);
        foreach($estatus_for as $estatus){
            $estatus = $estatus['valor'];
        }
        
        $id_user = Auth::user()->id;
        $id_Accion = 3; //Eliminación
        $trazas = Traza_Funcionarios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos de Reseña: '.
        'V'.$cedula.' || '.$primer_nombre.' '.$segundo_nombre.' || '.$primer_apellido.' || '.
        $segundo_apellido.' || '.$genero.' || '.$fecha_nacimiento.' || '.$credencial.' || '.$jerarquia.' || '.
        $telefono.' || '.$estatus]);

        $funcionarios = Funcionario::find($id);
        $funcionarios->delete();
        return redirect()->route('funcionarios.index')->with('eliminar', 'Ok');
    }
}
