<?php
include '../koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID Penjualan tidak ditemukan!";
    exit;
}
$penjualanID = $_GET['id'];

$query_penjualan = mysqli_query($koneksi, "SELECT p.TanggalPenjualan, pl.NamaPelanggan FROM penjualan p JOIN pelanggan pl ON p.PelangganID = pl.PelangganID WHERE p.PenjualanID = '$penjualanID'");
$data_penjualan = mysqli_fetch_assoc($query_penjualan);

$query_produk = mysqli_query($koneksi, "SELECT * FROM produk");

$query_detail = mysqli_query($koneksi, "SELECT dp.*, pr.NamaProduk, pr.Harga FROM detailpenjualan dp JOIN produk pr ON dp.ProdukID = pr.ProdukID WHERE dp.PenjualanID = '$penjualanID'");

$total_harga = 0;
while ($row = mysqli_fetch_assoc($query_detail)) {
    $total_harga += $row['SubTotal'];
}

mysqli_query($koneksi, "UPDATE penjualan SET TotalHarga = '$total_harga' WHERE PenjualanID = '$penjualanID'");


if (isset($_POST['tambah_produk'])) {
    $produkID = $_POST['produkID'];
    $jumlah = $_POST['jumlah'];

    $query_harga = mysqli_query($koneksi, "SELECT Harga, Stok FROM produk WHERE ProdukID = '$produkID'");
    $data_harga = mysqli_fetch_assoc($query_harga);
    $harga = $data_harga['Harga'];
    $stok = $data_harga['Stok'];
    
    $subtotal = $harga * $jumlah;

    $stok_baru = $stok - $jumlah;
    if ($stok_baru < 0) {
        echo "<script>alert('Stok tidak mencukupi!');</script>";
    } else {
        mysqli_query($koneksi, "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, SubTotal) VALUES ('$penjualanID', '$produkID', '$jumlah', '$subtotal')");
        mysqli_query($koneksi, "UPDATE produk SET Stok = '$stok_baru' WHERE ProdukID = '$produkID'");
        echo "<script>window.location.href='detail_pembelian.php?id=$penjualanID';</script>";
    }
}

if (isset($_GET['hapus'])) {
    $detailID = $_GET['hapus'];
    
    $query_hapus = mysqli_query($koneksi, "SELECT * FROM detailpenjualan WHERE DetailID = '$detailID'");
    $data_hapus = mysqli_fetch_assoc($query_hapus);
    $produkID = $data_hapus['ProdukID'];
    $jumlah = $data_hapus['JumlahProduk'];
    $subtotal = $data_hapus['SubTotal'];
    
    mysqli_query($koneksi, "UPDATE produk SET Stok = Stok + '$jumlah' WHERE ProdukID = '$produkID'");
    
    mysqli_query($koneksi, "DELETE FROM detailpenjualan WHERE DetailID = '$detailID'");
    
    mysqli_query($koneksi, "UPDATE penjualan SET TotalHarga = TotalHarga - '$subtotal' WHERE PenjualanID = '$penjualanID'");
    
    echo "<script>window.location.href='detail_pembelian.php?id=$penjualanID';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembelian</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <style>
        .navbar-brand {
            font-weight: bold;
        }

        .card{
            margin: 10px 0;
        }
    </style>
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
                    <a class="nav-link" href="admin.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="databarang.php">Pendataan Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="pembelian.php">Pembelian</a>
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
    <div class="col-12">
        <div class="alert alert-warning" role="alert">
            <h4 class="alert-heading">Halaman Detail Pembelian!</h4>
            <p>Saat ini anda sedang dihalaman detail pembelian. Kelola data pembelian disini.</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">

                <!-- Informasi Pelanggan -->
                <div class="col-lg-5 col-md-6">
                    <div class="card text-bg">
                        <div class="card-body">
                            <h5 class="card-tittle">Informasi Pelanggan</h5>
                            <table class="table table-responsive">
                                <tr>
                                    <td>Tanggal Pembelian</td>
                                    <td>: <?= $data_penjualan['TanggalPenjualan']; ?></td>
                                </tr>
                                <tr>
                                    <td>Nama Pelanggan</td>
                                    <td>: <?= $data_penjualan['NamaPelanggan']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tambah -->
                <div class="col-lg-7 col-md-6">
                    <div class="card text-bg">
                        <div class="card-body">
                            <form method="POST">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk & Harga</th>
                                            <th>Jumlah</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="produkID" class="form-control">
                                                    <?php while ($produk = mysqli_fetch_assoc($query_produk)) { ?>
                                                        <option value="<?= $produk['ProdukID']; ?>">
                                                            <?= $produk['NamaProduk']; ?> - Rp<?= number_format($produk['Harga']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="jumlah" class="form-control" required>
                                            </td>
                                            <td>
                                            <button type="submit" name="tambah_produk" class="btn btn-secondary btn-sm">Tambah</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            
                <!-- Daftar -->
                <div class="col-lg-9 col-md-6">
                    <div class="card text-bg">
                        <div class="card-body">
                            <h5 class="card-tittle">Daftar Produk Penjualan</h5>
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query_detail = mysqli_query($koneksi, "SELECT dp.*, pr.NamaProduk, pr.Harga FROM detailpenjualan dp JOIN produk pr ON dp.ProdukID = pr.ProdukID WHERE dp.PenjualanID = '$penjualanID'");
                                    while ($row = mysqli_fetch_assoc($query_detail)) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $row['NamaProduk']; ?></td>
                                            <td>Rp<?= number_format($row['Harga']); ?></td>
                                            <td><?= $row['JumlahProduk']; ?></td>
                                            <td>Rp<?= number_format($row['SubTotal']); ?></td>
                                            <td><a href="detail_pembelian.php?id=<?= $penjualanID; ?>&hapus=<?= $row['DetailID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?');">Hapus</a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Total Penjualan -->
                <div class="col-lg-3 col-md-6">
                    <div class="card text-bg">
                        <div class="card-body">
                            <h5 class="card-title">Total Penjualan:</h5>
                            <h2>Rp<?= number_format($total_harga); ?></h2>
                        </div>
                    </div>
                    <div class="card text-bg mt-3">
                        <div class="card-body">
                            <a href="pembelian.php" class="btn btn-warning">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>