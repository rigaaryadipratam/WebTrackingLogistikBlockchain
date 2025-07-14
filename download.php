<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blockchain Data Export - Logistics Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 24px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
        }
        .btn {
            border-radius: 6px;
            font-weight: 500;
        }
        .progress-container {
            display: none;
        }
        .debug-log {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            font-family: monospace;
            font-size: 12px;
            max-height: 300px;
            overflow-y: auto;
            white-space: pre-wrap;
        }
        .raw-log {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 10px;
            margin: 5px 0;
            font-family: monospace;
            font-size: 12px;
        }
        .event-found {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 10px;
            margin: 5px 0;
        }
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .status-success { background-color: #28a745; }
        .status-warning { background-color: #ffc107; }
        .status-danger { background-color: #dc3545; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-box-seam me-2"></i>Logistics Blockchain Tracker
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="bi bi-arrow-left me-1"></i> Back to Main
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h2><i class="bi bi-download me-3"></i>Blockchain Data Export</h2>
                <p class="text-muted">Extract and export all blockchain data including products, transactions, and events</p>
            </div>
        </div>

        <!-- Configuration -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-gear me-2"></i>Configuration
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="ganacheUrl" class="form-label">Ganache URL</label>
                                <input type="text" class="form-control" id="ganacheUrl" value="http://localhost:7545">
                            </div>
                            <div class="col-md-6">
                                <label for="contractAddress" class="form-label">Contract Address</label>
                                <input type="text" class="form-control" id="contractAddress" value="0x02186DC50B534c48D37ea2d2c60FF40aC28e92c8">
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Export Format</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="exportFormat" id="formatJSON" value="json" checked>
                                        <label class="form-check-label" for="formatJSON">
                                            <i class="bi bi-filetype-json me-2"></i>JSON Format
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="exportFormat" id="formatCSV" value="csv">
                                        <label class="form-check-label" for="formatCSV">
                                            <i class="bi bi-filetype-csv me-2"></i>CSV Format
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Actions</h6>
                                    <button class="btn btn-primary me-2" id="scanRawData">
                                        <i class="bi bi-search me-2"></i>Scan Blockchain Data
                                    </button>
                                    <button class="btn btn-success me-2" id="exportAll" disabled>
                                        <i class="bi bi-download me-2"></i>Export Data
                                    </button>
                                    <button class="btn btn-warning" id="clearLogs">
                                        <i class="bi bi-trash me-2"></i>Clear Logs
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3 id="totalLogs">0</h3>
                        <small>Total Logs</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3 id="productEvents">0</h3>
                        <small>Product Events</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3 id="locationEvents">0</h3>
                        <small>Location Events</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress -->
        <div class="row mb-4 progress-container" id="progressCard">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-hourglass-split me-2"></i>Processing Status
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span id="progressStatus">Processing...</span>
                            <span id="progressPercent">0%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Signatures -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-fingerprint me-2"></i>Event Signatures Detected
                    </div>
                    <div class="card-body">
                        <div id="eventSignatures">
                            <p class="text-muted">Click "Scan Blockchain Data" to detect event signatures...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Display -->
        <div class="row">
            <!-- Parsed Events -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-list-check me-2"></i>Parsed Events
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        <div id="parsedEvents">
                            <p class="text-muted">Parsed events will appear here after scanning...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Raw Logs -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-code me-2"></i>Raw Blockchain Logs
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        <div id="rawLogs">
                            <p class="text-muted">Raw logs will appear here after scanning...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debug Log -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-bug me-2"></i>Debug Information</span>
                        <button class="btn btn-sm btn-outline-secondary" id="clearDebugBtn">Clear</button>
                    </div>
                    <div class="card-body">
                        <div id="debugLog" class="debug-log">
                            Debug information will appear here...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Summary -->
        <div class="row mt-4" id="exportSummary" style="display: none;">
            <div class="col-12">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-check-circle me-2"></i>Export Summary
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <h4 class="text-success" id="exportProductCount">0</h4>
                                <small class="text-muted">Products Exported</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-info" id="exportLocationCount">0</h4>
                                <small class="text-muted">Locations Exported</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-primary" id="exportTotalCount">0</h4>
                                <small class="text-muted">Total Events</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/web3@1.8.1/dist/web3.min.js"></script>
    <script>
        let web3;
        let allRawLogs = [];
        let allParsedEvents = [];

        // Debug logging
        function debugLog(message, data = null) {
            const timestamp = new Date().toLocaleTimeString();
            let logMessage = `[${timestamp}] ${message}`;
            if (data) {
                logMessage += `\n${JSON.stringify(data, null, 2)}`;
            }
            
            const debugDiv = document.getElementById('debugLog');
            debugDiv.innerHTML += logMessage + '\n\n';
            debugDiv.scrollTop = debugDiv.scrollHeight;
            console.log(message, data);
        }

        // Show/hide progress
        function showProgress(message, percent = 0) {
            document.getElementById('progressCard').style.display = 'block';
            document.getElementById('progressStatus').textContent = message;
            document.getElementById('progressPercent').textContent = percent + '%';
            document.getElementById('progressBar').style.width = percent + '%';
        }

        function hideProgress() {
            document.getElementById('progressCard').style.display = 'none';
        }

        // Setup event listeners
        document.getElementById('scanRawData').addEventListener('click', scanRawBlockchainData);
        document.getElementById('exportAll').addEventListener('click', exportAllData);
        document.getElementById('clearLogs').addEventListener('click', clearAllLogs);
        document.getElementById('clearDebugBtn').addEventListener('click', () => {
            document.getElementById('debugLog').innerHTML = 'Debug information will appear here...';
        });

        function clearAllLogs() {
            document.getElementById('debugLog').innerHTML = 'Debug information will appear here...';
            document.getElementById('rawLogs').innerHTML = '<p class="text-muted">Raw logs will appear here after scanning...</p>';
            document.getElementById('parsedEvents').innerHTML = '<p class="text-muted">Parsed events will appear here after scanning...</p>';
            document.getElementById('eventSignatures').innerHTML = '<p class="text-muted">Click "Scan Blockchain Data" to detect event signatures...</p>';
            
            // Reset statistics
            document.getElementById('totalLogs').textContent = '0';
            document.getElementById('productEvents').textContent = '0';
            document.getElementById('locationEvents').textContent = '0';
            
            // Hide export summary
            document.getElementById('exportSummary').style.display = 'none';
            document.getElementById('exportAll').disabled = true;
        }

        // Scan raw blockchain data
        async function scanRawBlockchainData() {
            try {
                const ganacheUrl = document.getElementById('ganacheUrl').value;
                const contractAddress = document.getElementById('contractAddress').value;
                
                showProgress('Connecting to blockchain...', 10);
                debugLog('🚀 STARTING RAW BLOCKCHAIN SCAN');
                debugLog('Ganache URL:', ganacheUrl);
                debugLog('Contract Address:', contractAddress);
                
                // Initialize Web3
                web3 = new Web3(ganacheUrl);
                
                // Test connection
                const isConnected = await web3.eth.net.isListening();
                if (!isConnected) {
                    throw new Error('Cannot connect to Ganache!');
                }
                
                showProgress('Connected! Getting blockchain data...', 30);
                const latestBlock = await web3.eth.getBlockNumber();
                debugLog('✅ Connected! Latest block:', latestBlock);
                
                // Get ALL logs for the contract
                showProgress('Scanning contract logs...', 50);
                debugLog('📡 Getting ALL logs for contract...');
                allRawLogs = await web3.eth.getPastLogs({
                    fromBlock: 0,
                    toBlock: 'latest',
                    address: contractAddress
                });
                
                debugLog(`📋 FOUND ${allRawLogs.length} RAW LOGS`);
                
                showProgress('Processing event signatures...', 70);
                // Display raw logs
                displayRawLogs();
                
                // Analyze event signatures
                analyzeEventSignatures();
                
                showProgress('Parsing events...', 85);
                // Parse all events with multiple methods
                parseAllEvents();
                
                showProgress('Updating statistics...', 95);
                // Update statistics
                updateStatistics();
                
                showProgress('Scan completed!', 100);
                setTimeout(hideProgress, 2000);
                
                // Enable export button
                document.getElementById('exportAll').disabled = false;
                
            } catch (error) {
                debugLog('❌ ERROR:', error.message);
                hideProgress();
                alert('Error: ' + error.message);
            }
        }

        // Display raw logs
        function displayRawLogs() {
            const container = document.getElementById('rawLogs');
            
            if (allRawLogs.length === 0) {
                container.innerHTML = '<div class="alert alert-warning"><strong>No logs found!</strong> Check your contract address.</div>';
                return;
            }
            
            let html = `<h6>Found ${allRawLogs.length} Raw Logs:</h6>`;
            
            allRawLogs.forEach((log, index) => {
                html += `
                    <div class="raw-log">
                        <strong>LOG ${index + 1}:</strong><br>
                        <strong>Block:</strong> ${log.blockNumber}<br>
                        <strong>Transaction:</strong> ${log.transactionHash}<br>
                        <strong>Address:</strong> ${log.address}<br>
                        <strong>Topics:</strong> ${log.topics.join(', ')}<br>
                        <strong>Data:</strong> ${log.data}<br>
                        <strong>First Topic (Event Signature):</strong> ${log.topics[0]}
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        // Analyze event signatures - CORE LOGIC PRESERVED
        function analyzeEventSignatures() {
            const container = document.getElementById('eventSignatures');
            const signatures = new Map();
            
            // Expected signatures - FIXED WITH CORRECT SIGNATURE!
            const expectedSigs = {
                'ProductAdded(string,string,address)': web3.utils.keccak256('ProductAdded(string,string,address)'),
                'ProductAdded(string,string)': web3.utils.keccak256('ProductAdded(string,string)'),
                'LocationUpdated(string,string,uint256)': web3.utils.keccak256('LocationUpdated(string,string,uint256)')
            };
            
            debugLog('🔍 Expected Event Signatures:');
            for (const [name, sig] of Object.entries(expectedSigs)) {
                debugLog(`${name} = ${sig}`);
            }
            
            // Analyze actual signatures in logs
            for (const log of allRawLogs) {
                if (log.topics && log.topics.length > 0) {
                    const sig = log.topics[0];
                    if (!signatures.has(sig)) {
                        signatures.set(sig, 0);
                    }
                    signatures.set(sig, signatures.get(sig) + 1);
                }
            }
            
            let html = '<h6>Event Signatures Found in Blockchain:</h6>';
            
            for (const [sig, count] of signatures) {
                const isProductAdded3 = sig === expectedSigs['ProductAdded(string,string,address)'];
                const isProductAdded2 = sig === expectedSigs['ProductAdded(string,string)'];
                const isLocationUpdated = sig === expectedSigs['LocationUpdated(string,string,uint256)'];
                
                let eventName = 'Unknown Event';
                let alertClass = 'alert-warning';
                
                if (isProductAdded3) {
                    eventName = 'ProductAdded(string,string,address) ✅ CORRECT!';
                    alertClass = 'alert-success';
                } else if (isProductAdded2) {
                    eventName = 'ProductAdded(string,string) ⚠️ OLD FORMAT';
                    alertClass = 'alert-warning';
                } else if (isLocationUpdated) {
                    eventName = 'LocationUpdated(string,string,uint256)';
                    alertClass = 'alert-info';
                }
                
                html += `
                    <div class="alert ${alertClass}">
                        <strong>${eventName}</strong><br>
                        Signature: <code>${sig}</code><br>
                        Count: ${count} events
                    </div>
                `;
            }
            
            if (signatures.size === 0) {
                html += '<div class="alert alert-danger">NO EVENT SIGNATURES FOUND!</div>';
            }
            
            container.innerHTML = html;
            
            debugLog('📊 Signature Analysis Complete');
            debugLog('Found signatures:', Object.fromEntries(signatures));
        }

        // Parse all events with multiple methods - CORE LOGIC PRESERVED
        function parseAllEvents() {
            debugLog('🔧 PARSING ALL EVENTS WITH MULTIPLE METHODS');
            allParsedEvents = [];
            
            const container = document.getElementById('parsedEvents');
            let html = '<h6>Parsed Events:</h6>';
            
            for (let i = 0; i < allRawLogs.length; i++) {
                const log = allRawLogs[i];
                debugLog(`Processing log ${i + 1}/${allRawLogs.length}`);
                
                // Try multiple parsing methods
                const parsedEvent = parseEventMultipleMethods(log);
                allParsedEvents.push(parsedEvent);
                
                // Only display ProductAdded and LocationUpdated events
                if (parsedEvent.eventType === 'ProductAdded' || parsedEvent.eventType === 'LocationUpdated') {
                    const alertClass = parsedEvent.eventType === 'ProductAdded' ? 'alert-success' : 'alert-info';
                    
                    html += `
                        <div class="${alertClass}">
                            <strong>Event ${i + 1}: ${parsedEvent.eventType}</strong><br>
                            Block: ${log.blockNumber} | TX: ${log.transactionHash}<br>
                    `;
                    
                    if (parsedEvent.eventType === 'ProductAdded') {
                        html += `Barcode: ${parsedEvent.barcode || 'N/A'} | Name: ${parsedEvent.name || 'N/A'}`;
                        if (parsedEvent.creator) {
                            html += ` | Creator: ${parsedEvent.creator}`;
                        }
                        html += '<br>';
                    } else if (parsedEvent.eventType === 'LocationUpdated') {
                        html += `Barcode: ${parsedEvent.barcode || 'N/A'} | Location: ${parsedEvent.location || 'N/A'}<br>`;
                    }
                    
                    html += `Raw Data: ${log.data}<br>`;
                    html += `</div>`;
                }
            }
            
            container.innerHTML = html;
            debugLog('✅ Event parsing complete');
        }

        // Parse event with multiple methods - CORE LOGIC PRESERVED
        function parseEventMultipleMethods(log) {
            const signature = log.topics[0];
            const productSig3Params = web3.utils.keccak256('ProductAdded(string,string,address)');
            const productSig2Params = web3.utils.keccak256('ProductAdded(string,string)');
            const locationSig = web3.utils.keccak256('LocationUpdated(string,string,uint256)');
            
            debugLog(`Parsing log with signature: ${signature}`);
            debugLog(`ProductAdded(string,string,address) signature: ${productSig3Params}`);
            debugLog(`ProductAdded(string,string) signature: ${productSig2Params}`);
            debugLog(`LocationUpdated signature: ${locationSig}`);
            
            if (signature === productSig3Params) {
                debugLog('🎯 FOUND ProductAdded EVENT WITH 3 PARAMETERS!');
                
                // Method 1: Decode with 3 parameters (barcode, name, creator)
                try {
                    const decoded = web3.eth.abi.decodeLog([
                        { type: 'string', name: 'barcode' },
                        { type: 'string', name: 'name' },
                        { type: 'address', name: 'creator' }
                    ], log.data, log.topics.slice(1));
                    
                    debugLog('✅ Method 1 Success - ProductAdded(3 params) decoded:', decoded);
                    return {
                        eventType: 'ProductAdded',
                        barcode: decoded.barcode,
                        name: decoded.name,
                        creator: decoded.creator,
                        blockNumber: log.blockNumber,
                        transactionHash: log.transactionHash,
                        method: 'ABI Decode (3 params)'
                    };
                } catch (e) {
                    debugLog('⚠️ Method 1 Failed:', e.message);
                }
                
                // Method 2: Manual parameter decode
                try {
                    const decoded = web3.eth.abi.decodeParameters(['string', 'string', 'address'], log.data);
                    
                    debugLog('✅ Method 2 Success - ProductAdded manual decode:', decoded);
                    return {
                        eventType: 'ProductAdded',
                        barcode: decoded[0],
                        name: decoded[1],
                        creator: decoded[2],
                        blockNumber: log.blockNumber,
                        transactionHash: log.transactionHash,
                        method: 'Manual Decode (3 params)'
                    };
                } catch (e) {
                    debugLog('⚠️ Method 2 Failed:', e.message);
                }
                
                // Method 3: Raw data extraction
                debugLog('⚠️ Using Method 3 - Raw data for ProductAdded');
                return {
                    eventType: 'ProductAdded',
                    barcode: 'DECODE_FAILED',
                    name: 'DECODE_FAILED',
                    creator: 'DECODE_FAILED',
                    blockNumber: log.blockNumber,
                    transactionHash: log.transactionHash,
                    rawData: log.data,
                    method: 'Raw Data (Decode Failed)'
                };
                
            } else if (signature === productSig2Params) {
                debugLog('🎯 FOUND ProductAdded EVENT WITH 2 PARAMETERS!');
                
                // Try multiple decoding methods for ProductAdded (2 params)
                
                // Method 1: Standard ABI decode
                try {
                    const decoded = web3.eth.abi.decodeLog([
                        { type: 'string', name: 'barcode' },
                        { type: 'string', name: 'name' }
                    ], log.data, log.topics.slice(1));
                    
                    debugLog('✅ Method 1 Success - ProductAdded(2 params) decoded:', decoded);
                    return {
                        eventType: 'ProductAdded',
                        barcode: decoded.barcode,
                        name: decoded.name,
                        blockNumber: log.blockNumber,
                        transactionHash: log.transactionHash,
                        method: 'ABI Decode (2 params)'
                    };
                } catch (e) {
                    debugLog('⚠️ Method 1 Failed:', e.message);
                }
                
                // Method 2: Manual hex parsing
                try {
                    const decoded = web3.eth.abi.decodeParameters(['string', 'string'], log.data);
                    
                    debugLog('✅ Method 2 Success - ProductAdded manual decode:', decoded);
                    return {
                        eventType: 'ProductAdded',
                        barcode: decoded[0],
                        name: decoded[1],
                        blockNumber: log.blockNumber,
                        transactionHash: log.transactionHash,
                        method: 'Manual Decode (2 params)'
                    };
                } catch (e) {
                    debugLog('⚠️ Method 2 Failed:', e.message);
                }
                
                // Method 3: Raw data extraction
                debugLog('⚠️ Using Method 3 - Raw data for ProductAdded');
                return {
                    eventType: 'ProductAdded',
                    barcode: 'DECODE_FAILED',
                    name: 'DECODE_FAILED',
                    blockNumber: log.blockNumber,
                    transactionHash: log.transactionHash,
                    rawData: log.data,
                    method: 'Raw Data (Decode Failed)'
                };
                
            } else if (signature === locationSig) {
                debugLog('📍 Found LocationUpdated event');
                
                try {
                    const decoded = web3.eth.abi.decodeLog([
                        { type: 'string', name: 'barcode' },
                        { type: 'string', name: 'location' },
                        { type: 'uint256', name: 'timestamp' }
                    ], log.data, log.topics.slice(1));
                    
                    return {
                        eventType: 'LocationUpdated',
                        barcode: decoded.barcode,
                        location: decoded.location,
                        timestamp: decoded.timestamp,
                        blockNumber: log.blockNumber,
                        transactionHash: log.transactionHash,
                        method: 'Standard ABI Decode'
                    };
                } catch (e) {
                    debugLog('⚠️ LocationUpdated decode failed:', e.message);
                    return {
                        eventType: 'LocationUpdated',
                        error: e.message,
                        blockNumber: log.blockNumber,
                        transactionHash: log.transactionHash,
                        rawData: log.data,
                        method: 'Decode Failed'
                    };
                }
            } else {
                debugLog('❓ Unknown event signature');
                return {
                    eventType: 'Unknown',
                    signature: signature,
                    blockNumber: log.blockNumber,
                    transactionHash: log.transactionHash,
                    rawData: log.data,
                    method: 'Unknown Signature'
                };
            }
        }

        // Update statistics
        function updateStatistics() {
            const productCount = allParsedEvents.filter(e => e.eventType === 'ProductAdded').length;
            const locationCount = allParsedEvents.filter(e => e.eventType === 'LocationUpdated').length;
            
            document.getElementById('totalLogs').textContent = allRawLogs.length;
            document.getElementById('productEvents').textContent = productCount;
            document.getElementById('locationEvents').textContent = locationCount;
            
            debugLog('📊 FINAL STATISTICS:');
            debugLog(`Total Logs: ${allRawLogs.length}`);
            debugLog(`ProductAdded Events: ${productCount}`);
            debugLog(`LocationUpdated Events: ${locationCount}`);
            
            if (productCount === 0) {
                debugLog('⚠️ WARNING: NO ProductAdded EVENTS FOUND!');
                debugLog('This could mean:');
                debugLog('1. Wrong contract address');
                debugLog('2. Different event signature');
                debugLog('3. Events not emitted yet');
            }
        }

        // Export all data
        function exportAllData() {
            if (allRawLogs.length === 0) {
                alert('No data to export! Please scan first.');
                return;
            }
            
            showProgress('Preparing export...', 25);
            debugLog('💾 EXPORTING ALL DATA');
            
            const productEvents = allParsedEvents.filter(e => e.eventType === 'ProductAdded');
            const locationEvents = allParsedEvents.filter(e => e.eventType === 'LocationUpdated');
            
            const exportData = {
                metadata: {
                    exportDate: new Date().toISOString(),
                    contractAddress: document.getElementById('contractAddress').value,
                    ganacheUrl: document.getElementById('ganacheUrl').value,
                    totalRawLogs: allRawLogs.length,
                    totalParsedEvents: productEvents.length + locationEvents.length
                },
                rawLogs: allRawLogs,
                productEvents: productEvents,
                locationEvents: locationEvents
            };
            
            showProgress('Generating file...', 75);
            
            // Get selected format
            const format = document.querySelector('input[name="exportFormat"]:checked').value;
            
            if (format === 'json') {
                downloadJSON(exportData);
            } else if (format === 'csv') {
                downloadCSV(exportData);
            }
            
            showProgress('Export completed!', 100);
            setTimeout(hideProgress, 2000);
            
            // Show export summary
            document.getElementById('exportSummary').style.display = 'block';
            document.getElementById('exportProductCount').textContent = productEvents.length;
            document.getElementById('exportLocationCount').textContent = locationEvents.length;
            document.getElementById('exportTotalCount').textContent = productEvents.length + locationEvents.length;
            
            debugLog('✅ Export completed!');
            
            // Show summary alert
            const summaryMessage = `Export completed successfully!\n\n` +
                                 `📦 ProductAdded Events: ${productEvents.length}\n` +
                                 `📍 LocationUpdated Events: ${locationEvents.length}\n` +
                                 `📄 Total Events: ${productEvents.length + locationEvents.length}\n` +
                                 `📄 Total Raw Logs: ${allRawLogs.length}`;
            
            setTimeout(() => alert(summaryMessage), 1000);
        }

        // Download as JSON
        function downloadJSON(data) {
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
            const jsonString = JSON.stringify(data, null, 2);
            const blob = new Blob([jsonString], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `blockchain-export-${timestamp}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // Download as CSV
        function downloadCSV(data) {
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
            let csvContent = '';
            
            // Add metadata header
            csvContent += 'EXPORT METADATA\n';
            csvContent += 'Export Date,' + data.metadata.exportDate + '\n';
            csvContent += 'Contract Address,' + data.metadata.contractAddress + '\n';
            csvContent += 'Ganache URL,' + data.metadata.ganacheUrl + '\n';
            csvContent += 'Total Raw Logs,' + data.metadata.totalRawLogs + '\n';
            csvContent += 'Total Events,' + data.metadata.totalParsedEvents + '\n';
            csvContent += '\n';
            
            // Product Events CSV
            if (data.productEvents && data.productEvents.length > 0) {
                csvContent += 'PRODUCT EVENTS\n';
                csvContent += 'Event Type,Barcode,Name,Creator,Block Number,Transaction Hash,Method\n';
                data.productEvents.forEach(event => {
                    csvContent += `"${event.eventType}","${event.barcode || 'N/A'}","${event.name || 'N/A'}","${event.creator || 'N/A'}","${event.blockNumber || 'N/A'}","${event.transactionHash || 'N/A'}","${event.method || 'N/A'}"\n`;
                });
                csvContent += '\n';
            }

            // Location Events CSV
            if (data.locationEvents && data.locationEvents.length > 0) {
                csvContent += 'LOCATION EVENTS\n';
                csvContent += 'Event Type,Barcode,Location,Timestamp,Block Number,Transaction Hash,Method\n';
                data.locationEvents.forEach(event => {
                    csvContent += `"${event.eventType}","${event.barcode || 'N/A'}","${event.location || 'N/A'}","${event.timestamp || 'N/A'}","${event.blockNumber || 'N/A'}","${event.transactionHash || 'N/A'}","${event.method || 'N/A'}"\n`;
                });
                csvContent += '\n';
            }

            // Raw Logs CSV
            csvContent += 'RAW LOGS\n';
            csvContent += 'Block Number,Transaction Hash,Address,Topics,Data\n';
            data.rawLogs.forEach(log => {
                csvContent += `"${log.blockNumber}","${log.transactionHash}","${log.address}","${log.topics.join(';')}","${log.data}"\n`;
            });

            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `blockchain-export-${timestamp}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // Auto-start on page load
        document.addEventListener('DOMContentLoaded', function() {
            debugLog('🚀 Page loaded - Ready to scan blockchain');
        });
    </script>
</body>
</html>