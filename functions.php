<?php
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'toggle_busy') {
    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;
    
    if (!$id || $status === null) {
        echo json_encode(['success' => false, 'message' => 'Неверные параметры']);
        exit;
    }
    
    $worker = Worker::find($id);
    if (!$worker) {
        echo json_encode(['success' => false, 'message' => 'Сотрудник не найден']);
        exit;
    }
    
    $worker->is_busy = (bool)$status;
    $worker->save();
    
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'add_worker') {
    $full_name = $_POST['full_name'] ?? '';
    $skill = $_POST['skill'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $salary = $_POST['salary'] ?? 0;
    
    if (!$full_name || !$skill || !$phone || !$salary) {
        echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
        exit;
    }
    
    try {
        $worker = Worker::create([
            'full_name' => $full_name,
            'skill' => $skill,
            'phone' => $phone,
            'salary' => $salary,
            'is_busy' => false
        ]);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);