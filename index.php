<?php
require_once __DIR__ . '/bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;

try {
    // Проверка подключения
    $pdo = Capsule::connection()->getPdo();
    echo "Подключение к БД успешно!\n\n";
    
    // Проверка создания записи
    $client = Client::create([
        'name' => 'Тестовый Клиент',
        'email' => 'test@example.com',
        'phone' => '+79990001122',
        'password_hash' => password_hash('test123', PASSWORD_DEFAULT)
    ]);
    echo "Создан клиент с ID: " . $client->id . "\n";
    
    // Проверка чтения
    $found = Client::find($client->id);
    echo "Найден клиент: " . $found->name . "\n";
    
    // Проверка обновления
    $found->update(['name' => 'Обновленный Клиент']);
    echo "Клиент обновлен\n";
    
    echo "\nВсе тесты пройдены успешно!\n";
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}