<?php
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/AuthController.php';

/**
 * Controller para gerenciamento de clientes
 */
class ClienteController {
    private $authController;
    
    public function __construct() {
        $this->authController = new AuthController();
    }
    
    /**
     * Lista clientes com filtros e paginação
     */
    public function index() {
        $this->authController->requireLogin();
        
        $clienteModel = new Cliente();
        
        // Filtros
        $search = $_GET['search'] ?? '';
        $limit = $_GET['limit'] ?? 50;
        $page = $_GET['page'] ?? 1;
        
        // Calcular offset para paginação
        $offset = ($page - 1) * $limit;
        
        // Buscar clientes
        $clientes = $clienteModel->getAll(1, $limit, $search);
        
        // Contar total para paginação
        $totalClientes = $clienteModel->count();
        $totalPaginas = ceil($totalClientes / $limit);
        
        $dados = [
            'clientes' => $clientes,
            'search' => $search,
            'page' => $page,
            'limit' => $limit,
            'total_clientes' => $totalClientes,
            'total_paginas' => $totalPaginas
        ];
        
        include __DIR__ . '/../views/clientes/index.php';
    }
    
    /**
     * Exibe formulário de novo cliente
     */
    public function create() {
        $this->authController->requireLogin();
        include __DIR__ . '/../views/clientes/create.php';
    }
    
    /**
     * Salva novo cliente
     */
    public function store() {

        $this->authController->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/clientes/create');
            exit;
        }
        
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'telefone' => preg_replace('/\D/', '', $_POST['telefone'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'data_nascimento' => $_POST['data_nascimento'] ?? null,
            'endereco' => trim($_POST['endereco'] ?? ''),
            'observacoes' => trim($_POST['observacoes'] ?? '')
        ];
        
        // Validações
        $errors = $this->validateCliente($dados);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/clientes/create');
            exit;
        }
        
        $clienteModel = new Cliente();
        $clienteId = $clienteModel->create($dados);
        
        if ($clienteId) {
            $_SESSION['success'] = 'Cliente cadastrado com sucesso!';
            header('Location: ' . SITE_URL . '/clientes');
        } else {
            $_SESSION['error'] = 'Erro ao cadastrar cliente';
            header('Location: ' . SITE_URL . '/clientes/create');
        }
        exit;
    }
    
    /**
     * Exibe detalhes do cliente
     */
    /**
 * Exibir detalhes de um cliente específico
 */
