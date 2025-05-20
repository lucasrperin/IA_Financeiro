<?php
// chatCohere.php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: text/plain; charset=utf-8');

// lê o payload JSON
$input      = json_decode(file_get_contents('php://input'), true);
$resumo     = $input['resumo']   ?? [];
$userPrompt = trim($input['prompt'] ?? '');

// serializa os dados para enviar sempre ao modelo
$resumoJson = json_encode($resumo, JSON_PRETTY_PRINT);

// monta o prompt final: se o usuário escreveu algo, inclui o texto dele + os dados;
// caso contrário, usa o prompt padrão
if ($userPrompt !== '') {
    $finalPrompt = $userPrompt
                 . "\n\nCom base nos seguintes dados de despesas:\n"
                 . $resumoJson;
} else {
    $finalPrompt = "Você é um assistente financeiro. Com base neste resumo:\n"
                 . $resumoJson
                 . "\nForneça 3 dicas práticas para reduzir gastos.";
}

$cohereKey = getenv('COHERE_API_KEY') ?: 'YkR5ExF4aAuu4hVbTbWB4qKc0umqUjjskCpEpisi';
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
