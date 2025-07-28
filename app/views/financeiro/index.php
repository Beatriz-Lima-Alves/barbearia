<?php
$title = 'Relatórios Financeiros - Sistema de Barbearia';
$currentPage = 'financeiro';

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="fas fa-chart-line me-2"></i>
        Relatórios Financeiros
    </h1>
    <div>
        <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#filtroModal">
            <i class="fas fa-filter me-1"></i>
            Filtros
        </button>
        <button class="btn btn-success" onclick="exportarRelatorio()">
            <i class="fas fa-download me-1"></i>
            Exportar
        </button>
    </div>
</div>

<!-- Cards de Estatísticas Financeiras -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card border-start-success">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-dollar-sign fa-2x text-success"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Receita Total</div>
                    <div class="h4 mb-0 text-success">
                        R$ <?= number_format($relatorio['receita_total']['receita_total'] ?? 0, 2, ',', '.') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card border-start-primary">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Agendamentos</div>
                    <div class="h4 mb-0"><?= $relatorio['receita_total']['total_agendamentos'] ?? 0 ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card border-start-info">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chart-bar fa-2x text-info"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Ticket Médio</div>
                    <div class="h4 mb-0">
                        R$ <?= number_format($relatorio['receita_total']['ticket_medio'] ?? 0, 2, ',', '.') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card border-start-warning">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-percentage fa-2x text-warning"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Taxa Conversão</div>
                    <div class="h4 mb-0"><?= number_format($relatorio['taxa_conversao'] ?? 0, 1) ?>%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Gráfico de Receita -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-area me-2"></i>
                    Receita por Período
                </h5>
            </div>
            <div class="card-body" style="height: 400px; position: relative;">
                <canvas id="receitaChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Barbeiros -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy me-2"></i>
                    Top Barbeiros
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($relatorio['top_barbeiros'])): ?>
                    <?php foreach ($relatorio['top_barbeiros'] as $index => $barbeiro): ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="position-badge bg-<?= $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'info') ?> text-white me-3">
                            <?= $index + 1 ?>º
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold"><?= htmlspecialchars($barbeiro['nome']) ?></div>
                            <div class="small text-muted">
                                <?= $barbeiro['total_agendamentos'] ?> agendamentos
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">
                                R$ <?= number_format($barbeiro['receita'], 2, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">Nenhum dado disponível</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Serviços Mais Vendidos -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-scissors me-2"></i>
                    Serviços Mais Vendidos
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($receita_servicos)): ?>
                    <?php foreach ($receita_servicos as $servico): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($servico['servico']) ?></div>
                            <div class="small text-muted"><?= $servico['total_agendamentos'] ?> agendamentos</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">R$ <?= number_format($servico['receita'], 2, ',', '.') ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">Nenhum serviço encontrado</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Clientes Top -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    Top Clientes
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($top_clientes)): ?>
                    <?php foreach ($top_clientes as $cliente): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($cliente['cliente']) ?></div>
                            <div class="small text-muted"><?= $cliente['total_agendamentos'] ?> agendamentos</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">R$ <?= number_format($cliente['total_gasto'], 2, ',', '.') ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">Nenhum cliente encontrado</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas Detalhadas -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Resumo do Período
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-primary"><?= $cancelamentos['agendados'] ?? 0 ?></div>
                            <div class="small text-muted">Agendados</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-success"><?= $cancelamentos['realizados'] ?? 0 ?></div>
                            <div class="small text-muted">Realizados</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-danger"><?= $cancelamentos['cancelados'] ?? 0 ?></div>
                            <div class="small text-muted">Cancelados</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-warning"><?= number_format($cancelamentos['percentual_realizados'] ?? 0, 1) ?>%</div>
                            <div class="small text-muted">Taxa Sucesso</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Filtros -->
