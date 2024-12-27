<?php
include 'db.php';

$result = $conn->query("SELECT peminjaman.*, buku.judul, anggota.nama
                        FROM peminjaman
                        JOIN buku ON peminjaman.id_buku = buku.id_buku
                        JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman</title>
</head>
<body>
    <h1>Riwayat Peminjaman</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['judul']; ?></td>
                        <td><?= $row['tanggal_pinjam']; ?></td>
                        <td><?= $row['tanggal_kembali']; ?></td>
                        <td><?= $row['status']; ?></td>
                        <td>
                            <?php if ($row['status'] == 'dipinjam'): ?>
                                <a href="kembalikan.php?id=<?= $row['id_peminjaman']; ?>">Kembalikan</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
