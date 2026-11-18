<?php
// XAMPP / local development database settings.
// Change DB_USER / DB_PASS if your MySQL installation uses different credentials.
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'taskwise_db');
define('DB_USER', 'root');
define('DB_PASS', '');

function taskwise_db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}
