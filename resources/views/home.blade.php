@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Alexandra</div>

                    <div class="panel-body">
                        <a href="{{ url('/vote/index') }}">Ingresar ---></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
