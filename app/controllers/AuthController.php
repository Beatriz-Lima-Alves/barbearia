<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';

/**
 * Controller responsável pela autenticação de usuários
 */
class AuthController {
    
    /**
     * Exibe a página de login
     */
    public function login() {
        // Se já estiver logado, redireciona para dashboard
        if ($this->isLoggedIn()) {
            header('Location: ' . SITE_URL . '/dashboard');
            exit;
        }
        
        include __DIR__ . '/../views/auth/login.php';
    }
    
    /**
     * Processa o login do usuário
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        
        if (empty($email) || empty($senha)) {
            $_SESSION['error'] = 'Email e senha são obrigatórios';
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
        
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->getByEmail($email);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            if (!$usuario['ativo']) {
                $_SESSION['error'] = 'Usuário inativo. Contate o administrador.';
                header('Location: ' . SITE_URL . '/login');
                exit;
            }
            
            // Login bem-sucedido
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nome'] = $usuario['nome'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['user_tipo'] = $usuario['tipo'];
            $_SESSION['login_time'] = time();
            
            // Regenerar ID da sessão para segurança
            session_regenerate_id(true);
            
            $_SESSION['success'] = 'Login realizado com sucesso!';
            header('Location: ' . SITE_URL . '/dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Email ou senha incorretos';
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
    }
    
    /**
     * Realiza logout do usuário
     */
    public function logout() {
        session_destroy();
        header('Location: ' . SITE_URL . '/login');
        exit;
    }
    
    /**
     * Exibe página de cadastro (apenas para administradores)
     */
    public function register() {
        $this->requireAdmin();
        include __DIR__ . '/../views/auth/register.php';
    }
    
    /**
     * Processa cadastro de novo usuário
     */
    public function processRegister() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/register');
            exit;
        }
        
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'senha' => $_POST['senha'] ?? '',
            'confirmar_senha' => $_POST['confirmar_senha'] ?? '',
            'tipo' => $_POST['tipo'] ?? 'barbeiro',
            'especialidades' => trim($_POST['especialidades'] ?? ''),
            'horario_inicio' => $_POST['horario_inicio'] ?? '08:00',
            'horario_fim' => $_POST['horario_fim'] ?? '18:00',
            'dias_trabalho' => $_POST['dias_trabalho'] ?? []
        ];
        
        // Validações
        $errors = [];
        
        if (empty($dados['nome'])) {
            $errors[] = 'Nome é obrigatório';
        }
        
        if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email válido é obrigatório';
        }
        
        if (strlen($dados['senha']) < 6) {
            $errors[] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        if ($dados['senha'] !== $dados['confirmar_senha']) {
            $errors[] = 'Senhas não conferem';
        }
        
        if (!in_array($dados['tipo'], ['barbeiro', 'administrador'])) {
            $errors[] = 'Tipo de usuário inválido';
        }
        
        // Verificar se email já existe
        $usuarioModel = new Usuario();
        if ($usuarioModel->getByEmail($dados['email'])) {
            $errors[] = 'Email já cadastrado no sistema';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/register');
            exit;
        }
        
        // Criar usuário
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $dados['dias_trabalho'] = implode(',', $dados['dias_trabalho']);
        
        $userId = $usuarioModel->create($dados);
        
        if ($userId) {
            $_SESSION['success'] = 'Usuário cadastrado com sucesso!';
            header('Location: ' . SITE_URL . '/usuarios');
        } else {
            $_SESSION['error'] = 'Erro ao cadastrar usuário';
            header('Location: ' . SITE_URL . '/register');
        }
        exit;
    }
    
    /**
     * Verifica se usuário está logado
     */
    public function isLoggedIn() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        // Verificar timeout da sessão
        if (isset($_SESSION['login_time']) && 
            (time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
            session_destroy();
            return false;
        }
        
        return true;
    }
    
    /**
     * Requer que usuário esteja logado
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = 'Você precisa estar logado para acessar esta página';
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
    }
    
    /**
     * Requer que usuário seja administrador
     */
    public function requireAdmin() {
        $this->requireLogin();
        
        if ($_SESSION['user_tipo'] !== 'administrador') {
            $_SESSION['error'] = 'Acesso negado. Apenas administradores podem acessar esta página.';
            header('Location: ' . SITE_URL . '/dashboard');
            exit;
        }
    }
    
    /**
     * Verifica se usuário é administrador
     */
    public function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['user_tipo'] === 'administrador';
    }
    
    /**
     * Obtém dados do usuário logado
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'nome' => $_SESSION['user_nome'],
            'email' => $_SESSION['user_email'],
            'tipo' => $_SESSION['user_tipo']
        ];
    }
}
?>
