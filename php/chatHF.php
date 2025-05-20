<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: text/plain; charset=utf-8');

$input  = json_decode(file_get_contents('php://input'), true);
$resumo = $input['resumo'] ?? [];

$hfApiKey = getenv('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') ?: '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';
if (!$hfApiKey) {
    echo 'Erro: chave da HuggingFace API não configurada.';
    exit;
}

// Usar modelo gratuito 't5-small'

$endpoint = 'https://api-inference.huggingface.co/models/t5-small';

$prompt = "Você é um assistente financeiro. Com base neste resumo:\n"
        . json_encode($resumo, JSON_PRETTY_PRINT)
        . "\nForneça 3 dicas práticas para reduzir gastos.";

$payload = json_encode([
    'inputs'  => $prompt,
    'options' => ['wait_for_model' => true]
]);

$ch = curl_init($endpoint);
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $hfApiKey,
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

if ($httpCode === 404) {
    echo "Erro: modelo '{$model}' não encontrado na API HuggingFace.";
    exit;
}

if ($httpCode !== 200) {
    echo "HuggingFace API error (HTTP {$httpCode}): {$response}";
    exit;
}

$data = json_decode($response, true);
if (isset($data[0]['generated_text'])) {
    echo $data[0]['generated_text'];
} else {
    echo 'Erro: resposta inválida da HuggingFace API.';
}