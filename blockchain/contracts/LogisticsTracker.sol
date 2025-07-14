// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

contract LogisticsTracker {
    // Struktur data untuk produk
    struct Product {
        string name;
        bool exists;
        address creator;
    }
    
    // Struktur data untuk lokasi/status
    struct TrackingUpdate {
        string location;
        uint256 timestamp;
    }
    
    // Mapping dari barcode ke data produk
    mapping(string => Product) private products;
    
    // Mapping dari barcode ke array lokasi
    mapping(string => TrackingUpdate[]) private trackingHistory;
    
    // Event untuk tracking
    event ProductAdded(string barcode, string name, address creator);
    event LocationUpdated(string barcode, string location, uint256 timestamp);
    
    // Menambahkan produk baru
    function addProduct(string memory barcode, string memory name) public returns (bool) {
        // Pastikan produk belum ada
        require(!products[barcode].exists, "Product already exists");
        
        // Tambahkan produk baru
        products[barcode] = Product({
            name: name,
            exists: true,
            creator: msg.sender
        });
        
        // Trigger event
        emit ProductAdded(barcode, name, msg.sender);
        return true;
    }
    
    // Update lokasi produk
    function updateLocation(string memory barcode, string memory location) public returns (bool) {
        // Pastikan produk sudah ada
        require(products[barcode].exists, "Product does not exist");
        
        // Tambahkan update lokasi baru
        trackingHistory[barcode].push(TrackingUpdate({
            location: location,
            timestamp: block.timestamp
        }));
        
        // Trigger event
        emit LocationUpdated(barcode, location, block.timestamp);
        return true;
    }
    
    // Mendapatkan info produk
    function getProductInfo(string memory barcode) public view returns (string memory, bool) {
        Product memory product = products[barcode];
        return (product.name, product.exists);
    }
    
    // Mendapatkan jumlah update lokasi
    function getLocationUpdatesCount(string memory barcode) public view returns (uint256) {
        return trackingHistory[barcode].length;
    }
    
    // Mendapatkan update lokasi berdasarkan index
    function getLocationUpdate(string memory barcode, uint256 index) 
        public view returns (string memory, uint256) {
        require(index < trackingHistory[barcode].length, "Index out of bounds");
        
        TrackingUpdate memory update = trackingHistory[barcode][index];
        return (update.location, update.timestamp);
    }
    
    // Simple test function
    function testFunction() public pure returns (string memory) {
        return "Contract works!";
    }
}