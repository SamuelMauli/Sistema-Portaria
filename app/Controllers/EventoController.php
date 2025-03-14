<?php
namespace App\Controllers;

use App\Models\EventoModel;

class EventoController {
    private $eventoModel;

    public function __construct() {
        $this->eventoModel = new EventoModel();
    }

    /**
     * Lista todos os eventos.
     */
    public function listar() {
        $eventos = $this->eventoModel->listarEventos();
        require __DIR__ . '/../Views/eventos/listar.php';
    }

    /**
     * Exibe o formulário para criar um novo evento.
     */
    public function criar() {
        require __DIR__ . '/../Views/eventos/criar.php';
    }

    /**
     * Salva um novo evento.
     */
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'];
            $data_inicio = $_POST['data_inicio'];
            $data_fim = $_POST['data_fim'];

            if ($this->eventoModel->criarEvento($titulo, $descricao, $data_inicio, $data_fim)) {
                header('Location: /eventos');
                exit();
            } else {
                echo "Erro ao criar evento.";
            }
        }
    }

    /**
     * Exclui um evento.
     */
    public function excluir($id) {
        if ($this->eventoModel->excluirEvento($id)) {
            header('Location: /eventos');
            exit();
        } else {
            echo "Erro ao excluir evento.";
        }
    }
}