<?php
// php/despesas_php.php
require_once __DIR__ . '/../config/database.php';

$modulo = "despesas";
$chave  = "salario";
$salarioAtual = "";

// 1) Se vier POST com o campo “salario”, trata INSERÇÃO ou ATUALIZAÇÃO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salario'])) {
    // 1.1) Sanitização básica: remove “R$” e espaços
    $inputBruto = trim($_POST['salario']);
    $inputBruto = str_replace(['R$', ' '], '', $inputBruto);

    // 1.2) Detecta formato brasileiro (com vírgula) ou formato “padrão” (com ponto)
    if (strpos($inputBruto, ',') !== false) {
        // Há vírgula → assumimos notação brasileira: ponto=milhar, vírgula=decimal
        $inputBruto = str_replace('.', '', $inputBruto);    // remove separador de milhar
        $inputBruto = str_replace(',', '.', $inputBruto);   // converte vírgula em ponto
    } else {
        // Sem vírgula → assumimos que o ponto é separador decimal (ou não há separador)
        // Removemos eventuais vírgulas que não fossem decimais e espaços (já removidos acima)
        $inputBruto = str_replace(',', '', $inputBruto);
        // Mantemos o(s) ponto(s) para casas decimais
    }

    // 1.3) Agora $inputBruto deve ser algo como “3500.00” ou “2550.75”
    if (is_numeric($inputBruto)) {
        // Formata com duas casas decimais
        $valorFormatado = number_format((float)$inputBruto, 2, '.', '');

        // 1.4) Insere ou atualiza o parâmetro no banco
        $stmtParam = $conn->prepare("SELECT id FROM parametros WHERE modulo = ? AND chave = ? LIMIT 1");
        $stmtParam->bind_param("ss", $modulo, $chave);
        $stmtParam->execute();
        $resParam = $stmtParam->get_result();

        if ($resParam->num_rows > 0) {
            // Atualiza
            $stmtUp = $conn->prepare("UPDATE parametros SET valor = ? WHERE modulo = ? AND chave = ?");
            $stmtUp->bind_param("sss", $valorFormatado, $modulo, $chave);
            $stmtUp->execute();
            $stmtUp->close();
        } else {
            // Insere
            $stmtIns = $conn->prepare("INSERT INTO parametros (modulo, chave, valor) VALUES (?, ?, ?)");
            $stmtIns->bind_param("sss", $modulo, $chave, $valorFormatado);
            $stmtIns->execute();
            $stmtIns->close();
        }

        $stmtParam->close();
    }
    // Se não for numérico, não fazemos nada (você pode inserir validação extra e mensagem de erro, se desejar)
}

// 2) Puxamos o valor atual (se existir) para pré-preencher o campo
$stmtParam = $conn->prepare("SELECT valor FROM parametros WHERE modulo = ? AND chave = ? LIMIT 1");
$stmtParam->bind_param("ss", $modulo, $chave);
$stmtParam->execute();
$resParam = $stmtParam->get_result();
if ($resParam->num_rows > 0) {
    $row = $resParam->fetch_assoc();
    $salarioAtual = $row['valor'];
}
$stmtParam->close();

$stmt = $conn->query(
  "SELECT * FROM despesas ORDER BY data DESC"
);
$despesas = $stmt->fetch_all(MYSQLI_ASSOC);
?>