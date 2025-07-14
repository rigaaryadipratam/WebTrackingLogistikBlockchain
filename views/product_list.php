<!-- File: views/product_list.php -->


<?php

if (isset($_SESSION['show_location_popup']) && $_SESSION['show_location_popup']) {
    $productName = htmlspecialchars($_SESSION['new_product_name'] ?? 'Produk');
    $productId = $_SESSION['new_product_id'] ?? '';
    $barcode = $_SESSION['barcode'];
    
    echo "<script>
        Swal.fire({
            title: 'Produk Berhasil Ditambahkan!',
            text: 'Apakah Anda ingin menambahkan lokasi untuk produk $productName?',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tambah Lokasi',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php?action=update&barcode=$barcode';
            }
        });
    </script>";
    
    // Clear session
    unset($_SESSION['show_location_popup']);
    unset($_SESSION['new_product_id']);
    unset($_SESSION['new_product_name']);
}
?>



<div class="page-header">
    <h2>Produk</h2>
    <p class="text-muted">Lihat dan kelola semua produk yang terdaftar dalam sistem pelacakan</p>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <form class="d-flex">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="search" class="form-control" placeholder="Cari produk..." id="searchProducts">
            </div>
        </form>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="index.php?action=add_product" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Tambah Produk
        </a>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="card shadow-sm">
        <div class="card-body py-5">
            <div class="text-center">
                <div class="mb-3">
                    <i class="bi bi-box text-muted" style="font-size: 4rem;"></i>
                </div>
                <h4 class="mb-3">Produk Tidak Ditemukan</h4>
                <p class="text-muted mb-4">Inventaris produk Anda masih kosong.</p>
                <a href="index.php?action=add_product" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Produk Pertama
                </a>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong>Data Produk</strong>
                <span class="badge bg-primary ms-2"><?php echo count($products); ?> Produk</span>
            </div>
            
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="productsTable">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Barcode</th>
                        <th scope="col">Produk</th>
                        <th scope="col">Ditambahkan</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-upc me-2 text-primary"></i>
                                    <span><?php echo htmlspecialchars($product['barcode']); ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-3 p-2 me-2">
                                        <i class="bi bi-box text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium"><?php echo htmlspecialchars($product['name']); ?></div>
                                        <?php if (!empty($product['description'])): ?>
                                            <div class="small text-muted text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($product['description']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div><?php echo date('d M Y', strtotime($product['created_at'])); ?></div>
                                    <small class="text-muted"><?php echo date('H:i', strtotime($product['created_at'])); ?></small>
                                </div>
                            </td>
                            <td>
                                <?php 
                                $updates = $product['updates_count']; 
                                if ($updates > 0): 
                                ?>
                                    <div class="d-flex align-items-center">
                                        <div class="badge bg-success me-2 px-2 py-1"><?php echo $updates; ?></div>
                                        <span class="small">Pembaruan Lokasi</span>
                                    </div>
                                <?php else: ?>
                                    <div class="badge bg-secondary">Belum Ada</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?action=update&barcode=<?php echo urlencode($product['barcode']); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Perbarui Lokasi
                                    </a>
                                    <a href="index.php?action=track&barcode=<?php echo urlencode($product['barcode']); ?>" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-eye"></i> Lihat Histori Lokasi
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <div class="small text-muted">Menampilkan <?php echo count($products); ?> produk</div>
            <nav aria-label="Navigasi halaman">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Sebelumnya</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Berikutnya</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
<?php endif; ?>

<!-- Modal untuk pembaruan lokasi -->
<div class="modal fade" id="updateLocationModal" tabindex="-1" aria-labelledby="updateLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateLocationModalLabel">Perbarui Lokasi Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="index.php?action=update_location" id="updateLocationForm">
                    <input type="hidden" name="barcode" id="updateBarcode" value="">
                    <div class="mb-3">
                        <label for="modalNewLocation" class="form-label">Lokasi/Status Baru</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" class="form-control" id="modalNewLocation" name="location" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="modalLocationDetails" class="form-label">Detail Tambahan (Opsional)</label>
                        <textarea class="form-control" id="modalLocationDetails" name="details" rows="2"></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-database me-2"></i> Simpan ke Database
                        </button>
                        <button type="button" id="modalUpdateLocationBlockchain" class="btn btn-success">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Perbarui di Blockchain
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk informasi blockchain -->
<div class="modal fade" id="blockchainInfoModal" tabindex="-1" aria-labelledby="blockchainInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success bg-opacity-10">
                <h5 class="modal-title" id="blockchainInfoModalLabel">
                    <i class="bi bi-box text-success me-2"></i> Data Produk di Blockchain
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="blockchainInfoContent">
                <div class="d-flex justify-content-center py-5">
                    <div class="spinner-border text-primary me-2" role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>
                    <span>Memuat data dari blockchain...</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<script>
