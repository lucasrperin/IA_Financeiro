<?php
// chatCohere.php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: text/plain; charset=utf-8');

// 1) Busca a chave da Cohere na tabela settings
$stmt = $conn->prepare("SELECT value FROM settings WHERE name = 'cohere_api_key'");
$stmt->execute();
$stmt->bind_result($cohereKey);
$stmt->fetch();
$stmt->close();

$input      = json_decode(file_get_contents('php://input'), true);
$resumo     = $input['resumo']   ?? [];
$userPrompt = trim($input['prompt'] ?? '');

if (!$cohereKey) {
    echo 'Erro: chave da Cohere API não configurada.';
    exit;
}

// 2) Tratamento de saudações simples (não envolve dados financeiros nem salário)
$lc        = mb_strtolower($userPrompt, 'UTF-8');
$greetings = ['bom dia', 'boa tarde', 'boa noite', 'olá', 'ola', 'oi'];
if (in_array($lc, $greetings, true)) {
    echo ucfirst($lc) . "! Em que posso ajudar com suas finanças hoje?";
    exit;
}

// 3) Detecta se é um pedido de análise financeira (inclui “salário” agora)
$financeKeywords = [
    'despesa', 'despesas', 'receita', 'receitas',
    'fluxo de caixa', 'saldo', 'gasto', 'gastos',
    'lucro', 'investimento', 'orçamento', 'percentual',
    'custo', 'contábil', 'balanço', 'análise',
    'salário', 'salario'   // <- adicionadas
];
$isFinance = false;
foreach ($financeKeywords as $kw) {
    if (stripos($lc, $kw) !== false) {
        $isFinance = true;
        break;
    }
}

// 4) Se for análise financeira (isto inclui qualquer menção a “salário”), buscamos o SALÁRIO
$salarioTexto = "";
if ($isFinance) {
    $moduloSal = "despesas";
    $chaveSal  = "salario";

    $stmtSal = $conn->prepare("SELECT valor FROM parametros WHERE modulo = ? AND chave = ? LIMIT 1");
    $stmtSal->bind_param("ss", $moduloSal, $chaveSal);
    $stmtSal->execute();
    $resSal = $stmtSal->get_result();

    if ($resSal->num_rows > 0) {
        $rowSal = $resSal->fetch_assoc();
        $valorSalario = $rowSal['valor']; // ex.: "3500.00"
        // Formata para notação brasileira (opcional):
        setlocale(LC_ALL, 'pt_BR.UTF-8');
        $salBr = number_format((float)$valorSalario, 2, ',', '.'); // ex.: "3.500,00"
        $salarioTexto = "Salário mensal: R\$ {$salBr}\n\n";
    } else {
        // Se não houver nenhum salário cadastrado, avisamos no prompt
        $salarioTexto = "Salário mensal não informado.\n\n";
    }

    $stmtSal->close();
}

// 5) Define o prompt de sistema com as regras
$systemPrompt = <<<TXT
Você é um assistente financeiro especializado em análise de dados financeiros empresariais e pessoais.

Regras de interação:
- Se o usuário solicitar algo relacionado a finanças (despesas, receitas, fluxo de caixa, orçamento, saldo etc.), utilize sempre o valor do salário (se houver) e os dados de despesas fornecidos para fornecer análise conforme solicitado.
- Se o usuário fizer qualquer outra pergunta ou apenas conversar, responda de forma natural e coerente, SEM usar ou mencionar os dados de salário ou despesas.
TXT;

// 6) Monta o histórico de mensagens para a chamada de chat
if ($isFinance) {
    // Inclui SALÁRIO + DESPESAS no conteúdo do usuário
    $conteudoUser = $userPrompt
                  . "\n\n"
                  . $salarioTexto
                  . "Dados de despesas:\n"
                  . json_encode($resumo, JSON_PRETTY_PRINT);
} else {
    // Para qualquer outra pergunta, apenas o texto do usuário
    $conteudoUser = $userPrompt;
}

$messages = [
    ['role' => 'system', 'content' => $systemPrompt],
    ['role' => 'user',   'content' => $conteudoUser]
];

// 7) Chama a API de chat da Cohere
$payload = json_encode([
    'model'       => 'command-r-plus',
    'messages'    => $messages,
    'temperature' => 0.7,
    'max_tokens'  => 500
]);

$ch = curl_init('https://api.cohere.ai/v2/chat');
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $cohereKey,
        'Content-Type: application/json',
    ],
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_RETURNTRANSFER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error    = curl_error($ch);
curl_close($ch);

if ($response === false) {
    echo 'Erro de comunicação: ' . $error;
    exit;
}
if ($httpCode !== 200) {
    echo "Cohere API error (HTTP {$httpCode}): {$response}";
    exit;
}

$data = json_decode($response, true);
echo $data['message']['content'][0]['text'] 
     ?? 'Erro: resposta inválida da Cohere API.';
