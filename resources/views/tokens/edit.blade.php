@extends('layouts.app')
@extends('tokens.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Editar Token</b></h3>
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
   
                        {!! Form::model($token, array('method' => 'PATCH','route' => ['tokens.update', $token->id], 'files' => true)) !!}
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="email">Dependencia</label>
                                    {!! Form::text('dependencia', $token->Dependencias->Nombre, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="email">Organismo</label>
                                    {!! Form::text('organismo', $token->Dependencias->Organismo, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="email">Ministerio</label>
                                    {!! Form::text('ministerio', $token->Dependencias->Ministerio, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="email">Token</label>
                                    {!! Form::text('token', $token->token, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Fecha de Generación</label>
                                    {!! Form::text('fecha_generacion', date('d/m/Y H:i:s', strtotime($token->created_at)).'. Hace '.$fecha_generacion.' días', array('class' => 'form-control timepicker', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Fecha de Expiración</label>
                                    {!! Form::text('fecha_expiracion', date('d/m/Y H:i:s', strtotime($token->expires_at)).'. En '.$fecha_expiracion.' días', array('class' => 'form-control timepicker', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Última vez usado</label>
                                    @if ($token->last_used_at)
                                        {!! Form::text('ultimo_uso', date('d/m/Y H:i:s', strtotime($token->last_used_at)).'. Hace '.$ultimo_uso.' horas', array('class' => 'form-control timepicker', 'readonly')) !!}
                                    @else
                                        {!! Form::text('ultimo_uso', 'No utilizada', array('class' => 'form-control timepicker', 'readonly'))!!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Duración (En días)</label>
                                    {!! Form::number('duracion_token', $token->duracion_token, array('class' => 'form-control timepicker')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                {!! Form::button('<i class="fa fa-key"> Actualizar Token</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                            </div>
                        {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
