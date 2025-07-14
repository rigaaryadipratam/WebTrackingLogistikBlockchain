# 📦 Sistem Web Tracking Logistik Berbasis Blockchain dan Smart Contract

Repository ini merupakan bagian dari Tugas Akhir berjudul **"Implementasi Smart Contract Solidity pada Blockchain Local Network untuk Web Tracking Logistik"** yang dikembangkan oleh _Riga Aryadi Pratama_ sebagai mahasiswa Program Studi Matematika, Institut Teknologi Sepuluh Nopember (ITS).

---

## 🔍 Deskripsi Proyek

Proyek ini bertujuan mengatasi permasalahan dalam sistem logistik konvensional yang sering mengalami kendala pada aspek transparansi, pelacakan real-time, dan keamanan data. Solusi yang ditawarkan adalah membangun sistem pelacakan logistik berbasis web yang terintegrasi dengan *smart contract* menggunakan Solidity pada jaringan *blockchain* lokal (Ganache), serta dihubungkan melalui arsitektur PHP Native.

---

## 🚀 Fitur Utama

- ✅ Input data produk beserta metadata distribusinya
- 📍 Pelacakan distribusi produk secara real-time
- 🔄 Pembaruan lokasi distribusi pada setiap titik
- 🔒 Audit histori data logistik berbasis *blockchain* (immutable)
- 🔗 Integrasi MetaMask untuk otentikasi transaksi
- 🌐 Interaksi blockchain melalui Web3.js

---

## ⚙️ Teknologi yang Digunakan

| Komponen       | Teknologi                      |
|----------------|--------------------------------|
| Backend        | PHP Native 8, MySQL            |
| Smart Contract | Solidity (Ethereum)            |
| Blockchain     | Ganache (Local Blockchain)     |
| Frontend       | HTML, Bootstrap, JavaScript    |
| Integrasi      | Web3.js, MetaMask              |

---

## 🧪 Hasil Pengujian

- ✅ Tingkat keberhasilan fungsional: **100%**
- ⚡ Rata-rata waktu respons: **10,40 ms**
- 🔐 Skor keamanan (CVSS v3.1): **0.0 (None)** terhadap skenario manipulasi/penghapusan data

---

## ▶️ Cara Menjalankan

1. **Jalankan Ganache** untuk membuat blockchain lokal.

2. **Deploy smart contract** ke Ganache menggunakan Remix IDE atau Truffle.

3. **Sambungkan MetaMask** ke jaringan Ganache lokal dengan mengimpor _private key_ dari salah satu akun Ganache.

4. **Konfigurasi koneksi Web3.js** di file `app.js` agar sesuai dengan:
   - `contractAddress` hasil dari proses deploy
   - `ABI` dari smart contract yang telah dibuat

5. **Aktifkan server lokal PHP**
   ```bash
   php -S localhost:8000
   ```

6. **Akses sistem melalui browser**
   ```url
   http://localhost:8000
   ```

---

## 📂 Struktur Folder

```bash
├── contracts/           # Berisi file smart contract Solidity
├── web/                 # Folder utama sistem web (PHP, JS, CSS)
│   ├── index.php
│   ├── config/
│   ├── functions/
│   └── views/
├── app.js               # Koneksi blockchain via Web3.js
├── database.sql         # Struktur database MySQL
└── README.md            # Dokumentasi proyek
```

---

## 👨‍💻 Developer

- **Nama:** Riga Aryadi Pratama  
- **NRP:** 5002211172  
- **Program Studi:** Matematika, ITS  
- **Dosen Pembimbing:** Dr. Budi Setiyono, S.Si., M.T.

---

## 📄 Lisensi

Proyek ini disediakan untuk keperluan akademik dan riset. Bebas digunakan dan dimodifikasi dengan tetap mencantumkan atribusi kepada penulis.
