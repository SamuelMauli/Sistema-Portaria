<?php
function getConnection() {
    $host = 'localhost';
    $user = 'root';
    $password = 'rootB4ll3s0l';
    $database = 'portaria';

    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    return $conn;
}


?>
