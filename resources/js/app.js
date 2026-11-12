import $ from 'jquery';
import * as bootstrap from 'bootstrap';

window.$ = window.jQuery = $;
window.bootstrap = bootstrap;

$(function () {
  // ---------- 1. Проверка формы (Bootstrap Validation) ----------
  // Если поля заполнены неверно — не отправляем форму и подсвечиваем ошибки
  $('.needs-validation').on('submit', function (e) {
    if (!this.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
    }
    $(this).addClass('was-validated');
  });

  // ---------- 2. Подтверждение удаления ----------
  $(document).on('submit', '.delete-form', function () {
    return confirm('Удалить самолёт ' + $(this).data('name') + '?');
  });

  // ---------- 3. Модальное окно с подробностями ----------
  const modalEl = document.getElementById('planeModal');
  if (!modalEl) return; // мы не на странице списка

  const modal = new bootstrap.Modal(modalEl);
  const $cards = $('.plane-card');
  let current = 0; // номер карточки, которая сейчас показана в окне

  // Убираем подсказки старого содержимого, иначе они останутся висеть на странице
  function removeHints() {
    $('#planeModal [data-bs-toggle="popover"]').each(function () {
      const instance = bootstrap.Popover.getInstance(this);
      if (instance) instance.dispose();
    });
    $('#planeModal [data-bs-toggle="tooltip"]').each(function () {
      const instance = bootstrap.Tooltip.getInstance(this);
      if (instance) instance.dispose();
    });
  }

  function initPopovers() {
    $('#planeModal [data-bs-toggle="popover"]').each(function () {
      bootstrap.Popover.getOrCreateInstance(this);
    });
  }

  // Tooltip включаем, только когда окно уже показано, иначе подсказка встанет со сдвигом
  function initTooltips() {
    $('#planeModal [data-bs-toggle="tooltip"]').each(function () {
      bootstrap.Tooltip.getOrCreateInstance(this);
    });
  }

  // Загружаем подробности самолёта с номером i (запрос на planes/{id})
  function load(i) {
    current = (i + $cards.length) % $cards.length; // зацикливаем: после последнего — первый
    const $card = $cards.eq(current);

    $('#planeModalTitle').text($card.data('title'));
    $('#planeCounter').text(`${current + 1} / ${$cards.length}`);

    $.get($card.data('url'))
      .done(function (html) {
        removeHints();
        $('#planeModal .modal-body').html(html);
        initPopovers();
        if ($(modalEl).hasClass('show')) initTooltips();
      })
      .fail(function () {
        $('#planeModal .modal-body').html('<p class="text-danger mb-0">Не удалось загрузить данные.</p>');
      });
  }

  // Клик по карточке открывает окно (кроме клика по кнопкам внизу карточки)
  $cards.on('click', function (e) {
    if ($(e.target).closest('.card-footer').length) return;
    load($cards.index(this));
    modal.show();
  });

  // Стрелки на клавиатуре переключают самолёты, пока окно открыто
  $(document).on('keydown', function (e) {
    if (!$(modalEl).hasClass('show')) return;
    if (e.key === 'ArrowRight') load(current + 1);
    if (e.key === 'ArrowLeft') load(current - 1);
  });

  // События Bootstrap ловим обычным addEventListener (jQuery их не видит)
  modalEl.addEventListener('shown.bs.modal', initTooltips);
  modalEl.addEventListener('hidden.bs.modal', removeHints);
});
