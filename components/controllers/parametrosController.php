<?php
// components/controllers/parametrosController.php
include_once __DIR__ . "/../config/database.php";

/**
 * Busca o parâmetro pela combinação (modulo, chave).
 * Retorna array associativo [id, modulo, chave, valor, criado_em, atualizado_em] ou false se não existir.
 */
function getParametro($modulo, $chave) {
    global $conn;
    $sql = "SELECT * FROM parametros WHERE modulo = ? AND chave = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $modulo, $chave);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return false;
    }
    return $result->fetch_assoc();
}

/**
 * Insere um novo parâmetro.
 */
function insertParametro($modulo, $chave, $valor) {
    global $conn;
    $sql = "INSERT INTO parametros (modulo, chave, valor) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $modulo, $chave, $valor);
    return $stmt->execute();
}

/**
 * Atualiza o valor de um parâmetro existente (e ajusta atualizado_em automaticamente).
 */
function updateParametro($modulo, $chave, $valor) {
    global $conn;
    $sql = "UPDATE parametros SET valor = ? WHERE modulo = ? AND chave = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $valor, $modulo, $chave);
    return $stmt->execute();
}
?>
