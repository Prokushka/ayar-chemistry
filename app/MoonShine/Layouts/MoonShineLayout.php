<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\UI\Components\{Breadcrumbs,
    Components,
    Layout\Flash,
    Layout\Div,
    Layout\Body,
    Layout\Burger,
    Layout\Content,
    Layout\Footer,
    Layout\Head,
    Layout\Favicon,
    Layout\Assets,
    Layout\Meta,
    Layout\Header,
    Layout\Html,
    Layout\Layout,
    Layout\Logo,
    Layout\Menu,
    Layout\Sidebar,
    Layout\ThemeSwitcher,
    Layout\TopBar,
    Layout\Wrapper,
    When};
use App\MoonShine\Resources\ProductResource;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\OrderResource;
use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\PriceTierResource;
use App\MoonShine\Resources\PriceEventResource;

final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),

        ];
    }

    protected function menu(): array
    {
        return [
            MenuItem::make('Товары', ProductResource::class),
            MenuItem::make('Пользователи', UserResource::class),
            MenuItem::make('Заказы', OrderResource::class),

            MenuItem::make('Категории', CategoryResource::class),
            MenuItem::make('Cкидки на товары', PriceTierResource::class),
            MenuItem::make('Ивенты', PriceEventResource::class),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

         $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();

    }


}
