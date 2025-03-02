<?php
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $NamaPelanggan = $_POST['NamaPelanggan'];
    $Alamat = $_POST['Alamat'];
    $NomorTelepon = $_POST['NomorTelepon'];

    $query = "INSERT INTO pelanggan (NamaPelanggan, Alamat, NomorTelepon) VALUES ('$NamaPelanggan', '$Alamat', '$NomorTelepon')";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: pelanggan.php");
    }
}

if (isset($_POST['update'])) {
    $PelangganID = $_POST['PelangganID'];
    $NamaPelanggan = $_POST['NamaPelanggan'];
    $Alamat = $_POST['Alamat'];
    $NomorTelepon = $_POST['NomorTelepon'];

    $query = "UPDATE pelanggan SET NamaPelanggan='$NamaPelanggan', Alamat='$Alamat', NomorTelepon='$NomorTelepon' WHERE PelangganID=$PelangganID";

    mysqli_query($koneksi, $query);
    header('Location: pelanggan.php');
}

if (isset($_POST['hapus'])) {
    $PelangganID = $_POST['PelangganID'];
    $query = "DELETE FROM pelanggan WHERE PelangganID=$PelangganID";
    mysqli_query($koneksi, $query);
    header('Location: pelanggan.php');
}

$data_pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan");

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
    <title>Aplikasi Kasir | Page Pelanggan</title>
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
                    <a class="nav-link" href="pembelian.php">Pembelian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="pelanggan.php">Pelanggan</a>
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
            <h4 class="alert-heading">Halaman Registrasi!</h4>
            <p>Saat ini anda sedang dihalaman Registrasi. Kelola data akun disini.</p>
        </div>
    </div>
    <button class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambahkan Pengguna</button>
    <table class="table table-responsive">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama Pelanggan</th>
            <th>Alamat</th>
            <th>Nomor Telepon</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($data_pelanggan)) { ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['NamaPelanggan']; ?></td>
                <td><?= $row['Alamat']; ?></td>
                <td><?= $row['NomorTelepon']; ?></td>
                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['PelangganID']; ?>">Edit</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $row['PelangganID']; ?>">Hapus</button>
                </td>
            </tr>

            <!-- Edit -->
            <div class="modal fade" id="editModal<?= $row['PelangganID']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="PelangganID" value="<?= $row['PelangganID']; ?>">
                                <div class="mb-3">
                                    <label>Nama Petugas</label>
                                    <input type="text" name="NamaPelanggan" class="form-control" value="<?= $row['NamaPelanggan']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Alamat</label>
                                    <input type="text" name="Alamat" class="form-control" value="<?= $row['Alamat']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Nomor Telepon</label>
                                    <input type="integer" name="NomorTelepon" class="form-control" value="<?= $row['NomorTelepon']; ?>">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hapus -->
            <div class="modal fade" id="hapusModal<?= $row['PelangganID']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Hapus Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="PelangganID" value="<?= $row['PelangganID']; ?>">
                                <p>Apakah Anda yakin ingin menghapus data <b><?= $row['NamaPelanggan']; ?></b>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
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
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="NamaPelanggan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <input type="text" name="Alamat" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nomor Telepon</label>
                        <input type="text" name="NomorTelepon" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>