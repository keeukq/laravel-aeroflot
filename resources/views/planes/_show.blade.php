{{-- Подробности о самолёте. Этот кусок показывается и в модальном окне, и на отдельной странице. --}}
<img src="{{ $plane->image_url }}" alt="{{ $plane->brand }} {{ $plane->model }}" class="img-fluid mb-3"
     data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="картинка">

<p class="lead">{{ $plane->category ?? '—' }}</p>

<dl class="row mb-0">
    <dt class="col-sm-5">Бортовой номер</dt>
    <dd class="col-sm-7">{{ $plane->tail_number }}</dd>

    <dt class="col-sm-5">
        <span class="popover-term" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
              data-bs-placement="top"
              data-bs-content="Скорость, на которой самолёт летит большую часть маршрута.">Крейсерская скорость</span>
    </dt>
    <dd class="col-sm-7">{{ $plane->cruise_speed }} км/ч</dd>

    <dt class="col-sm-5">Дата ввода в эксплуатацию</dt>
    <dd class="col-sm-7">{{ $plane->commissioned_at_formatted ?? '—' }}</dd>

    <dt class="col-sm-5">Длина</dt>
    <dd class="col-sm-7">{{ $plane->length ?? '—' }}</dd>

    <dt class="col-sm-5">Размах крыла</dt>
    <dd class="col-sm-7">{{ $plane->wingspan ?? '—' }}</dd>

    <dt class="col-sm-5">
        <span class="popover-term" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
              data-bs-placement="top"
              data-bs-content="C — бизнес-класс, W — комфорт, Y — эконом. Цифры — количество мест.">Компоновка салона</span>
    </dt>
    <dd class="col-sm-7">{{ $plane->cabin ?? '—' }}</dd>

    <dt class="col-sm-5">
        <span class="popover-term" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
              data-bs-placement="top"
              data-bs-content="Наибольшая масса самолёта, с которой ему разрешено взлетать.">Макс. взлётная масса</span>
    </dt>
    <dd class="col-sm-7">{{ $plane->mtow ?? '—' }}</dd>

    <dt class="col-sm-5">Двигатели</dt>
    <dd class="col-sm-7">{{ $plane->engine ?? '—' }}</dd>

    <dt class="col-sm-5">Макс. высота полёта</dt>
    <dd class="col-sm-7">{{ $plane->altitude ?? '—' }}</dd>

    <dt class="col-sm-5">Макс. дальность</dt>
    <dd class="col-sm-7">{{ $plane->flight_range ?? '—' }}</dd>

    <dt class="col-sm-5">Имена самолётов</dt>
    <dd class="col-sm-7">{{ $plane->names ?? '—' }}</dd>
</dl>
