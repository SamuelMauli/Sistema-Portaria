<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/db.php');

// Configuração de conexão com o banco de dados
$host = 'localhost'; // Endereço do banco de dados
$dbname = 'portaria_db'; // Nome do banco de dados
$username = 'samuel'; // Nome de usuário do banco de dados
$password = ''; // Senha do banco de dados

try {
    // Criando a conexão PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurando para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Em caso de erro, exibe a mensagem de erro
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}


// Busca todas as transportadoras
$sql = "SELECT id, nome FROM transportadoras";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$transportadoras = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca todas as finalidades
$sql = "SELECT id, descricao FROM finalidades";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$finalidades = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
    <style>
        /* Reset e Estilos Básicos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
        }

        /* Container principal */
        .container {
            display: flex;
            height: 100vh;
            width: 100%;
            justify-content: flex-start;

        }

        .container-cadastro {
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 20px;
            margin-right:250px
        }

        h2 {
            text-align: center;
            color: #007BFF;
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* Mensagens de sucesso e erro */
        .success-message, .error-message {
            background-color: #f1f1f1;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 5px solid;
            border-radius: 5px;
            font-size: 16px;
        }

        .success-message {
            border-color: #28a745;
            color: #28a745;
        }

        .error-message {
            border-color: #dc3545;
            color: #dc3545;
        }

        /* Botões de navegação */
        .nav-buttons {
            display: flex;
            justify-content: space-evenly;
            margin-bottom: 30px;
        }

        .nav-button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 12px 24px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            min-width: 150px;
            text-align: center;
        }

        .nav-button:hover {
            background-color: #0056b3;
        }

        /* Formulários */
        .form-section {
            display: none;
            margin-bottom: 30px;
        }

        .form-section.active {
            display: block;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"], select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus, select:focus {
            border-color: #007BFF;
        }

        button[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container-cadastro {
                padding: 20px;
            }

            .nav-buttons {
                flex-direction: column;
                align-items: center;
            }

            .nav-button {
                margin-bottom: 10px;
                width: 100%;
            }

            input[type="text"], input[type="password"], select, button[type="submit"] {
                width: 100%;
            }
        }


    </style>
</head>
<body>
    <?php include('../includes/sidebar.php'); ?>

    <div class="container">
        <div class="container-cadastro">
            <h2>Cadastro</h2>
            <p>Clique em uma das opções abaixo para cadastrar:</p>

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

            <!-- Formulário do Funcionário -->
            <div id="form-funcionario" class="form-section" style="display:none;">
                <h4>Cadastro de Funcionário</h4>
                <form method="POST" action="cadastro.php">
                    <input type="hidden" name="tipoCadastro" value="funcionario">
                    <div>
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" autocomplete="off" required>
                    </div>
                    <div>
                        <label for="telefone">Telefone:</label>
                        <input type="text" id="telefone" name="telefone" required>
                    </div>
                    <button type="submit">Cadastrar</button>
                </form>
            </div>

           <!-- Formulário do Motorista -->
            <div id="form-motorista" class="form-section" style="display:none;">
                <h4>Cadastro de Motorista</h4>
                <form method="POST" action="cadastro.php">
                    <input type="hidden" name="tipoCadastro" value="motorista">
                    <div>
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" autocomplete="off" required>
                    </div>
                    <div>
                        <label for="doc_identidade">Documento de Identidade:</label>
                        <input type="text" id="doc_identidade" name="doc_identidade" required>
                    </div>
                    <div>
                        <label for="telefone">Telefone:</label>
                        <input type="text" id="telefone" name="telefone" required>
                    </div>
                    <div>
                        <label for="transportadora_id">Transportadora:</label>
                        <select id="transportadora_id" name="transportadora_id" required>
                            <option value="">Selecione uma Transportadora</option>
                            <?php foreach ($transportadoras as $transportadora): ?>
                                <option value="<?= $transportadora['id'] ?>"><?= $transportadora['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit">Cadastrar</button>
                </form>
            </div>


            <!-- Formulário da Transportadora -->
            <div id="form-transportadora" class="form-section" style="display:none;">
                <h4>Cadastro de Transportadora</h4>
                <form method="POST" action="cadastro.php">
                    <input type="hidden" name="tipoCadastro" value="transportadora">
                    <div>
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" autocomplete="off" required>
                    </div>
                    <div>
                        <label for="telefone">Telefone:</label>
                        <input type="text" id="telefone" name="telefone" required>
                    </div>
                    <div>
                        <label for="endereco">Endereço:</label>
                        <input type="text" id="endereco" name="endereco" required>
                    </div>
                    <button type="submit">Cadastrar</button>
                </form>
            </div>

            <!-- Formulário do Usuário do Sistema -->
            <div id="form-usuario_sistema" class="form-section" style="display:none;">
                <h4>Cadastro de Usuário</h4>
                <form method="POST" action="cadastro.php">
                    <input type="hidden" name="tipoCadastro" value="usuario_sistema">
                    <div>
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" autocomplete="off" required>
                    </div>
                    <div>
                        <label for="login">Login:</label>
                        <input type="text" id="login" name="login" required>
                    </div>
                    <div>
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                    <div>
                        <label for="nivel_acesso">Nível de Acesso:</label>
                        <select id="nivel_acesso" name="nivel_acesso" required>
                            <option value="admin">Admin</option>
                            <option value="porteiro">Porteiro</option>
                        </select>
                    </div>
                    <button type="submit">Cadastrar</button>
                </form>
            </div>

            <!-- Formulário do Visitante -->
            <div id="form-visitante" class="form-section" style="display:none;">
                <h4>Cadastro de Visitante</h4>
                <form method="POST" action="cadastro.php">
                    <input type="hidden" name="tipoCadastro" value="visitante">
                    <div>
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" autocomplete="off" required>
                    </div>
                    <div>
                        <label for="doc_identidade">Documento de Identidade:</label>
                        <input type="text" id="doc_identidade" name="doc_identidade" required>
                    </div>
                    <div>
                        <label for="empresa">Empresa:</label>
                        <input type="text" id="empresa" name="empresa">
                    </div>
                    <div>
                        <label for="veiculo">Veículo:</label>
                        <input type="text" id="veiculo" name="veiculo">
                    </div>
                    <div>
                        <label for="telefone">Telefone:</label>
                        <input type="text" id="telefone" name="telefone" required>
                    </div>
                    <div>
                        <label for="finalidade_id">Finalidade:</label>
                        <select id="finalidade_id" name="finalidade_id" required>
                            <option value="">Selecione uma Finalidade</option>
                            <?php foreach ($finalidades as $finalidade): ?>
                                <option value="<?= $finalidade['id'] ?>"><?= $finalidade['descricao'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit">Cadastrar</button>
                </form>
            </div>


            <script>
                document.querySelectorAll('.nav-button').forEach(button => {
                    button.addEventListener('click', () => {
                        // Ocultar todos os formulários
                        document.querySelectorAll('.form-section').forEach(form => {
                            form.style.display = 'none';
                        });

                        // Mostrar o formulário correspondente ao botão clicado
                        const formId = button.getAttribute('data-form');
                        document.getElementById(formId).style.display = 'block';
                    });
                });
            </script>

        </div>
    </div>
</body>
</html>