<?php
session_start();
require_once __DIR__ . '/bootstrap.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        $client = Client::where('email', $email)->first();
        
        if ($client && password_verify($password, $client->password_hash)) {
            $_SESSION['client_id'] = $client->id;
            $_SESSION['client_name'] = $client->name;
            $_SESSION['client_email'] = $client->email;
            header('Location: client_dashboard.php');
            exit;
        } else {
            $error = 'Неверный email или пароль';
        }
    } else {
        $error = 'Заполните все поля';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - СтройПрофи</title>
    <link rel="stylesheet" href="css/autorizStyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="logo-header">
            <h1 class="logo">СтройПрофи</h1>
            <p class="subtitle">Вход в личный кабинет</p>
        </div>

        <div class="auth-form active" id="authForm">
            <?php if ($error): ?>
                <div class="message error" style="display: block; background: #fee; color: #d32f2f; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Ваш email">
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required placeholder="Ваш пароль">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Войти</button>
                </div>

                <div class="form-footer">
                    <p>Нет аккаунта? <a href="register_client.php">Зарегистрироваться</a></p>
                    <p style="margin-top: 10px;">
                        <a href="index.html" style="color: var(--primary-color); text-decoration: none;">
                            <i class="fas fa-arrow-left"></i> Вернуться на главную
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>