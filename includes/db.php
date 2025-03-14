<?php

require_once('env.php');

function criarBancoDeDados() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);

        if ($conn->connect_error) {
            throw new Exception("Erro ao conectar ao servidor MySQL: " . $conn->connect_error);
        }

        $conn->close();
    } catch (Exception $e) {
        error_log($e->getMessage()); 
        die("Erro ao configurar o banco de dados. Contate o administrador.");
    }
}

function conectarBancoDeDados() {
    try {
        criarBancoDeDados();

        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($conn->connect_error) {
            throw new Exception("Erro ao conectar ao banco de dados: " . $conn->connect_error);
        }

        $conn->set_charset("utf8");

        return $conn;
    } catch (Exception $e) {
        error_log($e->getMessage()); 
        die("Erro ao conectar ao banco de dados. Contate o administrador.");
    }
}

function fecharConexao($conn) {
    if ($conn) {
        $conn->close();
    }
}

$conn = conectarBancoDeDados();

?>
