<?php
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' AND role='$role'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        session_start();
        $_SESSION["username"] == $username;
        $_SESSION["role"] == $role;
        
        if ($role=='administrator') {
            header("Location: admin/admin.php");
        } else if ($role=='petugas') {
            header("Location: petugas/petugas.php");
        } else {
            echo "<script> alert('Role tidak dikenali') window.location='index.html'</script>";
        }
    } else {
        echo "<script> alert('Login gagal, periksa kembali username, password, dan role anda') window.location='index.html';</script>";
    }
}

?>