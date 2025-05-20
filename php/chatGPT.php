<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: text/plain; charset=utf-8');

// Recupera payload JSON
$input = json_decode(file_get_contents('php://input'), true);
$resumo = $input['resumo'] ?? [];

// Chave da OpenAI (definida diretamente)
$apiKey = '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';

// Prepara mensagens para o chat
$messages = [
    ['role' => 'system', 'content' => 'Você é um assistente financeiro.'],
    ['role' => 'user', 'content' => "Com base neste resumo:\n" . json_encode($resumo, JSON_PRETTY_PRINT) . "\nForneça 3 dicas práticas para reduzir gastos."]
];

$payload = json_encode([
    'model'    => 'gpt-3.5-turbo',
    'messages' => $messages
]);

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
if ($response === false) {
    echo 'Curl error: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "API error: HTTP $httpCode\n$response";
    exit;
}

if ($httpCode === 429) {
    echo "Erro: cota excedida na API do OpenAI. Verifique seu plano e limites de uso.";
    exit;
}

$data = json_decode($response, true);
if (isset($data['choices'][0]['message']['content'])) {
    echo $data['choices'][0]['message']['content'];
} else {
    echo 'Erro ao gerar análise: resposta inválida da API.';
}