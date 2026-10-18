<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cart;

class CartTest extends TestCase
{
    /**
     * Подготовка окружения перед каждым тестом
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Инициализируем сессию для тестового окружения
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Очищаем корзину перед каждым тестом для обеспечения изоляции
        $_SESSION['cart'] = [];
    }

    /**
     * Очистка окружения после каждого теста
     */
    protected function tearDown(): void
    {
        unset($_SESSION['cart']);
        parent::tearDown();
    }

    /** @test */
    public function it_adds_item_to_empty_cart()
    {
        Cart::add(1, 'Холодильник Samsung', 55000.00, 'fridge.jpg', 1);
        
        $items = Cart::getItems();
        $this->assertCount(1, $items);
        $this->assertEquals('Холодильник Samsung', $items[0]['name']);
        $this->assertEquals(1, Cart::getCount());
    }

    /** @test */
    public function it_updates_quantity_of_existing_item()
    {
        Cart::add(1, 'Стиральная машина LG', 40000.00);
        Cart::updateQuantity(1, 2); // Увеличиваем количество до 2
        
        $items = Cart::getItems();
        $this->assertEquals(2, $items[0]['quantity']);
        $this->assertEquals(2, Cart::getCount());
    }

    /** @test */
    public function it_calculates_total_price_correctly()
    {
        Cart::add(1, 'Телевизор Sony', 80000.00, '', 1);      // 80 000
        Cart::add(2, 'Пульт ДУ', 2000.00, '', 2);             // 2 * 2 000 = 4 000
        
        // Ожидаемая сумма: 80 000 + 4 000 = 84 000
        $this->assertEquals(84000.00, Cart::getTotal());
    }

    /** @test */
    public function it_removes_item_from_cart()
    {
        Cart::add(1, 'Микроволновка', 15000.00);
        Cart::add(2, 'Электрический чайник', 3000.00);
        
        Cart::remove(1); // Удаляем микроволновку
        
        $items = Cart::getItems();
        $this->assertCount(1, $items);
        $this->assertEquals(2, $items[0]['id']);
        $this->assertEquals(1, Cart::getCount());
    }
}
