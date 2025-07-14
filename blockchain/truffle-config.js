module.exports = {
  networks: {
    development: {
      host: "127.0.0.1",
      port: 7545,  // Port default Ganache GUI (ganti dengan 8545 jika menggunakan ganache-cli)
      network_id: "*"
    }
  },
  compilers: {
    solc: {
      version: "0.8.17",  // Gunakan versi yang stabil
      settings: {
        optimizer: {
          enabled: true,
          runs: 200
        }
      }
    }
  }
};

