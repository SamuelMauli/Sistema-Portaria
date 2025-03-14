<?php
namespace App\Models;

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $config = require __DIR__ . '/../../config/database.php';
        $this->conn = new \mysqli($config['host'], $config['username'], $config['password'], $config['database']);

        if ($this->conn->connect_error) {
            die("Erro ao conectar ao banco de dados: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}