<?php
session_start();
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário e remove espaços extras
    $login = trim($_POST['login']);
    $password = trim($_POST['senha']);

    // Verifica se os campos estão preenchidos
    if (empty($login) || empty($password)) {
        $_SESSION['login_error'] = 'Usuário e senha são obrigatórios.';
        header('Location: ../pages/login.php');
        exit();
    }

    // Conecta ao banco de dados
    $conn = conectarBancoDeDados();
    
    // Prepara a consulta para buscar o usuário
    $stmt = $conn->prepare("SELECT id, login, senha FROM usuarios WHERE login = ?");
    $stmt->bind_param('s', $login);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário foi encontrado
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifica se a senha está correta
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

    // Redireciona de volta para a página de login com erro
    header('Location: ../pages/login.php');
    exit();
}
?>
