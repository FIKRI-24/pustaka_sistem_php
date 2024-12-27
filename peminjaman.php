<?php
include 'db.php';

// Ambil data buku dan anggota
$buku = $conn->query("SELECT * FROM buku WHERE stok > 0");
$anggota = $conn->query("SELECT * FROM anggota");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_anggota = $_POST['id_anggota'];
    $id_buku = $_POST['id_buku'];
    $tanggal_pinjam = date('Y-m-d');
    $tanggal_kembali = date('Y-m-d', strtotime('+7 days'));

    // Kurangi stok buku
    $conn->query("UPDATE buku SET stok = stok - 1 WHERE id_buku = $id_buku");

    // Masukkan data ke tabel peminjaman
    $conn->query("INSERT INTO peminjaman (id_anggota, id_buku, tanggal_pinjam, tanggal_kembali)
                  VALUES ('$id_anggota', '$id_buku', '$tanggal_pinjam', '$tanggal_kembali')");

    header('Location: daftar_peminjaman.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Peminjaman Buku</h1>
    <form action="" method="POST">
        <label>Anggota</label><br>
        <select name="id_anggota" required>
            <?php while ($row = $anggota->fetch_assoc()): ?>
                <option value="<?= $row['id_anggota']; ?>"><?= $row['nama']; ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Buku</label><br>
        <select name="id_buku" required>
            <?php while ($row = $buku->fetch_assoc()): ?>
                <option value="<?= $row['id_buku']; ?>"><?= $row['judul']; ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">Pinjam</button>
    </form>
</body>
</html>
