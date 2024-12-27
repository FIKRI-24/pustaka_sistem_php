<?php
session_start(); // Mulai session 


$users = [
    'admin' => 'password123', 
    'user1' => 'mypassword'
];

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Memeriksa apakah username dan password valid
if (isset($users[$username]) && $users[$username] === $password) {
    $_SESSION['username'] = $username; 
    header('Location: index.php'); 
    exit(); 
} else {
    echo 'Username atau password salah';
}
?>
