<?php
session_start();
require_once('../includes/db.php');

$username = $_POST['username'];
$password = $_POST['password'];

// Simula��o de autentica��o (substitua por sua l�gica de banco de dados)
if ($username === 'admin' && $password === '1234') {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = $username;
    header('Location: ../pages/dashboard.php');
} else {
    echo "Usu�rio ou senha inv�lidos.";
}
?>
