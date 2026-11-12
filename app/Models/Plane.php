<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plane extends Model
{
    // SoftDeletes: delete() не стирает строку, а ставит дату в deleted_at
    use SoftDeletes;

    // Какие поля можно заполнять массово (Plane::create([...]))
    protected $fillable = [
        'brand', 'model', 'tail_number', 'cruise_speed', 'image',
        'category', 'length', 'wingspan', 'cabin', 'mtow',
        'engine', 'altitude', 'flight_range', 'names', 'commissioned_at',
    ];

    // Преобразование типов: из базы приходит строка, а мы получаем объект Carbon (дату)
    protected $casts = [
        'commissioned_at' => 'date',
        'cruise_speed' => 'integer',
    ];

    // МУТАТОР (срабатывает при записи): бортовой номер всегда в верхнем регистре
    protected function tailNumber(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtoupper(trim($value)),
        );
    }

    // АКСЕССОР (срабатывает при чтении): $plane->commissioned_at_formatted
    // вернёт дату в виде 25.12.2018
    protected function commissionedAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->commissioned_at?->format('d.m.Y'),
        );
    }
}
