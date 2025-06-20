<?php
// Configurações de conexão
$host     = 'localhost';
$usuario  = 'root';
$senha    = '';
$banco    = 'controle_financeiro';


// Cria a conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica erros
if ($conn->connect_error) {
    die('Falha na conexão: ' . $conn->connect_error);
}

// Ajusta charset
$conn->set_charset('utf8mb4');
?>
