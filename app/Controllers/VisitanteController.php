<?php
namespace App\Controllers;

use App\Models\VisitanteModel;

class VisitanteController {
    private $visitanteModel;

    public function __construct() {
        $this->visitanteModel = new VisitanteModel();
    }

    /**
     * Lista todos os visitantes.
     */
    public function listar() {
        $visitantes = $this->visitanteModel->listarVisitantes();
        require __DIR__ . '/../Views/visitantes/listar.php';
    }

    /**
     * Exibe o formulário para criar um novo visitante.
     */
    public function criar() {
        require __DIR__ . '/../Views/visitantes/criar.php';
    }

    /**
     * Salva um novo visitante.
     */
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'];
            $doc_identidade = $_POST['doc_identidade'];
            $empresa = $_POST['empresa'];
            $veiculo = $_POST['veiculo'];
            $telefone = $_POST['telefone'];
            $finalidade_id = $_POST['finalidade_id'];

            if ($this->visitanteModel->criarVisitante($nome, $doc_identidade, $empresa, $veiculo, $telefone, $finalidade_id)) {
                header('Location: /visitantes');
                exit();
            } else {
                echo "Erro ao criar visitante.";
            }
        }
    }

    /**
     * Exclui um visitante.
     */
    public function excluir($id) {
        if ($this->visitanteModel->excluirVisitante($id)) {
            header('Location: /visitantes');
            exit();
        } else {
            echo "Erro ao excluir visitante.";
        }
    }
}