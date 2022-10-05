@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Editar Dependencia</b></h3>
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
 
                        {!! Form::model($dependencia, ['method' => 'PUT','route' => ['dependencias.update', $dependencia->id]]) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <h4 class="page__heading"><b>Datos de la Dependencia</b></h4>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Organismo</label>
                                    {!! Form::text('organismo', $dependencia->Organismo, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Dependencia</label>
                                    {!! Form::text('dependencia', $dependencia->Nombre, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Ministerio</label>
                                    {!! Form::text('ministerio', $dependencia->Ministerio, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <h4 class="page__heading"><b>Datos del Representante</b></h4>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Letra</label>
                                    {!! Form::select('letra_cedula', ['V' => 'V', 'E' => 'E'], $dependencia->person->letra_cedula, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Cédula</label>
                                    {!! Form::text('cedula', $dependencia->person->cedula, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Primer Nombre</label>
                                    {!! Form::text('primer_nombre', $dependencia->person->primer_nombre, array('class' => 'form-control', 'onkeyup'=>'mayus(this);', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Segundo Nombre</label>
                                    {!! Form::text('segundo_nombre', $dependencia->person->segundo_nombre, array('class' => 'form-control', 'onkeyup'=>'mayus(this);')) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Primer Apellido</label>
                                    {!! Form::text('primer_apellido', $dependencia->person->primer_apellido, array('class' => 'form-control', 'onkeyup'=>'mayus(this);', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Segundo Apellido</label>
                                    {!! Form::text('segundo_apellido', $dependencia->person->segundo_apellido, array('class' => 'form-control', 'onkeyup'=>'mayus(this);')) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="email">Género</label>
                                    {!! Form::select('id_genero', $genero, $dependencia->person->id_genero, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Número de Contacto</label>
                                    {!! Form::text('telefono', $dependencia->person->telefono, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    {!! Form::email('correo', $dependencia->person->correo_electronico, array('class' => 'form-control', 'required' => 'required')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <h4 class="page__heading"><b>Servicios a Consumir</b></h4>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <br/>
                                    @php
                                        print_r($dependencias_servicios); 
                                    @endphp
                                    @foreach($servicios as $servicio)
                                        <label>{{ Form::checkbox('id_servicios[]', $servicio->id, in_array($servicio->nombre, $dependencias_servicios) ? true : false, array('class' => 'name')) }}
                                        {{ $servicio->nombre }}</label>
                                        <br/>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection
