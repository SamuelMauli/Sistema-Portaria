<?php
// Arquivo: includes/db.php

// Carrega as variáveis de ambiente
require_once('env.php');

// Função para criar o banco de dados se não existir
function criarBancoDeDados() {
    try {
        // Conecta ao servidor MySQL sem selecionar um banco de dados
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);

        // Verifica se a conexão foi bem-sucedida
        if ($conn->connect_error) {
            throw new Exception("Erro ao conectar ao servidor MySQL: " . $conn->connect_error);
        }

        // Fecha a conexão temporária
        $conn->close();
    } catch (Exception $e) {
        error_log($e->getMessage()); // Log do erro
        die("Erro ao configurar o banco de dados. Contate o administrador.");
    }
}

// Função para conectar ao banco de dados
function conectarBancoDeDados() {
    try {
        // Cria o banco de dados se ele não existir
        criarBancoDeDados();

        // Conecta ao banco de dados
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        // Verifica se a conexão foi bem-sucedida
        if ($conn->connect_error) {
            throw new Exception("Erro ao conectar ao banco de dados: " . $conn->connect_error);
        }

        // Define o charset para UTF-8
        $conn->set_charset("utf8");

        return $conn;
    } catch (Exception $e) {
        error_log($e->getMessage()); // Log do erro
        die("Erro ao conectar ao banco de dados. Contate o administrador.");
    }
}

// Função para fechar a conexão
function fecharConexao($conn) {
    if ($conn) {
        $conn->close();
    }
}

// Conecta ao banco de dados
$conn = conectarBancoDeDados();

// Exemplo de uso:
// $conn = conectarBancoDeDados();
// ... (operações com o banco de dados)
// fecharConexao($conn);
?>
