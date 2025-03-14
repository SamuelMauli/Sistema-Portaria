<?php
namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\Database;
use Exception;

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->usuarioModel = new UsuarioModel($db);
    }

    /**
     * Processa o login do usuário.
     */
    public function login() {
        session_start();
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('Método de requisição inválido.');
        }
    
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    
        if (empty($username) || empty($password)) {
            $this->redirectWithError('Usuário e senha são obrigatórios.');
        }
    
        try {
            $usuario = $this->usuarioModel->buscarPorLogin($username);
    
            if (!$usuario) {
                $this->redirectWithError('Usuário não encontrado.');
            }
    
            if (password_verify($password, $usuario['senha'])) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['username'] = $usuario['nome'];
                header('Location: /dashboard');
                exit();
            } else {
                $this->redirectWithError('Senha incorreta.');
            }
        } catch (Exception $e) {
            $this->redirectWithError('Erro ao processar o login: ' . $e->getMessage());
        }
    }

    /**
     * Processa o registro de um novo usuário.
     */
    public function register() {
        session_start();
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('Método de requisição inválido.');
        }
    
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    
        if (empty($username) || empty($password)) {
            $this->redirectWithError('Usuário e senha são obrigatórios.');
        }
    
        try {
            $senhaHash = password_hash($password, PASSWORD_DEFAULT);
            $this->usuarioModel->criarUsuario($username, $senhaHash);
    
            $_SESSION['success'] = 'Usuário registrado com sucesso. Faça login.';
            header('Location: /login');
            exit();
        } catch (Exception $e) {
            $this->redirectWithError('Erro ao registrar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Realiza o logout do usuário.
     */
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /login');
        exit();
    }

    /**
     * Redireciona com uma mensagem de erro.
     */
    private function redirectWithError($mensagem) {
        $_SESSION['login_error'] = $mensagem;
        header('Location: /login');
        exit();
    }
}