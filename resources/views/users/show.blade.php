@extends('layouts.app')
@extends('users.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Detallado de Usuario</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <a href="{{ route('users.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                            </div>
                        </div>
                        <br>
 
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="">Funcionario Asignado</label>
                                    <input type="text" class="form-control" value="{{$user->funcionario->jerarquia->valor.'. '.$user->funcionario->person->primer_nombre.' '.$user->funcionario->person->segundo_nombre}}" readonly>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label for="">Roles</label>
                                    {!! Form::select('roles', $roles, $user->roles, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label for="email">Teléfono</label>
                                    {!! Form::text('telefono', $user->funcionario->telefono, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            @if($user->status == true)
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label for="email">Estatus</label>
                                        {!! Form::text('id_estatus', 'Activo', array('class' => 'form-control', 'readonly')) !!}
                                    </div>
                                </div>
                            @else
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label for="email">Estatus</label>
                                        {!! Form::text('id_estatus', 'Inactivo', array('class' => 'form-control', 'readonly')) !!}
                                    </div>
                                </div>
                            @endif 
                            <div class="col-xs-12 col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label for="email">Usuario</label>
                                    {!! Form::text('users', $user->users, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div> 
                            <div class="col-xs-12 col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label for="email">Último Inicio de Sesión</label>
                                    {!! Form::text('last_login', $user->last_login, array('class' => 'form-control', 'readonly')) !!}
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
