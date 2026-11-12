<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Токен нужен Laravel для защиты форм; JS тоже может его использовать --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Аэрофлот — Самолётный парк')</title>
    {{-- Файлы, которые собрал Laravel Mix (лежат в public/) --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

{{-- ШАПКА: компонент Navbar --}}
<nav class="navbar navbar-dark site-navbar">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('planes.index') }}">
            <img src="{{ asset('images/aeroflot.png') }}" alt="Логотип Аэрофлот" class="site-logo">
            Аэрофлот
        </a>
        <a class="btn btn-primary" href="{{ route('planes.create') }}">Добавить</a>
    </div>
</nav>

<main class="container my-4">
    {{-- Сообщение об успешном действии (его кладёт в сессию контроллер через ->with('status', ...)) --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
        </div>
    @endif

    {{-- Сюда каждая страница вставляет своё содержимое --}}
    @yield('content')
</main>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
