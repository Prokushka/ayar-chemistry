<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;


use Illuminate\Database\Eloquent\Builder;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Order>
 */
class OrderResource extends ModelResource
{
    protected string $model = Order::class;

    protected string $title = 'Orders';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make('Заказчик', 'user', formatted: fn($item) => "$item->name - $item->phone"),
            Text::make('Статус','status')->sortable(function (Builder $query, $column, $direction) {
                $query->orderByRaw(
                    "CASE WHEN $column = 'В обработке' THEN 0
                     WHEN $column = 'Новый' THEN 1
                     ELSE 2 END,
                     $column $direction
                ");
            })
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
                    Box::make('Обязательные поля', array(
                        ID::make(),
                        BelongsTo::make('Заказчик', 'user',
                          formatted: fn($item) => "$item->name. номер телефона - $item->phone. Указанная почта $item->email",
                        resource: UserResource::class)->searchable(),
                        Number::make('Конечная цена', 'total_cost')->buttons()->min(0)->step(0.01),
                        Number::make('прибыль', 'total_profit')->buttons()->min(0)->step(0.01),

                        Select::make('Статус заказа', 'status')

                            ->options(array(
                                'Новый' => 'Новый',
                                'В обработке' => 'В обработке',
                                'Подтверждён' => 'Подтверждён',
                                'Завершён' => 'Завершён',
                                'Отменён' => 'Отменён',
                            ))->default('Новый'),
                    ))
                ]),
                Column::make([
                    Box::make('Связь', [
                        BelongsToMany::make('Товары', 'products',
                            formatted: fn($item) => "$item->title. количество - $item->quantity" , resource: ProductResource::class)
                        ->columnLabel('Инфо о товарах')
                        ->fields([
                            Text::make('Количество', 'quantity'),
                            Number::make('Закупочная цена', 'purchase_price')->buttons()->min(0)->step(0.01),
                            Number::make('Продаваема цена', 'sale_price')->buttons()->min(0)->step(0.01),
                            Number::make('Прибыль', 'profit')->buttons()->min(0)->step(0.01),

                        ])
                        ->asyncSearch(
                            'title',
                            formatted: function ($product) {
                                return $product->title . '. Кол-во - ' . $product->quantity;
                            },

                        ),

                    ])
                ])

            ]),

        ];
    }

    public function afterUpdated($item): mixed
    {
        if ($item->status === 'Подтверждён'){
            foreach ($item->products as $product) {
                $qty = $product->pivot->quantity;


                $product->decrement('quantity', $qty);
            }
        }
        return $item;


    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make('Заказчик', 'user',
                fn($item) => "$item->name. номер телефона - $item->phone. Указанная почта $item->email",
                UserResource::class),
            Number::make('Конечная цена', 'total_cost')->buttons()->min(0)->step(0.01),
            Number::make('прибыль', 'total_profit')->buttons()->min(0)->step(0.01),
            Text::make('Статус заказа', 'status'),
            BelongsToMany::make('Товары', 'products', formatted: fn($item) => "$item->title. количество - $item->quantity" , resource: ProductResource::class)
                ->columnLabel('Инфо о товарах')
                ->fields([
                    Text::make('Количество', 'quantity'),
                    Number::make('Закупочная цена', 'purchase_price')->buttons()->min(0)->step(0.01),
                    Number::make('Продаваема цена', 'sale_price')->buttons()->min(0)->step(0.01),
                    Number::make('Прибыль', 'profit')->buttons()->min(0)->step(0.01),

                ])
        ];
    }

    /**
     * @param Order $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

}
