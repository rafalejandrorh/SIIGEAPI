@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Tokens</b></h3>
        </div>

            <div class="row">
                <div class="col-lg-12" style="position:relative;z-index:1000">
                    <div class="card">
                        <div class="card-body">
                                {!! Form::open(array('route' => 'dependencias.index','method' => 'GET')) !!}
                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                            'dependencia' => 'Dependencias'], 
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
                                @can('tokens.create')
                                <a class="btn btn-success" href="{{ route('tokens.create') }}">Crear Token</a>  
                                <br>                      
                                @endcan
                                        <table class="table table-striped mt-2 display dataTable table-hover">
                                            <thead>
                                                <tr role="row">
                                                    <th>Dependencia</th>
                                                    <th>Fecha de Generación</th>
                                                    <th>Fecha de Expiración</th>
                                                    <th>Último vez usado</th>
                                                    <th>Estatus</th>
                                                    <th>Acciones</th>
                                                </tr>    
                                            </thead>
                                                @foreach ($tokens as $token)
                                                <tr role="row" class="odd">
                                                    <td class="sorting_1">{{$token->Dependencias->Nombre}}</td>
                                                    <td class="sorting_1">{{date('d/m/Y H:i:s', strtotime($token->created_at))}}</td>
                                                    
                                                    @if ($token->expires_at >= date('Y-m-d H:i:s'))
                                                        <td class="sorting_1">{{date('d/m/Y H:i:s', strtotime($token->expires_at))}}</td>
                                                    @else 
                                                        <td>{{date('d/m/Y H:i:s', strtotime($token->expires_at))}} <button class="btn btn-danger">Expirado</button></td>
                                                    @endif
                                                    
                                                    @if ($token->last_used_at)
                                                        <td class="sorting_1" align="center">{{date('d/m/Y H:i:s', strtotime($token->last_used_at))}}</td>
                                                    @else
                                                        <td class="sorting_1" align="center">{{ 'No Utilizada' }}</td>
                                                    @endif

                                                    @can('tokens.update_status')
                                                        @if ($token->estatus == true)
                                                            <td class="sorting_1">
                                                                {!! Form::model($token, ['method' => 'PATCH','route' => ['tokens.update_status', $token->id]]) !!}    
                                                                    {!! Form::button('Activo', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                                                {!! Form::close() !!} 
                                                        @else
                                                            <td class="sorting_1">
                                                                {!! Form::model($token, ['method' => 'PATCH','route' => ['tokens.update_status', $token->id]]) !!}    
                                                                    {!! Form::button('Inactivo', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                                                {!! Form::close() !!} 
                                                            </td>
                                                        @endif
                                                    @elsecan('tokens.index')
                                                        @if($token->estatus == true)
                                                            <td class="sorting_1">
                                                                <button class="btn btn-primary">Activo</button>
                                                            </td>
                                                        @else
                                                            <td class="sorting_1">                                                        
                                                                <button class="btn btn-danger">Inactivo</button>
                                                            </td>
                                                        @endif 
                                                    @endcan
                                                    

                                                    <td align="center">
                                                        @can('tokens.show')
                                                            <a class="btn btn-info" href="{{ route('tokens.show', $token->id) }}"><i class='fa fa-eye'></i></a>
                                                        @endcan
                                                        @can('tokens.edit')
                                                            @if ($token->expires_at >= date('Y-m-d H:i:s'))
                                                                
                                                            @else
                                                                <a class="btn btn-primary" href="{{ route('tokens.edit', $token->id) }}"><i class='fa fa-edit'></i></a>
                                                            @endif
                                                        @endcan
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="pagination justify-content-end">  
                                            {{ $tokens->appends(request()->input())->links() }}          
                                        </div> 
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