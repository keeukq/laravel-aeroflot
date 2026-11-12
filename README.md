# Аэрофлот — самолётный парк (ЛР3, Laravel 12)

Учебный проект, вариант 12. Главная сущность — самолёт (фото, марка, модель, бортовой номер, крейсерская скорость и др.).
Реализован полный CRUD: список карточек, просмотр в модальном окне, добавление, редактирование, удаление.

## Что использовано

- Laravel 12, SQLite, Eloquent (миграция, модель с мутатором и аксессорами, Soft Deletes, сидер)
- Resource-контроллер `PlaneController` и маршруты `Route::resource` в `routes/web.php`
- Blade: общий шаблон `layouts/app`, страницы `planes/*`, одна форма `_form` для создания и редактирования
- Валидация: HTML5 + Bootstrap (на клиенте) и `validate()` с правилами в контроллере (на сервере)
- Загрузка фото в `storage/app/public/planes` с уменьшением до 800 px (PHP GD)
- Laravel Mix: сборка Bootstrap 5, jQuery и SCSS из `resources/` в `public/`

## Запуск

Нужны PHP 8.2+ (с расширениями gd и sqlite), Composer и Node.js.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Сайт откроется на http://127.0.0.1:8000

Для разработки (пересборка стилей и скриптов при изменении файлов) в отдельной вкладке терминала: `npm run watch`.

## Структура

| Путь | Что там |
|---|---|
| `database/migrations/*_create_planes_table.php` | таблица `planes` |
| `app/Models/Plane.php` | модель |
| `database/seeders/PlaneSeeder.php` | 5 самолётов + картинки из `database/seeders/images` |
| `app/Http/Controllers/PlaneController.php` | CRUD, валидация, загрузка фото |
| `resources/views/` | шаблоны Blade |
| `resources/js/app.js`, `resources/scss/app.scss` | исходники фронтенда |
| `webpack.mix.js` | настройка Laravel Mix |

Автор: Максименко Н. А. 
