@extends('layouts.app')
@extends('users_siipol.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Usuarios SIIPOL</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            {!! Form::open(array('route' => 'users_siipol.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-3 col-sm-3 col-md-5">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', [
                                        '' => 'Limpiar Búsqueda',
                                        'interno' => 'Funcionario CICPC',
                                        'externo' => 'Funcionario Externo',
                                        ], 
                                        'Seleccionar', array('class' => 'form-control select2')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <div class="form-group">
                                        {!! Form::text('buscador', null, array('class' => 'form-control', 'onkeyup' => 'mayus(this);', 'placeholder' => 'Ingrese Cédula de Identidad')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    {!! Form::button('<i class="fa fa-search"> Buscar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}

                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                @if (isset($Users['DatosUsuario']))
                                                    <th>Credencial</th>
                                                @endif
                                                <th>Cédula</th>
                                                <th>Nombre del Funcionario</th>
                                                <th>Usuario</th>
                                                @if(isset($Users['DatosUsuarioExterno']))
                                                    <th>Organismo</th>
                                                @else
                                                    <th>Dependencia</th>
                                                @endif
                                                <th>Comentarios</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($Users))
                                                @foreach ($Users as $user)
                                                <tr role="row" class="odd">
                                                    @if ($user['tipo_usuario'] == 'interno')
                                                        <td class="sorting_1">{{$user['credencial']}}</td>
                                                    @endif
                                                    <td class="sorting_1">{{$user['cedula']}}</td>
                                                    <td class="sorting_1">{{$user['primer_nombre'].' '.$user['segundo_nombre'].' '.$user['primer_apellido'].' '.$user['segundo_apellido']}}</td>
                                                    <td class="sorting_1">{{$user['usuario']}}</td>
                                                    @if ($user['tipo_usuario'] == 'externo')
                                                        <td class="sorting_1">{{$user['Organismo']}}</td>
                                                    @else
                                                        <td class="sorting_1">{{$user['division']}}</td>
                                                    @endif
                                                    <td class="sorting_1">{{$user['comentarios']}}</td>
                                                    <td align="center">
                                                        @can('users_siipol.edit')
                                                            <a class="btn btn-primary" href="{{ route('users_siipol.edit', [$user['id'], $user['usuario']]) }}"><i class='fa fa-edit'></i></a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection