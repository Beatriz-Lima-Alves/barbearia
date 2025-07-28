<?php
require_once 'config/database.php';
require_once 'app/models/Usuario.php';
require_once 'app/models/Cliente.php';
require_once 'app/models/Servico.php';
require_once 'app/models/Agendamento.php';
require_once 'app/models/Financeiro.php';

echo "<h2>Teste dos Models para Dashboard</h2>";

try {
    // Testar cada model
    echo "<h3>1. Testando Usuario Model</h3>";
    $usuarioModel = new Usuario();
    $barbeiros = $usuarioModel->getBarbeiros();
    echo "<p>✅ UsuarioModel funcionando - " . count($barbeiros) . " barbeiros encontrados</p>";
    
    echo "<h3>2. Testando Cliente Model</h3>";
    $clienteModel = new Cliente();
    $totalClientes = $clienteModel->count();
    echo "<p>✅ ClienteModel funcionando - $totalClientes clientes encontrados</p>";
    
    echo "<h3>3. Testando Servico Model</h3>";
    $servicoModel = new Servico();
    $totalServicos = $servicoModel->count();
    echo "<p>✅ ServicoModel funcionando - $totalServicos serviços encontrados</p>";
    
    echo "<h3>4. Testando Agendamento Model</h3>";
    $agendamentoModel = new Agendamento();
    $hoje = date('Y-m-d');
    $agendamentosHoje = $agendamentoModel->getAgendamentosDoDia($hoje);
    echo "<p>✅ AgendamentoModel funcionando - " . count($agendamentosHoje) . " agendamentos hoje</p>";
    
    $proximosAgendamentos = $agendamentoModel->getProximosAgendamentos(5);
    echo "<p>✅ Próximos agendamentos: " . count($proximosAgendamentos) . " encontrados</p>";
    
    echo "<h3>5. Testando Financeiro Model</h3>";
    $financeiroModel = new Financeiro();
    $mesAtual = date('Y-m-01');
    $fimMes = date('Y-m-t');
    
    $receitaMes = $financeiroModel->getReceitaTotal($mesAtual, $fimMes);
    echo "<p>✅ FinanceiroModel funcionando - Receita do mês: R$ " . number_format($receitaMes['receita_total'] ?? 0, 2, ',', '.') . "</p>";
    
    $estatisticasCancelamentos = $financeiroModel->getEstatisticasCancelamentos($mesAtual, $fimMes);
    echo "<p>✅ Estatísticas de cancelamentos obtidas</p>";
    
    $topClientes = $financeiroModel->getTopClientes(5, $mesAtual, $fimMes);
    echo "<p>✅ Top clientes: " . count($topClientes) . " encontrados</p>";
    
    $receitaBarbeiros = $financeiroModel->getReceitaPorBarbeiro($mesAtual, $fimMes);
    echo "<p>✅ Receita por barbeiro: " . count($receitaBarbeiros) . " barbeiros com receita</p>";
    
    echo "<h3>✅ Todos os models estão funcionando!</h3>";
    echo "<p style='color: green; font-weight: bold;'>O dashboard deve funcionar agora. Tente fazer login novamente!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>

