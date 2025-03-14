<?php
namespace App\Models;

use App\Models\Database;

class SalaModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lista todas as salas.
     */
    public function listarSalas() {
        $query = "SELECT s.id, s.nome, s.ocupada, r.nome AS responsavel, av.data_visita, av.hora_visita 
                  FROM salas s
                  LEFT JOIN agenda_visitas av ON s.id = av.sala_aeb_id
                  LEFT JOIN responsaveis r ON av.responsavel_aeb_id = r.id";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Cria uma nova sala.
     */
    public function criarSala($nome, $capacidade, $ocupada = 0) {
        $stmt = $this->db->prepare("INSERT INTO salas (nome, capacidade, ocupada) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $nome, $capacidade, $ocupada);
        return $stmt->execute();
    }

    /**
     * Atualiza o status de ocupação de uma sala.
     */
    public function atualizarStatus($id, $ocupada) {
        $stmt = $this->db->prepare("UPDATE salas SET ocupada = ? WHERE id = ?");
        $stmt->bind_param("ii", $ocupada, $id);
        return $stmt->execute();
    }

    /**
     * Exclui uma sala.
     */
    public function excluirSala($id) {
        $stmt = $this->db->prepare("DELETE FROM salas WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}