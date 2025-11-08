<?php
$env = require __DIR__ . '/.env.php';
$dsn = "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];
try {
  $pdo = new PDO($dsn, $env['DB_USER'], $env['DB_PASS'], $options);
} catch (PDOException $e) {
  http_response_code(500);
  echo "DB connection failed.";
  error_log("DB error: ".$e->getMessage());
  exit;
}
