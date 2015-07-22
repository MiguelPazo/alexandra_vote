@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Ingreso al Sistema</div>

                    <div class="panel-body">
                        {!! Form::open(['url' => 'auth/login', 'method' => 'POST', 'id' => 'formLogin' ]) !!}                        
                        <div class="form-group">
                            {!! Form::label('dni', 'Ingrese su DNI:') !!}
                            {!! Form::text('dni', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('code', 'Ingrese su código UCE:') !!}
                            {!! Form::text('code', null, ['class' => 'form-control']) !!}
                        </div>
                        <button type="submit" class="btn btn-default">Ingresar</button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/app/login.js') }}"></script>
    <script src="{{ asset('/js/app/tools/sha256.js') }}"></script>
@endsection