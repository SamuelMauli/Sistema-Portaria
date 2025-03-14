<?php
namespace App\Controllers;

use App\Models\EventoModel;
use App\Models\SalaModel;

class DashboardController {
    private $eventoModel;
    private $salaModel;

    public function __construct() {
        $this->eventoModel = new EventoModel();
        $this->salaModel = new SalaModel();
    }

    public function index() {
        $eventos = $this->eventoModel->listarEventos();
        $salas = $this->salaModel->listarSalas();

        require __DIR__ . '/../Views/dashboard/index.php';
    }
}