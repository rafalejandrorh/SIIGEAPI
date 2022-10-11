<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Genero;
use App\Models\Person;
use Alert;
use App\Models\Dependencias;
use App\Models\Dependencias_Servicios;
use App\Models\Servicios;
use App\Models\Traza_Dependencias;

class DependenciasController extends Controller
{
    function __construct()
    {
        $this->middleware('can:dependencias.index')->only('index');
        $this->middleware('can:dependencias.create')->only('create');
        $this->middleware('can:dependencias.show')->only('show');
        $this->middleware('can:dependencias.edit')->only('edit', 'update');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->all();

        if($request->tipo_busqueda == 'dependencia'){
            $dependencias = Dependencias::Where('Nombre', 'LIKE', '%'.$request->buscador.'%')->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Dependencias::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'ministerio'){
            $dependencias = Dependencias::Where('Ministerio', 'LIKE', '%'.$request->buscador.'%')->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Dependencias::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'organismo'){
            $dependencias = Dependencias::Where('Organismo', 'LIKE', '%'.$request->buscador.'%')->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Dependencias::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else{
            $dependencias = Dependencias::paginate(10);
        }
        
        return view('dependencias.index', ['dependencias' => $dependencias]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $genero = Genero::pluck('valor', 'id')->all();
        $servicios = Servicios::get();
        return view('dependencias.create',compact('genero', 'servicios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);die;
        $person = new Person();
        $dependencia = new Dependencias();
        $dependencias_servicios = new Dependencias_Servicios();
        $servicios = new Servicios();

        $cedula = $request['cedula'];
        $obtener_persona = $person->where('cedula','=',$cedula)->get();
        $validar_persona = $person->where('cedula','=',$cedula)->exists();

        if($validar_persona == true){
            
            $dependencia->Nombre = $request->dependencia;
            $dependencia->Ministerio = $request->ministerio;
            $dependencia->Organismo = $request->organismo;
            $dependencia->id_person = $obtener_persona[0]['id'];
            $dependencia->save();
            $id_dependencia = $dependencia->id;

            $i = 0;
            $nombre_servicio = null;
            while($i<count($request->id_servicios))
            {
                $dependencias_servicios->id_dependencias = $id_dependencia;
                $dependencias_servicios->id_servicios = $request->id_servicios[$i];
                $dependencias_servicios->save();

                $nombre = $servicios->where('id', $request->id_servicios[$i])->get();
                $nombre_servicio .= $nombre[0]['nombre'].', ';

                $i++;
            }

            $persona = $person->Find($obtener_persona[0]['id'], ['id']);
            $persona->update($request->all('telefono', 'correo'));

            $generos = Genero::get();
            $genero_for = $generos->Where('id', $obtener_persona[0]['id_genero']);
            foreach($genero_for as $genero){
                $genero = $genero['valor'];
            }

            $id_user = Auth::user()->id;
            $id_Accion = 1; //Registro
            $trazas = Traza_Dependencias::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Datos de la Dependencia: '.$request->organismo.', '.$request->dependencia.', Adscrito al: '.$request->ministerio.
            'Datos del Representante: '.$obtener_persona[0]['letra_cedula'].$obtener_persona[0]['cedula'].
            ' || '.$obtener_persona[0]['primer_nombre'].', '.$obtener_persona[0]['segundo_nombre'].' || '.
            $obtener_persona[0]['primer_apellido'].', '.$obtener_persona[0]['segundo_apellido'].' || '.
            $genero.' || '.$obtener_persona[0]['telefono'].' || '.$obtener_persona[0]['correo_electronico'].' || Servicios a Consumir: '.$nombre_servicio]);

            Alert()->success('Dependencia Creada Satisfactoriamente');
            return redirect()->route('dependencias.index');
        }     

        if($validar_persona == false){

            $person->letra_cedula = 'V';
            $person->cedula = $request->cedula;
            $person->primer_nombre = $request->primer_nombre;
            $person->segundo_nombre = $request->segundo_nombre;
            $person->primer_apellido = $request->primer_apellido;
            $person->segundo_apellido = $request->segundo_apellido;
            $person->id_genero = $request->id_genero;
            $person->telefono = $request->telefono;
            $person->correo_electronico = $request->correo;
            $person->save();
            $id_person = $person->id;

            $dependencia->Nombre = $request->dependencia;
            $dependencia->Ministerio = $request->ministerio;
            $dependencia->Organismo = $request->organismo;
            $dependencia->id_person = $id_person;
            $dependencia->save();
            $id_dependencia = $dependencia->id;

            $i = 0;
            $nombre_servicio = null;
            while($i<count($request->id_servicios))
            {
                $dependencias_servicios->id_dependencias = $id_dependencia;
                $dependencias_servicios->id_servicios = $request->id_servicios[$i];
                $dependencias_servicios->save();

                $nombre = $servicios->where('id', $request->id_servicios[$i])->get();
                $nombre_servicio .= $nombre[0]['nombre'].', ';

                $i++;
            }

            $generos = Genero::get();

            $genero_for = $generos->Where('id', $request['id_genero']);
            foreach($genero_for as $genero){
                $genero = $genero['valor'];
            }

            $id_user = Auth::user()->id;
            $id_Accion = 1; //Registro
            $trazas = Traza_Dependencias::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Datos de la Dependencia: '.$request->organismo.', '.$request->dependencia.', Adscrito al: '.$request->ministerio.
            '|| Datos del Representante: '.$request->letra_cedula.$request->cedula.
            ' || '.$request->primer_nombre.' || '.$request->segundo_nombre.' || '.$request->primer_apellido.' || '.
            $request->segundo_apellido.' || '.$genero.' || '.$request->telefono.' || '.$request->correo.' || Servicios a Consumir: '.$nombre_servicio]);

            Alert()->success('Dependencia Creada Satisfactoriamente');
            return redirect()->route('dependencias.index');
        }

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
    public function edit(Dependencias $dependencia)
    {
        $genero = Genero::pluck('valor', 'id')->all();
        $servicios = Servicios::get();
        $dependencias_servicios = Dependencias_Servicios::join('servicios', 'servicios.id', '=', 'dependencias_servicios.id_servicios')
        ->where('dependencias_servicios.id_dependencias', $dependencia->id)
        ->pluck('servicios.nombre', 'servicios.id')->all();
        //$dependencias_servicios = Dependencias_Servicios::all*()
        // dd($dependencias_servicios);die;
        return view('dependencias.edit', compact('dependencia', 'genero', 'servicios', 'dependencias_servicios'));
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
        $dependencias = Dependencias::Find($id, ['id']);
        $dependencias->update([
            'Nombre' => $request->dependencia,
            'Organismo' => $request->organismo,
            'Ministerio' => $request->ministerio
            ]);

        $idPerson = $dependencias->Where('id', $id)->select('id_person')->get();
        $id_person = $idPerson[0]['id_person'];

        $personas = Person::Find($id_person);
        $personas->update([
            'letra_cedula' => $request->letra_cedula, 
            'cedula' => $request->cedula, 
            'primer_nombre' => $request->primer_nombre, 
            'segundo_nombre' => $request->segundo_nombre, 
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido, 
            'id_genero' => $request->id_genero, 
            'telefono' => $request->telefono, 
            'correo_electronico' => $request->correo
            ]);

        $dependencias_servicios = new Dependencias_Servicios();
        $validacion_dependencias_servicios = $dependencias_servicios->where('id_dependencias', $id)->exists();
        if($validacion_dependencias_servicios == true)
        {
            $servicios_dependencia = $dependencias_servicios->where('id_dependencias', $id);
            $servicios_dependencia->delete();  
        }

        $i = 0;
        $nombre_servicio = null;
        while($i<count($request->id_servicios))
        {
            $dependencias_servicios = new Dependencias_Servicios();
            $dependencias_servicios->id_dependencias = $id;
            $dependencias_servicios->id_servicios = $request->id_servicios[$i];
            $dependencias_servicios->save();

            $servicios = new Servicios();
            $nombre = $servicios->where('id', $request->id_servicios[$i])->get();
            $nombre_servicio .= $nombre[0]['nombre'].', ';

            $i++;
        };

        $generos = Genero::get();
        $genero_for = $generos->Where('id', $request->id_genero);
        foreach($genero_for as $genero){
            $genero = $genero['valor'];
        }

        $id_user = Auth::user()->id;
        $id_Accion = 2; //Actualización
        $trazas = Traza_Dependencias::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos de la Dependencia: '.$request->organismo.', '.$request->dependencia.', Adscrito al: '.$request->ministerio.
        '|| Datos del Representante: '.$request->letra_cedula.$request->cedula.' || '.
        $request->primer_nombre.', '.$request->segundo_nombre.' || '.$request->primer_apellido.', '.
        $request->segundo_apellido.' || '.$genero.' || '.$request->fecha_nacimiento.' || '.$request->telefono.' || '.$request->correo.' || Servicios a Consumir: '.$nombre_servicio]);
    
        Alert()->success('Dependencia Actualizada Satisfactoriamente');
        return redirect()->route('dependencias.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
