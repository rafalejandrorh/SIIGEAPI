@extends('layouts.app')
@extends('funcionarios.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Editar Funcionario</b></h3>
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
                                <a href="{{ route('funcionarios.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                            </div>
                        </div>
                        <br>
 
                        {!! Form::model($funcionario, ['method' => 'PUT','route' => ['funcionarios.update', $funcionario->id]]) !!}
                        <div class="row">
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="name">Credencial</label>
                                    {!! Form::text('credencial', $funcionario->credencial, array('class' => 'form-control', 'maxlength' => '10')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Cédula</label>
                                    {!! Form::text('cedula', $funcionario->person->cedula, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Primer Nombre</label>
                                    {!! Form::text('primer_nombre', $funcionario->person->primer_nombre, array('class' => 'form-control', 'onkeyup'=>'mayus(this);', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Segundo Nombre</label>
                                    {!! Form::text('segundo_nombre', $funcionario->person->segundo_nombre, array('class' => 'form-control', 'onkeyup'=>'mayus(this);')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Primer Apellido</label>
                                    {!! Form::text('primer_apellido', $funcionario->person->primer_apellido, array('class' => 'form-control', 'onkeyup'=>'mayus(this);', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Segundo Apellido</label>
                                    {!! Form::text('segundo_apellido', $funcionario->person->segundo_apellido, array('class' => 'form-control', 'onkeyup'=>'mayus(this);')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Género</label>
                                    {!! Form::select('id_genero', $genero, $funcionario->person->id_genero, array('class' => 'form-control select2')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Fecha de Nacimiento</label>
                                    {!! Form::date('fecha_nacimiento', $funcionario->person->fecha_nacimiento, ['class'=>'form-control datepicker','autocomplete' => 'off']) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Teléfono</label>
                                    {!! Form::text('telefono', $funcionario->person->telefono, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Jerarquía</label>
                                    {!! Form::select('id_jerarquia', $jerarquia, $funcionario->id_jerarquia, array('class' => 'form-control select2')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Estatus Laboral</label>
                                    {!! Form::select('id_estatus', $estatus, $funcionario->id_estatus, array('class' => 'form-control select2', 'required' => 'required')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
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
