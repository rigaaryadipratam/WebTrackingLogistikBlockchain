</div>
    <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">Logistics Blockchain Tracker (Programming and Visual Computing Laboratory) &copy; <?php echo date('Y'); ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Prototype Version 1.0</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/web3@1.8.1/dist/web3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?php if ($action === 'add_product' || $action === 'track'): ?>
    <script>
        // Contract ABI and address from config.php
        const contractABI = <?php 
            $contractJson = file_get_contents(CONTRACT_ABI_PATH);
            $contract = json_decode($contractJson, true);
            echo json_encode($contract['abi']); 
        ?>;
        
        const contractAddress = '<?php echo CONTRACT_ADDRESS; ?>';
    </script>
    <script src="assets/js/blockchain.js"></script>
    <?php endif; ?>
    
    <?php if ($action === 'track'): ?>
    <script src="assets/js/track.js"></script>
    <?php endif; ?>

    <!-- Debug information -->
<?php if (isset($_GET['debug']) && $_GET['debug'] == 1): ?>
<script>
    console.log('Blockchain Setup:', <?php echo json_encode($blockchainSetup); ?>);
    
    // Check contract ABI file
    <?php if (file_exists(CONTRACT_ABI_PATH)): ?>
    console.log('Contract ABI exists');
    <?php else: ?>
    console.error('Contract ABI file not found: <?php echo CONTRACT_ABI_PATH; ?>');
    <?php endif; ?>
    
    // Test provider connection
    fetch('<?php echo BLOCKCHAIN_PROVIDER; ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            jsonrpc: '2.0',
            method: 'web3_clientVersion',
            params: [],
            id: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Provider connection test:', data);
    })
    .catch(error => {
        console.error('Provider connection error:', error);
    });
</script>
<?php endif; ?>
</body>
</html>