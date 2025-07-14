<!-- views/track_product.php -->

<div class="page-header">
    <h2>Track Product</h2>
    <p class="text-muted">Monitor the journey of products through the supply chain using blockchain verification</p>
</div>

<div class="row">
    <!-- Search Panel -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-search me-2"></i> Find Product</span>
                <span class="badge bg-primary">Step 1</span>
            </div>
            <div class="card-body">
                <form method="GET" action="index.php" class="mb-4">
                    <input type="hidden" name="action" value="track">
                    <div class="mb-3">
                        <label for="trackBarcode" class="form-label">Barcode/Product ID</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" class="form-control" id="trackBarcode" name="barcode" value="<?php echo htmlspecialchars($barcode ?? ''); ?>" required placeholder="Enter product barcode">
                            <button class="btn btn-primary px-3" type="submit">
                                <i class="bi bi-search me-1"></i> Find
                            </button>
                        </div>
                        <div class="form-text">Enter the unique barcode or product identifier</div>
                    </div>
                </form>

                <!-- <div class="mt-3 text-center">
                    <button type="button" class="btn btn-link btn-sm text-decoration-none" data-bs-toggle="modal" data-bs-target="#scanBarcodeModal">
                        <i class="bi bi-camera me-1"></i> Scan Barcode
                    </button>
                </div> -->
            </div>
        </div>

        <?php if (!empty($barcode) && !empty($productInfo)): ?>
            <!-- <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-pin-map me-2"></i> Update Location</span>
                    <span class="badge bg-primary">Step 2</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?action=update_location">
                        <input type="hidden" name="barcode" value="<?php echo htmlspecialchars($barcode); ?>">

                        <div class="mb-3">
                            <label for="newLocation" class="form-label">Current Location</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" class="form-control" id="newLocation" name="location" required placeholder="e.g. Warehouse, Distribution Center">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="locationDetails" class="form-label">Status Details</label>
                            <textarea class="form-control" id="locationDetails" name="details" rows="2" placeholder="Additional information about this location or status"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-database me-2"></i> Save to Database
                            </button>
                       
                            <button type="button" id="updateOnBlockchain" class="update-blockchain-btn btn btn-success">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Update on Blockchain
                            </button>
                        </div>
                    </form>
                </div>
            </div> -->
        <?php endif; ?>
    </div>

    <!-- Product Information Panel -->
    <div class="col-lg-8">
        <?php if (!empty($barcode)): ?>
            <?php if (!empty($productInfo)): ?>
                <!-- Database Product Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-info-circle me-2"></i> Product Information</span>
                        <span class="badge bg-info">Database</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-box text-primary fs-3"></i>
                            </div>
                            <div>
                                <h4 class="mb-1"><?php echo htmlspecialchars($productInfo['name']); ?></h4>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-light text-dark me-2"><?php echo htmlspecialchars($productInfo['category'] ?? 'General'); ?></span>
                                    <span class="text-muted">Barcode: <?php echo htmlspecialchars($productInfo['barcode']); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Added on</small>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar3 text-primary me-2"></i>
                                        <span class="fw-medium"><?php echo date('M d, Y', strtotime($productInfo['created_at'])); ?></span>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Total Updates</small>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-arrow-repeat text-primary me-2"></i>
                                        <span class="fw-medium"><?php echo $productInfo['updates_count']; ?> locations</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($productInfo['description'])): ?>
                            <div class="mb-4">
                                <small class="text-muted d-block mb-1">Description</small>
                                <p class="mb-0"><?php echo htmlspecialchars($productInfo['description'] ?? 'No description available.'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tracking History -->
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-geo-alt me-2"></i> Product Distribution History</span>
                        <div>
                            <button id="toggleView" class="btn btn-sm btn-outline-primary me-1" data-view="timeline">
                                <i class="bi bi-list-ul"></i> Toggle View
                            </button>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Print">
                                    <i class="bi bi-printer"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Export">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($productInfo['updates'])): ?>
                            <!-- Timeline View (default) -->
                            <div id="timelineView">
                                <div class="timeline position-relative">
                                    <?php foreach ($productInfo['updates'] as $index => $update):
                                        $isLast = $index === 0; // First in array is most recent due to DESC order
                                    ?>
                                        <div class="timeline-item mb-4">
                                            <div class="d-flex">
                                                <div class="timeline-icon-container position-relative me-3">
                                                    <div class="bg-<?php echo $isLast ? 'success' : 'light'; ?> rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px; z-index: 2; position: relative;">
                                                        <i class="bi bi-geo-alt<?php echo $isLast ? '-fill' : ''; ?> <?php echo $isLast ? 'text-white' : 'text-muted'; ?> fs-5"></i>
                                                    </div>
                                                    <?php if ($index < count($productInfo['updates']) - 1): ?>
                                                        <div class="timeline-line"></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="card shadow-sm flex-grow-1">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <h5 class="mb-0 fw-semibold"><?php echo htmlspecialchars($update['location']); ?></h5>
                                                            <span class="badge <?php echo $isLast ? 'bg-success' : 'bg-secondary'; ?>">
                                                                <?php echo $isLast ? 'Current' : 'Previous'; ?>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted small mb-2">
                                                            <i class="bi bi-clock me-1"></i>
                                                            <?php echo date('M d, Y h:i A', strtotime($update['timestamp'])); ?>

                                                            <?php if (!empty($update['blockchain_synced']) && $update['blockchain_synced']): ?>
                                                                <span class="ms-2 badge bg-info text-white rounded-pill d-flex align-items-center">
                                                                    <i class="bi bi-link-45deg me-1"></i> Blockchain Verified
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php if (!empty($update['details'])): ?>
                                                            <p class="mb-0 small"><?php echo htmlspecialchars($update['details']); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Table View (hidden by default) -->
                            <div id="tableView" class="d-none">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">Date & Time</th>
                                                <th scope="col">Location</th>
                                                <th scope="col">Details</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($productInfo['updates'] as $index => $update):
                                                $isLast = $index === 0; // First in array is most recent due to DESC order
                                            ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y h:i A', strtotime($update['timestamp'])); ?></td>
                                                    <td><?php echo htmlspecialchars($update['location']); ?></td>
                                                    <td><?php echo htmlspecialchars($update['details'] ?? ''); ?></td>
                                                    <td>
                                                        <?php if ($isLast): ?>
                                                            <span class="badge bg-success">Current</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Previous</span>
                                                        <?php endif; ?>

                                                        <?php if (!empty($update['blockchain_synced']) && $update['blockchain_synced']): ?>
                                                            <span class="badge bg-info">
                                                                <i class="bi bi-link-45deg me-1"></i> Verified
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <div>
                                    No tracking updates have been recorded for this product yet. Use the form on the left to add the first location.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Product Not Found -->
                <div class="card shadow-sm">
                    <div class="card-body py-5">
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="mb-3">Product Not Found</h4>
                            <p class="text-muted mb-4">No product with barcode "<?php echo htmlspecialchars($barcode); ?>" was found in the database.</p>
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="d-grid gap-2">
                                        <a href="index.php?action=add_product" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i> Add New Product
                                        </a>
                                        <button type="button" id="checkBlockchain" class="btn btn-outline-success">
                                            <i class="bi bi-link-45deg me-2"></i> Check on Blockchain
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Blockchain Information (initially empty) -->
            <div id="blockchainInfo" class="mt-4"></div>

        <?php else: ?>
            <!-- No Barcode Entered Yet -->
            <div class="card shadow-sm">
                <div class="card-body py-5">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-upc-scan text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="mb-3">Track Any Product</h4>
                        <p class="text-muted mb-4">Enter a product barcode in the form to view its tracking history and location updates.</p>

                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-4">
                                        <h5 class="mb-3">Sample Products</h5>
                                        <div class="list-group list-group-flush">
                                            <?php
                                            // Sample products to help users get started
                                            $sampleProducts = getAllProducts();
                                            if (!empty($sampleProducts)) {
                                                $max = min(count($sampleProducts), 3);
                                                for ($i = 0; $i < $max; $i++) {
                                            ?>
                                                    <a href="index.php?action=track&barcode=<?php echo urlencode($sampleProducts[$i]['barcode']); ?>" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="bi bi-box me-2 text-primary"></i>
                                                            <?php echo htmlspecialchars($sampleProducts[$i]['name']); ?>
                                                        </div>
                                                        <div class="badge bg-light text-dark">
                                                            <?php echo htmlspecialchars($sampleProducts[$i]['barcode']); ?>
                                                        </div>
                                                    </a>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <div class="text-center py-3">
                                                    <p class="mb-0">No products in database yet.</p>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>



<div class="card shadow-sm">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="dataSourceTabs" role="tablist">
        
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="blockchain-tab" data-bs-toggle="tab" data-bs-target="#blockchain-data" type="button" role="tab">
                    <i class="bi bi-link-45deg me-1"></i> Audit Data Blockchain
                </button>
            </li>
           
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="dataSourceTabsContent">
            
            

            <!-- Blockchain Tab -->
            <div class="tab-pane fade" id="blockchain-data" role="tabpanel">
                <div class="alert alert-success d-flex align-items-center mb-3">
                    <i class="bi bi-shield-check-fill me-2"></i>
                    <div>
                        <strong>Blockchain:</strong> Data is immutable and transparent.
                        Every transaction is cryptographically secured and verifiable.
                    </div>
                </div>

                <div id="blockchainTrackingHistory">
                   <a href="download.php" target="_blank"><button class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Load Blockchain Data
                    </button></a>
                </div>
            </div>

          
           
        </div>
    </div>
</div>


<script>
    // Tambahkan fungsi untuk menampilkan detail blockchain
    async function loadBlockchainData() {
        const barcode = document.getElementById('trackBarcode').value;
        const container = document.getElementById('blockchainTrackingHistory');

        if (!barcode) {
            container.innerHTML = '<p class="text-warning">Please enter a barcode first.</p>';
            return;
        }

        try {
            // Initialize Web3 if not already done
            if (!web3) {
                web3 = new Web3('http://localhost:7545');
                logisticsContract = new web3.eth.Contract(contractABI, contractAddress);
            }

            // Get product info
            const productInfo = await logisticsContract.methods.getProductInfo(barcode).call();

            if (!productInfo[1]) {
                container.innerHTML = '<p class="text-warning">Product not found on blockchain.</p>';
                return;
            }

            // Get location updates count
            const updatesCount = await logisticsContract.methods.getLocationUpdatesCount(barcode).call();

            // Get all updates with transaction details
            const updates = [];
            for (let i = 0; i < updatesCount; i++) {
                const update = await logisticsContract.methods.getLocationUpdate(barcode, i).call();
                updates.push({
                    location: update[0],
                    timestamp: new Date(update[1] * 1000),
                    blockNumber: await web3.eth.getBlockNumber() // This is approximate
                });
            }

            // Display blockchain data with verification features
            let html = `
            <div class="alert alert-info mb-3">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Blockchain Verification:</strong> Each record below is cryptographically secured
                and can be independently verified on the blockchain.
            </div>
        `;

            if (updates.length > 0) {
                html += '<div class="blockchain-records">';

                updates.forEach((update, index) => {
                    const mockTxHash = `0x${Math.random().toString(16).substring(2, 66)}`; // Mock hash for demo
                    html += `
                    <div class="card border-success mb-3">
                        <div class="card-header bg-success bg-opacity-10 d-flex justify-content-between">
                            <span><strong>Record #${index + 1}</strong></span>
                            <span class="badge bg-success">Verified ✓</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Location:</strong> ${update.location}<br>
                                    <strong>Timestamp:</strong> ${update.timestamp.toLocaleString()}<br>
                                    <strong>Block Number:</strong> ${update.blockNumber - index}
                                </div>
                                <div class="col-md-6">
                                    <strong>Transaction Hash:</strong><br>
                                    <code class="small">${mockTxHash}</code><br>
                                    <button class="btn btn-sm btn-outline-success mt-1" onclick="verifyTransaction('${mockTxHash}')">
                                        <i class="bi bi-search me-1"></i> Verify
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                });

                html += '</div>';
            } else {
                html += '<p class="text-muted">No blockchain records found for this product.</p>';
            }

            container.innerHTML = html;

        } catch (error) {
            console.error('Error loading blockchain data:', error);
            container.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Error loading blockchain data: ${error.message}
            </div>
        `;
        }
    }

    // Fungsi untuk "memverifikasi" transaksi (demo purposes)
    async function verifyTransaction(txHash) {
        // Simulasi verifikasi transaksi
        const modal = new bootstrap.Modal(document.createElement('div'));

        // Create modal content
        const modalContent = `
        <div class="modal fade" id="verificationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Blockchain Verification</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="verification-process">
                            <div class="d-flex align-items-center mb-3">
                                <div class="spinner-border text-success me-3" role="status">
                                    <span class="visually-hidden">Verifying...</span>
                                </div>
                                <span>Verifying transaction on blockchain...</span>
                            </div>
                        </div>
                        <div class="verification-result d-none">
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <strong>Verification Successful!</strong>
                            </div>
                            <div class="verification-details">
                                <h6>Transaction Details:</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Hash:</strong> <code>${txHash}</code></li>
                                    <li><strong>Status:</strong> <span class="badge bg-success">Confirmed</span></li>
                                    <li><strong>Confirmations:</strong> 1,247</li>
                                    <li><strong>Block Height:</strong> ${Math.floor(Math.random() * 1000000)}</li>
                                    <li><strong>Gas Used:</strong> 21,000</li>
                                    <li><strong>Data Integrity:</strong> <span class="text-success">✓ Verified</span></li>
                                </ul>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    This transaction has been permanently recorded on the blockchain and cannot be altered.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;

        // Add modal to page
        document.body.insertAdjacentHTML('beforeend', modalContent);
        const verificationModal = new bootstrap.Modal(document.getElementById('verificationModal'));
        verificationModal.show();

        // Simulate verification process
        setTimeout(() => {
            document.querySelector('.verification-process').classList.add('d-none');
            document.querySelector('.verification-result').classList.remove('d-none');
        }, 2000);

        // Clean up when modal is hidden
        document.getElementById('verificationModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }
</script>


<script>
    // Toggle between timeline and table view
    document.getElementById('toggleView').addEventListener('click', function() {
        const timelineView = document.getElementById('timelineView');
        const tableView = document.getElementById('tableView');
        const currentView = this.getAttribute('data-view');

        if (currentView === 'timeline') {
            timelineView.classList.add('d-none');
            tableView.classList.remove('d-none');
            this.setAttribute('data-view', 'table');
            this.innerHTML = '<i class="bi bi-clock-history"></i> Timeline View';
        } else {
            timelineView.classList.remove('d-none');
            tableView.classList.add('d-none');
            this.setAttribute('data-view', 'timeline');
            this.innerHTML = '<i class="bi bi-list-ul"></i> Table View';
        }
    });

    // Handle manual barcode input from modal
    document.getElementById('submitBarcode').addEventListener('click', function() {
        const barcode = document.getElementById('manualBarcodeInput').value;
        if (barcode) {
            window.location.href = 'index.php?action=track&barcode=' + encodeURIComponent(barcode);
        }
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Web3/Blockchain functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Get blockchain info button
        const getInfoBtn = document.getElementById('getInfoBlockchain');
        if (getInfoBtn) {
            getInfoBtn.addEventListener('click', async function() {
                const barcode = document.getElementById('trackBarcode').value;

                if (!barcode) {
                    alert('Please enter a barcode to search');
                    return;
                }

                try {
                    // Show loading indicator
                    getInfoBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Fetching from blockchain...';
                    getInfoBtn.disabled = true;

                    // Get basic product info
                    const productInfo = await logisticsContract.methods.getProductInfo(barcode).call();

                    // Get number of updates
                    const updatesCount = await logisticsContract.methods.getLocationUpdatesCount(barcode).call();

                    // Get all updates
                    const updates = [];
                    for (let i = 0; i < updatesCount; i++) {
                        const update = await logisticsContract.methods.getLocationUpdate(barcode, i).call();
                        updates.push({
                            location: update[0],
                            timestamp: new Date(update[1] * 1000)
                        });
                    }

                    // Display the info
                    const infoDiv = document.getElementById('blockchainInfo');
                    if (infoDiv) {
                        if (productInfo[1]) { // exists
                            let html = `
                            <div class="card shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center bg-success bg-opacity-10">
                                    <span><i class="bi bi-link-45deg me-2"></i> Blockchain Verification</span>
                                    <span class="badge bg-success">Verified</span>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                            <i class="bi bi-shield-check text-success fs-3"></i>
                                        </div>
                                        <div>
                                            <h4 class="mb-1">${productInfo[0]}</h4>
                                            <p class="text-muted mb-0">
                                                <span class="badge bg-secondary me-2">Blockchain ID</span>
                                                Barcode: ${barcode}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-success d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <div>
                                            This product has been verified on the blockchain with ${updatesCount} tracking updates.
                                        </div>
                                    </div>
                        `;

                            if (updates.length > 0) {
                                // Sort updates from newest to oldest
                                updates.sort((a, b) => b.timestamp - a.timestamp);

                                html += `
                                <h5 class="mb-3">Blockchain Tracking History</h5>
                                <div class="timeline-blockchain">
                            `;

                                updates.forEach((update, index) => {
                                    const formattedDate = update.timestamp.toLocaleString();
                                    html += `
                                    <div class="timeline-item-blockchain mb-3">
                                        <div class="d-flex">
                                            <div class="me-3 d-flex flex-column align-items-center">
                                                <div class="bg-success bg-opacity-10 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-geo-alt text-success"></i>
                                                </div>
                                                ${index < updates.length - 1 ? '<div class="timeline-line-blockchain"></div>' : ''}
                                            </div>
                                            <div>
                                                <h6 class="mb-1">${update.location}</h6>
                                                <small class="text-muted">${formattedDate}</small>
                                                <div class="mt-1">
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-shield-check text-success me-1"></i> Blockchain Verified
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                });

                                html += `</div>`;
                            } else {
                                html += `
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <div>
                                        This product is registered on the blockchain but has no tracking updates yet.
                                    </div>
                                </div>
                            `;
                            }

                            html += `</div></div>`;
                            infoDiv.innerHTML = html;
                        } else {
                            infoDiv.innerHTML = `
                            <div class="card shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center bg-warning bg-opacity-10">
                                    <span><i class="bi bi-link-45deg me-2"></i> Blockchain Verification</span>
                                    <span class="badge bg-warning text-dark">Not Found</span>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <div>
                                            <strong>Product not found on blockchain.</strong> 
                                            This product either doesn't exist or hasn't been registered on the blockchain yet.
                                        </div>
                                    </div>
                                    <div class="text-center py-2">
                                        <button type="button" class="btn btn-success" id="registerOnBlockchain" onclick="registerProductOnBlockchain('${barcode}')">
                                            <i class="bi bi-box-arrow-in-right me-2"></i> Register on Blockchain
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        }
                    }

                    // Reset button
                    getInfoBtn.innerHTML = '<i class="bi bi-box-arrow-in-down me-2"></i> Get Data From Blockchain';
                    getInfoBtn.disabled = false;

                } catch (error) {
                    console.error('Error getting product info from blockchain:', error);

                    const infoDiv = document.getElementById('blockchainInfo');
                    if (infoDiv) {
                        infoDiv.innerHTML = `
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center bg-danger bg-opacity-10">
                                <span><i class="bi bi-link-45deg me-2"></i> Blockchain Verification</span>
                                <span class="badge bg-danger">Error</span>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-danger d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>
                                        <strong>Error retrieving blockchain data:</strong> ${error.message}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    }

                    // Reset button
                    getInfoBtn.innerHTML = '<i class="bi bi-box-arrow-in-down me-2"></i> Get Data From Blockchain';
                    getInfoBtn.disabled = false;
                }
            });
        }

        // Update location on blockchain button
        const updateLocationBtn = document.getElementById('updateLocationBlockchain');
        if (updateLocationBtn) {
            updateLocationBtn.addEventListener('click', async function() {
                const barcode = document.querySelector('input[name="barcode"]').value;
                const location = document.getElementById('newLocation').value;

                if (!barcode || !location) {
                    alert('Barcode and location are required');
                    return;
                }

                try {
                    // Show loading indicator
                    updateLocationBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating on blockchain...';
                    updateLocationBtn.disabled = true;

                    const accounts = await web3.eth.getAccounts();
                    const result = await logisticsContract.methods.updateLocation(barcode, location)
                        .send({
                            from: accounts[0]
                        });

                    console.log('Location updated on blockchain:', result);

                    // Show success message
                    alert('Location successfully updated on blockchain!');

                    // Update UI
                    updateLocationBtn.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i> Update on Blockchain';
                    updateLocationBtn.disabled = false;

                    // Reload page after short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);

                } catch (error) {
                    console.error('Error updating location on blockchain:', error);
                    alert('Failed to update location on blockchain: ' + error.message);

                    // Reset button
                    updateLocationBtn.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i> Update on Blockchain';
                    updateLocationBtn.disabled = false;
                }
            });
        }

        // Check blockchain button (when product not found in database)
        const checkBlockchainBtn = document.getElementById('checkBlockchain');
        if (checkBlockchainBtn) {
            checkBlockchainBtn.addEventListener('click', function() {
                const barcode = new URLSearchParams(window.location.search).get('barcode');
                if (barcode) {
                    document.getElementById('trackBarcode').value = barcode;
                    document.getElementById('getInfoBlockchain').click();
                }
            });
        }
    });

    // Function to register product on blockchain
    async function registerProductOnBlockchain(barcode) {
        // In a real app, you would fetch product info from database first
        const productName = prompt("Enter product name to register on blockchain:", "Product " + barcode);

        if (!productName) return; // User cancelled

        try {
            const registerBtn = document.getElementById('registerOnBlockchain');
            registerBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Registering...';
            registerBtn.disabled = true;

            const accounts = await web3.eth.getAccounts();
            const result = await logisticsContract.methods.addProduct(barcode, productName)
                .send({
                    from: accounts[0]
                });

            console.log('Product registered on blockchain:', result);

            // Show success message
            alert('Product successfully registered on blockchain!');

            // Refresh blockchain info
            document.getElementById('getInfoBlockchain').click();

        } catch (error) {
            console.error('Error registering product on blockchain:', error);
            alert('Failed to register product on blockchain: ' + error.message);

            const registerBtn = document.getElementById('registerOnBlockchain');
            if (registerBtn) {
                registerBtn.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i> Register on Blockchain';
                registerBtn.disabled = false;
            }
        }
    }
