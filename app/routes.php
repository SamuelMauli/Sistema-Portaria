<?php
require __DIR__ . '/../config/env.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\SalaController;
use App\Controllers\EventoController;
use App\Controllers\VisitanteController;

$authController = new AuthController();
$dashboardController = new DashboardController();
$salaController = new SalaController();
$eventoController = new EventoController();
$visitanteController = new VisitanteController();

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        require __DIR__ . '/Views/auth/login.php';
        break;
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            require __DIR__ . '/Views/auth/login.php';
        }
        break;
    case '/dashboard':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dashboardController->index();
        } else {
            require __DIR__ . '/Views/dashboard/index.php';
        }
        break;
    case '/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            require __DIR__ . '/../Views/auth/register.php';
        }
        break;
    case '/logout':
        $authController->logout();
        break;
    case '/salas':
        $salaController->listar();
        break;
    case '/salas/criar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $salaController->salvar();
        } else {
            $salaController->criar();
        }
        break;
    case '/eventos':
        $eventoController->listar();
        break;
    case '/eventos/criar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventoController->salvar();
        } else {
            $eventoController->criar();
        }
        break;
    case '/visitantes':
        $visitanteController->listar();
        break;
    case '/visitantes/criar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $visitanteController->salvar();
        } else {
            $visitanteController->criar();
        }
        break;
    default:
        http_response_code(404);
        echo "Página não encontrada.";
        break;
}