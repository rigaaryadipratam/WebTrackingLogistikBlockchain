let web3;
let logisticsContract;


document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded, initializing blockchain connection...');
    checkMetamask();
});


function checkMetamask() {
    const statusDiv = document.getElementById('metamaskStatus');
    if (!statusDiv) return;

    if (typeof window.ethereum !== 'undefined') {
        statusDiv.innerHTML = `
            <div class="alert alert-info d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div>
                    Metamask detected. Please connect your wallet to use blockchain features.
                    <button id="connectMetamask" class="btn btn-primary btn-sm ms-3">Connect Wallet</button>
                </div>
            </div>
        `;

        document.getElementById('connectMetamask').addEventListener('click', connectMetamask);
    } else {
        statusDiv.innerHTML = `
            <div class="alert alert-warning d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    Metamask not detected. Please install Metamask for blockchain interaction.
                    <div class="mt-2">
                        <a href="https://metamask.io/download/" target="_blank" class="btn btn-sm btn-warning me-2">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Get Metamask
                        </a>
                        <button id="useGanache" class="btn btn-sm btn-secondary">
                            <i class="bi bi-gear me-1"></i> Use Ganache Directly
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('useGanache').addEventListener('click', useGanacheProvider);
    }
}


async function connectMetamask() {
    const statusDiv = document.getElementById('metamaskStatus');
    if (!statusDiv) return;

    try {

        const accounts = await window.ethereum.request({
            method: 'eth_requestAccounts'
        });
        console.log('Connected to Metamask:', accounts);


        statusDiv.innerHTML = `
            <div class="alert alert-success d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <span>Connected to wallet: <strong>${accounts[0].substring(0, 6)}...${accounts[0].substring(accounts[0].length - 4)}</strong></span>
                    <span class="badge bg-success d-flex align-items-center">
                        <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Active
                    </span>
                </div>
            </div>
        `;


        web3 = new Web3(window.ethereum);


        initializeContract();


        setupEventListeners();


        window.ethereum.on('accountsChanged', function (accounts) {
            console.log('Account changed:', accounts);
            if (accounts.length > 0) {
                statusDiv.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div class="d-flex justify-content-between w-100 align-items-center">
                            <span>Connected to wallet: <strong>${accounts[0].substring(0, 6)}...${accounts[0].substring(accounts[0].length - 4)}</strong></span>
                            <span class="badge bg-success d-flex align-items-center">
                                <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Active
                            </span>
                        </div>
                    </div>
                `;
            } else {
                checkMetamask();
            }
        });

        window.ethereum.on('chainChanged', function (chainId) {
            console.log('Network changed:', chainId);
            window.location.reload();
        });

        return accounts;
    } catch (error) {
        console.error('Failed to connect to Metamask:', error);

        statusDiv.innerHTML = `
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    Failed to connect to Metamask: ${error.message}
                    <button id="retryConnect" class="btn btn-danger btn-sm ms-3">Retry</button>
                </div>
            </div>
        `;

        document.getElementById('retryConnect').addEventListener('click', connectMetamask);
        return null;
    }
}


function useGanacheProvider() {
    const statusDiv = document.getElementById('metamaskStatus');
    if (!statusDiv) return;

    try {

        web3 = new Web3('http://localhost:7545');
        console.log('Using Ganache provider directly');

        statusDiv.innerHTML = `
            <div class="alert alert-secondary d-flex align-items-center">
                <i class="bi bi-gear-fill me-2"></i>
                <div>
                    Using Ganache provider directly. No Metamask confirmations will appear.
                </div>
            </div>
        `;


        initializeContract();


        setupEventListeners();
    } catch (error) {
        console.error('Failed to connect to Ganache:', error);

        statusDiv.innerHTML = `
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    Failed to connect to Ganache: ${error.message}
                    <button id="retryGanache" class="btn btn-danger btn-sm ms-3">Retry</button>
                </div>
            </div>
        `;

        document.getElementById('retryGanache').addEventListener('click', useGanacheProvider);
    }
}


