<?php
// Incluindo o arquivo de conexão com o banco de dados
include('db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obter o tipo de cadastro
    $tipoCadastro = $_POST['tipoCadastro'];

    // Validação dos dados
    if (empty($_POST['nome']) || empty($_POST['telefone'])) {
        echo "Nome e telefone são obrigatórios!";
        exit;
    }

    // Processar dados para cada tipo de cadastro
    if ($tipoCadastro === 'funcionario') {
        // Coletar dados do formulário
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];

        // Preparar a query para inserir no banco de dados
        $sql = "INSERT INTO funcionarios (nome, telefone) VALUES (:nome, :telefone)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);

        // Executar a query e verificar se foi inserido com sucesso
        if ($stmt->execute()) {
            echo "Funcionário cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar funcionário.";
        }
    } elseif ($tipoCadastro === 'motorista') {
        // Coletar dados do formulário
        $nome = $_POST['nome'];
        $doc_identidade = $_POST['doc_identidade'];
        $telefone = $_POST['telefone'];
        $transportadora_id = $_POST['transportadora_id'];

        // Preparar a query para inserir no banco de dados
        $sql = "INSERT INTO motoristas (nome, doc_identidade, telefone, transportadora_id) VALUES (:nome, :doc_identidade, :telefone, :transportadora_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':doc_identidade', $doc_identidade);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':transportadora_id', $transportadora_id);

        // Executar a query e verificar se foi inserido com sucesso
        if ($stmt->execute()) {
            echo "Motorista cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar motorista.";
        }
    } elseif ($tipoCadastro === 'transportadora') {
        // Coletar dados do formulário
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];

        // Preparar a query para inserir no banco de dados
        $sql = "INSERT INTO transportadoras (nome, telefone, endereco) VALUES (:nome, :telefone, :endereco)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);

        // Executar a query e verificar se foi inserido com sucesso
        if ($stmt->execute()) {
            echo "Transportadora cadastrada com sucesso!";
        } else {
            echo "Erro ao cadastrar transportadora.";
        }
    } elseif ($tipoCadastro === 'usuario_sistema') {
        // Coletar dados do formulário
        $nome = $_POST['nome'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografar senha

        // Preparar a query para inserir no banco de dados
        $sql = "INSERT INTO usuarios_sistema (nome, senha) VALUES (:nome, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':senha', $senha);

        // Executar a query e verificar se foi inserido com sucesso
        if ($stmt->execute()) {
            echo "Usuário do sistema cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar usuário do sistema.";
        }
    } elseif ($tipoCadastro === 'visitante') {
        // Coletar dados do formulário
        $nome = $_POST['nome'];
        $doc_identidade = $_POST['doc_identidade'];
        $empresa = $_POST['empresa'];
        $veiculo = $_POST['veiculo'];
        $telefone = $_POST['telefone'];
        $finalidade_id = $_POST['finalidade_id'];

        // Preparar a query para inserir no banco de dados
        $sql = "INSERT INTO visitantes (nome, doc_identidade, empresa, veiculo, telefone, finalidade_id) 
                VALUES (:nome, :doc_identidade, :empresa, :veiculo, :telefone, :finalidade_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':doc_identidade', $doc_identidade);
        $stmt->bindParam(':empresa', $empresa);
        $stmt->bindParam(':veiculo', $veiculo);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':finalidade_id', $finalidade_id);

        // Executar a query e verificar se foi inserido com sucesso
        if ($stmt->execute()) {
            echo "Visitante cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar visitante.";
        }
    } else {
        echo "Tipo de cadastro inválido.";
    }
}
?>
