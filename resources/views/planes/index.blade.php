@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Самолётный парк</h1>

    {{-- СЕТКА: сколько карточек в ряд на каждом breakpoint (xxxl — наш, от 1800px) --}}
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4 row-cols-xxxl-5 g-4">
        @forelse ($planes as $plane)
            <div class="col">
                {{-- data-url и data-title читает JS, когда нужно открыть окно с подробностями --}}
                <div class="card h-100 plane-card"
                     data-url="{{ route('planes.show', $plane) }}"
                     data-title="{{ $plane->brand }} {{ $plane->model }}">
                    <img src="{{ $plane->image_url }}" class="card-img-top img-fluid"
                         alt="{{ $plane->brand }} {{ $plane->model }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $plane->model }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $plane->brand }}</h6>
                        <p class="card-text">
                            Бортовой номер: {{ $plane->tail_number }}<br>
                            Крейсерская скорость: {{ $plane->cruise_speed }} км/ч
                        </p>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="{{ route('planes.edit', $plane) }}" class="btn btn-sm btn-dark">Изменить</a>

                        {{-- Браузер умеет отправлять только GET и POST, поэтому DELETE "подделываем" через @method --}}
                        <form method="POST" action="{{ route('planes.destroy', $plane) }}"
                              class="delete-form" data-name="{{ $plane->brand }} {{ $plane->model }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>Самолётов пока нет. Нажмите «Добавить».</p>
        @endforelse
    </div>

    {{-- МОДАЛЬНОЕ ОКНО (одно; содержимое подгружает JS с адреса planes/{id}) --}}
    <div class="modal fade" id="planeModal" tabindex="-1" aria-labelledby="planeModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="planeModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer justify-content-between">
                    <small class="text-body-secondary">Переключение: клавиши ← и →</small>
                    <span id="planeCounter" class="badge text-bg-secondary"></span>
                </div>
            </div>
        </div>
    </div>
@endsection
