<?php
/**
 * PERFIL CONTROLLER
 * Salvar como: C:\xampp\htdocs\barbearia-new\app\controllers\PerfilController.php
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Agendamento.php';
require_once __DIR__ . '/AuthController.php';

class PerfilController {
    
   
    
     public function __construct() {
        $this->authController = new AuthController();
    }
    
    /**
     * Verificar se usuário está logado
     */
    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Você precisa estar logado para acessar esta página';
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
    }
    
    /**
     * Exibir perfil do usuário logado
     */
    public function index() {
        $this->requireLogin();
        $usuarioModel = new Usuario();
        $agendamentoModel = new Agendamento();
        try {
            // Buscar dados do usuário
            $usuario = $usuarioModel->getById($_SESSION['user_id']);
            
            if (!$usuario) {
                $_SESSION['error'] = 'Usuário não encontrado';
                header('Location: ' . SITE_URL . '/dashboard');
                exit;
            }
            
            // Buscar estatísticas do usuário
            $estatisticas = $usuarioModel->getEstatisticas($_SESSION['user_id']);
            
            // Buscar próximo agendamento se for barbeiro
            $proximoAgendamento = null;
            if ($usuario['tipo'] === 'barbeiro') {
                $proximoAgendamento = $agendamentoModel->getProximoAgendamentoBarbeiro($_SESSION['user_id']);
            }
            
            include __DIR__ . '/../views/perfil/index.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao carregar perfil: ' . $e->getMessage();
            header('Location: ' . SITE_URL . '/dashboard');
            exit;
        }
    }
    
    /**
     * Exibir formulário de edição do perfil
     */
    public function edit() {
        $this->requireLogin();
        $usuarioModel = new Usuario();
        $agendamentoModel = new Agendamento();
        
        try {
            // Buscar dados do usuário
            $usuario = $usuarioModel->getById($_SESSION['user_id']);
            
            if (!$usuario) {
                $_SESSION['error'] = 'Usuário não encontrado';
                header('Location: ' . SITE_URL . '/dashboard');
                exit;
            }
            
            include __DIR__ . '/../views/perfil/edit.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao carregar perfil: ' . $e->getMessage();
            header('Location: ' . SITE_URL . '/perfil');
            exit;
        }
    }
    
    /**
     * Atualizar perfil do usuário
     */
    public function update() {
        $this->requireLogin();
        $usuarioModel = new Usuario();
        $agendamentoModel = new Agendamento();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/perfil/edit');
            exit;
        }
        
        try {
            // Validar dados
            $dadosAtualizacao = [
                'nome' => trim($_POST['nome'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'senha_atual' =>  trim($_POST['senha_atual'] ?? ''),
                'nova_senha' =>  trim($_POST['nova_senha'] ?? ''),
                'confirmar_senha' =>  trim($_POST['confirmar_senha'] ?? ''),

            ];


            if (!empty($dadosAtualizacao['nova_senha']) && !empty($dadosAtualizacao['senha_atual'])) {
                $dadosAtualizacao['senha'] = password_hash($dadosAtualizacao['confirmar_senha'], PASSWORD_DEFAULT);
            }
            

            // Validar dados usando o model
            $validacao = $usuarioModel->validarDadosAtualizacao($dadosAtualizacao, $_SESSION['user_id']);
            
            if (!$validacao['valido']) {
                $_SESSION['errors'] = $validacao['erros'];
                $_SESSION['form_data'] = $_POST;
                header('Location: ' . SITE_URL . '/perfil/edit');
                exit;
            }
            
            // Atualizar dados usando o model
            $sucesso = $usuarioModel->update($_SESSION['user_id'], $dadosAtualizacao);
            
            if ($sucesso) {
                // Atualizar sessão
                $_SESSION['user_nome'] = $dadosAtualizacao['nome'];
                $_SESSION['user_email'] = $dadosAtualizacao['email'];
                
                // Limpar dados do formulário
                unset($_SESSION['form_data']);
                unset($_SESSION['errors']);
                
                $_SESSION['success'] = 'Perfil atualizado com sucesso!';
                header('Location: ' . SITE_URL . '/perfil');
            } else {
                $_SESSION['error'] = 'Erro ao atualizar perfil';
                header('Location: ' . SITE_URL . '/perfil');
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao atualizar perfil: ' . $e->getMessage();
            header('Location: ' . SITE_URL . '/perfil');
        }
        
        exit;
    }
}
?>
