// PHP/index_php.php
<?php
require_once __DIR__ . '/../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor     = $_POST['valor'];
    $categoria = $_POST['categoria'];
    $data      = $_POST['data'];
    $descricao = $_POST['descricao'] ?? '';

    $stmt = $conn->prepare(
        "INSERT INTO despesas (valor, categoria, data, descricao) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('dsss', $valor, $categoria, $data, $descricao);
    $stmt->execute();
    $stmt->close();
}

header('Location: ../views/despesas.php');
exit;