function initializeContract() {
    try {

        const contractABI = [

            {
                "inputs": [{
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
                "outputs": [{
                    "internalType": "bool",
                    "name": "",
                    "type": "bool"
                }],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [{
                    "internalType": "string",
                    "name": "barcode",
                    "type": "string"
                }],
                "name": "getProductInfo",
                "outputs": [{
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
            },

        ];


        const contractAddress = '00x02186DC50B534c48D37ea2d2c60FF40aC28e92c8';


        logisticsContract = new web3.eth.Contract(
            contractABI,
            contractAddress
        );

        console.log('Contract initialized:', logisticsContract._address);


        setupBlockchainButtons();


        verifyContractConnection();
    } catch (error) {
        console.error('Failed to initialize contract:', error);


        const blockchainStatus = document.getElementById('blockchainStatus');
        if (blockchainStatus) {
            blockchainStatus.innerHTML = `
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        Failed to initialize contract: ${error.message}
                    </div>
                </div>
            `;
        }
    }
}


function setupBlockchainButtons() {
    console.log('Setting up blockchain buttons...');

    // Add Product to Blockchain button
    const addToBlockchainBtn = document.getElementById('addToBlockchain');
    if (addToBlockchainBtn) {
        console.log('Add to Blockchain button found');

        addToBlockchainBtn.addEventListener('click', async function () {
            console.log('Add to Blockchain button clicked');

            // Get product details from form
            const barcode = document.getElementById('barcode').value;
            const name = document.getElementById('name').value;

            console.log('Product details:', {
                barcode,
                name
            });

            if (!barcode || !name) {
                alert('Please fill in both barcode and product name');
                return;
            }

            try {
                // Show loading indicator
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Adding to blockchain...';
                this.disabled = true;

                // Get accounts
                const accounts = await web3.eth.getAccounts();
                console.log('Available accounts:', accounts);

                if (!accounts || accounts.length === 0) {
                    throw new Error('No Ethereum accounts available. Please connect your wallet.');
                }

                // Send transaction to blockchain
                console.log('Sending transaction to add product to blockchain...');
                const result = await logisticsContract.methods.addProduct(barcode, name)
                    .send({
                        from: accounts[0],
                        gas: 500000 // Set appropriate gas limit
                    });

                console.log('Transaction successful:', result);

                // Show success message
                alert('Product successfully added to blockchain!');

                // Verify product exists on blockchain
                const verifyResult = await logisticsContract.methods.getProductInfo(barcode).call();
                console.log('Verification result:', verifyResult);

                if (verifyResult[1]) { // exists = true
                    console.log('Product verified on blockchain:', verifyResult[0]);
                } else {
                    console.warn('Product not found on blockchain after adding!');
                }

            } catch (error) {
                console.error('Error adding product to blockchain:', error);
                alert('Failed to add product to blockchain: ' + error.message);
            } finally {
                // Reset button
                this.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i> Add to Blockchain';
                this.disabled = false;
            }
        });
    } else {
        console.warn('Add to Blockchain button not found in the DOM');
    }

    // Update Location on Blockchain button
    const updateLocationBtn = document.getElementById('updateLocationBlockchain');
    if (updateLocationBtn) {
        console.log('Update Location button found');

        updateLocationBtn.addEventListener('click', async function () {
            // Implementation for updating location
            // Similar to addProduct but calling updateLocation function
            // ...
        });
    }

    // Get Info from Blockchain button
    const getInfoBtn = document.getElementById('getInfoBlockchain');
    if (getInfoBtn) {
        console.log('Get Info button found');

        getInfoBtn.addEventListener('click', async function () {
            // Implementation for getting product info
            // ...
        });
    }
}

// Setup event listeners for blockchain events
function setupEventListeners() {
    if (!logisticsContract || !logisticsContract.events) {
        console.warn('Contract events not available');
        return;
    }

    // Listen for ProductAdded events
    logisticsContract.events.ProductAdded({
            fromBlock: 'latest'
        })
        .on('data', function (event) {
            console.log('ProductAdded event detected:', event.returnValues);

            // Show notification
            showNotification(
                'Product Added to Blockchain',
                `Product "${event.returnValues.name}" (${event.returnValues.barcode}) was added to blockchain.`
            );
        })
        .on('error', function (error) {
            console.error('Error in ProductAdded event listener:', error);
        });

    // Listen for LocationUpdated events if available
    if (logisticsContract.events.LocationUpdated) {
        logisticsContract.events.LocationUpdated({
                fromBlock: 'latest'
            })
            .on('data', function (event) {
                console.log('LocationUpdated event detected:', event.returnValues);

                // Show notification
                showNotification(
                    'Location Updated on Blockchain',
                    `Product ${event.returnValues.barcode} location updated to "${event.returnValues.location}".`
                );
            })
            .on('error', function (error) {
                console.error('Error in LocationUpdated event listener:', error);
            });
    }
}

// Verify contract connection
function verifyContractConnection() {
    if (!logisticsContract || !logisticsContract.methods) {
        console.warn('Contract methods not available');
        return;
    }

    // Try calling a view function
    logisticsContract.methods.getProductInfo('TEST001').call()
        .then(result => {
            console.log('Contract test result:', result);

            // Update blockchain status if needed
            const blockchainStatus = document.getElementById('blockchainStatus');
            if (blockchainStatus) {
                blockchainStatus.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>
                            Contract connected successfully. Ready to use blockchain features.
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Contract test error:', error);

            // Update blockchain status if needed
            const blockchainStatus = document.getElementById('blockchainStatus');
            if (blockchainStatus) {
                blockchainStatus.innerHTML = `
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            Contract test failed: ${error.message}
                        </div>
                    </div>
                `;
            }
        });
}

// Show notification
function showNotification(title, message) {
    // Create notification element if not exists
    let notifications = document.getElementById('blockchain-notifications');
    if (!notifications) {
        notifications = document.createElement('div');
        notifications.id = 'blockchain-notifications';
        notifications.style.position = 'fixed';
        notifications.style.bottom = '20px';
        notifications.style.right = '20px';
        notifications.style.zIndex = '1050';
        document.body.appendChild(notifications);
    }

    // Create notification
    const notif = document.createElement('div');
    notif.className = 'toast show';
    notif.setAttribute('role', 'alert');
    notif.setAttribute('aria-live', 'assertive');
    notif.setAttribute('aria-atomic', 'true');
    notif.innerHTML = `
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong class="me-auto">${title}</strong>
            <small>just now</small>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;

    // Add to notifications area
    notifications.appendChild(notif);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        notif.classList.remove('show');
        setTimeout(() => {
            notifications.removeChild(notif);
        }, 500);
    }, 5000);
}

// Verify Product on Blockchain button
const verifyBtn = document.getElementById('verifyFromBlockchain');
if (verifyBtn) {
    verifyBtn.addEventListener('click', async function () {
        // Get product barcode (adjust selector as needed)
        const barcode = document.getElementById('productBarcode').value ||
            document.getElementById('trackBarcode').value;

        if (!barcode) {
            alert('Product barcode not found');
            return;
        }

        // Show loading
        const resultDiv = document.getElementById('verificationResult');
        if (resultDiv) {
            resultDiv.innerHTML = `
                <div class="alert alert-info d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div>
                        Verifying product on blockchain...
                    </div>
                </div>
            `;
        }

        try {
            // Call contract method
            const productInfo = await logisticsContract.methods.getProductInfo(barcode).call();
            console.log('Product info from blockchain:', productInfo);

            if (resultDiv) {
                if (productInfo[1]) { // exists = true
                    resultDiv.innerHTML = `
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>
                                <strong>Product verified on blockchain!</strong><br>
                                Name: ${productInfo[0]}<br>
                                Barcode: ${barcode}
                            </div>
                        </div>
                    `;

                    // Get location updates if available
                    if (logisticsContract.methods.getLocationUpdatesCount) {
                        const updatesCount = await logisticsContract.methods.getLocationUpdatesCount(barcode).call();

                        if (updatesCount > 0) {
                            let updatesHtml = `
                                <div class="mt-3">
                                    <h6>Location History on Blockchain (${updatesCount} updates)</h6>
                                    <ul class="list-group">
                            `;

                            for (let i = 0; i < updatesCount; i++) {
                                const update = await logisticsContract.methods.getLocationUpdate(barcode, i).call();
                                const location = update[0];
                                const timestamp = new Date(update[1] * 1000).toLocaleString();

                                updatesHtml += `
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <strong>${location}</strong>
                                            <small class="text-muted">${timestamp}</small>
                                        </div>
                                    </li>
                                `;
                            }

                            updatesHtml += `</ul></div>`;
                            resultDiv.innerHTML += updatesHtml;
                        }
                    }
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-warning d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <strong>Product not found on blockchain.</strong><br>
                                This product may exist in the database but has not been registered on the blockchain yet.
                            </div>
                        </div>
                    `;
                }
            }
        } catch (error) {
            console.error('Error verifying product on blockchain:', error);

            if (resultDiv) {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            <strong>Error verifying product:</strong> ${error.message}
                        </div>
                    </div>
                `;
            }
        }
    });
}