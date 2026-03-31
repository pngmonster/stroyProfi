<?php
require_once __DIR__ . '/bootstrap.php';

// Получение активной вкладки
$tab = $_GET['tab'] ?? 'clients';

// Получение данных
$clients = Client::all();
$workers = Worker::all();
$orders = Order::with('client')->get();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>
    <link rel="stylesheet" href="css/adminStyle.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Админ панель</h1>
        </header>

        <div class="tabs">
            <a href="?tab=clients" class="tab <?= $tab === 'clients' ? 'active' : '' ?>">
                📋 Все клиенты
            </a>
            <a href="?tab=workers" class="tab <?= $tab === 'workers' ? 'active' : '' ?>">
                👥 Все сотрудники
            </a>
            <a href="?tab=orders" class="tab <?= $tab === 'orders' ? 'active' : '' ?>">
                📦 Все заказы
            </a>
        </div>

        <div class="tab-content">
            <?php if ($tab === 'clients'): ?>
                <div class="clients-section">
                    <div class="stats">
                        <div class="stat-card">
                            <span class="stat-label">Всего клиентов</span>
                            <span class="stat-value"><?= $clients->count() ?></span>
                        </div>
                    </div>
                    
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Имя</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Дата регистрации</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td><?= $client->id ?></td>
                                    <td><?= htmlspecialchars($client->name) ?></td>
                                    <td><?= htmlspecialchars($client->email) ?></td>
                                    <td><?= htmlspecialchars($client->phone) ?></td>
                                    <td><?= date('d.m.Y H:i', strtotime($client->created_at)) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($tab === 'workers'): ?>
                <div class="workers-section">
                    <div class="stats">
                        <div class="stat-card">
                            <span class="stat-label">Всего сотрудников</span>
                            <span class="stat-value"><?= $workers->count() ?></span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-label">Заняты</span>
                            <span class="stat-value"><?= $workers->where('is_busy', true)->count() ?></span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-label">Свободны</span>
                            <span class="stat-value"><?= $workers->where('is_busy', false)->count() ?></span>
                        </div>
                    </div>

                    <button class="btn-add" onclick="openAddModal()">➕ Добавить сотрудника</button>

                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ФИО</th>
                                    <th>Навык</th>
                                    <th>Телефон</th>
                                    <th>Зарплата</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($workers as $worker): ?>
                                <tr>
                                    <td><?= $worker->id ?></td>
                                    <td><?= htmlspecialchars($worker->full_name) ?></td>
                                    <td><?= htmlspecialchars($worker->skill) ?></td>
                                    <td><?= htmlspecialchars($worker->phone) ?></td>
                                    <td><?= number_format($worker->salary, 0, '', ' ') ?> ₽</td>
                                    <td>
                                        <span class="status-badge <?= $worker->is_busy ? 'status-busy' : 'status-free' ?>">
                                            <?= $worker->is_busy ? 'Занят' : 'Свободен' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn-toggle" onclick="toggleBusy(<?= $worker->id ?>, <?= $worker->is_busy ? 'false' : 'true' ?>)">
                                            <?= $worker->is_busy ? 'Освободить' : 'Занять' ?>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Модальное окно добавления сотрудника -->
                <div id="addModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeAddModal()">&times;</span>
                        <h2>Добавить сотрудника</h2>
                        <form id="addWorkerForm">
                            <div class="form-group">
                                <label>ФИО:</label>
                                <input type="text" name="full_name" required>
                            </div>
                            <div class="form-group">
                                <label>Навык:</label>
                                <input type="text" name="skill" required>
                            </div>
                            <div class="form-group">
                                <label>Телефон:</label>
                                <input type="text" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label>Зарплата:</label>
                                <input type="number" name="salary" step="1000" required>
                            </div>
                            <button type="submit" class="btn-submit">Сохранить</button>
                        </form>
                    </div>
                </div>

            <?php elseif ($tab === 'orders'): ?>
                <div class="orders-section">
                    <div class="stats">
                        <div class="stat-card">
                            <span class="stat-label">Всего заказов</span>
                            <span class="stat-value"><?= $orders->count() ?></span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-label">Общая стоимость</span>
                            <span class="stat-value"><?= number_format($orders->sum('price'), 0, '', ' ') ?> ₽</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-label">Общая площадь</span>
                            <span class="stat-value"><?= number_format($orders->sum('area'), 1) ?> м²</span>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Клиент</th>
                                    <th>Стоимость</th>
                                    <th>Площадь</th>
                                    <th>Адрес</th>
                                    <th>Дата создания</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= $order->id ?></td>
                                    <td><?= htmlspecialchars($order->title) ?></td>
                                    <td><?= htmlspecialchars($order->client->name ?? 'Не указан') ?></td>
                                    <td><?= number_format($order->price, 0, '', ' ') ?> ₽</td>
                                    <td><?= number_format($order->area, 1) ?> м²</td>
                                    <td><?= htmlspecialchars($order->address) ?></td>
                                    <td><?= date('d.m.Y', strtotime($order->created_at)) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Функции для работы с сотрудниками
        function toggleBusy(id, newStatus) {
            fetch('functions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=toggle_busy&id=' + id + '&status=' + newStatus
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Ошибка: ' + data.message);
                }
            });
        }

        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        document.getElementById('addWorkerForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'add_worker');
            
            fetch('functions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Ошибка: ' + data.message);
                }
            });
        });

        // Закрытие модального окна при клике вне его
        window.onclick = function(event) {
            const modal = document.getElementById('addModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>