// Function to handle opening location update modal
function addUpdateLocation(barcode) {
    document.getElementById('updateBarcode').value = barcode;
    const modal = new bootstrap.Modal(document.getElementById('updateLocationModal'));
    modal.show();
    
    // Set up blockchain update button
    document.getElementById('modalUpdateLocationBlockchain').addEventListener('click', async function() {
        const location = document.getElementById('modalNewLocation').value;
        
        if (!location) {
            alert('Please enter a location');
            return;
        }
        
        try {
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating on blockchain...';
            this.disabled = true;
            
            const accounts = await web3.eth.getAccounts();
            const result = await logisticsContract.methods.updateLocation(barcode, location)
                .send({ from: accounts[0] });
            
            console.log('Location updated on blockchain:', result);
            alert('Location successfully updated on blockchain!');
            
            // Reset button and refresh page
            this.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i> Update on Blockchain';
            this.disabled = false;
            
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } catch (error) {
            console.error('Error updating location on blockchain:', error);
            alert('Failed to update location on blockchain: ' + error.message);
            
            // Reset button
            this.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i> Update on Blockchain';
            this.disabled = false;
        }
    });
}

// Function to get and display blockchain info
async function getBlockchainInfo(barcode) {
    const modal = new bootstrap.Modal(document.getElementById('blockchainInfoModal'));
    modal.show();
    
    const contentDiv = document.getElementById('blockchainInfoContent');
    
    try {
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
                timestamp: new Date(update[1] * 1000).toLocaleString()
            });
        }
        
        // Display the info
        if (productInfo[1]) { // exists
            let html = `
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-box text-success fs-3"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">${productInfo[0]}</h4>
                                <p class="text-muted mb-0">
                                    <span class="badge bg-secondary me-2">Blockchain ID</span>
                                    Barcode: ${barcode}
                                </p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Status</small>
                                    <span class="badge bg-success">Verified on Blockchain</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Tracking Updates</small>
                                    <span class="fw-medium">${updatesCount} updates recorded</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            if (updates.length > 0) {
                html += `
                    <h5 class="mb-3">Location Tracking History</h5>
                    <div class="timeline mb-3">
                `;
                
                updates.forEach((update, index) => {
                    const isLast = index === updates.length - 1;
                    html += `
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="position-relative me-3">
                                    <div class="bg-${isLast ? 'primary' : 'light'} rounded-circle p-2" style="width: 40px; height: 40px; text-align: center;">
                                        <i class="bi bi-geo-alt${isLast ? '-fill' : ''} ${isLast ? 'text-white' : ''}"></i>
                                    </div>
                                    ${!isLast ? `<div class="timeline-line"></div>` : ''}
                                </div>
                                <div>
                                    <h6 class="mb-1">${update.location}</h6>
                                    <small class="text-muted">${update.timestamp}</small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `</div>`;
                
                html += `
                    <div class="alert alert-info d-flex align-items-center mt-3">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>
                            All data shown above is securely stored on the blockchain and cannot be altered.
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            No tracking updates have been recorded on the blockchain for this product yet.
                        </div>
                    </div>
                `;
            }
            
            contentDiv.innerHTML = html;
        } else {
            contentDiv.innerHTML = `
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>Product not found on blockchain.</strong> 
                        This product exists in the database but hasn't been registered on the blockchain yet.
                    </div>
                </div>
                <div class="text-center py-3">
                    <button type="button" class="btn btn-success" id="registerOnBlockchain" onclick="registerProductOnBlockchain('${barcode}')">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Register on Blockchain
                    </button>
                </div>
            `;
        }
        
    } catch (error) {
        console.error('Error getting product info from blockchain:', error);
        contentDiv.innerHTML = `
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    <strong>Error retrieving blockchain data:</strong> ${error.message}
                </div>
            </div>
        `;
    }
}

// Function to register product on blockchain
async function registerProductOnBlockchain(barcode) {
    // You would need to get the product name from the database
    // For this example, we'll use a placeholder
    const productName = "Product " + barcode;
    
    try {
        const registerBtn = document.getElementById('registerOnBlockchain');
        registerBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Registering...';
        registerBtn.disabled = true;
        
        const accounts = await web3.eth.getAccounts();
        const result = await logisticsContract.methods.addProduct(barcode, productName)
            .send({ from: accounts[0] });
        
        console.log('Product registered on blockchain:', result);
        
        // Show success message and refresh
        alert('Product successfully registered on blockchain!');
        setTimeout(() => {
            getBlockchainInfo(barcode); // Refresh the modal content
        }, 1000);
        
    } catch (error) {
        console.error('Error registering product on blockchain:', error);
        alert('Failed to register product on blockchain: ' + error.message);
        
        const registerBtn = document.getElementById('registerOnBlockchain');
        registerBtn.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i> Register on Blockchain';
        registerBtn.disabled = false;
    }
}

// Search functionality
document.getElementById('searchProducts').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const table = document.getElementById('productsTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const barcode = rows[i].cells[0].textContent.toLowerCase();
        const name = rows[i].cells[1].textContent.toLowerCase();
        const category = rows[i].cells[2].textContent.toLowerCase();
        const origin = rows[i].cells[3].textContent.toLowerCase();
        
        if (barcode.includes(searchValue) || name.includes(searchValue) || 
            category.includes(searchValue) || origin.includes(searchValue)) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
});
</script>

<style>
.timeline-line {
    position: absolute;
    top: 40px;
    left: 20px;
    bottom: -30px;
    width: 2px;
    background-color: #eee;
    z-index: -1;
}
</style>


<!-- Script untuk integrasi Metamask -->
<script>
// Contract ABI - ganti dengan ABI kontrak Anda
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

