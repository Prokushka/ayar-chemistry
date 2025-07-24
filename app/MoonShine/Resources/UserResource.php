<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Collapse;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\PasswordRepeat;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<User>
 */
class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Users';

    protected string $column = 'email';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя','name'),
            Email::make('E-mail', 'email'),
            Text::make('Телефон', 'phone')
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Grid::make([
                Column::make([
                    Box::make('Основная информация',[
                        ID::make(),
                        Text::make('Имя','name'),
                        Email::make('E-mail', 'email'),
                        Text::make('Телефон', 'phone')
                    ])
                ])->columnSpan(8),
                Column::make([
                    Box::make('Необходимые данные',[
                    Password::make('Пароль', 'password'),
                    PasswordRepeat::make('Повторите пароль', 'password_confirmation'),
                    ])
                ])->columnSpan(5)

            ])

        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Имя','name')->required(),
            Email::make('e-mail', 'email')->required(),
            Password::make('Пароль', 'password')->required(),
            PasswordRepeat::make('Повторите пароль', 'password_confirmation')->required(),

        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email', 'min:3'],
            'password' => ['required', 'min:5', 'required_with:password_confirmation', 'same:password_confirmation']
        ];
    }
}

/**
 * @param User $item
 *
 * @return array<string, string[]|string>
 * @see https://laravel.com/docs/validation#available-validation-ru
 * **/
