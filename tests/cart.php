<?php

declare(strict_types=1);

namespace Tests\Models;

use App\Models\Cart;
use PHPUnit\Framework\TestCase;

class CartSuccessTest extends TestCase
{
    protected function setUp(): void
    {
        // Инициализируем сессию для теста
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        // Очищаем корзину перед каждым тестом
        Cart::clear();
    }

    protected function tearDown(): void
    {
        // Гарантированно очищаем сессию после теста
        if (isset($_SESSION)) {
            unset($_SESSION[Cart::STORAGE_KEY]);
            @session_destroy();
        }
    }

    public function testAddItemAndIncrementQuantity(): void
    {
        $id = 101;
        $name = 'Test Product';
        $price = 99.99;
        $image = '/assets/img/test.jpg';
        $quantity = 2;

        // 1. Добавляем товар впервые
        $resultAdd = Cart::add($id, $name, $price, $image, $quantity);
        self::assertTrue($resultAdd, 'Добавление товара должно возвращать true');

        $cart = Cart::getItems();
        self::assertCount(1, $cart, 'В корзине должен быть 1 товар');
        self::assertEquals($id, $cart[0]['id']);
        self::assertEquals($quantity, $cart[0]['quantity']);

        // 2. Добавляем тот же товар ещё раз (должно увеличиться количество)
        $additionalQuantity = 3;
        $resultIncrement = Cart::add($id, $name, $price, $image, $additionalQuantity);
        self::assertTrue($resultIncrement, 'Повторное добавление должно возвращать true');

        $updatedCart = Cart::getItems();
        self::assertCount(1, $updatedCart, 'Количество позиций не должно увеличиться, только quantity');
        self::assertEquals($quantity + $additionalQuantity, $updatedCart[0]['quantity']);
    }
}
