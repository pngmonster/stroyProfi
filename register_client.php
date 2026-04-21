<?php
session_start();
require_once __DIR__ . '/bootstrap.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    if (!$name || !$email || !$phone || !$password || !$confirm) {
        $error = 'Заполните все поля';
    } elseif ($password !== $confirm) {
        $error = 'Пароли не совпадают';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен быть не менее 6 символов';
    } elseif (Client::where('email', $email)->exists()) {
        $error = 'Пользователь с таким email уже существует';
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $client = Client::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password_hash' => $password_hash
            ]);
            
            $success = 'Регистрация успешна! Теперь вы можете войти.';
            
            // Очищаем форму
            $_POST = [];
        } catch (Exception $e) {
            $error = 'Ошибка при регистрации: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - СтройПрофи</title>
    <link rel="stylesheet" href="css/autorizStyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="logo-header">
            <h1 class="logo">СтройПрофи</h1>
            <p class="subtitle">Регистрация</p>
        </div>

        <div class="auth-form active" id="authForm">
            <?php if ($error): ?>
                <div class="message error" style="display: block; background: #fee; color: #d32f2f; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="message success" style="display: block; background: #e8f5e9; color: #388e3c; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" id="name" name="name" required placeholder="Ваше имя" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Ваш email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="text" id="phone" name="phone" required placeholder="+7XXXXXXXXXX" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required placeholder="Придумайте пароль" minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Подтверждение пароля</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Повторите пароль">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='login_client.php'">
                        Уже есть аккаунт
                    </button>
                </div>

                <div class="form-footer">
                    <p>
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