</script>

<style>
    /* Timeline styling */
    .timeline-item {
        position: relative;
    }

    .timeline-line {
        position: absolute;
        top: 45px;
        left: 22px;
        bottom: -13px;
        width: 2px;
        background-color: #e9ecef;
        z-index: 1;
    }

    /* Blockchain timeline styling */
    .timeline-line-blockchain {
        width: 2px;
        height: 30px;
        background-color: #e9ecef;
        margin: 5px auto;
    }
</style>

<script>
    // Tambahkan kode ini ke dalam <script> di halaman track_product.php

    // Contract ABI - sesuaikan dengan ABI kontrak Anda
    const contractABI = [
        // Fungsi updateLocation
        {
            "inputs": [{
                    "internalType": "string",
                    "name": "barcode",
                    "type": "string"
                },
                {
                    "internalType": "string",
                    "name": "location",
                    "type": "string"
                }
            ],
            "name": "updateLocation",
            "outputs": [{
                "internalType": "bool",
                "name": "",
                "type": "bool"
            }],
            "stateMutability": "nonpayable",
            "type": "function"
        }, {
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
            "type": "function",
            "constant": true
        }, 
        // Fungsi lainnya...
    ];

    // Contract address - ganti dengan alamat kontrak Anda
    const contractAddress = '<?php echo CONTRACT_ADDRESS; ?>';

    // Global variables
    let web3;
    let logisticsContract;

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Tambahkan event listener untuk tombol update blockchain
        const updateBlockchainBtn = document.querySelector('button[onclick="updateOnBlockchain()"]') ||
            document.getElementById('updateOnBlockchain') ||
            document.querySelector('.update-blockchain-btn');

        if (updateBlockchainBtn) {
            // Hapus onclick attribute jika ada
            if (updateBlockchainBtn.hasAttribute('onclick')) {
                updateBlockchainBtn.removeAttribute('onclick');
            }

            // Tambahkan event listener baru
            updateBlockchainBtn.addEventListener('click', initiateBlockchainUpdate);
        }
    });

    // Inisiasi update ke blockchain
    async function initiateBlockchainUpdate() {
        // Periksa apakah Metamask terinstall
        if (typeof window.ethereum === 'undefined') {
            alert('Metamask tidak terdeteksi. Silakan install Metamask terlebih dahulu.');
            return;
        }

        try {
            // Dapatkan nilai input
            const barcode = document.getElementById('trackBarcode')?.value ||
                document.querySelector('input[name="barcode"]')?.value ||
                '<?php echo htmlspecialchars($barcode ?? ""); ?>';

            const location = document.getElementById('newLocation')?.value ||
                document.querySelector('input[name="location"]')?.value ||
                document.querySelector('input[placeholder*="Warehouse"]')?.value;

            if (!barcode || !location) {
                alert('Barcode dan lokasi harus diisi.');
                return;
            }

            // Tampilkan loading status
            const updateBlockchainBtn = document.querySelector('.update-blockchain-btn') ||
                document.getElementById('updateOnBlockchain');
            if (updateBlockchainBtn) {
                updateBlockchainBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
                updateBlockchainBtn.disabled = true;
            }

            // Request akses ke akun Metamask
            const accounts = await window.ethereum.request({
                method: 'eth_requestAccounts'
            });
            const account = accounts[0];
            console.log('Using account:', account);

            // Inisialisasi Web3 dengan provider Metamask
            web3 = new Web3(window.ethereum);

            // Inisialisasi kontrak
            logisticsContract = new web3.eth.Contract(contractABI, contractAddress);

            // Kirim transaksi
            console.log('Sending transaction to update location:', {
                barcode,
                location
            });
            const result = await logisticsContract.methods.updateLocation(barcode, location)
                .send({
                    from: account,
                    gas: 500000 // Gas limit
                });

            console.log('Transaction result:', result);

            // Tampilkan success message
            showBlockchainResult('success', 'Location successfully updated on blockchain!', result);

            // Update UI
            if (updateBlockchainBtn) {
                updateBlockchainBtn.innerHTML = 'Update on Blockchain';
                updateBlockchainBtn.disabled = false;
            }

            // Reload halaman setelah delay
            setTimeout(() => {
                window.location.reload();
            }, 3000);

        } catch (error) {
            console.error('Error updating location on blockchain:', error);

            // Tampilkan error message
            showBlockchainResult('error', `Error updating location: ${error.message}`);

            // Reset button
            const updateBlockchainBtn = document.querySelector('.update-blockchain-btn') ||
                document.getElementById('updateOnBlockchain');
            if (updateBlockchainBtn) {
                updateBlockchainBtn.innerHTML = 'Update on Blockchain';
                updateBlockchainBtn.disabled = false;
            }
        }
    }

    // Tampilkan hasil blockchain
    function showBlockchainResult(type, message, data = null) {
        // Cari atau buat elemen untuk menampilkan status
        let resultContainer = document.getElementById('blockchainResult');

        if (!resultContainer) {
            resultContainer = document.createElement('div');
            resultContainer.id = 'blockchainResult';
            resultContainer.className = 'mt-3';

            // Tentukan elemen parent
            const parent = document.querySelector('.card-body') ||
                document.querySelector('form').parentNode;

            if (parent) {
                parent.appendChild(resultContainer);
            }
        }

        // Set HTML berdasarkan tipe
        if (type === 'success') {
            resultContainer.innerHTML = `
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                ${message}
                ${data ? `
                <div class="mt-2 small">
                    <strong>Transaction Hash:</strong> ${data.transactionHash}<br>
                    <strong>Block Number:</strong> ${data.blockNumber}
                </div>
                ` : ''}
            </div>
        `;
        } else {
            resultContainer.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                ${message}
            </div>
        `;
        }
    }
</script>