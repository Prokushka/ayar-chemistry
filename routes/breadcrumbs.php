<?php

use App\Models\Category;
use App\Models\Product;
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('main', function (Diglactic\Breadcrumbs\Generator $trail) {
    $trail->push('Главная', route('main'));
});

// Все категории
Breadcrumbs::for('product.index', fn(Diglactic\Breadcrumbs\Generator $trail) => [
    $trail->parent('main'),
    $trail->push('Товары', route('product.index'))
]);

Breadcrumbs::for('order.index', fn(Diglactic\Breadcrumbs\Generator $trail) => [
    $trail->parent('main'),
    $trail->push('Заказы', route('order.index'))
]);
Breadcrumbs::for('order.check', fn(Diglactic\Breadcrumbs\Generator $trail, int $id) => [
    $trail->parent('order.index'),
    $trail->push($id, route('order.check', $id))
]);

Breadcrumbs::for('product.category.show', function (Diglactic\Breadcrumbs\Generator $trails, Category $category){
    $trails->parent('product.index');
    $trails->push($category->title, route('product.category.show', $category->slug));
});

// Конкретная категория
Breadcrumbs::for('product.show', fn(Diglactic\Breadcrumbs\Generator $trail, Product $product) => [
    $trail->parent('product.category.show', $product->category)
        ->push($product->title, route('product.show', $product->slug))
]);

