<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../includes/db.php');

// Funcão para processar os cadastros de cada tipo
function processForm($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tipoCadastro = $_POST['tipoCadastro'];

        switch ($tipoCadastro) {
            case 'funcionario_aeb':
                $nome = $_POST['nome'];
                $telefone = $_POST['telefone'];
                $stmt = $conn->prepare("INSERT INTO funcionarios_aeb (nome, telefone) VALUES (?, ?)");
                $stmt->bind_param("ss", $nome, $telefone);
                $stmt->execute();
                echo "Funcionario AEB cadastrado com sucesso!";
                break;

            case 'motorista':
                $nome = $_POST['nome'];
                $doc_identidade = $_POST['doc_identidade'];
                $telefone = $_POST['telefone'];
                $transportadora_id = $_POST['transportadora_id'];
                $stmt = $conn->prepare("INSERT INTO motoristas (nome, doc_identidade, telefone, transportadora_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssi", $nome, $doc_identidade, $telefone, $transportadora_id);
                $stmt->execute();
                echo "Motorista cadastrado com sucesso!";
                break;

            case 'transportadora':
                $nome = $_POST['nome'];
                $telefone = $_POST['telefone'];
                $endereco = $_POST['endereco'];
                $stmt = $conn->prepare("INSERT INTO transportadoras (nome, telefone, endereco) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nome, $telefone, $endereco);
                $stmt->execute();
                echo "Transportadora cadastrada com sucesso!";
                break;

            case 'usuario_sistema':
                $nome = $_POST['nome'];
                $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO usuarios (nome, senha) VALUES (?, ?)");
                $stmt->bind_param("ss", $nome, $senha);
                $stmt->execute();
                echo "Usuario do sistema cadastrado com sucesso!";
                break;

            case 'visitante':
                $nome = $_POST['nome'];
                $doc_identidade = $_POST['doc_identidade'];
                $empresa = $_POST['empresa'];
                $veiculo = $_POST['veiculo'];
                $telefone = $_POST['telefone'];
                $finalidade_id = $_POST['finalidade'];
                $stmt = $conn->prepare("INSERT INTO visitantes (nome, doc_identidade, empresa, veiculo, telefone, finalidade) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $nome, $doc_identidade, $empresa, $veiculo, $telefone, finalidade);
                $stmt->execute();
                echo "Visitante cadastrado com sucesso!";
                break;
        }
    }
}

$conn = getConnection();
processForm($conn);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Sistema de Portaria</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.formulario-cadastro').hide();
            $('#form-funcionario_aeb').show();

            $('.nav-button').click(function() {
                $('.formulario-cadastro').hide();
                var formToShow = $(this).data('form');
                $('#' + formToShow).show();
            });
        });
    </script>

</head>
<body>

<div class="container">
    <?php include('../includes/sidebar.php'); ?>

    <div class="container-cadastro">
        <h2>Cadastro</h2>
        <p style="text-align: center;">Clique em uma das opcoes abaixo para cadastrar:</p>

        <div class="nav-buttons">
            <button class="nav-button" data-form="form-funcionario_aeb">Funcionario AEB</button>
            <button class="nav-button" data-form="form-motorista">Motorista</button>
            <button class="nav-button" data-form="form-transportadora">Transportadora</button>
            <button class="nav-button" data-form="form-usuario_sistema">Usuario do Sistema</button>
            <button class="nav-button" data-form="form-visitante">Visitante</button>
        </div>

        <form id="form-funcionario_aeb" class="formulario-cadastro" method="POST" action="cadastro.php">
            <h3>Cadastro Funcionario AEB</h3>
            <input type="hidden" name="tipoCadastro" value="funcionario_aeb">
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div style="flex: 1;">
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone" required>
                </div>
            </div>
            <button type="submit">Cadastrar Funcionario AEB</button>
        </form>

        <!-- Cadastro Motorista -->
        <form id="form-motorista" class="formulario-cadastro" method="POST" action="cadastro.php" style="display:none;">
            <h3>Cadastro Motorista</h3>
            <input type="hidden" name="tipoCadastro" value="motorista">
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div style="flex: 1;">
                    <label for="doc_identidade">Documento de Identidade:</label>
                    <input type="text" name="doc_identidade" required>
                </div>
            </div>
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone" required>
                </div>
                <div style="flex: 1;">
                    <label for="transportadora_id">Transportadora (ID):</label>
                    <input type="text" name="transportadora_id" required>
                </div>
            </div>
            <button type="submit">Cadastrar Motorista</button>
        </form>

        <!-- Cadastro Transportadora -->
        <form id="form-transportadora" class="formulario-cadastro" method="POST" action="cadastro.php" style="display:none;">
            <h3>Cadastro Transportadora</h3>
            <input type="hidden" name="tipoCadastro" value="transportadora">
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div style="flex: 1;">
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone" required>
                </div>
            </div>
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="endereco">Endereco:</label>
                    <input type="text" name="endereco" required>
                </div>
            </div>
            <button type="submit">Cadastrar Transportadora</button>
        </form>

        <!-- Cadastro Usuario de Sistema -->
        <form id="form-usuario_sistema" class="formulario-cadastro" method="POST" action="cadastro.php" style="display:none;">
            <h3>Cadastro Usuario de Sistema</h3>
            <input type="hidden" name="tipoCadastro" value="usuario_sistema">
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div style="flex: 1;">
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" required>
                </div>
            </div>
            <button type="submit">Cadastrar Usuario de Sistema</button>
        </form>

        <!-- Cadastro Visitante -->
        <form id="form-visitante" class="formulario-cadastro" method="POST" action="cadastro.php" style="display:none;">
            <h3>Cadastro Visitante</h3>
            <input type="hidden" name="tipoCadastro" value="visitante">
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div style="flex: 1;">
                    <label for="doc_identidade">Documento de Identidade:</label>
                    <input type="text" name="doc_identidade" required>
                </div>
            </div>
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="empresa">Empresa:</label>
                    <input type="text" name="empresa" required>
                </div>
                <div style="flex: 1;">
                    <label for="veiculo">Veículo:</label>
                    <input type="text" name="veiculo">
                </div>
            </div>
            <div class="input-group">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone">
                </div>
                <div style="flex: 1;">
                    <label for="finalidade_id">Finalidade (ID):</label>
                    <input type="text" name="finalidade_id" required>
                </div>
            </div>
            <button type="submit">Cadastrar Visitante</button>
        </form>

    </div>
</div>

</body>
</html>
