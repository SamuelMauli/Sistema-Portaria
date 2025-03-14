<?php
session_start();
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['senha']);

    if (empty($login) || empty($password)) {
        $_SESSION['login_error'] = 'Usuário e senha são obrigatórios.';
        header('Location: ../pages/login.php');
        exit();
    }

    $conn = conectarBancoDeDados();
    
    $stmt = $conn->prepare("SELECT id, login, senha FROM usuarios WHERE login = ?");
    $stmt->bind_param('s', $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login'] = $user['login'];
            header('Location: ../pages/dashboard.php');
            exit();
        } else {
            $_SESSION['login_error'] = 'Senha incorreta.';
        }
    } else {
        $_SESSION['login_error'] = 'Usuário não encontrado.';
    }

    header('Location: ../pages/login.php');
    exit();
}
?>
