@extends('layouts.auth_app')
@section('title')
    SIREPOL
@endsection
@section('content')
    <div class="login-main-text">
        <div class="title text-center">
            <h2 style="color:#000000"><b>Sistema Integrado de Gestión de API´s</b></h2>
        </div>  
    </div>
    <div class="card card-primary">
        <div class="card-header">
            <h4>Crear Preguntas de Seguridad</h4>
        </div>

        <div class="card-body">
            {!! Form::open(array('route' => 'questions.create','method'=>'POST')) !!}

                <div class="form-group has-feedback">
                    <label for="question">Pregunta N°1</label>
                    {!! Form::select('question1', $question1, [], array('class' => 'form-control', 'required')) !!}
                </div>
                <div class="form-group has-feedback">
                    {!! Form::text('response1', null, array('class' => 'form-control', 'required', 'placeholder' => 'Ingresa tu Respuesta')) !!}   
                </div>

                <div class="form-group has-feedback">
                    <label for="question">Pregunta N°2</label>
                    {!! Form::select('question2', $question2, [], array('class' => 'form-control', 'required')) !!}
                </div>
                <div class="form-group has-feedback">
                    {!! Form::text('response2', null, array('class' => 'form-control', 'required', 'placeholder' => 'Ingresa tu Respuesta')) !!}   
                </div>

                <div class="form-group has-feedback">
                    <label for="question">Pregunta N°3</label>
                    {!! Form::select('question3', $question3, [], array('class' => 'form-control', 'required')) !!}
                </div>
                <div class="form-group has-feedback">
                    {!! Form::text('response3', null, array('class' => 'form-control', 'required', 'placeholder' => 'Ingresa tu Respuesta')) !!}   
                </div>

                <div class="form-group has-feedback">
                    {!! Form::hidden('id_user', $id_user, array('class' => 'form-control')) !!}   
                </div>

                <div class="form-group">
                    {!! Form::button('Registrar', array('class' => 'btn btn-primary btn-lg btn-block', 'type' => 'submit')) !!}   
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
