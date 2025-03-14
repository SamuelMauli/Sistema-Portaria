<?php
namespace App\Controllers;

use App\Models\SalaModel;

class SalaController {
    private $salaModel;

    public function __construct() {
        $this->salaModel = new SalaModel();
    }

    /**
     * Lista todas as salas.
     */
    public function listar() {
        $salas = $this->salaModel->listarSalas();
        require __DIR__ . '/../Views/salas/listar.php';
    }

    /**
     * Exibe o formulário para criar uma nova sala.
     */
    public function criar() {
        require __DIR__ . '/../Views/salas/criar.php';
    }

    /**
     * Salva uma nova sala.
     */
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'];
            $capacidade = $_POST['capacidade'];
            $ocupada = $_POST['ocupada'] ?? 0;

            if ($this->salaModel->criarSala($nome, $capacidade, $ocupada)) {
                header('Location: /salas');
                exit();
            } else {
                echo "Erro ao criar sala.";
            }
        }
    }

    /**
     * Atualiza o status de ocupação de uma sala.
     */
    public function atualizarStatus($id, $ocupada) {
        if ($this->salaModel->atualizarStatus($id, $ocupada)) {
            header('Location: /salas');
            exit();
        } else {
            echo "Erro ao atualizar status da sala.";
        }
    }

    /**
     * Exclui uma sala.
     */
    public function excluir($id) {
        if ($this->salaModel->excluirSala($id)) {
            header('Location: /salas');
            exit();
        } else {
            echo "Erro ao excluir sala.";
        }
    }
}