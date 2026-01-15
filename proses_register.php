<?php
include "koneksi_akademik.php";

if (isset($_POST['register'])) {

    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi_password'];

    if ($password !== $konfirmasi) {
        header("Location: register.php?pesan=Password dan konfirmasi tidak cocok");
        exit();
    }

    // Cek email
    $cek = $db->prepare("SELECT id FROM pengguna WHERE email = ?");
    $cek->bind_param("s", $email);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        header("Location: register.php?pesan=Email sudah terdaftar");
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // INSERT tanpa PRIMARY KEY
    $sql = "INSERT INTO pengguna (nama_lengkap, email, password) 
            VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);

    $stmt->bind_param("sss", $nama, $email, $password_hash);

    if ($stmt->execute()) {
        header("Location: login.php?pesan=Registrasi berhasil");
    } else {
        echo "Gagal daftar: " . $stmt->error;
    }

    $stmt->close();
    $cek->close();
}
$db->close();
?>
