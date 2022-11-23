@extends('layouts.app')
@extends('roles.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Roles</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
        
                        @can('roles.create')
                        <a class="btn btn-success" href="{{ route('roles.create') }}">Nuevo</a>                        
                        @endcan

                            <table class="table table-striped mt-2 display dataTable table-hover">
                                <thead>                                         
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </thead>  
                                <tbody>
                                @foreach ($roles as $role)
                                <tr>                           
                                    <td>{{ $role->name }}</td>
                                    <td>                                
                                        @can('roles.edit')
                                            <a class="btn btn-primary" href="{{ route('roles.edit', $role->id) }}"><i class='fa fa-edit'></i></a>
                                        @endcan
                                        
                                        @can('roles.destroy')
                                            {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline', 'class' => 'eliminar']) !!}
                                                {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>               
                            </table>

                            <!-- Centramos la paginacion a la derecha -->
                            <div class="pagination justify-content-end">
                                {{ $roles->appends(request()->input())->links() }} 
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
                'El Rol ha sido Eliminado.',
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
