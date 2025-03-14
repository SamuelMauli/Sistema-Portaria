<?php
session_start();
require_once('../includes/db.php'); // Inclui o arquivo db.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = 'Usuário e senha são obrigatórios.';
        header('Location: ../pages<a href="/login">Login</a>');
        exit();
    }

    // Conecta ao banco de dados
    $conn = conectarBancoDeDados();

    // Verifica se o usuário existe
    $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE login = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Usuário encontrado, verifica a senha
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['senha'])) {
            // Senha correta, inicia a sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nome'];
            header('Location: ../pages/dashboard.php');
            exit();
        } else {
            // Senha incorreta
            $_SESSION['login_error'] = 'Senha incorreta.';
            header('Location: ../pages<a href="/login">Login</a>');
            exit();
        }
    } else {
        // Usuário não encontrado, cria o usuário administrador
        if ($stmt->execute()) {
            // Usuário criado com sucesso, tenta fazer login novamente
            $_SESSION['success'] = 'Usuário administrador criado com sucesso. Faça login.';
            header('Location: ../pages<a href="/login">Login</a>');
            exit();
        } else {
            // Erro ao criar o usuário
            $_SESSION['login_error'] = 'Erro ao criar usuário administrador.';
            header('Location: ../pages<a href="/login">Login</a>');
            exit();
        }
    }
}
?>