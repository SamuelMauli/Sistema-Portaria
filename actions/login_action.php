<?php
session_start();
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = getConnection();

    $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE nome = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['senha'])) {
            // Login bem-sucedido, define as variáveis de sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nome'];

            header('Location: ../pages/dashboard.php');
            exit();
        } else {
            $_SESSION['login_error'] = 'Senha incorreta. Tente novamente.';
            header('Location: ../pages/login.php');
            exit();
        }
    } else {
        $_SESSION['login_error'] = 'Usuário não encontrado.';
        header('Location: ../pages/login.php');
        exit();
    }
}
?>
