@extends('layouts.app')
@extends('users.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Editar Usuario</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                     
                        @if ($errors->any())                                                
                            <div class="alert alert-dark alert-dismissible fade show" role="alert">
                            <strong>¡Revise los campos!</strong>                        
                                @foreach ($errors->all() as $error)                                    
                                    <span class="badge badge-danger">{{ $error }}</span>
                                @endforeach                        
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <a href="{{ route('users.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                            </div>
                        </div>
                        <br>
 
                        {!! Form::model($user, ['method' => 'PUT','route' => ['users.update', $user->id]]) !!}
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="">Funcionario Asignado</label>
                                    <input type="text" class="form-control" value="{{$user->funcionario->jerarquia->valor.'. '.$user->funcionario->person->primer_nombre.' '.$user->funcionario->person->segundo_nombre}}" readonly>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="">Roles</label>
                                    {!! Form::select('roles', $roles, $user->roles, array('class' => 'form-control select2', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="email">Usuario</label>
                                    {!! Form::text('users', $user->users, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}

                        {!! Form::model($user, ['method' => 'PATCH','route' => ['users.reset', $user->id], 'class' => 'confirmation']) !!}
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    {!! Form::button('<i class="fa fa-reply"> Reestablecer Contraseña</i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}

                        {!! Form::model($user, ['method' => 'DELETE','route' => ['questions.destroy', $user->id], 'class' => 'confirmation']) !!}
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                {!! Form::button('<i class="fa fa-trash"> Eliminar Preguntas de Seguridad</i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}

                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')

    <script>
        $('.confirmation').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Estás seguro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Sí, estoy seguro!'
            }).then((result) => {
            if (result.value) {
                this.submit();
            }
            })
        });
    </script>

@endsection
