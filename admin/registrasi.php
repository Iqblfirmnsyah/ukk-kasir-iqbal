<?php
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $nama_petugas = $_POST['nama_petugas'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "INSERT INTO users (nama_petugas, username, password, role) VALUES ('$nama_petugas', '$username', '$password', '$role')";

    if (mysqli_query($koneksi, $query)) {
        header('Location: registrasi.php');
    }
}


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_petugas = $_POST['nama_petugas'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        $query = "UPDATE users SET nama_petugas='$nama_petugas', username='$username', password='$password', role='$role' WHERE id=$id";
    } else {
        $query = "UPDATE users SET nama_petugas='$nama_petugas', username='$username', role='$role' WHERE id=$id";
    }
    mysqli_query($koneksi, $query);
    header('Location: registrasi.php');
}

if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM users WHERE id=$id";
    mysqli_query($koneksi, $query);
    header('Location: registrasi.php');
}


$data_users = mysqli_query($koneksi, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <style>
        .navbar-brand {
            font-weight: bold;
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
                    <a class="nav-link" href="pembelian.php">Pembelian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pelanggan.php">Pelanggan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="registrasi.php">Registrasi</a>
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
            <th>Nama Petugas</th>
            <th>Username</th>
            <th>Akses Petugas</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($data_users)) { ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['nama_petugas']; ?></td>
                <td><?= $row['username']; ?></td>
                <td><?= $row['role']; ?></td>
                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">Edit</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $row['id']; ?>">Hapus</button>
                </td>
            </tr>

            <!-- Edit -->
            <div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <div class="mb-3">
                                    <label>Nama Petugas</label>
                                    <input type="text" name="nama_petugas" class="form-control" value="<?= $row['nama_petugas']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" value="<?= $row['username']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Password (Kosongkan jika tidak ingin mengubah)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Akses Petugas</label>
                                    <select name="role" class="form-control">
                                        <option>-- Pilih Role--</option>
                                        <option value="Administrator" <?= $row['role'] == 'administrator' ? 'selected' : ''; ?>>Administrator</option>
                                        <option value="Petugas" <?= $row['role'] == 'petugas' ? 'selected' : ''; ?>>Petugas</option>
                                    </select>
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
            <div class="modal fade" id="hapusModal<?= $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Hapus Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <p>Apakah Anda yakin ingin menghapus data <b><?= $row['nama_petugas']; ?></b>?</p>
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
                        <label>Nama Petugas</label>
                        <input type="text" name="nama_petugas" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Akses Petugas</label>
                        <select name="role" class="form-control" required>
                            <option>-- Pilih Role --</option>
                            <option value="Administrator">Administrator</option>
                            <option value="Petugas">Petugas</option>
                        </select>
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