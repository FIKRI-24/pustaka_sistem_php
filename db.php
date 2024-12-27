<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'pustaka_2';

$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
