<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!file_exists(CONTRACT_ABI_PATH)) {
    echo json_encode(['error' => 'Contract ABI file not found', 'path' => CONTRACT_ABI_PATH]);
    exit;
}

// Baca file JSON
$contractJson = file_get_contents(CONTRACT_ABI_PATH);
$contract = json_decode($contractJson, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Invalid JSON in contract file', 'json_error' => json_last_error_msg()]);
    exit;
}

// Return ABI dan alamat kontrak
echo json_encode([
    'abi' => $contract['abi'],
    'networks' => $contract['networks'],
    'contractAddress' => CONTRACT_ADDRESS
]);