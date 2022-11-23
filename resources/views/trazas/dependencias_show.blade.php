@extends('layouts.app')
@extends('trazas.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Detallado de Trazas de Dependencias</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <a href="{{ route('traza_dependencias.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                            </div>
                        </div>
                        <br>

                        {!! Form::model($dependencia, ['method' => 'PATCH','route' => ['traza_dependencias.update', $dependencia->id]]) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="name">Usuario</label>
                                    {!! Form::text('user', $dependencia->user->users, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="email">Acción</label>
                                    {!! Form::text('accion', $dependencia->acciones->valor, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="email">Fecha</label>
                                    {!! Form::text('fecha', $dependencia->created_at, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="email">Valores Modificados</label>
                                    {!! Form::textarea('valores_modificados', $dependencia->valores_modificados, array('class' => 'form-control', 'readonly')) !!}
                                </div>
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
