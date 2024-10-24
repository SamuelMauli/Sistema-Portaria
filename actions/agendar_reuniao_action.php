<?php
session_start();
require_once('../includes/db.php');

$username = $_POST['username'];
$password = $_POST['password'];

// Simulação de autenticação (substitua por sua lógica de banco de dados)
if ($username === 'admin' && $password === '1234') {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = $username;
    header('Location: ../pages/dashboard.php');
} else {
    echo "Usuário ou senha inválidos.";
}
?>
