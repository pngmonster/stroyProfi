<?php
session_start();
require_once __DIR__ . '/bootstrap.php';

if (!isset($_SESSION['client_id'])) {
    header('Location: login_client.php');
    exit;
}

$client = Client::find($_SESSION['client_id']);
$orders = Order::where('client_id', $client->id)->get();
$reviews = Review::where('client_id', $client->id)->get();
$hasReview = $reviews->isNotEmpty();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - <?= htmlspecialchars($client->name) ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="css/autorizStyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--secondary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
        }
        .order-card {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        .order-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .order-title {
            font-weight: 600;
            font-size: 16px;
            color: var(--primary-color);
        }
        .order-details {
            display: flex;
            gap: 20px;
            margin-top: 10px;
            font-size: 14px;
            color: var(--text-light);
        }
        .review-form {
            margin-top: 20px;
        }
        .star-rating {
            display: flex;
            gap: 10px;
            margin: 15px 0;
            cursor: pointer;
        }
        .star {
            font-size: 24px;
            color: #ddd;
            transition: color 0.3s ease;
        }
        .star.active {
            color: #ffc107;
        }
        .review-text {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            font-family: inherit;
        }
        .btn-submit-review {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }
        .existing-review {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .stars-display {
            color: #ffc107;
            margin-bottom: 10px;
        }
        .logout-btn {
            background: #d32f2f;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }
        .btn-calculator {
            background: var(--accent-color);
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-header">
            <h1 class="logo">СтройПрофи</h1>
            <p class="subtitle">Личный кабинет</p>
        </div>

        <div class="dashboard active" id="dashboard">
            <div class="user-info">
                <div class="user-avatar"><?= mb_substr($client->name, 0, 1) ?></div>
                <div class="user-details">
                    <h3><?= htmlspecialchars($client->name) ?></h3>
                    <p><?= htmlspecialchars($client->email) ?></p>
                    <p><?= htmlspecialchars($client->phone) ?></p>
                </div>
            </div>

            <!-- Мои заказы -->
            <div class="dashboard-section">
                <div class="section-title">
                    <i class="fas fa-box"></i> Мои заказы
                    <span style="float: right; font-size: 14px;">Всего: <?= $orders->count() ?></span>
                </div>
                
                <?php if ($orders->isEmpty()): ?>
                    <p style="text-align: center; color: var(--text-light); padding: 20px;">У вас пока нет заказов</p>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="order-card">
                            <div class="order-title"><?= htmlspecialchars($order->title) ?></div>
                            <div class="order-details">
                                <span><i class="fas fa-ruble-sign"></i> <?= number_format($order->price, 0, '', ' ') ?> ₽</span>
                                <span><i class="fas fa-vector-square"></i> <?= $order->area ?> м²</span>
                                <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($order->address) ?></span>
                                <span><i class="fas fa-calendar"></i> <?= date('d.m.Y', strtotime($order->created_at)) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Оставить отзыв -->
            <div class="dashboard-section">
                <div class="section-title">
                    <i class="fas fa-star"></i> Отзыв о компании
                </div>
                
                <?php if ($hasReview): ?>
                    <div class="existing-review">
                        <div class="stars-display">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $reviews->first()->stars ? 'active' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p><?= htmlspecialchars($reviews->first()->text) ?></p>
                        <small style="color: var(--text-light);">Отзыв оставлен: <?= date('d.m.Y', strtotime($reviews->first()->created_at)) ?></small>
                    </div>
                <?php else: ?>
                    <form id="reviewForm" class="review-form">
                        <div class="star-rating" id="starRating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star star" data-value="<?= $i ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <textarea class="review-text" id="reviewText" rows="4" placeholder="Расскажите о своем опыте работы с нашей компанией..."></textarea>
                        <button type="submit" class="btn-submit-review">Отправить отзыв</button>
                    </form>
                <?php endif; ?>
            </div>

            <button class="logout-btn" onclick="window.location.href='count.html'">
                <i class="fas fa-calculator"></i> Перейти к калькулятору
            </button>
            
            <button class="logout-btn" onclick="window.location.href='logout_client.php'">
                <i class="fas fa-sign-out-alt"></i> Выйти из аккаунта
            </button>

            <div style="margin-top: 20px; text-align: center;">
                <a href="index.html" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">
                    <i class="fas fa-arrow-left"></i> Вернуться на главную
                </a>
            </div>
        </div>
    </div>

    <script>
        // Звездный рейтинг
        const stars = document.querySelectorAll('.star');
        let selectedRating = 0;

        stars.forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = parseInt(this.dataset.value);
                updateStars(selectedRating);
            });
        });

        function updateStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        // Отправка отзыва
        document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const text = document.getElementById('reviewText').value.trim();
            
            if (selectedRating === 0) {
                alert('Пожалуйста, выберите оценку');
                return;
            }
            
            if (!text) {
                alert('Пожалуйста, напишите текст отзыва');
                return;
            }
            
            fetch('submit_review.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'stars=' + selectedRating + '&text=' + encodeURIComponent(text)
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
    </script>
</body>
</html>