public function show($id) {
    try {
        // Validar ID
        if (!is_numeric($id) || $id <= 0) {
            flash('error', 'Cliente não encontrado.');
            redirect('clientes');
            return;
        }

        // Buscar cliente usando sua classe DB
        $cliente = DB::selectOne("SELECT * FROM clientes WHERE id = ?", [$id]);

        if (!$cliente) {
            flash('error', 'Cliente não encontrado.');
            redirect('clientes');
            return;
        }

        // Buscar agendamentos do cliente com informações dos serviços e barbeiros
        $agendamentos = DB::select("
            SELECT 
                a.*,
                s.nome as servico_nome,
                s.valor as servico_valor,
                u.nome as barbeiro_nome
            FROM agendamentos a
            LEFT JOIN servicos s ON a.servico_id = s.id
            LEFT JOIN usuarios u ON a.barbeiro_id = u.id
            WHERE a.cliente_id = ?
            ORDER BY a.data_agendamento DESC
        ", [$id]);

         // Verificar se a consulta retornou dados válidos
        if ($agendamentos === false) {
            // logError('Erro na consulta de agendamentos', [
            //     'cliente_id' => $id,
            //     'user_id' => $_SESSION['user_id'] ?? null
            // ]);
            $agendamentos = []; // Definir como array vazio em caso de erro
        }

        // Garantir que $agendamentos seja sempre um array
        if (!is_array($agendamentos)) {
            $agendamentos = [];
        }

        // Processar agendamentos para garantir compatibilidade
        foreach ($agendamentos as &$agendamento) {
            // Se não tiver valor no agendamento, usar valor do serviço
            if (empty($agendamento['valor']) && !empty($agendamento['servico_valor'])) {
                $agendamento['valor'] = $agendamento['servico_valor'];
            }
            
            // Garantir que campos existam
            $agendamento['servico_nome'] = $agendamento['servico_nome'] ?? 'Serviço não informado';
            $agendamento['barbeiro_nome'] = $agendamento['barbeiro_nome'] ?? 'Barbeiro não informado';
        }
        // Estatísticas do cliente
        $stats = [
            'total_agendamentos' => count($agendamentos),
            'agendamentos_realizados' => count(array_filter($agendamentos, function($a) {
                return $a['status'] === 'realizado';
            })),
            'valor_total_gasto' => array_sum(array_map(function($a) {
                return ($a['status'] === 'realizado' && !empty($a['valor'])) ? $a['valor'] : 0;
            }, $agendamentos)),
            'proximo_agendamento' => $this->getProximoAgendamento($agendamentos)
        ];

        // Passar dados para a view
        $data = [
            'cliente' => $cliente,
            'agendamentos' => $agendamentos,
            'stats' => $stats
        ];

        // Incluir a view
        extract($data);
        include __DIR__ . '/../views/clientes/show.php';

    } catch (Exception $e) {
        logError('Erro ao exibir cliente: ' . $e->getMessage(), [
            'cliente_id' => $id,
            'user_id' => $_SESSION['user_id'] ?? null
        ]);
        
        flash('error', 'Erro interno do sistema. Tente novamente.');
        redirect('clientes');
    }
}

/**
 * Método auxiliar para encontrar próximo agendamento
 */
private function getProximoAgendamento($agendamentos) {
    $agendamentosFuturos = array_filter($agendamentos, function($a) {
        return strtotime($a['data_agendamento']) > time() && 
               in_array($a['status'], ['agendado', 'confirmado']);
    });

    if (empty($agendamentosFuturos)) {
        return null;
    }

    // Ordenar por data mais próxima
    usort($agendamentosFuturos, function($a, $b) {
        return strtotime($a['data_agendamento']) - strtotime($b['data_agendamento']);
    });

    return $agendamentosFuturos[0];
}

/**
 * Exportar histórico do cliente para CSV
 */
public function exportCSV($id) {
    try {
        // Verificar permissões
        if (!isset($_SESSION['user_id'])) {
            flash('error', 'Acesso negado.');
            redirect('login');
            return;
        }

        // Buscar cliente
        $cliente = DB::selectOne("SELECT * FROM clientes WHERE id = ?", [$id]);
        
        if (!$cliente) {
            flash('error', 'Cliente não encontrado.');
            redirect('clientes');
            return;
        }

        // Buscar agendamentos
        $agendamentos = DB::select("
            SELECT 
                DATE(a.data_agendamento) as data,
                TIME(a.data_agendamento) as hora,
                s.nome as servico,
                u.nome as barbeiro,
                a.status,
                COALESCE(a.valor, s.valor) as valor,
                a.observacoes
            FROM agendamentos a
            LEFT JOIN servicos s ON a.servico_id = s.id
            LEFT JOIN usuarios u ON a.barbeiro_id = u.id
            WHERE a.cliente_id = ?
            ORDER BY a.data_agendamento DESC
        ", [$id]);

        // Configurar headers para download
        $filename = 'historico_' . sanitize($cliente['nome']) . '_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Criar CSV
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Cabeçalho
        fputcsv($output, [
            'Data',
            'Hora', 
            'Serviço',
            'Barbeiro',
            'Status',
            'Valor',
            'Observações'
        ], ';');

        // Dados
        foreach ($agendamentos as $agendamento) {
            fputcsv($output, [
                formatDate($agendamento['data']),
                $agendamento['hora'],
                $agendamento['servico'] ?? 'Não informado',
                $agendamento['barbeiro'] ?? 'Não informado',
                STATUS_AGENDAMENTO[$agendamento['status']] ?? $agendamento['status'],
                $agendamento['valor'] ? formatMoney($agendamento['valor']) : 'Não informado',
                $agendamento['observacoes'] ?? ''
            ], ';');
        }

        fclose($output);
        exit;

    } catch (Exception $e) {
        logError('Erro ao exportar CSV: ' . $e->getMessage(), [
            'cliente_id' => $id,
            'user_id' => $_SESSION['user_id'] ?? null
        ]);
        
        flash('error', 'Erro ao gerar relatório.');
        redirect('clientes/show/' . $id);
    }
}

public function confirmarAgendamento($agendamentoId) {
    header('Content-Type: application/json');
    
    try {
        // Verificar se é requisição AJAX
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
            $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
            throw new Exception('Requisição inválida');
        }

        // Verificar permissões
        if (!isset($_SESSION['user_id'])) {
            throw new Exception('Usuário não autenticado');
        }

        // Validar ID do agendamento
        if (!is_numeric($agendamentoId) || $agendamentoId <= 0) {
            throw new Exception('ID do agendamento inválido');
        }

        // Buscar agendamento
        $agendamento = DB::selectOne("SELECT * FROM agendamentos WHERE id = ?", [$agendamentoId]);
        
        if (!$agendamento) {
            throw new Exception('Agendamento não encontrado');
        }

        // Verificar se pode ser confirmado
        if ($agendamento['status'] !== 'agendado') {
            throw new Exception('Este agendamento não pode ser confirmado');
        }

        // Atualizar status
        $updated = DB::execute("
            UPDATE agendamentos 
            SET status = 'confirmado', 
                updated_at = NOW() 
            WHERE id = ?
        ", [$agendamentoId]);

        if (!$updated) {
            throw new Exception('Erro ao atualizar agendamento');
        }

        // Log da ação
        logError('Agendamento confirmado', [
            'agendamento_id' => $agendamentoId,
            'user_id' => $_SESSION['user_id']
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Agendamento confirmado com sucesso!'
        ]);

    } catch (Exception $e) {
        logError('Erro ao confirmar agendamento: ' . $e->getMessage(), [
            'agendamento_id' => $agendamentoId,
            'user_id' => $_SESSION['user_id'] ?? null
        ]);

        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    
    exit;
}
    
    /**
     * Exibe formulário de edição
     */
    public function edit($id) {
        $this->authController->requireLogin();

        
        $clienteModel = new Cliente();
        $cliente = $clienteModel->getById($id);

        if (!$cliente) {
            $_SESSION['error'] = 'Cliente não encontrado';
            header('Location: ' . SITE_URL . '/clientes');
            exit;
        }

        // var_dump($cliente_edit);exit();
        
        include __DIR__ . '/../views/clientes/edit.php';
    }
    
    /**
     * Atualiza dados do cliente
     */
    public function update($id) {
        $this->authController->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SITE_URL . '/clientes/edit/' . $id);
            exit;
        }
        
        $clienteModel = new Cliente();
        $cliente = $clienteModel->getById($id);
        
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente não encontrado';
            header('Location: ' . SITE_URL . '/clientes');
            exit;
        }
        
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'telefone' => preg_replace('/\D/', '', $_POST['telefone'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'data_nascimento' => $_POST['data_nascimento'] ?? null,
            'endereco' => trim($_POST['endereco'] ?? ''),
            'observacoes' => trim($_POST['observacoes'] ?? ''),
            'ativo' => 1
        ];
        
        // Validações
        $errors = $this->validateCliente($dados, $id);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $dados;
            header('Location: ' . SITE_URL . '/clientes/edit/' . $id);
            exit;
        }
        
        if ($clienteModel->update($id, $dados)) {
            $_SESSION['success'] = 'Cliente atualizado com sucesso!';
            header('Location: ' . SITE_URL . '/clientes/show/' . $id);
        } else {
            $_SESSION['error'] = 'Erro ao atualizar cliente';
            header('Location: ' . SITE_URL . '/clientes/edit/' . $id);
        }
        exit;
    }
    
    /**
     * Desativa cliente (soft delete)
     */
    public function delete($id) {
        $this->authController->requireAdmin();
        
        $clienteModel = new Cliente();
        $cliente = $clienteModel->getById($id);
        
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente não encontrado';
            header('Location: ' . SITE_URL . '/clientes');
            exit;
        }
        
        // Verificar se tem agendamentos futuros
        $agendamentoModel = new Agendamento();
        $agendamentosFuturos = $agendamentoModel->getAll([
            'cliente_id' => $id,
            'data_inicio' => date('Y-m-d'),
            'status' => 'agendado'
        ]);
        
        if (!empty($agendamentosFuturos)) {
            $_SESSION['error'] = 'Não é possível desativar cliente com agendamentos futuros';
            header('Location: ' . SITE_URL . '/clientes/show/' . $id);
            exit;
        }
        
        if ($clienteModel->deactivate($id)) {
            $_SESSION['success'] = 'Cliente desativado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao desativar cliente';
        }
        
        header('Location: ' . SITE_URL . '/clientes');
        exit;
    }
    
    /**
     * API para busca de clientes (AJAX)
     */
    public function search() {
        $this->authController->requireLogin();
        
        header('Content-Type: application/json');
        
        $term = $_GET['term'] ?? '';
        
        if (strlen($term) < 2) {
            echo json_encode([]);
            exit;
        }
        // Calcular estatísticas
        $estatisticas = $this->calcularEstatisticas();

        $clienteModel = new Cliente();
        $clientes = $clienteModel->getAll(1, 10, $term);
        
        $resultado = [];
        foreach ($clientes as $cliente) {
            $resultado[] = [
                'id' => $cliente['id'],
                'label' => $cliente['nome'] . ' - ' . $cliente['telefone'],
                'value' => $cliente['nome'],
                'telefone' => $cliente['telefone'],
                'email' => $cliente['email']
            ];
        }
        
        echo json_encode($resultado);
        exit;
    }

     /**
     * Calcular estatísticas dos clientes
     */
    private function calcularEstatisticas() {
        try {
            // Total de clientes
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM clientes");
            $total = $stmt->fetchColumn();
            
            // Clientes ativos (que fizeram agendamento nos últimos 6 meses)
            $stmt = $this->pdo->query("
                SELECT COUNT(DISTINCT cliente_id) 
                FROM agendamentos 
                WHERE data_agendamento >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                AND status != 'cancelado'
            ");
            $ativos = $stmt->fetchColumn();
            
            // Novos clientes este mês
            $stmt = $this->pdo->query("
                SELECT COUNT(*) 
                FROM clientes 
                WHERE YEAR(data_cadastro) = YEAR(NOW()) 
                AND MONTH(data_cadastro) = MONTH(NOW())
            ");
            $novos_mes = $stmt->fetchColumn();
            
            // Clientes VIP (mais de 10 agendamentos)
            $stmt = $this->pdo->query("
                SELECT COUNT(DISTINCT cliente_id) 
                FROM agendamentos 
                WHERE status = 'realizado'
                GROUP BY cliente_id 
                HAVING COUNT(*) >= 10
            ");
            $vip = $stmt->rowCount();
            
            return [
                'total' => $total,
                'ativos' => $ativos,
                'novos_mes' => $novos_mes,
                'vip' => $vip
            ];
            
        } catch (PDOException $e) {
            return [
                'total' => 0,
                'ativos' => 0,
                'novos_mes' => 0,
                'vip' => 0
            ];
        }
    }
    
    /**
     * API para verificar se telefone já existe
     */
    public function checkTelefone() {
        $this->authController->requireLogin();
        
        header('Content-Type: application/json');
        
        $telefone = preg_replace('/\D/', '', $_GET['telefone'] ?? '');
        $excludeId = $_GET['exclude_id'] ?? null;
        
        if (empty($telefone)) {
            echo json_encode(['exists' => false]);
            exit;
        }
        
        $clienteModel = new Cliente();
        $exists = $clienteModel->telefoneExists($telefone, $excludeId);
        
        echo json_encode(['exists' => $exists]);
        exit;
    }
    
    /**
     * API para verificar se email já existe
     */
    public function checkEmail() {
        $this->authController->requireLogin();
        
        header('Content-Type: application/json');
        
        $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
        $excludeId = $_GET['exclude_id'] ?? null;
        
        if (empty($email)) {
            echo json_encode(['exists' => false]);
            exit;
        }
        
        $clienteModel = new Cliente();
        $exists = $clienteModel->emailExists($email, $excludeId);
        
        echo json_encode(['exists' => $exists]);
        exit;
    }
    
    /**
     * Relatório de aniversariantes
     */
    public function aniversariantes() {
        $this->authController->requireLogin();
        
        $mes = $_GET['mes'] ?? date('m');
        $ano = $_GET['ano'] ?? date('Y');
        
        $clienteModel = new Cliente();
        $aniversariantes = $clienteModel->getAniversariantesDoMes($mes);
        
        $dados = [
            'aniversariantes' => $aniversariantes,
            'mes' => $mes,
            'ano' => $ano,
            'nome_mes' => $this->getNomeMes($mes)
        ];
        
        include __DIR__ . '/../views/clientes/aniversariantes.php';
    }
    
    /**
     * Relatório de clientes mais frequentes
     */
    public function frequentes() {
        $this->authController->requireLogin();
        
        $limit = $_GET['limit'] ?? 20;
        
        $clienteModel = new Cliente();
        $clientes = $clienteModel->getMaisFrequentes($limit);
        
        $dados = [
            'clientes' => $clientes,
            'limit' => $limit
        ];
        
        include __DIR__ . '/../views/clientes/frequentes.php';
    }
    
    /**
     * Validação dos dados do cliente
     */
    private function validateCliente($dados, $clienteId = null) {
        $errors = [];
        
        // Nome obrigatório
        if (empty($dados['nome'])) {
            $errors[] = 'Nome é obrigatório';
        } elseif (strlen($dados['nome']) < 2) {
            $errors[] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        // Telefone obrigatório e único
        if (empty($dados['telefone'])) {
            $errors[] = 'Telefone é obrigatório';
        } elseif (strlen($dados['telefone']) < 10) {
            $errors[] = 'Telefone deve ter pelo menos 10 dígitos';
        } else {
            $clienteModel = new Cliente();
            if ($clienteModel->telefoneExists($dados['telefone'], $clienteId)) {
                $errors[] = 'Este telefone já está cadastrado para outro cliente';
            }
        }
        
        // Email (opcional, mas se informado deve ser válido e único)
        if (!empty($dados['email'])) {
            if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email inválido';
            } else {
                $clienteModel = new Cliente();
                if ($clienteModel->emailExists($dados['email'], $clienteId)) {
                    $errors[] = 'Este email já está cadastrado para outro cliente';
                }
            }
        }
        
        // Data de nascimento (opcional, mas se informada deve ser válida)
        if (!empty($dados['data_nascimento'])) {
            $dataNasc = DateTime::createFromFormat('Y-m-d', $dados['data_nascimento']);
            if (!$dataNasc || $dataNasc->format('Y-m-d') !== $dados['data_nascimento']) {
                $errors[] = 'Data de nascimento inválida';
            } elseif ($dataNasc > new DateTime()) {
                $errors[] = 'Data de nascimento não pode ser futura';
            } elseif ($dataNasc < new DateTime('1900-01-01')) {
                $errors[] = 'Data de nascimento muito antiga';
            }
        }
        
        return $errors;
    }
    
    /**
     * Converte número do mês para nome
     */
    private function getNomeMes($mes) {
        $meses = [
            '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março',
            '04' => 'Abril', '05' => 'Maio', '06' => 'Junho',
            '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro',
            '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
        ];
        
        return $meses[str_pad($mes, 2, '0', STR_PAD_LEFT)] ?? 'Mês Inválido';
    }
}
?>
