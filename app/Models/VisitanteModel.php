<?php
namespace App\Models;

use App\Models\Database;

class VisitanteModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lista todos os visitantes.
     */
    public function listarVisitantes() {
        $query = "SELECT id, nome, doc_identidade, empresa, veiculo, telefone, finalidade_id FROM visitantes";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Cria um novo visitante.
     */
    public function criarVisitante($nome, $doc_identidade, $empresa, $veiculo, $telefone, $finalidade_id) {
        $stmt = $this->db->prepare("INSERT INTO visitantes (nome, doc_identidade, empresa, veiculo, telefone, finalidade_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $nome, $doc_identidade, $empresa, $veiculo, $telefone, $finalidade_id);
        return $stmt->execute();
    }

    /**
     * Exclui um visitante.
     */
    public function excluirVisitante($id) {
        $stmt = $this->db->prepare("DELETE FROM visitantes WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}