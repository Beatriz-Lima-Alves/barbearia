<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/AuthController.php';

/**
 * Controller para gerenciamento de usuários (barbeiros e administradores)
 */
class UsuarioController {
    private $authController;
    
    public function __construct() {
        $this->authController = new AuthController();
    }
    
    /**
     * Lista todos os usuários
     */
    public function index() {
        $this->authController->requireAdmin();
        
        $usuarioModel = new Usuario();
        $usuarios = $usuarioModel->getAll();
        
        include __DIR__ . '/../views/usuarios/index.php';
    }
    
    /**
     * Exibe formulário de novo usuário
     */
    public function create() {
        $this->authController->requireAdmin();
        include __DIR__ . '/../views/usuarios/create.php';
    }
    
    /**
     * Salva novo usuário
     */
    public function store() {
        $this->authController->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/usuarios/create');
            exit;
        }
        
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'senha' => $_POST['senha'] ?? '',
            'confirmar_senha' => $_POST['confirmar_senha'] ?? '',
            'tipo' => $_POST['tipo'] ?? 'barbeiro',
            'especialidades' => trim($_POST['especialidades'] ?? ''),
            'horario_inicio' => $_POST['horario_inicio'] ?? '08:00:00',
            'horario_fim' => $_POST['horario_fim'] ?? '18:00:00',
            'dias_trabalho' => $_POST['dias_trabalho'] ?? []
        ];
        
        // Validações
        $errors = $this->validateUsuario($dados);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/usuarios/create');
            exit;
        }
        
        // Preparar dados para salvar
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $dados['dias_trabalho'] = is_array($dados['dias_trabalho']) ? 
            implode(',', $dados['dias_trabalho']) : $dados['dias_trabalho'];
        
        $usuarioModel = new Usuario();
        $usuarioId = $usuarioModel->create($dados);
        
        if ($usuarioId) {
            $_SESSION['success'] = 'Usuário cadastrado com sucesso!';
            header('Location: ' . SITE_URL . '/usuarios');
        } else {
            $_SESSION['error'] = 'Erro ao cadastrar usuário';
            header('Location: ' . SITE_URL . '/usuarios/create');
        }
        exit;
    }
    
    /**
     * Exibe detalhes do usuário
     */
    public function show($id) {
        $this->authController->requireLogin();
        
        try {
            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->getById($id);
            
            if (!$usuario) {
                $_SESSION['error'] = 'Usuário não encontrado';
                header('Location: ' . SITE_URL . '/usuarios');
                exit;
            }
            
            // Buscar próximo agendamento apenas para barbeiros usando o model
            $proximoAgendamento = null;
            if ($usuario['tipo'] === 'barbeiro') {
                $agendamentoModel = new Agendamento();
                $proximoAgendamento = $agendamentoModel->getProximoAgendamentoBarbeiro($id);
            }
            
            // Buscar estatísticas básicas do barbeiro (opcional)
            $estatisticas = null;
            if ($usuario['tipo'] === 'barbeiro') {
                $agendamentoModel = new Agendamento();
                $estatisticas = $agendamentoModel->getEstatisticasBarbeiro($id);
            }
            
            // Disponibilizar dados para a view
            $data = [
                'usuario' => $usuario,
                'proximoAgendamento' => $proximoAgendamento,
                'estatisticas' => $estatisticas
            ];
            
            // Extrair variáveis para a view
            extract($data);
            
            include __DIR__ . '/../views/usuarios/show.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao carregar usuário: ' . $e->getMessage();
            header('Location: ' . SITE_URL . '/usuarios');
            exit;
        }
    }
    
    /**
     * Exibe formulário de edição
     */
    public function edit($id) {
        $this->authController->requireLogin();
        
        // Usuários normais só podem editar próprio perfil, admins podem editar todos
        if (!$this->authController->isAdmin() && $id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Você não tem permissão para editar este usuário';
            header('Location: ' . SITE_URL . '/dashboard');
            exit;
        }
        
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->getById($id);
        
        if (!$usuario) {

            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . SITE_URL . '/usuarios');
            exit;
        }
        
        include __DIR__ . '/../views/usuarios/edit.php';
    }
    
    /**
     * Atualiza dados do usuário
     */
    public function update($id) {
        $this->authController->requireLogin();
        
        // Usuários normais só podem editar próprio perfil, admins podem editar todos
        if (!$this->authController->isAdmin() && $id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Você não tem permissão para editar este usuário';
            header('Location: ' . SITE_URL . '/dashboard');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/usuarios/edit/' . $id);
            exit;
        }
        
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->getById($id);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . SITE_URL . '/usuarios');
            exit;
        }
        
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'especialidades' => trim($_POST['especialidades'] ?? ''),
            'horario_inicio' => $_POST['horario_inicio'] ?? '08:00:00',
            'horario_fim' => $_POST['horario_fim'] ?? '18:00:00',
            'dias_trabalho' => $_POST['dias_trabalho'] ?? []
        ];
        
        // Apenas admins podem alterar tipo
        if ($this->authController->isAdmin()) {
            $dados['tipo'] = $_POST['tipo'] ?? $usuario['tipo'];
        }
        
        // Validações
        $errors = $this->validateUsuarioUpdate($dados, $id);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/usuarios/edit/' . $id);
            exit;
        }
        
        // Preparar dados para salvar
        $dados['dias_trabalho'] = is_array($dados['dias_trabalho']) ? 
            implode(',', $dados['dias_trabalho']) : $dados['dias_trabalho'];
        
        if ($usuarioModel->update($id, $dados)) {
            $_SESSION['success'] = 'Usuário atualizado com sucesso!';
            
            // Atualizar sessão se for o próprio usuário
            if ($id == $_SESSION['user_id']) {
                $_SESSION['user_nome'] = $dados['nome'];
                $_SESSION['user_email'] = $dados['email'];
                if (isset($dados['tipo'])) {
                    $_SESSION['user_tipo'] = $dados['tipo'];
                }
            }
            
            header('Location: ' . SITE_URL . '/usuarios/show/' . $id);
        } else {
            $_SESSION['error'] = 'Erro ao atualizar usuário';
            header('Location: ' . SITE_URL . '/usuarios/edit/' . $id);
        }
        exit;
    }
    
    /**
     * Exibe formulário de alteração de senha
     */
    public function changePassword($id) {
        $this->authController->requireLogin();
        
        // Usuários normais só podem alterar própria senha, admins podem alterar todas
        if (!$this->authController->isAdmin() && $id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Você não tem permissão para alterar a senha deste usuário';
            header('Location: ' . SITE_URL . '/dashboard');
            exit;
        }
        
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->getById($id);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . SITE_URL . '/usuarios');
            exit;
        }
        
        include __DIR__ . '/../views/usuarios/change_password.php';
    }
    
    /**
     * Processa alteração de senha
     */
    public function updatePassword($id) {
        $this->authController->requireLogin();
        
        // Usuários normais só podem alterar própria senha, admins podem alterar todas
        if (!$this->authController->isAdmin() && $id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Você não tem permissão para alterar a senha deste usuário';
            header('Location: ' . SITE_URL . '/dashboard');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/usuarios/change-password/' . $id);
            exit;
        }
        
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->getById($id);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . SITE_URL . '/usuarios');
            exit;
        }
        
        $senhaAtual = $_POST['senha_atual'] ?? '';
        $novaSenha = $_POST['nova_senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';
        
        $errors = [];
        
        // Se não for admin, verificar senha atual
        if (!$this->authController->isAdmin() || $id == $_SESSION['user_id']) {
            if (empty($senhaAtual)) {
                $errors[] = 'Senha atual é obrigatória';
            } elseif (!password_verify($senhaAtual, $usuario['senha'])) {
                $errors[] = 'Senha atual incorreta';
            }
        }
        
        // Validar nova senha
        if (empty($novaSenha)) {
            $errors[] = 'Nova senha é obrigatória';
        } elseif (strlen($novaSenha) < 6) {
            $errors[] = 'Nova senha deve ter pelo menos 6 caracteres';
        }
        
        if ($novaSenha !== $confirmarSenha) {
            $errors[] = 'Confirmação de senha não confere';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . SITE_URL . '/usuarios/change-password/' . $id);
            exit;
        }
        
        if ($usuarioModel->updatePassword($id, $novaSenha)) {
            $_SESSION['success'] = 'Senha alterada com sucesso!';
            header('Location: ' . SITE_URL . '/usuarios/show/' . $id);
        } else {
            $_SESSION['error'] = 'Erro ao alterar senha';
            header('Location: ' . SITE_URL . '/usuarios/change-password/' . $id);
        }
        exit;
    }
    
    /**
     * Desativa usuário
     */
    public function delete($id) {
        $this->authController->requireAdmin();
        
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Você não pode desativar sua própria conta';
            header('Location: ' . SITE_URL . '/usuarios');
            exit;
        }
        
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->getById($id);

        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . SITE_URL . '/usuarios');
            exit;
        }
        
        // Verificar se há agendamentos futuros
        if ($usuario['tipo'] === 'barbeiro') {
            $agendamentoModel = new Agendamento();
            $agendamentosFuturos = $agendamentoModel->getAll([
                'barbeiro_id' => $id,
                'data_inicio' => date('Y-m-d'),
                'status' => 'agendado'
            ]);
            
            if (!empty($agendamentosFuturos)) {
                $_SESSION['error'] = 'Não é possível desativar barbeiro com agendamentos futuros';
                header('Location: ' . SITE_URL . '/usuarios/show/' . $id);
                exit;
            }
        }
        
        if ($usuarioModel->delete($id)) {
            $_SESSION['success'] = 'Usuário desativado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao desativar usuário';
        }
        
        header('Location: ' . SITE_URL . '/usuarios');
        exit;
    }
    
    /**
     * Lista apenas barbeiros (API)
     */
    public function barbeiros() {
        $this->authController->requireLogin();
        
        header('Content-Type: application/json');
        
        $usuarioModel = new Usuario();
        $barbeiros = $usuarioModel->getBarbeiros();
        
        echo json_encode($barbeiros);
        exit;
    }
    
    /**
     * Validação para novo usuário
     */
    private function validateUsuario($dados) {
        $errors = [];
        
        // Nome obrigatório
        if (empty($dados['nome'])) {
            $errors[] = 'Nome é obrigatório';
        } elseif (strlen($dados['nome']) < 2) {
            $errors[] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        // Email obrigatório e único
        if (empty($dados['email'])) {
            $errors[] = 'Email é obrigatório';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        } else {
            $usuarioModel = new Usuario();
            if ($usuarioModel->getByEmail($dados['email'])) {
                $errors[] = 'Este email já está cadastrado';
            }
        }
        
        // Senha obrigatória
        if (empty($dados['senha'])) {
            $errors[] = 'Senha é obrigatória';
        } elseif (strlen($dados['senha']) < 6) {
            $errors[] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        if ($dados['senha'] !== $dados['confirmar_senha']) {
            $errors[] = 'Confirmação de senha não confere';
        }
        
        // Tipo válido
        if (!in_array($dados['tipo'], ['barbeiro', 'administrador'])) {
            $errors[] = 'Tipo de usuário inválido';
        }
        
        // Validar horários
        if ($dados['horario_inicio'] >= $dados['horario_fim']) {
            $errors[] = 'Horário de início deve ser anterior ao horário de fim';
        }
        
        return $errors;
    }
    
    /**
     * Validação para atualização de usuário
     */
    private function validateUsuarioUpdate($dados, $usuarioId) {
        $errors = [];
        
        // Nome obrigatório
        if (empty($dados['nome'])) {
            $errors[] = 'Nome é obrigatório';
        } elseif (strlen($dados['nome']) < 2) {
            $errors[] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        // Email obrigatório e único
        if (empty($dados['email'])) {
            $errors[] = 'Email é obrigatório';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        } else {
            $usuarioModel = new Usuario();
            $usuarioExistente = $usuarioModel->getByEmail($dados['email']);
            if ($usuarioExistente && $usuarioExistente['id'] != $usuarioId) {
                $errors[] = 'Este email já está cadastrado para outro usuário';
            }
        }
        
        // Validar horários
        if ($dados['horario_inicio'] >= $dados['horario_fim']) {
            $errors[] = 'Horário de início deve ser anterior ao horário de fim';
        }
        
        return $errors;
    }
}
?>
