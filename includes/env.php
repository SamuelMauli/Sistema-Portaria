<?php
// Arquivo: includes/env.php

// Verifica se o arquivo .env existe
if (!file_exists(__DIR__ . '/../.env')) {
    die("Arquivo .env não encontrado. Crie um arquivo .env baseado no .env.example.");
}

// Carrega as variáveis de ambiente do arquivo .env
$env = parse_ini_file(__DIR__ . '/../.env');

// Define as variáveis de ambiente
define('DB_HOST', $env['DB_HOST']);
define('DB_USER', $env['DB_USER']);
define('DB_PASSWORD', $env['DB_PASSWORD']);
define('DB_NAME', $env['DB_NAME']);
?>