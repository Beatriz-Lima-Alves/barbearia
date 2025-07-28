<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Servico.php';
require_once __DIR__ . '/../models/Agendamento.php';
require_once __DIR__ . '/../models/Financeiro.php';

/**
 * Controller do Dashboard
 */
class DashboardController {
    private $authController;
    
    public function __construct() {
        $this->authController = new AuthController();
    }
    
    /**
     * Página principal do dashboard
     */
    public function index() {
        // Verificar se está logado
        $this->authController->requireLogin();
        
        // Obter dados para o dashboard
        $agendamentoModel = new Agendamento();
        $clienteModel = new Cliente();
        $usuarioModel = new Usuario();
        $servicoModel = new Servico();
        $financeiroModel = new Financeiro();
        
        // Estatísticas gerais
        $hoje = date('Y-m-d');
        $mesAtual = date('Y-m-01');
        $fimMes = date('Y-m-t');
        
        $dados = [
            // Agendamentos de hoje
            'agendamentos_hoje' => $agendamentoModel->getAgendamentosDoDia($hoje),
            
            // Próximos agendamentos
            'proximos_agendamentos' => $agendamentoModel->getProximosAgendamentos(5),
            
            // Estatísticas do mês
            'receita_mes' => $financeiroModel->getReceitaTotal($mesAtual, $fimMes),
            
            // Contadores
            'total_clientes' => $clienteModel->count(),
            'total_barbeiros' => count($usuarioModel->getBarbeiros()),
            'total_servicos' => $servicoModel->count(),
            
            // Estatísticas de cancelamentos (mês atual)
            'agendamentos_status' => $financeiroModel->getEstatisticasCancelamentos($mesAtual, $fimMes),
            
            // Top clientes do mês
            'top_clientes' => $financeiroModel->getTopClientes(5, $mesAtual, $fimMes),
            
            // Serviços mais agendados
            'servicos_populares' => $servicoModel->getMaisAgendados(5),
            
            // Receita por barbeiro (mês atual)
            'receita_barbeiros' => $financeiroModel->getReceitaPorBarbeiro($mesAtual, $fimMes),
            
            // Dados do usuário logado
            'usuario_logado' => $this->authController->getCurrentUser()
        ];
        
        // Se for barbeiro, mostrar apenas seus agendamentos
        if ($dados['usuario_logado']['tipo'] === 'barbeiro') {
            $dados['agendamentos_hoje'] = $agendamentoModel->getAgendamentosDoDia($hoje, $dados['usuario_logado']['id']);
            $dados['meus_agendamentos_semana'] = $agendamentoModel->getAgendaBarbeiro(
                $dados['usuario_logado']['id'], 
                $hoje, 
                date('Y-m-d', strtotime('+7 days'))
            );
        }
        
        include __DIR__ . '/../views/dashboard/index.php';
    }
}
?>
