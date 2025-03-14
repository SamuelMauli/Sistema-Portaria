<?php
session_start();
require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipoCadastro = $_POST['tipoCadastro'];

    // Validação básica
    if (empty($_POST['nome']) || empty($_POST['telefone'])) {
        $_SESSION['error'] = "Nome e telefone são obrigatórios!";
        header('Location: ../pages/cadastro.php');
        exit();
    }

    try {
        $pdo->beginTransaction();

        if ($tipoCadastro === 'funcionario') {
            $nome = trim($_POST['nome']);
            $telefone = trim($_POST['telefone']);

            $sql = "INSERT INTO funcionarios (nome, telefone) VALUES (:nome, :telefone)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->execute();
            $_SESSION['success'] = "Funcionário cadastrado com sucesso!";
        } elseif ($tipoCadastro === 'motorista') {
            $nome = trim($_POST['nome']);
            $doc_identidade = trim($_POST['doc_identidade']);
            $telefone = trim($_POST['telefone']);
            $transportadora_id = intval($_POST['transportadora_id']);

            $sql = "INSERT INTO motoristas (nome, doc_identidade, telefone, transportadora_id) VALUES (:nome, :doc_identidade, :telefone, :transportadora_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':doc_identidade', $doc_identidade);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':transportadora_id', $transportadora_id);
            $stmt->execute();
            $_SESSION['success'] = "Motorista cadastrado com sucesso!";
        } elseif ($tipoCadastro === 'transportadora') {
            $nome = trim($_POST['nome']);
            $telefone = trim($_POST['telefone']);
            $endereco = trim($_POST['endereco']);

            $sql = "INSERT INTO transportadoras (nome, telefone, endereco) VALUES (:nome, :telefone, :endereco)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':endereco', $endereco);
            $stmt->execute();
            $_SESSION['success'] = "Transportadora cadastrada com sucesso!";
        } elseif ($tipoCadastro === 'usuario_sistema') {
            $nome = trim($_POST['nome']);
            $login = trim($_POST['login']);
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $nivel_acesso = $_POST['nivel_acesso']; // Supondo que o nível de acesso seja enviado via POST
        
            // Conexão com o banco de dados (exemplo usando PDO)
            $sql = "INSERT INTO usuarios (nome, login, senha, nivel_acesso) VALUES (:nome, :login, :senha, :nivel_acesso)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':nivel_acesso', $nivel_acesso);
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
                    VALUES (:nome, :doc_identidade, :empresa, :veiculo, :telefone, :finalidade_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':doc_identidade', $doc_identidade);
            $stmt->bindParam(':empresa', $empresa);
            $stmt->bindParam(':veiculo', $veiculo);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':finalidade_id', $finalidade_id);
            $stmt->execute();
            $_SESSION['success'] = "Visitante cadastrado com sucesso!";
        } else {
            throw new Exception("Tipo de cadastro inválido.");
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = $e->getMessage();
    } finally {
        header('Location: ../pages/cadastro.php');
        exit();
    }
}
?>