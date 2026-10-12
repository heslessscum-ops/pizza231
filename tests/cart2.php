<?php

declare(strict_types=1);

namespace Tests\Models;

use App\Models\Cart;
use PHPUnit\Framework\TestCase;

class CartErrorTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        Cart::clear();
    }

    protected function tearDown(): void
    {
        if (isset($_SESSION)) {
            unset($_SESSION[Cart::STORAGE_KEY]);
            @session_destroy();
        }
    }

    public function testAddItemWithInvalidIdType(): void
    {
        // Передаём строку вместо int для $id — это вызовет TypeError
        $invalidId = 'not_an_integer'; 
        $name = 'Bad Product';
        $price = 50.0;

        // Ожидаем, что метод выбросит TypeError
        $this->expectException(\TypeError::class);
        Cart::add($invalidId, $name, $price);
    }
}
