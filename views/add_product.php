<div class="page-header">
    <h2>Add New Product</h2>
    <p class="text-muted">Register a new product for tracking in the supply chain</p>
</div>


<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-box me-2"></i> Product Information</span>
                <span class="badge bg-primary">Step 1 of 2</span>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?action=add_product" id="productForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="barcode" class="form-label">Barcode/Product ID</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                <input type="text" class="form-control" id="barcode" name="barcode" required>
                            </div>
                            <div class="form-text">Enter a unique identifier for the product</div>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Product Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-box"></i></span>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-database me-2"></i> Save to Database
                        </button>
                        <button type="button" id="addToBlockchain" class="btn btn-success">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Add to Blockchain
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header">Blockchain Registration</div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>Why Use Blockchain?</h5>
                    <p class="text-muted">Registering products on the blockchain ensures:</p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check2-circle text-success me-2"></i> Immutable product history</li>
                        <li><i class="bi bi-check2-circle text-success me-2"></i> Tamper-proof records</li>
                        <li><i class="bi bi-check2-circle text-success me-2"></i> Transparent supply chain</li>
                        <li><i class="bi bi-check2-circle text-success me-2"></i> Decentralized verification</li>
                    </ul>
                </div>
                
                <!-- Transaction Status -->
                <div id="result" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>


<script>

const contractABI = [
    {
        "inputs": [
            {
                "internalType": "string",
                "name": "barcode",
                "type": "string"
            },
            {
                "internalType": "string",
                "name": "name",
                "type": "string"
            }
        ],
        "name": "addProduct",
        "outputs": [
            {
                "internalType": "bool",
                "name": "",
                "type": "bool"
            }
        ],
        "stateMutability": "nonpayable",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "string",
                "name": "barcode",
                "type": "string"
            }
        ],
        "name": "getProductInfo",
        "outputs": [
            {
                "internalType": "string",
                "name": "",
                "type": "string"
            },
            {
                "internalType": "bool",
                "name": "",
                "type": "bool"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    }
];

// Contract address - ganti dengan alamat kontrak Anda
const contractAddress = '<?php echo CONTRACT_ADDRESS; ?>';

// Global variables
let web3;
let logisticsContract;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    checkMetamask();
});

// Check Metamask status
function checkMetamask() {
    const statusDiv = document.getElementById('metamaskStatus');
    
    if (typeof window.ethereum !== 'undefined') {
        statusDiv.innerHTML = `
            <div class="alert alert-info d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div>
                    Metamask detected. Please connect your wallet.
                    <button id="connectMetamask" class="btn btn-sm btn-primary ms-3">Connect Wallet</button>
                </div>
            </div>
        `;
        
        document.getElementById('connectMetamask').addEventListener('click', connectMetamask);
    } else {
        statusDiv.innerHTML = `
            <div class="alert alert-warning d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    Metamask not detected. Please install Metamask or try using direct Ganache connection.
                    <button id="useGanache" class="btn btn-sm btn-warning ms-3">Use Ganache</button>
                </div>
            </div>
        `;
        
        document.getElementById('useGanache').addEventListener('click', useGanacheProvider);
    }
}

// Connect to Metamask
async function connectMetamask() {
    try {
        const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
        console.log('Connected to Metamask:', accounts);
        
        const statusDiv = document.getElementById('metamaskStatus');
        statusDiv.innerHTML = `
            <div class="alert alert-success d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>Connected to Metamask: ${accounts[0].substring(0, 8)}...${accounts[0].substring(accounts[0].length - 6)}</div>
            </div>
        `;
        
        // Initialize Web3 with Metamask provider
        web3 = new Web3(window.ethereum);
        
        // Initialize contract
        initializeContract();
        
        return accounts;
    } catch (error) {
        console.error('Failed to connect to Metamask:', error);
        
        const statusDiv = document.getElementById('metamaskStatus');
        statusDiv.innerHTML = `
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    Failed to connect to Metamask: ${error.message}
                    <button id="retryConnect" class="btn btn-sm btn-danger ms-3">Retry</button>
                </div>
            </div>
        `;
        
        document.getElementById('retryConnect').addEventListener('click', connectMetamask);
        
        throw error;
    }
}