<div class="modal fade" id="filtroModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtrar Relatório</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Data Início</label>
                            <input type="date" name="data_inicio" class="form-control" 
                                   value="<?= $_GET['data_inicio'] ?? date('Y-m-01') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data Fim</label>
                            <input type="date" name="data_fim" class="form-control" 
                                   value="<?= $_GET['data_fim'] ?? date('Y-m-t') ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Barbeiro</label>
                            <select name="barbeiro_id" class="form-select">
                                <option value="">Todos</option>
                                <?php if (isset($receita_barbeiros)): ?>
                                    <?php foreach ($receita_barbeiros as $barbeiro): ?>
                                        <option value="<?= $barbeiro['id'] ?>" 
                                                <?= ($_GET['barbeiro_id'] ?? '') == $barbeiro['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($barbeiro['barbeiro']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Serviço</label>
                            <select name="servico_id" class="form-select">
                                <option value="">Todos</option>
                                <?php if (isset($receita_servicos)): ?>
                                    <?php foreach ($receita_servicos as $servico): ?>
                                        <option value="<?= $servico['id'] ?>" 
                                                <?= ($_GET['servico_id'] ?? '') == $servico['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($servico['servico']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <a href="<?= SITE_URL ?>/financeiro" class="btn btn-outline-warning">Limpar</a>
                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.border-start-success {
    border-left: 4px solid #198754 !important;
}

.border-start-primary {
    border-left: 4px solid #0d6efd !important;
}

.border-start-info {
    border-left: 4px solid #0dcaf0 !important;
}

.border-start-warning {
    border-left: 4px solid #ffc107 !important;
}

.position-badge {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
}
</style>

<!-- CDN do Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Verificar se Chart.js foi carregado
if (typeof Chart === 'undefined') {
    console.error('Chart.js não foi carregado!');
    document.getElementById('receitaChart').innerHTML = '<p class="text-center text-muted">Erro ao carregar gráfico</p>';
} else {
    // Dados do gráfico vindos do PHP
    const receitaLabels = <?= json_encode($relatorio['grafico_receita']['labels'] ?? []) ?>;
    const receitaDataPHP = <?= json_encode($relatorio['grafico_receita']['data'] ?? []) ?>;
    
    // Usar sempre os dados vindos do PHP (corrigidos)
    let finalLabels = receitaLabels;
    let finalData = receitaDataPHP;
    
    // Se não houver dados do backend, criar labels básicos
    if (finalLabels.length === 0) {
        finalLabels = [];
        finalData = [];
        
        // Gerar últimos 7 dias apenas se não houver dados do backend
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            finalLabels.push(date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' }));
            finalData.push(0); // Zeros se não houver dados
        }
    }
    
    console.log('Dados recebidos do PHP:', {
        labels: receitaLabels,
        data: receitaDataPHP
    });
    
    console.log('Labels do gráfico:', finalLabels);
    console.log('Dados do gráfico:', finalData);
    
    // Criar o gráfico
    try {
        const receitaCtx = document.getElementById('receitaChart');
        if (!receitaCtx) {
            console.error('Canvas receitaChart não encontrado!');
        } else {
            const ctx = receitaCtx.getContext('2d');
            
            const receitaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: finalLabels,
                    datasets: [{
                        label: 'Receita (R$)',
                        data: finalData,
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#198754',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Receita: R$ ' + context.parsed.y.toFixed(2).replace('.', ',');
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Período'
                            }
                        },
                        y: {
                            display: true,
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Receita (R$)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + value.toFixed(2).replace('.', ',');
                                }
                            }
                        }
                    }
                }
            });
            
            console.log('Gráfico criado com sucesso!', receitaChart);
        }
    } catch (error) {
        console.error('Erro ao criar gráfico:', error);
        document.getElementById('receitaChart').innerHTML = '<p class="text-center text-muted">Erro ao criar gráfico: ' + error.message + '</p>';
    }
}

function exportarRelatorio() {
    const params = new URLSearchParams(window.location.search);
    params.append('export', 'csv');
    window.open(`<?= SITE_URL ?>/financeiro/exportarCSV?${params.toString()}`, '_blank');
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
