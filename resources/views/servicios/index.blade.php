@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Servicios</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            {!! Form::open(array('route' => 'servicios.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-3 col-sm-3 col-md-5">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                        'nombre' => 'Nombre',
                                        'metodo' => 'Método',
                                        ], 
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
                            </div>
                            {!! Form::close() !!}
                            @can('servicios.create')
                            <a class="btn btn-success" href="{{ route('servicios.create') }}">Registrar Servicios</a>                        
                            @endcan
                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>Nombre</th>
                                                <th>Método</th>
                                                <th>Estatus</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($servicios as $servicio)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$servicio->nombre}}</td>
                                                <td class="sorting_1">{{$servicio->valor}}</td>
                                                @can('servicios.update_status')
                                                    @if($servicio->estatus == true)
                                                        <td class="sorting_1">
                                                            {!! Form::model($servicio, ['method' => 'PATCH','route' => ['servicios.update_status', $servicio->id]]) !!}
                                                                {!! Form::button('Activo', ['type' => 'submit', 'class' => 'btn btn-info']) !!}
                                                            {!! Form::close() !!} 
                                                        </td>
                                                    @else
                                                        <td class="sorting_1">                                                        
                                                            {!! Form::model($servicio, ['method' => 'PATCH','route' => ['servicios.update_status', $servicio->id]]) !!}    
                                                                {!! Form::button('Inactivo', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                                            {!! Form::close() !!} 
                                                        </td>
                                                    @endif
                                                @elsecan('servicios.index')
                                                    @if($servicio->estatus == true)
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
                                                    @can('servicios.edit')
                                                        <a class="btn btn-primary" href="{{ route('servicios.edit', $servicio->id) }}"><i class='fa fa-edit'></i></a>
                                                    @endcan
                                                    @can('servicios.destroy')
                                                        {!! Form::open(['method' => 'DELETE','route' => ['servicios.destroy', $servicio->id],'style'=>'display:inline', 'class' => 'eliminar']) !!}
                                                            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                                        {!! Form::close() !!}                                                  
                                                    @endcan
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $servicios->appends(request()->input())->links() }}         
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

@section('scripts')

    @if (session('eliminar') == 'Ok')
        <script>
            Swal.fire(
                'Eliminado!',
                'La Reseña ha sido Eliminada.',
                'success'
            )
        </script>
    @endif

    <script>
        $('.eliminar').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
            if (result.value) {
                this.submit();
            }
            })

        });
    </script>

@endsection