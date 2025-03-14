<?php
namespace App\Models;

use Exception;
use mysqli;

class UsuarioModel {
    private $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    /**
     * Busca um usuário pelo login.
     */
    public function buscarPorLogin($login) {
        $stmt = $this->db->prepare("SELECT id, nome, senha FROM usuarios WHERE login = ?");
        $stmt->bind_param('s', $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Cria um novo usuário.
     */
    public function criarUsuario($login, $senhaHash) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (login, senha) VALUES (?, ?)");
        $stmt->bind_param('ss', $login, $senhaHash);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao criar usuário: " . $stmt->error);
        }
    }
}