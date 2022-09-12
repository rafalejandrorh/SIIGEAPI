@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Funcionarios</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            {!! Form::open(array('route' => 'funcionarios.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-3 col-sm-3 col-md-5">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                        'cedula' => 'Cédula',
                                        'credencial' => 'Credencial',
                                        'jerarquia' => 'Jerarquía', 
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
                            @can('funcionarios.create')
                            <a class="btn btn-success" href="{{ route('funcionarios.create') }}">Registrar Funcionario</a>                        
                            @endcan
                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>Credencial</th>
                                                <th>Cédula</th>
                                                <th>Funcionario</th>
                                                <th>Jerarquía</th>
                                                <th>Estatus Laboral</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($funcionarios as $funcionario)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$funcionario->credencial}}</td>
                                                <td class="sorting_1">{{$funcionario->person->cedula}}</td>
                                                <td class="sorting_1">{{$funcionario->person->primer_nombre.' '.$funcionario->person->primer_apellido}}</td>
                                                <td class="sorting_1">{{$funcionario->jerarquia->valor}}</td>
                                                <td class="sorting_1">{{$funcionario->estatus->valor}}</td>
                                                <td align="center">
                                                    @can('funcionarios.show')
                                                        <a class="btn btn-info" href="{{ route('funcionarios.show', $funcionario->id) }}"><i class='fa fa-eye'></i></a>
                                                    @endcan
                                                    @can('funcionarios.edit')
                                                        <a class="btn btn-primary" href="{{ route('funcionarios.edit', $funcionario->id) }}"><i class='fa fa-edit'></i></a>
                                                    @endcan
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $funcionarios->appends(request()->input())->links() }}
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