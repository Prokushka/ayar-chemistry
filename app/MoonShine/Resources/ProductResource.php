<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;


use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Attributes\SearchUsingFullText;
use MoonShine\Support\DTOs\FileItemExtra;
use MoonShine\UI\Components\Boolean;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\When;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use Ramsey\Uuid\Type\Decimal;

/**
 * @extends ModelResource<Product>
 */
class ProductResource extends ModelResource
{
    protected string $model = Product::class;

    protected string $title = 'Products';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Наименование товара', 'title'),
            Number::make('кол-во товара', 'quantity'),
            BelongsTo::make('Ивент','priceEvent', resource: PriceEventResource::class, formatted: 'title')
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
                   Box::make('Обязательные поля',[
                       ID::make(),
                       Text::make('Наименование товара', 'title'),
                       Image::make('Фото', 'image_url'),
                       Number::make('кол-во товара', 'quantity'),
                       BelongsTo::make('Категория', 'category',
                           formatted: fn($item) => "$item->title" , resource: CategoryResource::class),
                       Text::make('Вес/объём', 'size'),
                       BelongsToMany::make('Оптовые цены', 'priceTiers',
                           resource: PriceTierResource::class, formatted: fn($item) => "от $item->from_quantity шт. - $item->price руб.")
                          ->asyncSearch('from_quantity')->selectMode()
                           ->placeholder('Поиск по кол-ву товара')


                   ])
                ]),
                Column::make([
                    Box::make('Необязательные поля', [
                        Slug::make('Slug')
                            ->from('title'),
                        BelongsTo::make('Ивент','priceEvent', resource: PriceEventResource::class, formatted: 'title')->nullable()->creatable(),
                        Number::make('цена закупа', 'purchase_price')->buttons()->min(0)->step(0.01),
                        Text::make('описание', 'description'),
                        Text::make('sku', 'sku'),
                    ])
                ])

            ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [



                        ID::make(),
                        Text::make('Наименование товара', 'title')->reactive(),
                        Image::make('Фото', 'image_url')->extraAttributes(
                            fn(string $filename, int $index): ?FileItemExtra => new FileItemExtra(wide: false, auto: true, styles: 'width: 250px;')
                        ),
                         HasMany::make('Оптовые цены', 'priceTiers', resource: PriceTierResource::class)->relatedLink()->creatable(),
                        Number::make('кол-во товара', 'quantity'),
                        Slug::make('Slug')
                            ->from('title')->live(),
                        Number::make('цена закупа', 'purchase_price'),
                        Text::make('описание', 'description'),
                        Text::make('sku', 'sku')->nullable(),





        ];
    }
    #[SearchUsingFullText(['title', 'text'])]
    protected function search(): array
    {
        return ['id', 'name', 'sku'];
    }
}
    /**
     * @param Product $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-ru
     * **/
