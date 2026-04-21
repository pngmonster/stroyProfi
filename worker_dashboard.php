<?php
session_start();
require_once __DIR__ . '/bootstrap.php';

if (!isset($_SESSION['worker_id'])) {
    header('Location: login.php');
    exit;
}

$worker = Worker::find($_SESSION['worker_id']);
$orders = $worker->orders;

// Расчет зарплаты за текущий месяц
$current_month_orders = $orders->filter(function($order) {
    return $order->created_at->month === now()->month;
});

$total_salary = $worker->salary;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - <?= htmlspecialchars($worker->full_name) ?></title>
    <link rel="stylesheet" href="css/adminStyle.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Здравствуйте, <?= htmlspecialchars($worker->full_name) ?>!</h1>
            <a href="logout.php" style="background: #d32f2f; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;">Выйти</a>
        </div>

        <div class="stats" style="margin-bottom: 30px;">
            <div class="stat-card">
                <span class="stat-label">Статус</span>
                <span class="stat-value"><?= $worker->is_busy ? '🔴 Занят' : '🟢 Свободен' ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Зарплата</span>
                <span class="stat-value"><?= number_format($worker->salary, 0, '', ' ') ?> ₽</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Кол-во заказов</span>
                <span class="stat-value"><?= $orders->count() ?></span>
            </div>
        </div>

        <div class="tab-content">
            <h2 style="margin-bottom: 20px;">📋 Мои заказы</h2>
            
            <?php if ($orders->isEmpty()): ?>
                <p style="text-align: center; color: var(--text-light); padding: 40px;">Нет назначенных заказов</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID заказа</th>
                                <th>Название</th>
                                <th>Адрес</th>
                                <th>Стоимость</th>
                                <th>Площадь</th>
                                <th>Дата назначения</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= $order->id ?></td>
                                    <td><?= htmlspecialchars($order->title) ?></td>
                                    <td><?= htmlspecialchars($order->address) ?></td>
                                    <td><?= number_format($order->price, 0, '', ' ') ?> ₽</td>
                                    <td><?= number_format($order->area, 1) ?> м²</td>
                                    <td><?= date('d.m.Y', strtotime($order->created_at)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>