<?php
namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\AuthController;

class AuthControllerRegisterTest extends TestCase
{
    /** @test */
    public function it_validates_empty_email()
    {
        $controller = new AuthController();
        $requestData = [
            'name' => 'Иван Иванов',
            'email' => '',
            'password' => 'securepassword123',
            'password_confirm' => 'securepassword123'
        ];
        
        // Ожидаем, что метод вернет ошибку валидации
        $result = $controller->register($requestData);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Email обязателен', $result['message']);
    }

    /** @test */
    public function it_validates_password_mismatch()
    {
        $controller = new AuthController();
        $requestData = [
            'name' => 'Иван Иванов',
            'email' => 'ivan@example.com',
            'password' => 'password1',
            'password_confirm' => 'password2'
        ];
        
        $result = $controller->register($requestData);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Пароли не совпадают', $result['message']);
    }
}
