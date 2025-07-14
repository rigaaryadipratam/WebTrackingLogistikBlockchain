<?php
require_once 'config.php';
header('Content-Type: application/json');

try {
    if (!file_exists(CONTRACT_ABI_PATH)) {
        throw new Exception('Contract ABI file not found: ' . CONTRACT_ABI_PATH);
    }

    $contractJson = file_get_contents(CONTRACT_ABI_PATH);
    $contract = json_decode($contractJson, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON in contract file: ' . json_last_error_msg());
    }

    echo json_encode([
        'success' => true,
        'abi' => $contract['abi'],
        'address' => CONTRACT_ADDRESS
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}