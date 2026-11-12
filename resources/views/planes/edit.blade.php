@extends('layouts.app')

@section('title', 'Изменить самолёт')

@section('content')
    <h1 class="mb-4">Изменить: {{ $plane->brand }} {{ $plane->model }}</h1>
    @include('planes._form')
@endsection
