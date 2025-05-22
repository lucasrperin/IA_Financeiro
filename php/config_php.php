<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $api_key = trim($_POST['api_key'] ?? '');
    $stmt = $conn->prepare("
        INSERT INTO settings (name, value) VALUES ('cohere_api_key', ?)
        ON DUPLICATE KEY UPDATE value = VALUES(value)
    ");
    $stmt->bind_param('s', $api_key);
    $stmt->execute();
    $stmt->close();
    $saved = true;
}

// lê a chave atual
$stmt = $conn->prepare("SELECT value FROM settings WHERE name = 'cohere_api_key'");
$stmt->execute();
$stmt->bind_result($currentKey);
$stmt->fetch();
$stmt->close();
?>