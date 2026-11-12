@extends('layouts.app')

@section('title', $plane->brand . ' ' . $plane->model)

@section('content')
    <h1 class="mb-4">{{ $plane->brand }} {{ $plane->model }}</h1>

    <div class="card">
        <div class="card-body">
            @include('planes._show')
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('planes.index') }}" class="btn btn-outline-secondary">Назад к списку</a>
        <a href="{{ route('planes.edit', $plane) }}" class="btn btn-primary">Изменить</a>
    </div>
@endsection
