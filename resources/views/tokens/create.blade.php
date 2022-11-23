@extends('layouts.app')
@extends('tokens.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Crear Token</b></h3>
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
                                <a href="{{ route('tokens.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                            </div>
                        </div>
                        <br>

                        {!! Form::open(array('route' => 'tokens.store','method' => 'POST','files' => true)) !!}
                        <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label for="name">Fecha de Generación</label>
                                        {!! Form::date('fecha_resenna', $fecha_hoy, array('class' => 'form-control datepicker', 'required' => 'required', 'readonly' => 'readonly')) !!}
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="">Dependencia</label>
                                        <select name="dependencia" id="" class="form-control select2" required>
                                            <option value="">Seleccione</option>
                                        @foreach ($dependencias as $dependencia)
                                            <option value="{{ $dependencia->id }}"> {{$dependencia->Nombre.' ('.$dependencia->Organismo.') - '.$dependencia->Ministerio }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label for="email">Duración del Token (En Días)</label>
                                        {!! Form::number('duracion_token', null, array('class' => 'form-control datepicker', 'onkeyup'=>'mayus(this);',)) !!}
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
