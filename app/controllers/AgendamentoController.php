<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Agendamento.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Servico.php';
require_once __DIR__ . '/AuthController.php';

/**
 * Controller para gerenciamento de agendamentos
 */
class AgendamentoController {
    private $authController;
    
    public function __construct() {
        $this->authController = new AuthController();
    }
    
     /**
     * Lista agendamentos com filtros e estatísticas
     */
    public function index() {
        $this->authController->requireLogin();
        
        $agendamentoModel = new Agendamento();
        $usuarioModel = new Usuario();
        
        // Filtros
        $filtros = [
            'data_inicio' => $_GET['data_inicio'] ?? date('Y-m-d'),
            'data_fim' => $_GET['data_fim'] ?? date('Y-m-d', strtotime('+7 days')),
            'barbeiro_id' => $_GET['barbeiro_id'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];
        
        // Se não for admin, mostrar apenas agendamentos do próprio barbeiro
        if (!$this->authController->isAdmin()) {
            $filtros['barbeiro_id'] = $_SESSION['user_id'];
        }
        
        // Buscar agendamentos
        $agendamentos = $agendamentoModel->getAll($filtros);
        $barbeiros = $usuarioModel->getBarbeiros();
        
        // ===== CALCULAR ESTATÍSTICAS =====
        $hoje = date('Y-m-d');
        
        // Filtros para estatísticas (sempre considerando o barbeiro se não for admin)
        $filtroEstatisticas = [];
        if (!$this->authController->isAdmin()) {
            $filtroEstatisticas['barbeiro_id'] = $_SESSION['user_id'];
        }
        
        // 1. Agendamentos de HOJE
        $filtroHoje = array_merge($filtroEstatisticas, [
            'data_inicio' => $hoje,
            'data_fim' => $hoje
        ]);
        $agendamentosHoje = $agendamentoModel->getAll($filtroHoje);
        $totalHoje = count($agendamentosHoje);
        
        // 2. Agendamentos PENDENTES (status = 'agendado' e data >= hoje)
        $filtroPendentes = array_merge($filtroEstatisticas, [
            'status' => 'agendado',
            'data_inicio' => $hoje
        ]);
        $agendamentosPendentes = $agendamentoModel->getAll($filtroPendentes);
        $totalPendentes = count($agendamentosPendentes);
        
        // 3. Agendamentos CONCLUÍDOS (status = 'concluido')
        $filtroConcluidos = array_merge($filtroEstatisticas, [
            'status' => 'concluido'
        ]);
        $agendamentosConcluidos = $agendamentoModel->getAll($filtroConcluidos);
        $totalConcluidos = count($agendamentosConcluidos);
        
        // 4. Agendamentos CANCELADOS (status = 'cancelado')
        $filtroCancelados = array_merge($filtroEstatisticas, [
            'status' => 'cancelado'
        ]);
        $agendamentosCancelados = $agendamentoModel->getAll($filtroCancelados);
        $totalCancelados = count($agendamentosCancelados);
        
        // Preparar dados para a view
        $dados = [
            'agendamentos' => $agendamentos,
            'barbeiros' => $barbeiros,
            'filtros' => $filtros,
            'estatisticas' => [
                'hoje' => $totalHoje,
                'pendentes' => $totalPendentes,
                'concluidos' => $totalConcluidos,
                'cancelados' => $totalCancelados
            ]
        ];
        
        include __DIR__ . '/../views/agendamentos/index.php';
    }
    
    /**
     * Exibe formulário de novo agendamento
     */
    public function create() {
        $this->authController->requireLogin();
        
        $clienteModel = new Cliente();
        $usuarioModel = new Usuario();
        $servicoModel = new Servico();
        
        $clientes = $clienteModel->getAll();
        $barbeiros = $usuarioModel->getBarbeiros();
        $servicos = $servicoModel->getAll();
        
        include __DIR__ . '/../views/agendamentos/create.php';
    }

    /**
 * Exibir detalhes do agendamento
 */
public function show($id) {
    $this->authController->requireLogin();
    
    $agendamentoModel = new Agendamento();
    $agendamento = $agendamentoModel->getById($id);
    
    if (!$agendamento) {
        $_SESSION['error'] = 'Agendamento não encontrado.';
        header('Location: /agendamentos');
        exit();
    }
    
    include __DIR__ . '/../views/agendamentos/show.php';
}
    
    /**
     * Salva novo agendamento
     */
    public function store() {
        $this->authController->requireLogin();

        
        $dados = [
            'cliente_id' => $_POST['cliente_id'] ?? '',
            'barbeiro_id' => $_POST['barbeiro_id'] ?? '',
            'servico_id' => $_POST['servico_id'] ?? '',
            'data_agendamento' => $_POST['data'] ?? '',
            'hora_agendamento' => $_POST['hora'] ?? '',
            'observacoes' => trim($_POST['observacoes'] ?? '')
        ];
        
        // Validações
        $errors = $this->validateAgendamento($dados);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/agendamentos/create');
            exit;
        }
        
        $agendamentoModel = new Agendamento();
        $agendamentoId = $agendamentoModel->create($dados);
        
        if ($agendamentoId) {
            $_SESSION['success'] = 'Agendamento criado com sucesso!';
            
            // Enviar notificação por email (opcional)
            $this->enviarNotificacaoEmail($agendamentoId);
            
            header('Location: ' . SITE_URL . '/agendamentos');
        } else {
            $_SESSION['error'] = 'Erro ao criar agendamento';
            header('Location: ' . SITE_URL . '/agendamentos/create');
        }
        exit;
    }
    
