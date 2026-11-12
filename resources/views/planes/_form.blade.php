{{-- Одна форма для создания и редактирования.
     $plane->exists равно true, если самолёт уже есть в базе (редактирование). --}}
@php
    // Необязательные текстовые поля: имя поля => подпись
    $optional = [
        'category' => 'Категория',
        'length' => 'Длина',
        'wingspan' => 'Размах крыла',
        'cabin' => 'Компоновка салона',
        'mtow' => 'Макс. взлётная масса',
        'engine' => 'Двигатели',
        'altitude' => 'Макс. высота полёта',
        'flight_range' => 'Макс. дальность',
        'names' => 'Имена самолётов',
    ];
@endphp

{{-- novalidate отключает всплывашки браузера: проверку и оформление берёт на себя Bootstrap (класс needs-validation).
     enctype нужен, чтобы форма могла отправлять файл. --}}
<form method="POST"
      action="{{ $plane->exists ? route('planes.update', $plane) : route('planes.store') }}"
      enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    @if ($plane->exists)
        @method('PUT')
    @endif

    <div class="row g-3">
        {{-- HTML5-проверки: required, maxlength, pattern, min/max.
             Проверки сервера: @error показывает сообщение из контроллера. --}}
        <div class="col-md-6">
            <label for="brand" class="form-label">Марка</label>
            <input type="text" id="brand" name="brand" required maxlength="50"
                   class="form-control @error('brand') is-invalid @enderror"
                   value="{{ old('brand', $plane->brand) }}">
            <div class="invalid-feedback">@error('brand'){{ $message }}@else Укажите марку.@enderror</div>
        </div>

        <div class="col-md-6">
            <label for="model" class="form-label">Модель</label>
            <input type="text" id="model" name="model" required maxlength="50"
                   class="form-control @error('model') is-invalid @enderror"
                   value="{{ old('model', $plane->model) }}">
            <div class="invalid-feedback">@error('model'){{ $message }}@else Укажите модель.@enderror</div>
        </div>

        <div class="col-md-6">
            <label for="tail_number" class="form-label">Бортовой номер</label>
            <input type="text" id="tail_number" name="tail_number" required placeholder="RA-73134"
                   pattern="[A-Za-z0-9]{1,2}-[A-Za-z0-9]{3,5}"
                   class="form-control @error('tail_number') is-invalid @enderror"
                   value="{{ old('tail_number', $plane->tail_number) }}">
            <div class="invalid-feedback">@error('tail_number'){{ $message }}@else Формат номера: RA-73134.@enderror</div>
        </div>

        <div class="col-md-6">
            <label for="cruise_speed" class="form-label">Крейсерская скорость, км/ч</label>
            <input type="number" id="cruise_speed" name="cruise_speed" required min="100" max="1500" step="1"
                   class="form-control @error('cruise_speed') is-invalid @enderror"
                   value="{{ old('cruise_speed', $plane->cruise_speed) }}">
            <div class="invalid-feedback">@error('cruise_speed'){{ $message }}@else Введите число от 100 до 1500.@enderror</div>
        </div>

        <div class="col-md-6">
            <label for="commissioned_at" class="form-label">Дата ввода в эксплуатацию</label>
            <input type="date" id="commissioned_at" name="commissioned_at" max="{{ now()->format('Y-m-d') }}"
                   class="form-control @error('commissioned_at') is-invalid @enderror"
                   value="{{ old('commissioned_at', $plane->commissioned_at?->format('Y-m-d')) }}">
            <div class="invalid-feedback">@error('commissioned_at'){{ $message }}@else Дата не может быть в будущем.@enderror</div>
        </div>

        <div class="col-md-6">
            <label for="image" class="form-label">Фото</label>
            {{-- accept ограничивает выбор файла картинками; на create фото обязательно --}}
            <input type="file" id="image" name="image" accept="image/png,image/jpeg,image/webp"
                   @unless ($plane->exists) required @endunless
                   class="form-control @error('image') is-invalid @enderror">
            <div class="invalid-feedback">@error('image'){{ $message }}@else Выберите картинку (jpg, png, webp).@enderror</div>
            @if ($plane->exists)
                <div class="form-text">Оставьте пустым, чтобы не менять фото.</div>
            @endif
        </div>

        @if ($plane->exists)
            <div class="col-12">
                <img src="{{ $plane->image_url }}" alt="Текущее фото" class="img-thumbnail" style="max-height: 160px">
            </div>
        @endif

        @foreach ($optional as $name => $label)
            <div class="col-md-6">
                <label for="{{ $name }}" class="form-label">{{ $label }}</label>
                <input type="text" id="{{ $name }}" name="{{ $name }}" maxlength="255"
                       class="form-control @error($name) is-invalid @enderror"
                       value="{{ old($name, $plane->$name) }}">
                <div class="invalid-feedback">@error($name){{ $message }}@enderror</div>
            </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">{{ $plane->exists ? 'Сохранить' : 'Добавить' }}</button>
        <a href="{{ route('planes.index') }}" class="btn btn-outline-secondary">Отмена</a>
    </div>
</form>
