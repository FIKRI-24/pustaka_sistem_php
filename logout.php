<?php
session_start(); // Mulai session untuk menghancurkan session yang aktif

// Hapus semua data session
session_destroy(); 
header('Location: login.php'); // Arahkan ke halaman login setelah logout
exit();
?>
