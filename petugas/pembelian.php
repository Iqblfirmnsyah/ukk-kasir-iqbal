<?php
include '../koneksi.php';


$query_pelanggan = "SELECT * FROM pelanggan";
$result_pelanggan = mysqli_query($koneksi, $query_pelanggan);


$query_pembelian = "SELECT p.PenjualanID, p.TanggalPenjualan AS TanggalPembelian, pl.NamaPelanggan AS pelanggan, p.TotalHarga 
          FROM penjualan p
          JOIN pelanggan pl ON p.PelangganID = pl.PelangganID";
$result_pembelian = mysqli_query($koneksi, $query_pembelian);


if (isset($_POST['simpan'])) {
    $tanggal_pembelian = $_POST['tanggal_pembelian'];
    $pelanggan = $_POST['pelanggan'];
    
    $query = "INSERT INTO penjualan (TanggalPenjualan, PelangganID, TotalHarga) VALUES ('$tanggal_pembelian', '$pelanggan', '0')";
    if (mysqli_query($koneksi, $query)) {
        header('Location: pembelian.php');
    }
}


if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM penjualan WHERE PenjualanID=$id";
    if (mysqli_query($koneksi, $query)) {
        header('Location: pembelian.php');
    }
}


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $tanggal_pembelian = $_POST['tanggal_pembelian'];
    $pelanggan = $_POST['pelanggan'];
    
    $query = "UPDATE penjualan SET TanggalPenjualan='$tanggal_pembelian', PelangganID='$pelanggan' WHERE PenjualanID=$id";
    if (mysqli_query($koneksi, $query)) {
        header('Location: pembelian.php');
    }
}

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
    <title>Aplikasi Kasir | Page Pembelian</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand">Petugas Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="petugas.php">Dashboard</a>
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
                    <a class="nav-link" href="../index.html">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <div class="col-12">
        <div class="alert alert-warning" role="alert">
            <h4 class="alert-heading">Halaman Pembelian!</h4>
            <p>Saat ini anda sedang dihalaman pembelian. Kelola data pembelian disini.</p>
        </div>
    </div>
    <button class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Pembelian</button>
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Pembelian</th>
                <th>Pelanggan</th>
                <th>Total Harga</th>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($result_pembelian)) { ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['TanggalPembelian']; ?></td>
                <td><?= $row['pelanggan']; ?></td>
                <td>Rp <?= number_format($row['TotalHarga'], 0, ',', '.'); ?></td>
                <td>
                    <a href='detail_pembelian.php?id=<?= $row['PenjualanID']; ?>' class='btn btn-info btn-sm'>Detail</a>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['PenjualanID']; ?>">Edit</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $row['PenjualanID']; ?>">Hapus</button>
                </td>
            </tr>
            <!-- Modal Hapus -->
            <div class="modal fade" id="hapusModal<?= $row['PenjualanID']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Hapus Pembelian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $row['PenjualanID']; ?>">
                                <p>Apakah Anda yakin ingin menghapus data pembelian <b><?= $row['pelanggan']; ?></b>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal Edit -->
            <div class="modal fade" id="editModal<?= $row['PenjualanID']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Pembelian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $row['PenjualanID']; ?>">
                                <div class="mb-3">
                                    <label>Tanggal Pembelian</label>
                                    <input type="date" name="tanggal_pembelian" class="form-control" value="<?= $row['TanggalPembelian']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Pelanggan</label>
                                    <select name="pelanggan" class="form-control" required>
                                        <option value="">-- Pilih Pelanggan --</option>
                                        <?php mysqli_data_seek($result_pelanggan, 0); while ($sup = mysqli_fetch_assoc($result_pelanggan)) { ?>
                                            <option value="<?= $sup['PelangganID']; ?>" <?= ($sup['PelangganID'] == $row['pelanggan'] ? 'selected' : ''); ?>><?= $sup['NamaPelanggan']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </tbody>
    </table>
    
    <!-- Modal Tambah Pembelian -->
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pembelian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tanggal Pembelian</label>
                            <input type="date" name="tanggal_pembelian" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Pelanggan</label>
                            <select name="pelanggan" class="form-control" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                <?php 
                                mysqli_data_seek($result_pelanggan, 0);
                                while ($row = mysqli_fetch_assoc($result_pelanggan)) { ?>
                                    <option value="<?= $row['PelangganID']; ?>"><?= $row['NamaPelanggan']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>