<?php
include '../koneksi.php';

if (isset($_POST['simpan_produk'])) {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $result = mysqli_query($koneksi, "SELECT MAX(ProdukID) AS max_id FROM produk");
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];
    $produk_id_baru = $max_id + 1;

    $query = "INSERT INTO produk (ProdukID, NamaProduk, Harga, Stok) VALUES ('$produk_id_baru', '$nama_produk', '$harga', '$stok')";
    mysqli_query($koneksi, $query);
    header('Location: databarang.php');
}


if (isset($_POST['update_produk'])) {
    $produk_id = $_POST['produk_id'];
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $query = "UPDATE produk SET NamaProduk='$nama_produk', Harga='$harga', Stok='$stok' WHERE ProdukID=$produk_id";
    mysqli_query($koneksi, $query);
    header('Location: databarang.php');
}


if (isset($_POST['hapus_produk'])) {
    $produk_id = $_POST['produk_id'];
    $query = "DELETE FROM produk WHERE ProdukID=$produk_id";
    mysqli_query($koneksi, $query);
    header('Location: databarang.php');
}


$data_produk = mysqli_query($koneksi, "SELECT * FROM produk");

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
        
    </style>
    <title>Aplikasi Kasir | Page Pendataan Barang</title>
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
                    <a class="nav-link active" href="databarang.php">Pendataan Barang</a>
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
    <div class="col-12">
        <div class="alert alert-warning" role="alert">
            <h4 class="alert-heading">Halaman Pendataan Barang!</h4>
            <p>Saat ini anda sedang dihalaman pendataan barang. Kelola data barang disini.</p>
        </div>
    </div>
    <button class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#tambahProdukModal">Tambah Produk</button>
    <table class="table table-responsive">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($data_produk)) { ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['NamaProduk']; ?></td>
                <td>Rp. <?= number_format($row['Harga'], 2); ?></td>
                <td><?= $row['Stok']; ?></td>
                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editProdukModal<?= $row['ProdukID']; ?>">Edit</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusProdukModal<?= $row['ProdukID']; ?>">Hapus</button>
                </td>
            </tr>

            <!-- Edit -->
            <div class="modal fade" id="editProdukModal<?= $row['ProdukID']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Produk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="produk_id" value="<?= $row['ProdukID']; ?>">
                                <div class="mb-3">
                                    <label>Nama Produk</label>
                                    <input type="text" name="nama_produk" class="form-control" value="<?= $row['NamaProduk']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Harga</label>
                                    <input type="number" name="harga" class="form-control" value="<?= $row['Harga']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Stok</label>
                                    <input type="number" name="stok" class="form-control" value="<?= $row['Stok']; ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="update_produk" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hapus -->
            <div class="modal fade" id="hapusProdukModal<?= $row['ProdukID']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Hapus Produk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="produk_id" value="<?= $row['ProdukID']; ?>">
                                <p>Apakah Anda yakin ingin menghapus produk <b><?= $row['NamaProduk']; ?></b>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="hapus_produk" class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php } ?>
        </tbody>
    </table>
</div>

<!-- Tambah -->
<div class="modal fade" id="tambahProdukModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="simpan_produk" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>