<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use Illuminate\Support\Facades\Route;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Support\DTOs\AsyncCallback;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Burger;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;

#[\MoonShine\MenuManager\Attributes\SkipMenu]

class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Ваш Профиль';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
	{
        return [
            Grid::make([
                Column::make([
                    ActionButton::make('Экспортировать Excel')->style("color: #2e7d32")
                        ->setUrl(route('excel.download'))
                ])->columnSpan(4),

            ])
        ];
	}
}
