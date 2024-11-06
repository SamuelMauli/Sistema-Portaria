<?php
// Arquivo de configuração para credenciais
$host = 'localhost';
$user = 'root';
$password = 'root';
$database = 'portaria_db';

// Criação da conexão
$conn = new mysqli($host, $user, $password, $database);

// Checando se a conexão foi bem-sucedida
if ($conn->connect_error) {
    // Exibindo uma mensagem de erro genérica para produção
    error_log("Erro de conexão: " . $conn->connect_error); // Registra no log de erro
    die("Não foi possível conectar ao banco de dados."); // Mensagem genérica ao usuário
} else {
    echo "Conexão bem-sucedida!";
}

// Fechando a conexão ao banco de dados
$conn->close();
?>
