<?php

require_once('../includes/db.php');

// Conexão com o banco de dados
$servername = "localhost"; // Ajuste conforme o seu banco
$username = "samuel";        // Ajuste conforme o seu banco
$password = "";            // Ajuste conforme o seu banco
$dbname = "portaria_db";  // Ajuste conforme o seu banco

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se a busca foi feita para um campo específico
if (isset($_GET['tipo']) && isset($_GET['query'])) {
    $tipo = $_GET['tipo'];
    $query = $_GET['query'];

    switch ($tipo) {
        case 'funcionario':
            $sql = "SELECT id, nome FROM funcionarios WHERE nome LIKE '%$query%'";
            break;
        case 'motorista':
            $sql = "SELECT id, nome FROM motoristas WHERE nome LIKE '%$query%'";
            break;
        case 'transportadora':
            $sql = "SELECT id, nome FROM transportadoras WHERE nome LIKE '%$query%'";
            break;
        case 'visitante':
            $sql = "SELECT id, nome FROM visitantes WHERE nome LIKE '%$query%'";
            break;
        case 'finalidade':
            $sql = "SELECT id, descricao FROM finalidades WHERE descricao LIKE '%$query%'";
            break;
        default:
            $sql = "";
            break;
    }

    // Executar consulta
    $result = $conn->query($sql);

    // Retornar os resultados no formato JSON
    $options = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $options[] = $row;
        }
    }

    echo json_encode($options);
}

$conn->close();
?>
