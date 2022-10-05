<?php

namespace App\Http\Controllers;

use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlacklistController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('can:blacklist.index')->only('index');
    //     $this->middleware('can:blacklist.create')->only('create');
    //     $this->middleware('can:blacklist.show')->only('show');
    //     $this->middleware('can:blacklist.edit')->only('edit', 'update');
    //     $this->middleware('can:blacklist.destroy')->only('destroy');
    //     $this->middleware('can:blacklist.update_status')->only('update_status');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->all();

        if($request->tipo_busqueda == 'mac'){
            $blacklist = Blacklist::Where('mac', '=', $request->buscador)->paginate(10);

            $id_user = Auth::user()->id;
            $id_Accion = 5; //Búsqueda
            //$trazas = Traza_Token::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
            //'valores_modificados' => 'Tipo de Búsqueda: '.
            //$request->tipo_busqueda.'. Valor Buscado: '.$request->buscador]);
        }else{
            $blacklist = Blacklist::paginate(10);
        }

        return view('blacklist.index', compact('blacklist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blacklist.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $blacklist = new Blacklist();

        $blacklist->mac = $request->mac;
        $blacklist->save();

        $id_user = Auth::user()->id;
        $id_Accion = 1; //Registro
        //$trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        //'valores_modificados' => 'Datos de Usuario: '.$request['users'].' || Activo || '.$rol]);

        Alert()->success('MAC Ingresada Satisfactoriamente');
        return redirect()->route('blacklist.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Blacklist $blacklist)
    {
        return view('blacklist.edit', compact('blacklist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blacklist $blacklist)
    {
        $blacklist->update($request->all());

        $id_user = Auth::user()->id;
        $id_Accion = 2; //Actualización
        //$trazas = Traza_User::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        //'valores_modificados' => 'Datos de Usuario: '.$request['users'].' || Activo || '.$rol]);
    
        Alert()->success('MAC Actualizado Satisfactoriamente');
        return redirect()->route('blacklist.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id_user = Auth::user()->id;
        $id_Accion = 3; //Eliminación
        //$trazas = Traza_Resenna::create(['id_user' => $id_user, 'id_accion' => $id_Accion, 
        //'valores_modificados' => 'Datos de Reseña: '.
        //$fecha_resenna.' || '.$cedula.' || '.$primer_nombre.' '.$segundo_nombre.' || '.$primer_apellido.' || '.$segundo_apellido]);

        $servicios = Blacklist::find($id, ['id']);
        $servicios->delete();
        Alert()->success('La MAC indicada ha sido eliminada');
        return redirect()->route('blacklist.index');
    }
}
