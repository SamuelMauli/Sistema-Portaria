<?php
namespace App\Models;

use App\Models\Database;

class EventoModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lista todos os eventos.
     */
    public function listarEventos() {
        $query = "SELECT id, titulo, descricao, data_inicio AS start, data_fim AS end FROM eventos_calendario";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Cria um novo evento.
     */
    public function criarEvento($titulo, $descricao, $data_inicio, $data_fim) {
        $stmt = $this->db->prepare("INSERT INTO eventos_calendario (titulo, descricao, data_inicio, data_fim) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $titulo, $descricao, $data_inicio, $data_fim);
        return $stmt->execute();
    }

    /**
     * Exclui um evento.
     */
    public function excluirEvento($id) {
        $stmt = $this->db->prepare("DELETE FROM eventos_calendario WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}