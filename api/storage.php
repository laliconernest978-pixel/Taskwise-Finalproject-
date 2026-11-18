<?php
// Small compatibility API: the existing TaskWise prototype uses localStorage.
// This endpoint persists its shared data keys in MySQL without rewriting the UI.
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
require_once __DIR__ . '/../config/database.php';

$allowed = ['taskwiseUsers', 'taskwiseAssignments', 'taskwiseFiles'];
function respond(int $status, array $data): never {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}
try {
    $pdo = taskwise_db();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->query('SELECT storage_key, storage_value FROM taskwise_app_data');
        $data = [];
        foreach ($stmt as $row) $data[$row['storage_key']] = $row['storage_value'];
        respond(200, ['ok' => true, 'data' => $data]);
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: GET, POST');
        respond(405, ['ok' => false, 'error' => 'Method not allowed']);
    }
    $body = json_decode(file_get_contents('php://input'), true);
    if (!is_array($body)) respond(400, ['ok' => false, 'error' => 'Invalid JSON request']);
    $action = $body['action'] ?? 'save';
    if ($action === 'clear') {
        $pdo->exec('DELETE FROM taskwise_app_data');
        respond(200, ['ok' => true]);
    }
    $key = $body['key'] ?? '';
    if (!in_array($key, $allowed, true)) respond(400, ['ok' => false, 'error' => 'Unsupported storage key']);
    if ($action === 'remove') {
        $stmt = $pdo->prepare('DELETE FROM taskwise_app_data WHERE storage_key = ?');
        $stmt->execute([$key]);
        respond(200, ['ok' => true]);
    }
    if ($action !== 'save' || !isset($body['value']) || !is_string($body['value'])) {
        respond(400, ['ok' => false, 'error' => 'Invalid storage operation']);
    }
    // Ensure stored values are valid JSON before saving them.
    json_decode($body['value']);
    if (json_last_error() !== JSON_ERROR_NONE) respond(400, ['ok' => false, 'error' => 'Storage value must contain valid JSON']);
    $stmt = $pdo->prepare('INSERT INTO taskwise_app_data (storage_key, storage_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE storage_value = VALUES(storage_value), updated_at = CURRENT_TIMESTAMP');
    $stmt->execute([$key, $body['value']]);
    respond(200, ['ok' => true]);
} catch (Throwable $e) {
    error_log('TaskWise storage API: ' . $e->getMessage());
    respond(500, ['ok' => false, 'error' => 'Database connection or storage error. Check config/database.php and MySQL.']);
}
