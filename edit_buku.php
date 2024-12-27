<?php
include 'db.php';

$id = $_GET['id'];
$buku = $conn->query("SELECT * FROM buku WHERE id_buku = $id")->fetch_assoc();
$kategori = $conn->query("SELECT * FROM kategori");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $tahun = $_POST['tahun'];
    $id_kategori = $_POST['id_kategori'];
    $stok = $_POST['stok'];

    // Cek apakah ada foto yang diupload
    $foto = $_FILES['foto']['name'];
    if ($foto) {
        $target_dir = "uploads/"; // Direktori untuk menyimpan file foto
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file); // Pindahkan file foto ke folder tujuan

        // Update foto di database
        $conn->query("UPDATE buku SET judul = '$judul', penulis = '$penulis', tahun = '$tahun', id_kategori = '$id_kategori', stok = '$stok', foto = '$foto' 
                      WHERE id_buku = $id");
    } else {
        // Jika tidak ada foto yang diupload, update data tanpa mengganti foto
        $conn->query("UPDATE buku SET judul = '$judul', penulis = '$penulis', tahun = '$tahun', id_kategori = '$id_kategori', stok = '$stok' 
                      WHERE id_buku = $id");
    }

    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edit Buku</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Judul</label><br>
        <input type="text" name="judul" value="<?= $buku['judul']; ?>" required><br><br>

        <label>Penulis</label><br>
        <input type="text" name="penulis" value="<?= $buku['penulis']; ?>" required><br><br>

        <label>Tahun</label><br>
        <input type="number" name="tahun" value="<?= $buku['tahun']; ?>" required><br><br>

        <label>Kategori</label><br>
        <select name="id_kategori" required>
            <?php while ($row = $kategori->fetch_assoc()): ?>
                <option value="<?= $row['id_kategori']; ?>" <?= $buku['id_kategori'] == $row['id_kategori'] ? 'selected' : ''; ?>>
                    <?= $row['nama_kategori']; ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Stok</label><br>
        <input type="number" name="stok" value="<?= $buku['stok']; ?>" required><br><br>

        <label>Foto Buku (Opsional)</label><br>
        <input type="file" name="foto"><br><br>
        <p>Foto Saat Ini: <img src="uploads/<?= $buku['foto']; ?>" width="100" alt="Foto Buku"></p> <!-- Menampilkan foto buku saat ini -->

        <button type="submit">Simpan</button>
    </form>
</body>
</html>
