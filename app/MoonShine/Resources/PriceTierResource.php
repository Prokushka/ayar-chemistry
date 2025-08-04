<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\PriceTier;

use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Number;

/**
 * @extends ModelResource<PriceTier>
 */
class PriceTierResource extends ModelResource
{
    protected string $model = PriceTier::class;

    protected string $title = 'PriceTiers';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Number::make('От количества', 'from_quantity'),
            Number::make('Цена', 'price'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Number::make('От количества', 'from_quantity'),
                Number::make('Цена', 'price'),
                BelongsTo::make('товар', 'product', resource: ProductResource::class)
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
            Number::make('От количества', 'from_quantity'),
            Number::make('Цена', 'price'),
        ];
    }

    /**
     * @param PriceTier $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
