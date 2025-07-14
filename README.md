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
- 🔐 Skor keamanan (CVSS v3.1): **0.0 (None)** terhadap serangan manipulasi/penghapusan data

---

## ▶️ Cara Menjalankan

1. **Clone repository ini**
   ```bash
   git clone https://github.com/USERNAME/NAMA_REPOSITORY.git
Jalankan Ganache untuk membuat blockchain lokal.

Deploy smart contract ke Ganache menggunakan Remix IDE atau Truffle.

Sambungkan MetaMask ke jaringan Ganache lokal dengan mengimpor private key akun Ganache.

Konfigurasi koneksi Web3.js di file app.js agar sesuai dengan:

contractAddress hasil deploy

ABI dari smart contract kamu

Aktifkan server lokal PHP

bash
Copy
Edit
php -S localhost:8000
Akses sistem melalui browser:

arduino
Copy
Edit
http://localhost:8000
📂 Struktur Folder
bash
Copy
Edit
├── contracts/           # Berisi file smart contract Solidity
├── web/                 # Folder utama sistem web (PHP, JS, CSS)
│   ├── index.php
│   ├── config/
│   ├── functions/
│   └── views/
├── app.js               # Koneksi blockchain via Web3.js
├── database.sql         # Struktur database MySQL
└── README.md            # Dokumentasi proyek
👨‍💻 Developer
Nama: Riga Aryadi Pratama

NRP: 5002211172

Program Studi: Matematika, ITS

Dosen Pembimbing: Dr. Budi Setiyono, S.Si., M.T.

📄 Lisensi
Proyek ini disediakan untuk keperluan akademik dan riset. Bebas digunakan dan dimodifikasi dengan tetap mencantumkan atribusi kepada penulis.
