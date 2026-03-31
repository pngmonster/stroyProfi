<?php
session_start();
require_once __DIR__ . '/bootstrap.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $worker_id = $_POST['worker_id'] ?? '';
    
    if ($worker_id) {
        $worker = Worker::find($worker_id);
        if ($worker) {
            $_SESSION['worker_id'] = $worker->id;
            $_SESSION['worker_name'] = $worker->full_name;
            header('Location: worker_dashboard.php');
            exit;
        } else {
            $error = 'Сотрудник не найден';
        }
    } else {
        $error = 'Выберите сотрудника';
    }
}

$workers = Worker::all();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход для сотрудников</title>
    <link rel="stylesheet" href="css/adminStyle.css">
</head>
<body>
    <div class="admin-container">
        <div class="login-box" style="max-width: 400px; margin: 100px auto;">
            <div class="admin-header" style="text-align: center;">
                <h1>Вход для сотрудников</h1>
            </div>
            
            <?php if ($error): ?>
                <div style="background: #fee; color: #d32f2f; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" style="background: white; padding: 30px; border-radius: 12px; box-shadow: var(--shadow);">
                <div class="form-group">
                    <label for="worker_id">Выберите сотрудника:</label>
                    <select name="worker_id" id="worker_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
                        <option value="">-- Выберите ФИО --</option>
                        <?php foreach ($workers as $worker): ?>
                            <option value="<?= $worker->id ?>"><?= htmlspecialchars($worker->full_name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit" style="width: 100%; margin-top: 20px;">Войти</button>
            </form>
        </div>
    </div>
</body>
</html>