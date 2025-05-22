<?php
require_once __DIR__ . '/../config/database.php';

$stmt = $conn->query(
  "SELECT * FROM despesas ORDER BY data DESC"
);
$despesas = $stmt->fetch_all(MYSQLI_ASSOC);
?>