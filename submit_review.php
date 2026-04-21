<?php
session_start();
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/json');

if (!isset($_SESSION['client_id'])) {
    echo json_encode(['success' => false, 'message' => 'Не авторизован']);
    exit;
}

$stars = (int)($_POST['stars'] ?? 0);
$text = trim($_POST['text'] ?? '');

if ($stars < 1 || $stars > 5) {
    echo json_encode(['success' => false, 'message' => 'Неверная оценка']);
    exit;
}

if (empty($text)) {
    echo json_encode(['success' => false, 'message' => 'Введите текст отзыва']);
    exit;
}

$client_id = $_SESSION['client_id'];

// Проверяем, есть ли уже отзыв
$existing = Review::where('client_id', $client_id)->first();
if ($existing) {
    echo json_encode(['success' => false, 'message' => 'Вы уже оставляли отзыв']);
    exit;
}

try {
    $review = Review::create([
        'client_id' => $client_id,
        'stars' => $stars,
        'text' => $text
    ]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>