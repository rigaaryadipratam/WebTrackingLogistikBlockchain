</div>
<footer class="bg-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">Logistics Blockchain Tracker &copy; <?php echo date('Y'); ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">Prototype Version 1.0</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/web3@1.8.1/dist/web3.min.js"></script>
<script type="module" src="../assets/js/app.js"></script>
<script>
    // Metamask connection handling
    document.addEventListener('DOMContentLoaded', async function() {
        const statusDiv = document.getElementById('metamaskStatus');

        // Check for Metamask
        if (typeof window.ethereum === 'undefined' && typeof window.web3 === 'undefined') {
            statusDiv.className = 'alert alert-warning d-flex align-items-center mb-4';
            statusDiv.innerHTML = `
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>Metamask not detected.</strong> Please <a href="https://metamask.io/download/" target="_blank" class="alert-link">install Metamask</a> for the best experience.
                        <button id="continueWithoutMetamask" class="btn btn-sm btn-outline-secondary ms-3">Continue without Metamask</button>
                    </div>
                `;

            // Setup fallback if user wants to continue without Metamask
            document.getElementById('continueWithoutMetamask').addEventListener('click', function() {
                window.web3 = new Web3('http://localhost:7545');
                statusDiv.className = 'alert alert-secondary d-flex align-items-center mb-4';
                statusDiv.innerHTML = `
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>Using local Ganache connection (development only). Install Metamask for better experience.</div>
                    `;
                loadBlockchainData();
            });
            return;
        }

        try {
            // Modern dapp browsers
            window.web3 = new Web3(window.ethereum || window.web3.currentProvider);

            // Get network info
            const networkId = await web3.eth.net.getId();
            let networkName;
            switch (networkId) {
                case 1:
                    networkName = "Ethereum Mainnet";
                    break;
                case 3:
                    networkName = "Ropsten Testnet";
                    break;
                case 4:
                    networkName = "Rinkeby Testnet";
                    break;
                case 5:
                    networkName = "Goerli Testnet";
                    break;
                case 42:
                    networkName = "Kovan Testnet";
                    break;
                case 1337:
                    networkName = "Ganache Local";
                    break;
                default:
                    networkName = `Network #${networkId}`;
            }

            // Get accounts
            const accounts = await web3.eth.getAccounts();

            if (accounts.length === 0) {
                // No account connected
                statusDiv.className = 'alert alert-info d-flex align-items-center mb-4';
                statusDiv.innerHTML = `
                        <i class="bi bi-wallet2 me-2"></i>
                        <div>
                            Please connect your Metamask wallet to use blockchain features.
                            <button id="connectWallet" class="btn btn-sm btn-primary ms-3">Connect Wallet</button>
                        </div>
                    `;

                document.getElementById('connectWallet').addEventListener('click', async function() {
                    try {
                        await window.ethereum.request({
                            method: 'eth_requestAccounts'
                        });
                        // Reload to update state
                        window.location.reload();
                    } catch (error) {
                        console.error("Error connecting wallet:", error);
                        statusDiv.className = 'alert alert-danger d-flex align-items-center mb-4';
                        statusDiv.innerHTML = `
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div>Error connecting wallet: ${error.message}</div>
                            `;
                    }
                });
            } else {
                // Account connected
                const shortenedAccount = accounts[0].substring(0, 6) + '...' + accounts[0].substring(accounts[0].length - 4);
                statusDiv.className = 'alert alert-success d-flex align-items-center mb-4';
                statusDiv.innerHTML = `
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div class="d-flex justify-content-between w-100 align-items-center">
                            <span>Connected to Metamask: <strong>${shortenedAccount}</strong> on <strong>${networkName}</strong></span>
                            <button class="btn btn-sm btn-outline-success" type="button" disabled>
                                <i class="bi bi-circle-fill text-success me-1" style="font-size: 8px;"></i> Connected
                            </button>
                        </div>
                    `;

                // Initialize blockchain functionality
                loadBlockchainData();
            }

            // Listen for account/network changes
            window.ethereum.on('accountsChanged', function(accounts) {
                window.location.reload();
            });

            window.ethereum.on('chainChanged', function(networkId) {
                window.location.reload();
            });

        } catch (error) {
            console.error("Error initializing Web3:", error);
            statusDiv.className = 'alert alert-danger d-flex align-items-center mb-4';
            statusDiv.innerHTML = `
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>Error initializing blockchain connection: ${error.message}</div>
                `;
        }
    });

    // This function will be defined in app.js
    function loadBlockchainData() {
        if (typeof initBlockchainFunctions === 'function') {
            initBlockchainFunctions();
        }
    }
</script>
<script src="assets/js/app.js"></script>
<!-- Pastikan Web3.js dimuat dengan benar -->
<script src="https://cdn.jsdelivr.net/npm/web3@1.8.1/dist/web3.min.js"></script>
<script src="contract-config.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Use CONTRACT_ADDRESS and CONTRACT_ABI from contract-config.js
            logisticsContract = new web3.eth.Contract(CONTRACT_ABI, CONTRACT_ADDRESS);
            console.log('Contract initialized successfully');
            // Continue with your code...
        } catch (error) {
            console.error('Error initializing contract:', error);
        }
    });
</script>

