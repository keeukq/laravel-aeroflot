<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // up() выполняется при "php artisan migrate" — создаёт таблицу
    public function up(): void
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->id();                                // первичный ключ
            $table->string('brand');                     // марка: Boeing, Airbus
            $table->string('model');                     // модель: 777-300ER
            $table->string('tail_number')->unique();     // бортовой номер: RA-73134
            $table->unsignedInteger('cruise_speed');     // крейсерская скорость, км/ч
            $table->string('image')->nullable();         // путь к фото внутри storage
            $table->string('category')->nullable();      // дальне-/среднемагистральный
            $table->string('length')->nullable();        // длина
            $table->string('wingspan')->nullable();      // размах крыла
            $table->string('cabin')->nullable();         // компоновка салона
            $table->string('mtow')->nullable();          // макс. взлётная масса
            $table->string('engine')->nullable();        // двигатели
            $table->string('altitude')->nullable();      // макс. высота полёта
            $table->string('flight_range')->nullable();  // дальность полёта
            $table->string('names')->nullable();         // имена на борту
            $table->date('commissioned_at')->nullable(); // дата ввода в эксплуатацию
            $table->timestamps();                        // created_at, updated_at
            $table->softDeletes();                       // deleted_at (мягкое удаление)
        });
    }

    // down() выполняется при откате — удаляет таблицу
    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};
