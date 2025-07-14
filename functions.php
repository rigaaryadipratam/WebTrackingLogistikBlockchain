<?php

function formatTimestamp($timestamp)
{
    return date('Y-m-d H:i:s', $timestamp);
}


function checkBlockchainConnection()
{
    $url = BLOCKCHAIN_PROVIDER;
    $data = json_encode([
        'jsonrpc' => '2.0',
        'method' => 'web3_clientVersion',
        'params' => [],
        'id' => 1
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status == 200) {
        $result = json_decode($response, true);
        if (isset($result['result'])) {
            return [
                'connected' => true,
                'version' => $result['result']
            ];
        }
    }

    return [
        'connected' => false,
        'error' => $response
    ];
}


function verifyContractAddressFormat($address)
{
    return preg_match('/^0x[a-fA-F0-9]{40}$/', $address);
}


function getContractABI()
{
    if (file_exists(CONTRACT_ABI_PATH)) {
        $contractJson = file_get_contents(CONTRACT_ABI_PATH);
        $contract = json_decode($contractJson, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($contract['abi'])) {
            return $contract['abi'];
        }
    }
    return null;
}

function updateBlockchainStatus($barcode, $synced = true)
{
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE location_updates SET blockchain_synced = ? WHERE barcode = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("is", $synced, $barcode);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
