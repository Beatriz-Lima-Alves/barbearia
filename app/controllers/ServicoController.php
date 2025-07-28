<?php
require_once __DIR__ . '/../models/Servico.php';
require_once __DIR__ . '/AuthController.php';

/**
 * Controller para gerenciamento de serviços
 */
class ServicoController {
    private $authController;
    
    public function __construct() {
        $this->authController = new AuthController();
    }
    
    /**
     * Lista todos os serviços
     */
    public function index() {
        $this->authController->requireLogin();
        
        $servicoModel = new Servico();
        $servicos = $servicoModel->getAll();
        
        include __DIR__ . '/../views/servicos/index.php';
    }
    
    /**
     * Exibe formulário de novo serviço
     */
    public function create() {
        $this->authController->requireAdmin();
        include __DIR__ . '/../views/servicos/create.php';
    }
    
    /**
     * Salva novo serviço
     */
    public function store() {
        $this->authController->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/servicos/create');
            exit;
        }
        
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'duracao' => (int)($_POST['duracao'] ?? 30),
            'preco' => (float)str_replace(',', '.', $_POST['preco'] ?? 0)
        ];
        
        // Validações
        $errors = $this->validateServico($dados);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/servicos/create');
            exit;
        }
        
        $servicoModel = new Servico();
        $servicoId = $servicoModel->create($dados);
        
        if ($servicoId) {
            $_SESSION['success'] = 'Serviço cadastrado com sucesso!';
            header('Location: ' . SITE_URL . '/servicos');
        } else {
            $_SESSION['error'] = 'Erro ao cadastrar serviço';
            header('Location: ' . SITE_URL . '/servicos/create');
        }
        exit;
    }
    
    /**
     * Exibe detalhes do serviço
     */
    public function show($id) {
        $this->authController->requireLogin();
        
        $servicoModel = new Servico();
        $servico = $servicoModel->getById($id);
        
        if (!$servico) {
            $_SESSION['error'] = 'Serviço não encontrado';
            header('Location: ' . SITE_URL . '/servicos');
            exit;
        }
        
        // Obter estatísticas do serviço
        $estatisticas = $servicoModel->getReceitaTotal($id);
        
        $dados = [
            'servico' => $servico,
            'estatisticas' => $estatisticas
        ];
        
        include __DIR__ . '/../views/servicos/show.php';
    }
    
    /**
     * Exibe formulário de edição
     */
    public function edit($id) {
        $this->authController->requireAdmin();
        
        $servicoModel = new Servico();
        $servico = $servicoModel->getById($id);
        
        if (!$servico) {
            $_SESSION['error'] = 'Serviço não encontrado';
            header('Location: ' . SITE_URL . '/servicos');
            exit;
        }
        
        include __DIR__ . '/../views/servicos/edit.php';
    }
    
    /**
     * Atualiza dados do serviço
     */
    public function update($id) {
        $this->authController->requireAdmin();
        
        $servicoModel = new Servico();
        $servico = $servicoModel->getById($id);
        
        if (!$servico) {
            $_SESSION['error'] = 'Serviço não encontrado';
            header('Location: ' . SITE_URL . '/servicos');
            exit;
        }
        
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'duracao' => (int)($_POST['duracao'] ?? 30),
            'preco' => (float)str_replace(',', '.', $_POST['preco'] ?? 0),
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];
        
        // Validações
        $errors = $this->validateServico($dados, $id);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/servicos/edit/' . $id);
            exit;
        }
        
        if ($servicoModel->update($id, $dados)) {
            $_SESSION['success'] = 'Serviço atualizado com sucesso!';
            header('Location: ' . SITE_URL . '/servicos/show/' . $id);
        } else {
            $_SESSION['error'] = 'Erro ao atualizar serviço';
            header('Location: ' . SITE_URL . '/servicos/edit/' . $id);
        }
        exit;
    }
    
    /**
     * Desativa serviço
     */
    public function delete($id) {
        $this->authController->requireAdmin();
        
        $servicoModel = new Servico();
        $servico = $servicoModel->getById($id);
        
        if (!$servico) {
            $_SESSION['error'] = 'Serviço não encontrado';
            header('Location: ' . SITE_URL . '/servicos');
            exit;
        }
        
        // Verificar se há agendamentos futuros com este serviço
        $agendamentoModel = new Agendamento();
        $agendamentosFuturos = $agendamentoModel->getAll([
            'servico_id' => $id,
            'data_inicio' => date('Y-m-d'),
            'status' => 'agendado'
        ]);
        
        if (!empty($agendamentosFuturos)) {
            $_SESSION['error'] = 'Não é possível desativar serviço com agendamentos futuros';
            header('Location: ' . SITE_URL . '/servicos/show/' . $id);
            exit;
        }
        
        if ($servicoModel->deactivate($id)) {
            $_SESSION['success'] = 'Serviço desativado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao desativar serviço';
        }
        
        header('Location: ' . SITE_URL . '/servicos');
        exit;
    }

   /**
     * Duplicar serviço
     */
    public function duplicate($id) {
        $this->authController->requireAdmin();
        
        $servicoModel = new Servico();
        // Buscar serviço original usando a model
        $servicoOriginal = $servicoModel->getById($id);
        
        if (!$servicoOriginal) {
            $_SESSION['error'] = 'Serviço não encontrado';
            header('Location: ' . SITE_URL . '/servicos');
            exit;
        }
        
        // Obter novo nome
        $novoNome = trim($_POST['novo_nome'] ?? '');
        
        if (empty($novoNome)) {
            $_SESSION['error'] = 'Nome para o novo serviço é obrigatório';
            header('Location: ' . SITE_URL . '/servicos/show/' . $id);
            exit;
        }
        
        // Verificar se novo nome já existe usando a model
        if ($servicoModel->nomeExists($novoNome)) {
            $_SESSION['error'] = 'Já existe um serviço com este nome';
            header('Location: ' . SITE_URL . '/servicos/show/' . $id);
            exit;
        }
        
        // Duplicar serviço usando a model
        $novoServicoId = $servicoModel->duplicate($id, $novoNome);
        
        if ($novoServicoId) {
            $_SESSION['success'] = "Serviço '{$novoNome}' foi criado com base em '{$servicoOriginal['nome']}'!";
            header('Location: ' . SITE_URL . '/servicos/show/' . $novoServicoId);
        } else {
            throw new Exception('Erro ao duplicar serviço no banco de dados');
        }
        exit;
    }

    /**
     * reativa serviço
     */
    public function reactive($id) {
        $this->authController->requireAdmin();
        
        $servicoModel = new Servico();
        $servico = $servicoModel->getById($id);
        
        if (!$servico) {
            $_SESSION['error'] = 'Serviço não encontrado';
            header('Location: ' . SITE_URL . '/servicos');
            exit;
        }
        
        if ($servicoModel->active($id)) {
            $_SESSION['success'] = 'Serviço ativado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao ativar serviço';
        }
        
        header('Location: ' . SITE_URL . '/servicos');
        exit;
    }
    
    /**
     * Lista serviços ativos para API (JSON)
     */
    public function api() {
        $this->authController->requireLogin();
        
        header('Content-Type: application/json');
        
        $servicoModel = new Servico();
        $servicos = $servicoModel->getAtivos();
        
        echo json_encode($servicos);
        exit;
    }
    
    /**
     * Relatório de serviços mais populares
     */
    public function populares() {
        $this->authController->requireLogin();
        
        $limit = $_GET['limit'] ?? 10;
        
        $servicoModel = new Servico();
        $servicos = $servicoModel->getMaisAgendados($limit);
        
        $dados = [
            'servicos' => $servicos,
            'limit' => $limit
        ];
        
        include __DIR__ . '/../views/servicos/populares.php';
    }
    
    /**
     * Relatório de receita por serviço
     */
    public function receita() {
        $this->authController->requireLogin();
        
        $dataInicio = $_GET['data_inicio'] ?? date('Y-m-01');
        $dataFim = $_GET['data_fim'] ?? date('Y-m-t');
        
        $financeiroModel = new Financeiro();
        $receitaServicos = $financeiroModel->getReceitaPorServico($dataInicio, $dataFim);
        
        $dados = [
            'receita_servicos' => $receitaServicos,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim
        ];
        
        include __DIR__ . '/../views/servicos/receita.php';
    }
    
    /**
     * Validação dos dados do serviço
     */
    private function validateServico($dados, $servicoId = null) {
        $errors = [];
        
        // Nome obrigatório e único
        if (empty($dados['nome'])) {
            $errors[] = 'Nome é obrigatório';
        } elseif (strlen($dados['nome']) < 2) {
            $errors[] = 'Nome deve ter pelo menos 2 caracteres';
        } else {
            $servicoModel = new Servico();
            if ($servicoModel->nomeExists($dados['nome'], $servicoId)) {
                $errors[] = 'Já existe um serviço com este nome';
            }
        }
        
        // Duração válida
        if ($dados['duracao'] <= 0) {
            $errors[] = 'Duração deve ser maior que zero';
        } elseif ($dados['duracao'] > 480) { // 8 horas
            $errors[] = 'Duração máxima é de 480 minutos (8 horas)';
        }
        
        // Preço válido
        if ($dados['preco'] <= 0) {
            $errors[] = 'Preço deve ser maior que zero';
        } elseif ($dados['preco'] > 9999.99) {
            $errors[] = 'Preço máximo é R$ 9.999,99';
        }
        
        return $errors;
    }
}
?>
