@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Usuarios</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            {!! Form::open(array('route' => 'users.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-3 col-sm-3 col-md-5">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                        'cedula' => 'Cédula',
                                        'credencial' => 'Credencial',
                                        'jerarquia' => 'Jerarquía', 
                                        'usuario' => 'Usuario', 
                                        'estatus' => 'Estatus',
                                        'nombre' => 'Primer Nombre del Funcionario',
                                        'apellido' => 'Primer Apellido del Funcionario',], 
                                        'Seleccionar', array('class' => 'form-control select2')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <div class="form-group">
                                        {!! Form::text('buscador', null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    {!! Form::button('<i class="fa fa-search"> Buscar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                           
                            <a class="btn btn-success" href="{{ route('users.create') }}">Añadir Usuario</a>                        
                            
                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>Funcionario</th>
                                                <th>Jerarquía</th>
                                                <th>Usuario</th>
                                                <th>Estatus</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($Users as $user)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$user->funcionario->person->primer_nombre.' '.$user->funcionario->person->primer_apellido}}</td>
                                                <td class="sorting_1">{{$user->funcionario->jerarquia->valor}}</td>
                                                <td class="sorting_1">{{$user->users}}</td>
                                                @can('users.update_status')
                                                    @if($user->status == true)
                                                        <td class="sorting_1">
                                                            {{-- {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!} --}}
                                                            {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update_status', $user->id]]) !!}
                                                                {!! Form::button('Activo', ['type' => 'submit', 'class' => 'btn btn-info']) !!}
                                                            {!! Form::close() !!} 
                                                        </td>
                                                    @else
                                                        <td class="sorting_1">                                                        
                                                            {{-- {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!} --}}
                                                            {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update_status', $user->id]]) !!}    
                                                                {!! Form::button('Inactivo', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                                            {!! Form::close() !!} 
                                                        </td>
                                                    @endif
                                                @elsecan('users.index')
                                                    @if($user->status == true)
                                                        <td class="sorting_1">
                                                            <button class="btn btn-info">Activo</button>
                                                        </td>
                                                    @else
                                                        <td class="sorting_1">                                                        
                                                            <button class="btn btn-danger">Inactivo</button>
                                                        </td>
                                                    @endif  
                                                @endcan
                                                <td align="center">
                                                    @can('users.show')
                                                        <a class="btn btn-info" href="{{ route('users.show', $user->id) }}"><i class='fa fa-eye'></i></a>
                                                    @endcan
                                                    @can('users.edit')
                                                        <a class="btn btn-primary" href="{{ route('users.edit', $user->id) }}"><i class='fa fa-edit'></i></a>
                                                    @endcan
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $Users->appends(request()->input())->links() }}
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