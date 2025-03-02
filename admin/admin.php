<?php
include '../koneksi.php';

$totalStokQuery = mysqli_query($koneksi, "SELECT SUM(Stok) AS total_stok FROM produk");
$totalStokData = mysqli_fetch_assoc($totalStokQuery);
$totalStok = $totalStokData['total_stok'] ?? 0;


$totalPembelianQuery = mysqli_query($koneksi, "SELECT COUNT(*) AS total_pembelian FROM penjualan");
$totalPembelianData = mysqli_fetch_assoc($totalPembelianQuery);
$totalPembelian = $totalPembelianData['total_pembelian'] ?? 0;


$barangHabisQuery = mysqli_query($koneksi, "SELECT COUNT(*) AS barang_habis FROM produk WHERE Stok = 0");
$barangHabisData = mysqli_fetch_assoc($barangHabisQuery);
$barangHabis = $barangHabisData['barang_habis'] ?? 0;

$pelangganQuery = mysqli_query($koneksi, "SELECT COUNT(*) AS total_pelanggan FROM pelanggan");
$pelangganData = mysqli_fetch_assoc($pelangganQuery);
$totalPelanggan = $pelangganData['total_pelanggan'] ?? 0;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <style>
        .navbar-brand {
            font-weight: bold;
        }

        .card {
            margin: 0 2px;
        }
    </style>
    <title>Aplikasi Kasir | Page Dashboard Admin</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="admin.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="databarang.php">Pendataan Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pembelian.php">Pembelian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pelanggan.php">Pelanggan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="registrasi.php">Registrasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../index.html">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Selamat Datang di Aplikasi Kasir 114 Coffe & Resto!</h4>
                <p>Saat ini anda sedang dihalaman Dashboard Admin. Kelola data akun, barang, pembelian, dan stok barang dengan mudah melalui panel ini.</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Stok Barang</h5>
                    <p class="card-text"><?= $totalStok ?> Items</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-bg-info">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Pembelian</h5>
                    <p class="card-text"><?= $totalPembelian ?> Transaksi</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Pelanggan</h5>
                    <p class="card-text"><?= $totalPelanggan ?> Pelanggan</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Barang Habis</h5>
                    <p class="card-text"><?= $barangHabis ?> Items</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../bootstrap/css/bootstrap.min.js"></script>
</body>
</html>