@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Lista Negra (MAC´s)</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            {!! Form::open(array('route' => 'blacklist.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-3 col-sm-3 col-md-5">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                        'mac' => 'MAC',
                                        ], 
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
                            {{-- @can('blacklist.create') --}}
                            <a class="btn btn-success" href="{{ route('blacklist.create') }}">Registrar MAC</a>                        
                            {{-- @endcan --}}
                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>MAC</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($blacklist as $black)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$black->mac}}</td>
                                                <td align="center">
                                                    {{-- @can('blacklist.edit') --}}
                                                        <a class="btn btn-primary" href="{{ route('blacklist.edit', $black->id) }}"><i class='fa fa-edit'></i></a>
                                                    {{-- @endcan --}}
                                                    {{-- @can('blacklist.destroy') --}}
                                                        {!! Form::open(['method' => 'DELETE','route' => ['blacklist.destroy', $black->id],'style'=>'display:inline', 'class' => 'eliminar']) !!}
                                                            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                                        {!! Form::close() !!}                                                  
                                                    {{-- @endcan --}}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $blacklist->appends(request()->input())->links() }}         
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