// blockchain.js - Integrasi blockchain untuk aplikasi tracking logistik

// Global variables
let web3;
let logisticsContract;

// Contract ABI - diambil dari config PHP
const contractABI = [
    // ABI akan dimasukkan via PHP
];

// Contract address
const contractAddress = ''; // Akan diisi via PHP

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing blockchain integration...');
    checkMetamask();
});

// Check Metamask status
function checkMetamask() {
    const statusDiv = document.getElementById('metamaskStatus');
    if (!statusDiv) return;
    
    if (typeof window.ethereum !== 'undefined') {
        statusDiv.innerHTML = `
            <div class="alert alert-info d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div>
                    Metamask detected. Please connect your wallet to enable blockchain features.
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
                    Metamask not detected. Please install Metamask or use direct Ganache connection.
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
        if (statusDiv) {
            statusDiv.innerHTML = `
                <div class="alert alert-success d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>
                        Connected to Metamask: ${accounts[0].substring(0, 8)}...${accounts[0].substring(accounts[0].length - 6)}
                    </div>
                </div>
            `;
        }
        
        // Initialize Web3 with Metamask provider
        web3 = new Web3(window.ethereum);
        
        // Initialize contract
        initializeContract();
        
        return accounts;
    } catch (error) {
        console.error('Failed to connect to Metamask:', error);
        
        const statusDiv = document.getElementById('metamaskStatus');
        if (statusDiv) {
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
        }
        
        throw error;
    }
}

// Use Ganache provider
function useGanacheProvider() {
    try {
        // Initialize Web3 with Ganache provider
        web3 = new Web3('http://localhost:7545');
        console.log('Using Ganache provider directly');
        
        const statusDiv = document.getElementById('metamaskStatus');
        if (statusDiv) {
            statusDiv.innerHTML = `
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        Using Ganache provider directly. No Metamask confirmations will appear.
                    </div>
                </div>
            `;
        }
        
        // Initialize contract
        initializeContract();
    } catch (error) {
        console.error('Failed to connect to Ganache:', error);
        
        const statusDiv = document.getElementById('metamaskStatus');
        if (statusDiv) {
            statusDiv.innerHTML = `
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        Failed to connect to Ganache: ${error.message}
                    </div>
                </div>
            `;
        }
    }
}

// Initialize contract
function initializeContract() {
    try {
        // Check if contract address is valid
        if (!contractAddress || contractAddress === '') {
            throw new Error('Contract address not set');
        }
        
        // Initialize contract
        logisticsContract = new web3.eth.Contract(
            contractABI,
            contractAddress
        );
        
        console.log('Contract initialized:', logisticsContract);
        
        // Setup add product button
        setupAddProductButton();
        
        // Test contract connection
        testContractConnection();
    } catch (error) {
        console.error('Failed to initialize contract:', error);
        
        const resultDiv = document.getElementById('blockchainResult');
        if (resultDiv) {
            resultDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Failed to initialize contract: ${error.message}
                </div>
            `;
        }
    }
}

// Test contract connection
async function testContractConnection() {
    try {
        // Get accounts
        const accounts = await web3.eth.getAccounts();
        console.log('Available accounts:', accounts);
        
        if (accounts.length === 0) {
            throw new Error('No accounts available');
        }
        
        // Try a simple call to check contract
        const networkId = await web3.eth.net.getId();
        console.log('Connected to network ID:', networkId);
        
        const blockNumber = await web3.eth.getBlockNumber();
        console.log('Current block number:', blockNumber);
        
        const resultDiv = document.getElementById('blockchainResult');
        if (resultDiv) {
            resultDiv.innerHTML = `
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Blockchain connection successful. Ready to add products.
                </div>
            `;
        }
    } catch (error) {
        console.error('Contract connection test failed:', error);
        
        const resultDiv = document.getElementById('blockchainResult');
        if (resultDiv) {
            resultDiv.innerHTML = `
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Contract connection test failed: ${error.message}
                </div>
            `;
        }
    }
}

// Setup add product button
function setupAddProductButton() {
    const addButton = document.getElementById('addToBlockchain');
    if (!addButton) return;
    
    addButton.addEventListener('click', async function() {
        const barcode = document.getElementById('barcode').value;
        const name = document.getElementById('name').value;
        
        console.log('Adding product to blockchain:', { barcode, name });
        
        if (!barcode || !name) {
            alert('Please fill in both barcode and product name');
            return;
        }
        
        const resultDiv = document.getElementById('blockchainResult');
        if (resultDiv) {
            resultDiv.innerHTML = `
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Processing transaction...
                </div>
            `;
        }
        
        try {
            // Check if web3 is initialized
            if (!web3) {
                throw new Error('Web3 is not initialized. Please connect to Metamask or Ganache first.');
            }
            
            // Get accounts
            const accounts = await web3.eth.getAccounts();
            console.log('Using account:', accounts[0]);
            
            if (accounts.length === 0) {
                throw new Error('No accounts available. Please connect to Metamask or Ganache.');
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
            
            if (resultDiv) {
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Product added to blockchain successfully!
                        <div class="mt-2">
                            <small>Transaction Hash: ${result.transactionHash}</small><br>
                            <small>Block Number: ${result.blockNumber}</small>
                        </div>
                    </div>
                `;
            }
            
            // Verify product was added
            const verifyResult = await logisticsContract.methods.getProductInfo(barcode).call();
            console.log('Verification result:', verifyResult);
            
            if (verifyResult[1]) { // exists = true
                if (resultDiv) {
                    resultDiv.innerHTML += `
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Verification: Product "${verifyResult[0]}" found on blockchain.
                        </div>
                    `;
                }
            }
            
        } catch (error) {
            console.error('Error adding product to blockchain:', error);
            
            if (resultDiv) {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Error adding product to blockchain: ${error.message}
                    </div>
                `;
            }
        } finally {
            // Reset button
            this.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i> Add to Blockchain';
            this.disabled = false;
        }
    });
}