@extends('layouts.app')

@section('title', 'Новый самолёт')

@section('content')
    <h1 class="mb-4">Новый самолёт</h1>
    @include('planes._form')
@endsection
