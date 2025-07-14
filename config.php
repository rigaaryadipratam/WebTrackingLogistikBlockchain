<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'logistics_tracker');


// Blockchain configuration
define('CONTRACT_ADDRESS', '0x02186DC50B534c48D37ea2d2c60FF40aC28e92c8'); 
define('BLOCKCHAIN_PROVIDER', 'http://localhost:7545'); 

// Metadata path dari contract yang sudah di-compile
define('CONTRACT_ABI_PATH', __DIR__ .'/blockchain/build/contracts/LogisticsTracker.json');

// Check if ABI file exists
if (!file_exists(CONTRACT_ABI_PATH)) {
    error_log('Warning: Contract ABI file not found: '. CONTRACT_ABI_PATH);
}