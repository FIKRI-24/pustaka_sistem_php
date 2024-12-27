<?php
session_start();
include 'db.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Menangani proses saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $tahun = $_POST['tahun'];
    $id_kategori = $_POST['id_kategori'];
    $stok = $_POST['stok'];

    // Proses upload foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $file_name = $_FILES['foto']['name'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_size = $_FILES['foto']['size'];
        $file_type = $_FILES['foto']['type'];
        
        // Tentukan ekstensi file yang diizinkan
        $allowed_extensions = ['image/jpeg', 'image/png', 'image/jpg'];
        
        if (in_array($file_type, $allowed_extensions)) {
            // Tentukan lokasi penyimpanan file foto
            $upload_dir = 'uploads/';
            $file_path = $upload_dir . basename($file_name);

            // Cek apakah ukuran file tidak terlalu besar
            if ($file_size <= 2000000) {  // Maksimal 2MB
                // Pindahkan file ke folder upload
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Masukkan data buku ke database
                    $stmt = $conn->prepare("INSERT INTO buku (judul, penulis, tahun, id_kategori, stok, foto) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $judul, $penulis, $tahun, $id_kategori, $stok, $file_name);
                    $stmt->execute();

                    // Redirect ke halaman daftar buku setelah berhasil
                    header('Location: index.php');
                    exit();
                } else {
                    echo "Error saat mengunggah foto.";
                }
            } else {
                echo "Ukuran foto terlalu besar. Maksimal 2MB.";
            }
        } else {
            echo "Tipe file tidak didukung. Hanya JPG, JPEG, dan PNG yang diperbolehkan.";
        }
    } else {
        echo "Foto buku harus diunggah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Tambah Buku</h1>
    <form action="tambah_buku.php" method="POST" enctype="multipart/form-data">
        <label for="judul">Judul Buku:</label>
        <input type="text" name="judul" id="judul" required><br><br>

        <label for="penulis">Penulis:</label>
        <input type="text" name="penulis" id="penulis" required><br><br>

        <label for="tahun">Tahun Terbit:</label>
        <input type="number" name="tahun" id="tahun" required><br><br>

        <label for="id_kategori">Kategori:</label>
        <select name="id_kategori" id="id_kategori" required>
            <!-- Ambil data kategori dari database -->
            <?php
            $result_kategori = $conn->query("SELECT * FROM kategori");
            while ($row_kategori = $result_kategori->fetch_assoc()) {
                echo "<option value='{$row_kategori['id_kategori']}'>{$row_kategori['nama_kategori']}</option>";
            }
            ?>
        </select><br><br>

        <label for="stok">Stok:</label>
        <input type="number" name="stok" id="stok" required><br><br>

        <label for="foto">Foto Buku:</label>
        <input type="file" name="foto" id="foto" accept="image/*" required><br><br>

        <button type="submit">Tambah Buku</button>
    </form>
</body>
</html>
