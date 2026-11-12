<?php

use App\Http\Controllers\PlaneController;
use Illuminate\Support\Facades\Route;

// Главная страница сразу ведёт на список самолётов
Route::redirect('/', '/planes');

// Одна строка создаёт 7 маршрутов для CRUD:
// GET /planes (index), GET /planes/create (create), POST /planes (store),
// GET /planes/{plane} (show), GET /planes/{plane}/edit (edit),
// PUT /planes/{plane} (update), DELETE /planes/{plane} (destroy)
Route::resource('planes', PlaneController::class);
