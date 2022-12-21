<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use App\Models\Traza_Servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiciosController extends Controller
{
    function __construct()
    {
        $this->middleware('can:servicios.index')->only('index');
        $this->middleware('can:servicios.create')->only('create');
        $this->middleware('can:servicios.edit')->only('edit', 'update');
        $this->middleware('can:servicios.destroy')->only('destroy');
        $this->middleware('can:servicios.update_status')->only('update_status');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->all();

        if($request->tipo_busqueda == 'nombre'){
            $servicios = Servicios::Where('nombre', 'ilike', '%'.$request->buscador.'%')->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Servicios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);

        }else if($request->tipo_busqueda == 'metodo'){
            $servicios = Servicios::Where('valor', 'ilike', '%'.$request->buscador.'%')->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            $trazas = Traza_Servicios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            'valores_modificados' => 'Tipo de Búsqueda: '.
            $request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);
        }else{
            $servicios = Servicios::paginate(10);
        }

        return view('servicios.index', compact('servicios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $servicios = new Servicios();

        $servicios->nombre = $request->nombre;
        $servicios->valor  = $request->valor;
        $servicios->estatus = true;
        $servicios->save();

        $id_user = Auth::user()->id;
        $id_Accion = 1; //Registro
        $trazas = Traza_Servicios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos del Servicio: '.$request->nombre.' || Activo || '.$request->valor]);
        
        Alert()->success('Servicio Creado Satisfactoriamente');
        return redirect()->route('servicios.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Servicios $servicio)
    {
        return view('servicios.edit', compact('servicio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Servicios $servicios)
    {
        $servicios->update($request->all());

        $id_user = Auth::user()->id;
        $id_Accion = 2; //Actualización
        $trazas = Traza_Servicios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos del Servicio: '.$request->nombre.' || '.$request->metodo]);
    
        Alert()->success('Servicios Actualizado Satisfactoriamente');
        return redirect()->route('servicios.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $servicio = Servicios::Where('id', $id)->first()->toarray();
        $id_user = Auth::user()->id;
        $id_Accion = 3; //Eliminación
        $trazas = Traza_Servicios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos del Servicio: '.$servicio['nombre'].' || '.$servicio['valor']]);

        $servicios = Servicios::find($id, ['id']);
        $servicios->delete();
        Alert()->success('El servicio ha sido Eliminado');
        return redirect()->route('servicios.index');
    }

    public function update_status($id)
    {
        $servicios = Servicios::Where('id', $id)->first()->toarray();

        $id = $servicios['id'];
        $estatus = $servicios['estatus'];
        $valor = $servicios['valor'];
        $nombre = $servicios['nombre'];

        if($estatus == true)
        {
            $status = false;
            $notificacion = 'Inactivo';
        }else if($estatus == false){
            $status = true;
            $notificacion = 'Activo';
        }else{
            Alert()->error('No se actualizó el Estatus del Usuario', 'El Funcionario no se encuentra Activo, por lo que no se puede activar su Usuario');
            return back();
        }
        $servicios = Servicios::find($id, ['id']);
        $servicios->update(['estatus' => $status]);

        $id_user = Auth::user()->id;
        $id_Accion = 2; //Actualización
        $trazas = Traza_Servicios::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        'valores_modificados' => 'Datos del Servicio: '.$nombre.' || '.$valor.' || Estatus Previo: '.$estatus.' || Estatus Nuevo: '.$notificacion]);

        Alert()->success('Estatus de Servicio Actualizado', 'Nuevo Estatus: '.$notificacion);
        return redirect()->route('servicios.index');
    }
}
