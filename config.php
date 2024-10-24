<?php
// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_USER', 'portaria_user');
define('DB_PASS', 'rootB4ll3sol');
define('DB_NAME', 'portaria');

// Conexão com o banco de dados
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
    return $conn;
}
?>
