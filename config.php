<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'portaria_user');
define('DB_PASS', 'root');
define('DB_NAME', 'portaria_db');

function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
    return $conn;
}
?>
