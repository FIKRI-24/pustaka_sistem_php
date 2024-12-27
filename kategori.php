<?php
// Memulai sesi dan koneksi ke database
session_start();
include 'db.php'; // File koneksi database

// Redirect jika belum login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Tambah kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_kategori'])) {
    $nama_kategori = $_POST['nama_kategori'];
    if ($nama_kategori) {
        $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
        $stmt->bind_param("s", $nama_kategori);
        $stmt->execute();
        header('Location: kategori.php');
        exit();
    }
}

// Hapus kategori
if (isset($_GET['hapus'])) {
    $id_kategori = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id_kategori = ?");
    $stmt->bind_param("i", $id_kategori);
    $stmt->execute();
    header('Location: kategori.php');
    exit();
}

// Edit kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_kategori'])) {
    $id_kategori = $_POST['id_kategori'];
    $nama_kategori = $_POST['nama_kategori'];
    if ($id_kategori && $nama_kategori) {
        $stmt = $conn->prepare("UPDATE kategori SET nama_kategori = ? WHERE id_kategori = ?");
        $stmt->bind_param("si", $nama_kategori, $id_kategori);
        $stmt->execute();
        header('Location: kategori.php');
        exit();
    }
}

// Ambil data kategori
$result = $conn->query("SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Kategori</title>
    <link rel="stylesheet" href="style.css">
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #f9f9f9;
}

.navbar {
    width: 100%;
    padding: 15px;
    background-color: rgba(0, 0, 0, 0.7);
    position: fixed;
    top: 0;
    left: 0;
    color: white;
    text-align: center;
    z-index: 1000;
}

.navbar a {
    color: white;
    text-decoration: none;
    margin: 0 15px;
}

h1 {
    margin-top: 80px;
    color: #333;
}

/* Tabel */
table {
    width: 50%; /* Menyesuaikan lebar tabel agar lebih rapi */
    margin: 20px 0;
    border-collapse: collapse;
    text-align: center;
    background-color: white;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Menambahkan efek bayangan pada tabel */
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    word-wrap: break-word; /* Agar teks yang panjang dibungkus */
    text-align: center;
}

th {
    background-color: #4CAF50;
    color: white;
    width: 20%; /* Membatasi lebar kolom */
}

td {
    max-width: 200px; /* Membatasi lebar teks dalam kolom */
    overflow: hidden;
    text-overflow: ellipsis; /* Menambahkan elipsis jika teks terlalu panjang */
}

/* Menambahkan warna latar belakang zebra pada baris genap */
tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Tombol */
.btn {
    padding: 8px 16px;
    text-decoration: none;
    color: white;
    border-radius: 5px;
    font-size: 14px;
    margin: 5px;
    display: inline-block;
    text-align: center;
    margin-top: 25px;
}

.btn-tambah {
    background-color: #4CAF50;
}

.btn-edit {
    background-color: #007BFF;
}

.btn-hapus {
    background-color: #f44336;
}

/* Menjaga tombol dengan lebar yang sama dan tampilan rapi */
td button {
    width: 80px; /* Membuat tombol lebih kecil dan teratur */
    font-size: 14px; /* Menyesuaikan ukuran font tombol */
}

/* Responsif untuk layar lebih kecil */
@media (max-width: 768px) {
    table {
        width: 100%; /* Agar tabel bisa menyesuaikan ukuran layar kecil */
    }

    .btn {
        width: 100%; /* Tombol jadi lebar penuh pada perangkat mobile */
        font-size: 12px; /* Mengurangi ukuran font untuk tombol di perangkat kecil */
    }
}

    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="kategori.php">Kategori</a>
        <a href="buku.php">Buku</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Judul -->
    <h1>halaman Kategori</h1>

    <!-- Form Tambah Kategori -->
    <form method="POST" style="margin-bottom: 20px;">
        <input type="text" name="nama_kategori" placeholder="Nama Kategori" required>
        <button type="submit" name="tambah_kategori" class="btn btn-tambah">Tambah</button>
    </form>

    <!-- Tabel Kategori -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                        <td>
                            <!-- Tombol Edit -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id_kategori" value="<?= $row['id_kategori']; ?>">
                                <input type="text" name="nama_kategori" value="<?= htmlspecialchars($row['nama_kategori']); ?>" required>
                                <button type="submit" name="edit_kategori" class="btn btn-edit">Edit</button>
                            </form>

                            <!-- Tombol Hapus -->
                            <a href="kategori.php?hapus=<?= $row['id_kategori']; ?>" class="btn btn-hapus" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Tidak ada data kategori</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
