@extends('layout')

@section('content')
    <h1>Su voto ha sido registrado satisfactoriamente</h1>
    <a href="{{ url('/auth/logout') }}" role="button" class="btn btn-danger">SALIR</a>
@endsection