// Use Ganache provider
function useGanacheProvider() {
    try {
        // Initialize Web3 with Ganache provider
        web3 = new Web3('http://localhost:7545');
        console.log('Using Ganache provider');
        
        const statusDiv = document.getElementById('metamaskStatus');
        statusDiv.innerHTML = `
            <div class="alert alert-warning d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>Using Ganache provider directly. No Metamask confirmations will appear.</div>
            </div>
        `;
        
        // Initialize contract
        initializeContract();
    } catch (error) {
        console.error('Failed to connect to Ganache:', error);
        
        const statusDiv = document.getElementById('metamaskStatus');
        statusDiv.innerHTML = `
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>Failed to connect to Ganache: ${error.message}</div>
            </div>
        `;
    }
}

// Initialize contract
function initializeContract() {
    try {
        // Initialize contract
        logisticsContract = new web3.eth.Contract(
            contractABI,
            contractAddress
        );
        
        console.log('Contract initialized:', logisticsContract);
        
        // Setup button
        setupAddButton();
        
        // Get accounts and check contract
        web3.eth.getAccounts()
            .then(accounts => {
                console.log('Available accounts:', accounts);
                
                if (accounts.length > 0) {
                    // Test contract by calling a view function
                    return logisticsContract.methods.getProductInfo('TEST001').call()
                        .then(result => {
                            console.log('Contract test result:', result);
                            
                            const resultDiv = document.getElementById('result');
                            resultDiv.innerHTML = `
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <div>Contract connected successfully. Ready to add products.</div>
                                </div>
                            `;
                        })
                        .catch(error => {
                            console.error('Contract test error:', error);
                            // This might fail if TEST001 doesn't exist, which is fine
                            const resultDiv = document.getElementById('result');
                            resultDiv.innerHTML = `
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <div>Contract connected. Fill the form to add a product.</div>
                                </div>
                            `;
                        });
                }
            })
            .catch(error => {
                console.error('Account error:', error);
            });
    } catch (error) {
        console.error('Failed to initialize contract:', error);
        
        const resultDiv = document.getElementById('result');
        resultDiv.innerHTML = `
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>Failed to initialize contract: ${error.message}</div>
            </div>
        `;
    }
}

// Setup add button
function setupAddButton() {
    const addButton = document.getElementById('addToBlockchain');
    
    addButton.addEventListener('click', async function() {
        const barcode = document.getElementById('barcode').value;
        const name = document.getElementById('name').value;
        
        console.log('Adding product to blockchain:', { barcode, name });
        
        if (!barcode || !name) {
            alert('Please fill in both barcode and product name');
            return;
        }
        
        const resultDiv = document.getElementById('result');
        resultDiv.innerHTML = `
            <div class="alert alert-info d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div>Processing transaction...</div>
            </div>
        `;
        
        try {
            // Get accounts
            const accounts = await web3.eth.getAccounts();
            console.log('Using account:', accounts[0]);
            
            if (accounts.length === 0) {
                throw new Error('No accounts available');
            }
            
            // Show loading
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            this.disabled = true;
            
            // Send transaction - this will trigger Metamask popup if using Metamask
            console.log('Sending transaction to add product...');
            const result = await logisticsContract.methods.addProduct(barcode, name)
                .send({ 
                    from: accounts[0],
                    gas: 500000
                });
            
            console.log('Transaction result:', result);
            
            resultDiv.innerHTML = `
                <div class="alert alert-success d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>
                        <strong>Success!</strong> Product added to blockchain.
                        <div class="mt-2">
                            <small>Transaction Hash: ${result.transactionHash}</small><br>
                            <small>Block Number: ${result.blockNumber}</small>
                        </div>
                    </div>
                </div>
            `;
            
    
            const verifyResult = await logisticsContract.methods.getProductInfo(barcode).call();
            console.log('Verification result:', verifyResult);
            
            if (verifyResult[1]) { // exists = true
                resultDiv.innerHTML += `
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>Verification: Product "${verifyResult[0]}" found on blockchain.</div>
                    </div>
                `;
            }
            
        } catch (error) {
            console.error('Error adding product to blockchain:', error);
            
            resultDiv.innerHTML = `
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>Error adding product to blockchain: ${error.message}</div>
                </div>
            `;
        } finally {
            // Reset button
            this.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i> Add to Blockchain';
            this.disabled = false;
        }
    });
}
</script>