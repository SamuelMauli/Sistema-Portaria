<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/sidebar.php');
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoCadastro = $_POST['tipoCadastro'];

    try {
        if ($tipoCadastro === 'funcionario') {
            $nome = trim($_POST['nome']);
            $telefone = trim($_POST['telefone']);

            $sql = "INSERT INTO funcionarios (nome, telefone) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $nome, $telefone);
            $stmt->execute();
            $_SESSION['success'] = "Funcionário cadastrado com sucesso!";
        } elseif ($tipoCadastro === 'motorista') {
            $nome = trim($_POST['nome']);
            $doc_identidade = trim($_POST['doc_identidade']);
            $telefone = trim($_POST['telefone']);
            $transportadora_id = intval($_POST['transportadora_id']);

            $sql = "INSERT INTO motoristas (nome, doc_identidade, telefone, transportadora_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nome, $doc_identidade, $telefone, $transportadora_id);
            $stmt->execute();
            $_SESSION['success'] = "Motorista cadastrado com sucesso!";
        } elseif ($tipoCadastro === 'transportadora') {
            $nome = trim($_POST['nome']);
            $telefone = trim($_POST['telefone']);
            $endereco = trim($_POST['endereco']);

            $sql = "INSERT INTO transportadoras (nome, telefone, endereco) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nome, $telefone, $endereco);
            $stmt->execute();
            $_SESSION['success'] = "Transportadora cadastrada com sucesso!";
        } elseif ($tipoCadastro === 'usuario_sistema') {
            $nome = trim($_POST['nome']);
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios_sistema (nome, senha) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $nome, $senha);
            $stmt->execute();
            $_SESSION['success'] = "Usuário do sistema cadastrado com sucesso!";
        } elseif ($tipoCadastro === 'visitante') {
            $nome = trim($_POST['nome']);
            $doc_identidade = trim($_POST['doc_identidade']);
            $empresa = trim($_POST['empresa']);
            $veiculo = trim($_POST['veiculo']);
            $telefone = trim($_POST['telefone']);
            $finalidade_id = intval($_POST['finalidade_id']);

            $sql = "INSERT INTO visitantes (nome, doc_identidade, empresa, veiculo, telefone, finalidade_id) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $nome, $doc_identidade, $empresa, $veiculo, $telefone, $finalidade_id);
            $stmt->execute();
            $_SESSION['success'] = "Visitante cadastrado com sucesso!";
        } else {
            throw new Exception("Tipo de cadastro inválido.");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    } finally {
        header('Location: cadastro.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <?php include('../includes/sidebar.php'); ?>

        <div class="container-cadastro">
            <h2>Cadastro</h2>
            <p style="text-align: center;">Clique em uma das opções abaixo para cadastrar:</p>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="nav-buttons">
                <button class="nav-button" data-form="form-funcionario">Funcionario</button>
                <button class="nav-button" data-form="form-motorista">Motorista</button>
                <button class="nav-button" data-form="form-transportadora">Transportadora</button>
                <button class="nav-button" data-form="form-usuario_sistema">Usuario do Sistema</button>
                <button class="nav-button" data-form="form-visitante">Visitante</button>
            </div>

            <!-- Formulários de cadastro -->
            <?php include('forms/cadastro_forms.php'); ?>
        </div>
    </div>

    <script>
        // Lógica para alternar entre os formulários
        document.querySelectorAll('.nav-button').forEach(button => {
            button.addEventListener('click', function () {
                const formId = this.getAttribute('data-form');
                document.querySelectorAll('.formulario-cadastro').forEach(form => {
                    form.style.display = (form.id === formId) ? 'block' : 'none';
                });
            });
        });
    </script>
</body>
</html>