<script>
    // Tambahkan event listener untuk tombol "Verify on Blockchain"
document.addEventListener('DOMContentLoaded', function() {
    // Tambahkan event listener ke semua tombol verify
    const verifyButtons = document.querySelectorAll('.verify-blockchain');
    verifyButtons.forEach(button => {
        button.addEventListener('click', verifyProductOnBlockchain);
    });
});

// Fungsi untuk memverifikasi produk di blockchain
async function verifyProductOnBlockchain(event) {
    const button = event.currentTarget;
    const barcode = button.getAttribute('data-barcode');
    const productName = button.getAttribute('data-name');
    
    // Tampilkan loading state
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verifying...';
    button.disabled = true;
    
    try {
        // Check if contract is initialized
        if (!window.logisticsContract) {
            throw new Error("Blockchain connection not initialized");
        }
        
        // Call contract method to get product info
        const productInfo = await window.logisticsContract.methods.getProductInfo(barcode).call();
        
        // Extract info
        const [name, exists] = productInfo;
        
        // Create and show modal with result
        const modalHtml = `
        <div class="modal fade" id="verifyModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header ${exists ? 'bg-success text-white' : 'bg-warning'}">
                        <h5 class="modal-title">
                            <i class="bi ${exists ? 'bi-shield-check' : 'bi-shield-exclamation'} me-2"></i>
                            Blockchain Verification
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 bg-light p-3 rounded">
                                <i class="bi bi-box text-primary fs-3"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">${barcode}</h5>
                                <div class="text-muted small">Product Barcode</div>
                            </div>
                        </div>
                        
                        ${exists ? `
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <strong>Verified on Blockchain!</strong> This product exists on the blockchain.
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Name on Blockchain</div>
                                <div class="fw-bold">${name}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Name in Database</div>
                                <div class="fw-bold">${productName}</div>
                                ${name !== productName ? `
                                    <div class="alert alert-warning mt-2">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <small>Warning: Name mismatch between blockchain and database.</small>
                                    </div>
                                ` : ''}
                            </div>
                        ` : `
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Not Found on Blockchain!</strong> This product exists in the database but has not been registered on the blockchain yet.
                            </div>
                            <div class="mt-3">
                                <button id="registerProductBtn" class="btn btn-success" data-barcode="${barcode}" data-name="${productName}">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Register on Blockchain
                                </button>
                            </div>
                        `}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        `;
        
        // Append modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Show the modal
        const verifyModal = new bootstrap.Modal(document.getElementById('verifyModal'));
        verifyModal.show();
        
        // Add event listener for register button if product doesn't exist
        if (!exists) {
            document.getElementById('registerProductBtn').addEventListener('click', registerProductOnBlockchain);
        }
        
        // Listen for modal close to remove it from DOM
        document.getElementById('verifyModal').addEventListener('hidden.bs.modal', function () {
            this.remove();
        });
        
    } catch (error) {
        console.error("Error verifying product on blockchain:", error);
        alert(`Error verifying product: ${error.message}`);
    } finally {
        // Reset button state
        button.innerHTML = '<i class="bi bi-shield-check"></i> Verify on Blockchain';
        button.disabled = false;
    }
}

// Function to register product on blockchain
async function registerProductOnBlockchain(event) {
    const button = event.currentTarget;
    const barcode = button.getAttribute('data-barcode');
    const name = button.getAttribute('data-name');
    
    // Disable button and show loading
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Registering...';
    button.disabled = true;
    
    try {
        // Get accounts
        const accounts = await web3.eth.getAccounts();
        
        // Call contract method to add product
        const result = await window.logisticsContract.methods.addProduct(barcode, name)
            .send({ from: accounts[0] });
        
        console.log("Product registered on blockchain:", result);
        
        // Update modal content
        button.closest('.modal-body').innerHTML = `
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Success!</strong> Product has been successfully registered on the blockchain.
            </div>
            <div class="mb-3">
                <div class="text-muted small mb-1">Transaction Hash</div>
                <div class="text-break font-monospace">${result.transactionHash}</div>
            </div>
            <div class="mb-3">
                <div class="text-muted small mb-1">Gas Used</div>
                <div>${result.gasUsed}</div>
            </div>
        `;
        
    } catch (error) {
        console.error("Error registering product on blockchain:", error);
        
        // Update button to show error
        button.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> Error: ' + (error.message || 'Unknown error');
        button.disabled = false;
        button.className = 'btn btn-danger';
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<!-- Initialize Blockchain on every page -->
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            if (typeof initBlockchain === 'function') {
                console.log("Initializing blockchain...");
                await initBlockchain();
            } else {
                console.warn("initBlockchain function not found");
                
                // Fallback initialization
                try {
                    window.web3 = new Web3('http://localhost:7545');
                    const response = await fetch('get-contract-abi.php');
                    const data = await response.json();
                    
                    if (!data.error) {
                        window.logisticsContract = new web3.eth.Contract(data.abi, data.contractAddress);
                        console.log("Fallback contract initialization successful");
                    }
                } catch (error) {
                    console.error("Fallback initialization failed:", error);
                }
            }
        });
    </script>

    <!-- Include these in your footer file -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/web3@1.8.1/dist/web3.min.js"></script>
<script src="assets/js/app.js"></script>
</body>

</html>