<?php
include 'db.php';

$id_peminjaman = $_GET['id'];

// Perbarui status peminjaman dan tambahkan stok buku
$peminjaman = $conn->query("SELECT * FROM peminjaman WHERE id_peminjaman = $id_peminjaman")->fetch_assoc();
$id_buku = $peminjaman['id_buku'];

$conn->query("UPDATE peminjaman SET status = 'dikembalikan' WHERE id_peminjaman = $id_peminjaman");
$conn->query("UPDATE buku SET stok = stok + 1 WHERE id_buku = $id_buku");

header('Location: daftar_peminjaman.php');
exit;
?>