    /**
     * Exibe formulário de edição
     */
    public function edit($id) {
        $this->authController->requireLogin();
        
        $agendamentoModel = new Agendamento();
        $agendamento = $agendamentoModel->getById($id);
        
        if (!$agendamento) {
            $_SESSION['error'] = 'Agendamento não encontrado';
            header('Location: ' . SITE_URL . '/agendamentos');
            exit;
        }
        
        // Verificar permissão
        if (!$this->authController->isAdmin() && 
            $agendamento['barbeiro_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Você não tem permissão para editar este agendamento';
            header('Location: ' . SITE_URL . '/agendamentos');
            exit;
        }
        
        $clienteModel = new Cliente();
        $usuarioModel = new Usuario();
        $servicoModel = new Servico();
        
        $clientes = $clienteModel->getAll();
        $barbeiros = $usuarioModel->getBarbeiros();
        $servicos = $servicoModel->getAll();
        
        include __DIR__ . '/../views/agendamentos/edit.php';
    }
    
    /**
     * Atualiza agendamento
     */
    public function update($id) {
        $this->authController->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/agendamentos/edit/' . $id);
            exit;
        }
        
        $agendamentoModel = new Agendamento();
        $agendamento = $agendamentoModel->getById($id);
        
        if (!$agendamento) {
            $_SESSION['error'] = 'Agendamento não encontrado';
            header('Location: ' . SITE_URL . '/agendamentos');
            exit;
        }
        
        // Verificar permissão
        if (!$this->authController->isAdmin() && 
            $agendamento['barbeiro_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Você não tem permissão para editar este agendamento';
            header('Location: ' . SITE_URL . '/agendamentos');
            exit;
        }
        
        $dados = [
            'cliente_id' => $_POST['cliente_id'] ?? '',
            'barbeiro_id' => $_POST['barbeiro_id'] ?? '',
            'servico_id' => $_POST['servico_id'] ?? '',
            'data_agendamento' => $_POST['data_agendamento'] ?? '',
            'hora_agendamento' => $_POST['hora_agendamento'] ?? '',
            'status' => $_POST['status'] ?? 'agendado',
            'observacoes' => trim($_POST['observacoes'] ?? ''),
            'valor_cobrado' => $_POST['valor_cobrado'] ?? null
        ];
        
        // Validações
        $errors = $this->validateAgendamento($dados, $id);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/agendamentos/edit/' . $id);
            exit;
        }
        
        if ($agendamentoModel->update($id, $dados)) {
            $_SESSION['success'] = 'Agendamento atualizado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao atualizar agendamento';
        }
        
        header('Location: ' . SITE_URL . '/agendamentos');
        exit;
    }
    
