@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Registrar Dependencia</b></h3>
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
                                <a href="{{ route('dependencias.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                            </div>
                        </div>
                        <br>
                        
                        {!! Form::open(array('route' => 'dependencias.store','method'=>'POST')) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <h4 class="page__heading"><b>Datos de la Dependencia</b></h4>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Organismo</label>
                                    {!! Form::text('organismo', null, array('class' => 'form-control', 'required' => 'required', 'onkeyup'=>'mayus(this);')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="email">Dependencia del Organismo</label>
                                    {!! Form::text('dependencia', null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="email">Ministerio al que se encuentra Adscrito</label>
                                    {!! Form::text('ministerio', null, array('class' => 'form-control', 'required' => 'required', 'onkeyup'=>'mayus(this);')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">    
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <h4 class="page__heading"><b>Datos del Representante</b></h4>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Letra</label>
                                    {!! Form::select('letra_cedula', ['V' => 'V', 'E' => 'E'], null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Cédula</label>
                                    {!! Form::text('cedula', null, array('class' => 'form-control', 'maxlength' => '10')) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Primer Nombre</label>
                                    {!! Form::text('primer_nombre', null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Segundo Nombre</label>
                                    {!! Form::text('segundo_nombre', null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);',)) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Primer Apellido</label>
                                    {!! Form::text('primer_apellido', null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Segundo Apellido</label>
                                    {!! Form::text('segundo_apellido', null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);')) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Género</label>
                                    {!! Form::select('id_genero', $genero,[], array('class' => 'form-control', 'placeholder'=>'Seleccione', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Número de Contacto</label>
                                    {!! Form::text('telefono', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    {!! Form::email('correo', null, array('class' => 'form-control')) !!}
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
            </div>
        </div>
    </section>
@endsection
