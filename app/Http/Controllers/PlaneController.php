<?php

namespace App\Http\Controllers;

use App\Models\Plane;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlaneController extends Controller
{
    // Список всех самолётов (карточки)
    public function index()
    {
        $planes = Plane::orderBy('id')->get();

        return view('planes.index', compact('planes'));
    }

    // Страница с пустой формой
    public function create()
    {
        return view('planes.create', ['plane' => new Plane()]);
    }

    // Сохранение нового самолёта
    public function store(Request $request): RedirectResponse
    {
        $this->normalize($request);

        // validate() проверяет данные; при ошибке сам вернёт назад на форму
        // с сообщениями об ошибках и старыми значениями полей
        $data = $request->validate($this->rules(), $this->messages(), $this->attributes());

        $data['image'] = $this->saveImage($request->file('image'));

        Plane::create($data);

        return redirect()->route('planes.index')->with('status', 'Самолёт добавлен.');
    }

    // Просмотр одного самолёта.
    // Если запрос пришёл из JavaScript (окно Modal) — отдаём только кусок разметки.
    // Если человек открыл адрес в браузере напрямую — отдаём целую страницу.
    public function show(Request $request, Plane $plane)
    {
        if ($request->ajax()) {
            return view('planes._show', compact('plane'));
        }

        return view('planes.show', compact('plane'));
    }

    // Страница с формой редактирования (та же форма, поля уже заполнены)
    public function edit(Plane $plane)
    {
        return view('planes.edit', compact('plane'));
    }

    // Сохранение изменений
    public function update(Request $request, Plane $plane): RedirectResponse
    {
        $this->normalize($request);

        $data = $request->validate($this->rules($plane), $this->messages(), $this->attributes());

        if ($request->hasFile('image')) {
            // Загрузили новое фото: старый файл удаляем, новый сохраняем
            $this->deleteImage($plane->image);
            $data['image'] = $this->saveImage($request->file('image'));
        } else {
            // Фото не меняли: не трогаем старое
            unset($data['image']);
        }

        $plane->update($data);

        return redirect()->route('planes.index')->with('status', 'Изменения сохранены.');
    }

    // Удаление. Из-за SoftDeletes строка не стирается, а помечается в deleted_at
    public function destroy(Plane $plane): RedirectResponse
    {
        $plane->delete();

        return redirect()->route('planes.index')->with('status', 'Самолёт удалён.');
    }

    // ---------- Вспомогательные методы ----------

    // Приводим бортовой номер к единому виду ДО проверки,
    // чтобы "ra-73134" и "RA-73134" считались одним и тем же номером
    private function normalize(Request $request): void
    {
        $request->merge([
            'tail_number' => strtoupper(trim((string) $request->input('tail_number'))),
        ]);
    }

    // Правила проверки (Validation Rules)
    private function rules(?Plane $plane = null): array
    {
        return [
            'brand' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            // номер вида RA-73134, уникальный; при редактировании свою же запись не считаем
            'tail_number' => [
                'required',
                'regex:/^[A-Z0-9]{1,2}-[A-Z0-9]{3,5}$/',
                Rule::unique('planes', 'tail_number')->ignore($plane?->id),
            ],
            'cruise_speed' => ['required', 'integer', 'between:100,1500'],
            'commissioned_at' => ['nullable', 'date', 'before_or_equal:today'],
            'category' => ['nullable', 'string', 'max:255'],
            'length' => ['nullable', 'string', 'max:255'],
            'wingspan' => ['nullable', 'string', 'max:255'],
            'cabin' => ['nullable', 'string', 'max:255'],
            'mtow' => ['nullable', 'string', 'max:255'],
            'engine' => ['nullable', 'string', 'max:255'],
            'altitude' => ['nullable', 'string', 'max:255'],
            'flight_range' => ['nullable', 'string', 'max:255'],
            'names' => ['nullable', 'string', 'max:255'],
            // при создании фото обязательно, при редактировании — нет
            'image' => [$plane ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    // Сообщения об ошибках по-русски (:attribute подставит название поля)
    private function messages(): array
    {
        return [
            'required' => 'Поле «:attribute» обязательно для заполнения.',
            'string' => 'Поле «:attribute» должно быть текстом.',
            'integer' => 'Поле «:attribute» должно быть целым числом.',
            'date' => 'Поле «:attribute» должно быть датой.',
            'before_or_equal' => 'Дата в поле «:attribute» не может быть в будущем.',
            'unique' => 'Самолёт с таким бортовым номером уже есть.',
            'regex' => 'Бортовой номер должен выглядеть так: RA-73134.',
            'image' => 'Файл должен быть картинкой.',
            'mimes' => 'Допустимые форматы картинки: jpg, png, webp.',
            'max.string' => 'Поле «:attribute» не длиннее :max символов.',
            'max.file' => 'Картинка не больше 5 МБ.',
            'between.numeric' => 'Поле «:attribute» должно быть от :min до :max.',
        ];
    }

    // Человеческие названия полей для сообщений
    private function attributes(): array
    {
        return [
            'brand' => 'Марка',
            'model' => 'Модель',
            'tail_number' => 'Бортовой номер',
            'cruise_speed' => 'Крейсерская скорость',
            'commissioned_at' => 'Дата ввода в эксплуатацию',
            'category' => 'Категория',
            'length' => 'Длина',
            'wingspan' => 'Размах крыла',
            'cabin' => 'Компоновка салона',
            'mtow' => 'Макс. взлётная масса',
            'engine' => 'Двигатели',
            'altitude' => 'Макс. высота полёта',
            'flight_range' => 'Макс. дальность',
            'names' => 'Имена самолётов',
            'image' => 'Фото',
        ];
    }

    // Сохраняем фото в storage/app/public/planes, уменьшая его до 800 px по ширине.
    // Для этого используется встроенная в PHP библиотека GD.
    private function saveImage(UploadedFile $file): string
    {
        $contents = file_get_contents($file->getRealPath());
        $extension = $file->extension();

        if (function_exists('imagecreatefromstring')) {
            $image = @imagecreatefromstring($contents);

            if ($image !== false) {
                if (imagesx($image) > 800) {
                    // imagescale сама считает высоту, сохраняя пропорции
                    $image = imagescale($image, 800);
                }

                // Сохраняем прозрачность и записываем результат как PNG
                imagealphablending($image, false);
                imagesavealpha($image, true);

                ob_start();
                imagepng($image);
                $contents = ob_get_clean();
                $extension = 'png';
            }
        }

        $path = 'planes/' . Str::random(20) . '.' . $extension;
        Storage::disk('public')->put($path, $contents);

        return $path;
    }

    private function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
