<?php
session_start();
include 'db.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Jika belum login, arahkan ke halaman login
    exit();
}

// Tampilkan pesan selamat datang setelah login
$_SESSION['username'] . '!<br>';

// Ambil data buku
$result = $conn->query("SELECT id_buku, buku.judul, buku.penulis, buku.tahun, kategori.nama_kategori, buku.stok, buku.foto
                        FROM buku 
                        JOIN kategori ON buku.id_kategori = kategori.id_kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet"> <!-- AOS CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f9f9f9;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Warna transparan */
            padding: 10px 20px;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
            font-weight: bold;
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #4CAF50;
        }

        .navbar .brand {
            font-size: 20px;
            font-weight: bold;
            color: #4CAF50;
        }

        h1 {
            text-align: center;
            margin-top: 80px; /* Tambahkan jarak karena navbar fixed */
            color: #333;
        }

        .tambah {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            color: white;
            background-color: #4CAF50;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            transition: background 0.3s;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }

        .tambah:hover {
            background-color: #45a049;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            color: #333;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            color: #000;
        }

        td img {
            max-width: 100px;
            border-radius: 5px;
        }

        .btn {
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            display: inline-block;
            font-size: 14px;
            text-align: center;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #4CAF50;
        }

        .btn-edit:hover {
            background-color: #45a049;
        }

        .btn-hapus {
            background-color: #f44336;
        }

        .btn-hapus:hover {
            background-color: #e41e26;
        }

        .logout {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 15px;
        }

        .logout:hover {
            background-color: #e41e26;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="brand">Perpustakaan</div>
        <div>
            <a href="index.php">Home</a>
            <a href="kategori.php">Kategori</a>
            <a href="about.php">About</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <h1 data-aos="fade-up" data-aos-duration="1000">Daftar Buku</h1>

    <a href="tambah_buku.php" class="tambah" data-aos="zoom-in" data-aos-duration="1000">Tambah Buku</a>

    <!-- Tabel Daftar Buku -->
    <table border="1" cellpadding="10" cellspacing="0" data-aos="fade-up" data-aos-duration="1500">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr data-aos="fade-up" data-aos-duration="1500">
                        <td><?= $no++; ?></td>
                        <td><?= $row['judul']; ?></td>
                        <td><?= $row['penulis']; ?></td>
                        <td><?= $row['tahun']; ?></td>
                        <td><?= $row['nama_kategori']; ?></td>
                        <td><?= $row['stok']; ?></td>
                        <td>
                            <?php if ($row['foto']): ?>
                                <img src="uploads/<?= $row['foto']; ?>" alt="Foto Buku" width="100">
                            <?php else: ?>
                                <p>Tidak ada foto</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_buku.php?id=<?= $row['id_buku']; ?>" class="btn btn-edit">Edit</a>
                            <a href="hapus_buku.php?id=<?= $row['id_buku']; ?>" class="btn btn-hapus" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
