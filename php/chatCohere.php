<?php
// chatCohere.php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: text/plain; charset=utf-8');

// lê a chave da tabela settings
$stmt = $conn->prepare("SELECT value FROM settings WHERE name = 'cohere_api_key'");
$stmt->execute();
$stmt->bind_result($cohereKey);
$stmt->fetch();
$stmt->close();

$input      = json_decode(file_get_contents('php://input'), true);
$resumo     = $input['resumo']   ?? [];
$userPrompt = trim($input['prompt'] ?? '');

$resumoJson = json_encode($resumo, JSON_PRETTY_PRINT);
if ($userPrompt !== '') {
    $finalPrompt = $userPrompt
                 . "\n\nCom base nos seguintes dados de despesas:\n"
                 . $resumoJson;
} else {
    $finalPrompt = "Você é um assistente financeiro. Com base neste resumo:\n"
                 . $resumoJson
                 . "\nForneça 3 dicas práticas para reduzir gastos.";
}

if (!$cohereKey) {
    echo 'Erro: chave da Cohere API não configurada.';
    exit;
}

$payload = json_encode([
    'model'       => 'command-r-plus',
    'prompt'      => $finalPrompt,
    'max_tokens'  => 500,
    'temperature' => 0.7
]);

$ch = curl_init('https://api.cohere.ai/generate');
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $cohereKey,
        'Content-Type: application/json'
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
echo isset($data['text'])
     ? trim($data['text'])
     : 'Erro: resposta inválida da Cohere API.';
