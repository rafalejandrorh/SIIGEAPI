@extends('layouts.app')
@extends('sesion.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Ajustes</b></h3>
        </div>
        <div class="section-body">
            <div class="row">

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @if (!isset($password_status) || $password_status == false)
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <a href="{{ route('home') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                                    </div>
                                @endif
                                
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                <br>
                                    <h4>Datos de Usuario</h4>
                                </div>
                                @foreach ($user as $usr)
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="email">Funcionario Asignado</label>
                                            {!! Form::text('user', $usr->funcionario->jerarquia->valor.'. '.$usr->funcionario->person->primer_nombre.' '.$usr->funcionario->person->primer_apellido, array('class' => 'form-control', 'readonly')) !!}
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="email">Usuario</label>
                                            {!! Form::text('user', $usr->users, array('class' => 'form-control', 'readonly')) !!}
                                        </div>
                                    </div>
                                @endforeach   
                            </div>
                        </div>
                    </div>
                </div>

                @can('users.password')
                    <div class="col-lg-6">
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
                                
                            {!! Form::model($usr, ['method' => 'PATCH', 'route' => ['sesion.update', $usr->id]]) !!}
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <br>
                                    <h4>Modificar Contraseña</h4>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="password">Contraseña Actual</label>
                                        {!! Form::password('curr_password', array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="password">Contraseña Nueva</label>
                                        {!! Form::password('password', array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="confirm-password">Confirmar Contraseña</label>
                                        {!! Form::password('confirm-password', array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                            
                            </div>
                        </div>
                    </div>
                @endcan

                @can('users.questions')
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body"> 
                                {!! Form::model($usr, ['method' => 'PATCH','route' => ['questions.update', $user[0]['id']]]) !!}
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <h4>Modificar Preguntas de Seguridad</h4>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="password">Pregunta N°1</label>
                                            {!! Form::select('question1', $question1, [], array('class' => 'form-control')) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::text('response1', null, array('class' => 'form-control')) !!}
                                        </div>
                                            {!! Form::hidden('padre1', $padre1[0]['id_padre'], array('class' => 'form-control')) !!}
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="password">Pregunta N°2</label>
                                            {!! Form::select('question2', $question2, [], array('class' => 'form-control')) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::text('response2', null, array('class' => 'form-control')) !!}
                                        </div>
                                            {!! Form::hidden('padre2', $padre2[0]['id_padre'], array('class' => 'form-control')) !!}
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="password">Pregunta N°3</label>
                                            {!! Form::select('question3', $question3, [], array('class' => 'form-control')) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::text('response3', null, array('class' => 'form-control')) !!}
                                        </div>
                                            {!! Form::hidden('padre3', $padre3[0]['id_padre'], array('class' => 'form-control')) !!}
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="password">Contraseña Actual</label>
                                            {!! Form::password('password', array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                @endcan
                
            </div>
        </div>
    </section>
@endsection