    /**
     * Cancela agendamento
     */
    public function cancel($id) {
        $this->authController->requireLogin();
        
        $agendamentoModel = new Agendamento();
        $agendamento = $agendamentoModel->getById($id);
        
        if (!$agendamento) {
            $_SESSION['error'] = 'Agendamento não encontrado';
            header('Location: ' . SITE_URL . '/agendamentos');
            exit;
        }
        
        // Verificar permissão
        if (!$this->authController->isAdmin() && 
            $agendamento['barbeiro_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Você não tem permissão para cancelar este agendamento';
            header('Location: ' . SITE_URL . '/agendamentos');
            exit;
        }
        
        if ($agendamentoModel->cancel($id)) {
            $_SESSION['success'] = 'Agendamento cancelado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao cancelar agendamento';
        }
        
        header('Location: ' . SITE_URL . '/agendamentos');
        exit;
    }
    
    /**
     * Marca agendamento como realizado
     */
    public function complete($id) {
        $this->authController->requireLogin();
        
        $agendamentoModel = new Agendamento();
        $agendamento = $agendamentoModel->getById($id);
        
        if (!$agendamento) {
            $_SESSION['error'] = 'Agendamento não encontrado';
            header('Location: ' . SITE_URL . '/agendamentos');
            exit;
        }
        
        // Verificar permissão
        if (!$this->authController->isAdmin() && 
            $agendamento['barbeiro_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Você não tem permissão para alterar este agendamento';
            header('Location: ' . SITE_URL . '/agendamentos');
            exit;
        }
        
        if ($agendamentoModel->complete($id)) {
            $_SESSION['success'] = 'Agendamento marcado como realizado!';
        } else {
            $_SESSION['error'] = 'Erro ao atualizar agendamento';
        }
        
        header('Location: ' . SITE_URL . '/agendamentos');
        exit;
    }
    
    /**
     * API para buscar horários disponíveis
     */
    public function getHorariosDisponiveis() {
        $this->authController->requireLogin();
        
        header('Content-Type: application/json');
        
        $barbeiroId = $_GET['barbeiro_id'] ?? '';
        $data = $_GET['data'] ?? '';
        $servicoId = $_GET['servico_id'] ?? '';
        
        if (empty($barbeiroId) || empty($data) || empty($servicoId)) {
            echo json_encode(['success' => false, 'message' => 'Parâmetros obrigatórios']);
            exit;
        }
        
        $servicoModel = new Servico();
        $servico = $servicoModel->getById($servicoId);
        
        if (!$servico) {
            echo json_encode(['success' => false, 'message' => 'Serviço não encontrado']);
            exit;
        }
        
        $usuarioModel = new Usuario();
        $horarios = $usuarioModel->getHorariosDisponiveis($barbeiroId, $data, $servico['duracao']);
        
        echo json_encode(['success' => true, 'horarios' => $horarios]);
        exit;
    }
    
    /**
     * Valida dados do agendamento
     */
    private function validateAgendamento($dados, $agendamentoId = null) {
        $errors = [];
        
        if (empty($dados['cliente_id'])) {
            $errors[] = 'Cliente é obrigatório';
        }
        
        if (empty($dados['barbeiro_id'])) {
            $errors[] = 'Barbeiro é obrigatório';
        }
        
        if (empty($dados['servico_id'])) {
            $errors[] = 'Serviço é obrigatório';
        }
        
        if (empty($dados['data_agendamento'])) {
            $errors[] = 'Data é obrigatória';
        } else {
            $dataAgendamento = strtotime($dados['data_agendamento']);
            $hoje = strtotime(date('Y-m-d'));
            
            if ($dataAgendamento < $hoje) {
                $errors[] = 'Data não pode ser anterior a hoje';
            }
        }
        
        if (empty($dados['hora_agendamento'])) {
            $errors[] = 'Horário é obrigatório';
        }
        
        // Verificar disponibilidade do barbeiro
        if (!empty($dados['barbeiro_id']) && !empty($dados['data_agendamento']) && 
            !empty($dados['hora_agendamento']) && !empty($dados['servico_id'])) {
            
            $servicoModel = new Servico();
            $servico = $servicoModel->getById($dados['servico_id']);
            
            if ($servico) {
                $usuarioModel = new Usuario();
                
                // Se estiver editando, verificar se é o mesmo agendamento
                $agendamentoModel = new Agendamento();
                $conflito = $agendamentoModel->hasConflict(
                    $dados['barbeiro_id'],
                    $dados['data_agendamento'],
                    $dados['hora_agendamento'],
                    $servico['duracao'],
                    $agendamentoId
                );
                
                if ($conflito) {
                    $errors[] = 'Horário não disponível para este barbeiro';
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Envia notificação por email
     */
    private function enviarNotificacaoEmail($agendamentoId) {
        // Implementar envio de email se necessário
        // Aqui seria a integração com biblioteca de email como PHPMailer
        return true;
    }

    /**
 * Exibir página de agenda/calendário - COM CONTROLE DE ACESSO
 */
public function agenda() {
    $this->authController->requireLogin();
    
    try {
        $agendamentoModel = new Agendamento();
        $usuarioModel = new Usuario();
        
        // Filtros da URL
        $barbeiroId = $_GET['barbeiro_id'] ?? null;
        $dataInicio = $_GET['data_inicio'] ?? date('Y-m-d');
        $dataFim = $_GET['data_fim'] ?? date('Y-m-d', strtotime('+7 days'));
        $view = $_GET['view'] ?? 'geral'; // geral, semana, mes
        
        // *** CONTROLE DE ACESSO CORRIGIDO ***
        $filtros = [
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim
        ];
        
        if ($this->authController->isAdmin()) {
            // Admin pode ver todos os agendamentos
            if ($barbeiroId) {
                $filtros['barbeiro_id'] = $barbeiroId;
            }
        } else {
            // Barbeiro só vê seus próprios agendamentos
            $filtros['barbeiro_id'] = $_SESSION['user_id'];
        }
        
        // *** BUSCAR DADOS UNIFICADOS ***
        
        // 1. Buscar TODOS os agendamentos primeiro
        $todosAgendamentos = $agendamentoModel->getAll($filtros);
        
        // 2. Calcular contadores baseados nos dados reais DO PERÍODO FILTRADO
        $hoje = date('Y-m-d');
        $contadores = [
            'hoje' => 0,          // Só agendamentos de HOJE
            'pendentes' => 0,     // Agendados no PERÍODO filtrado  
            'concluidos' => 0,    // Realizados no PERÍODO filtrado
            'cancelados' => 0     // Cancelados no PERÍODO filtrado
        ];
        
        foreach ($todosAgendamentos as $agendamento) {
            $dataAgendamento = $agendamento['data_agendamento'];
            $status = strtolower(trim($agendamento['status']));
            
            // Contar por status (baseado nos dados REAIS do período filtrado)
            switch ($status) {
                case 'agendado':
                case 'pendente':
                case 'confirmado':
                    $contadores['pendentes']++;
                    // Se for hoje, contar também em "hoje"
                    if ($dataAgendamento === $hoje) {
                        $contadores['hoje']++;
                    }
                    break;
                case 'realizado':
                case 'concluido':
                case 'finalizado':
                    $contadores['concluidos']++;
                    break;
                case 'cancelado':
                case 'cancelada':
                    $contadores['cancelados']++;
                    break;
            }
        }
        
        // 3. Usar os agendamentos já buscados
        $agendamentos = $todosAgendamentos;
        
        // 4. Organizar agendamentos por data (CORREÇÃO DUPLICAÇÃO)
        $agendamentosPorData = [];
        $idsProcessados = []; // Controle para evitar duplicação
        
        foreach ($agendamentos as $agendamento) {
            $agendamentoId = $agendamento['id'];
            $data = $agendamento['data_agendamento'];
            
            // DEBUG: Log para identificar duplicações
            error_log("DEBUG ORGANIZAÇÃO - ID: {$agendamentoId}, Data: {$data}, Já processado: " . (in_array($agendamentoId, $idsProcessados) ? 'SIM' : 'NÃO'));
            
            // Verificar se já foi processado (usando chave única ID+DATA)
            $chaveUnica = $agendamentoId . '_' . $data;
            if (!in_array($chaveUnica, $idsProcessados)) {
                if (!isset($agendamentosPorData[$data])) {
                    $agendamentosPorData[$data] = [];
                }
                $agendamentosPorData[$data][] = $agendamento;
                $idsProcessados[] = $chaveUnica;
            }
        }
        
        // Ordenar as datas
        ksort($agendamentosPorData);
        
        // Ordenar agendamentos dentro de cada data por horário
        foreach ($agendamentosPorData as $data => &$agendamentosData) {
            usort($agendamentosData, function($a, $b) {
                return strcmp($a['hora_agendamento'], $b['hora_agendamento']);
            });
        }
        
        // 5. Buscar barbeiros para filtro
        $barbeiros = $usuarioModel->getBarbeiros();
        
        // *** PREPARAR DADOS PARA A VIEW ***
        $dados = [
            'contadores' => $contadores,
            'agendamentos' => $agendamentos,
            'agendamentosPorData' => $agendamentosPorData,
            'barbeiros' => $barbeiros,
            'filtros' => [
                'barbeiro_id' => $barbeiroId,
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
                'view' => $view
            ],
            'periodo' => [
                'inicio' => date('d/m/Y', strtotime($dataInicio)),
                'fim' => date('d/m/Y', strtotime($dataFim))
            ]
        ];
        
        // Extrair variáveis para o escopo da view
        extract($dados);
        
        // Incluir a view
        include __DIR__ . '/../views/agenda/index.php';
        
    } catch (Exception $e) {
        error_log('Erro na agenda: ' . $e->getMessage());
        $_SESSION['error'] = 'Erro ao carregar agenda: ' . $e->getMessage();
        
        // Dados padrão em caso de erro
        $contadores = ['hoje' => 0, 'pendentes' => 0, 'concluidos' => 0, 'cancelados' => 0];
        $agendamentos = [];
        $agendamentosPorData = [];
        $barbeiros = [];
        $filtros = ['barbeiro_id' => null, 'data_inicio' => date('Y-m-d'), 'data_fim' => date('Y-m-d', strtotime('+7 days')), 'view' => 'geral'];
        $periodo = ['inicio' => date('d/m/Y'), 'fim' => date('d/m/Y', strtotime('+7 days'))];
        
        include __DIR__ . '/../views/agenda/index.php';
    }
}
}
?>
