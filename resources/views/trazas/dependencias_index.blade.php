@extends('layouts.app')
@extends('trazas.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Trazas de Dependencias</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <a href="{{ route('trazas.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                                </div>
                            </div>
                            <br>

                            {!! Form::open(array('route' => 'traza_dependencias.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                        'cedula' => 'Cédula',
                                        'credencial' => 'Credencial',
                                        'jerarquia' => 'Jerarquía', 
                                        'usuario' => 'Usuario', 
                                        'nombre' => 'Primer Nombre del Funcionario',
                                        'apellido' => 'Primer Apellido del Funcionario',
                                        'accion' => 'Registro, Actualización o Eliminación de Reseña',
                                        'valores_modificados' => 'Valores Modificados'],
                                        'Seleccionar', array('class' => 'form-control select2')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <div class="form-group">
                                        {!! Form::text('buscador', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    {!! Form::button('<i class="fa fa-search"> Buscar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2">
                                    <a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#filtrar"><i class="fa fa-filter"></i> Filtro</a>
                                </div>
                            </div>
                            {!! Form::close() !!}

                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>Usuario</th>
                                                <th>Acción</th>
                                                <th>Fecha</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($dependencias as $dependencia)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$dependencia->user->users}}</td>
                                                <td class="sorting_1">{{$dependencia->acciones->valor}}</td>
                                                <td class="sorting_1">{{ date('d/m/Y H:i:s', strtotime($dependencia->created_at)) }}</td>
                                                <td>
                                                    <a class="btn btn-info" href="{{ route('traza_dependencias.show', $dependencia->id) }}"><i class='fa fa-eye'></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $dependencias->appends(request()->input())->links() }}
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                {{-- Modal de Filtro --}}
                <div class="modal fade" id="filtrar" tabindex="-1" aria-labelledby="filtro" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h3 class="page__heading text-white"><b>Filtro</b></h3>
                                <span aria-hidden="true" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</span>
                            </div>
                        {!! Form::open(array('route' => 'traza_dependencias.index','method' => 'GET')) !!}
                        <div class="modal-body">
        
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="email">Desde</label>
                                        {!! Form::date('fecha_inicio', null, array('class' => 'form-control datepicker')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="email">Hasta</label>
                                        {!! Form::date('fecha_fin', null, array('class' => 'form-control datepicker')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label for="email">Acción</label>
                                        {!! Form::select('id_accion', $accion, [], 
                                        array('class' => 'form-control', 'placeholder'=>'Seleccione')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label for="email">Usuario</label>
                                        {!! Form::select('id_usuario', $user, [], 
                                        array('class' => 'form-control', 'placeholder'=>'Seleccione')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    {!! Form::hidden('filtro', 1, array('class' => 'form-control datepicker')) !!}
                                    {!! Form::button('<i class="fa fa-check"> Aplicar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
        
                        </div>
                        {!! Form::close() !!}
                    </div>
                    </div>
                </div>

</section>
@endsection