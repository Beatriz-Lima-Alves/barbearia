<?php
$title = 'Dashboard - Sistema de Barbearia';
$currentPage = 'dashboard';

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="fas fa-chart-line me-2"></i>
        Dashboard
    </h1>
    <div class="text-white-50">
        <i class="fas fa-calendar me-1"></i>
        <?= date('d/m/Y H:i') ?>
    </div>
</div>

<!-- Cards de Estatísticas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Agendamentos Hoje</div>
                    <div class="h4 mb-0"><?= count($dados['agendamentos_hoje']) ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users fa-2x text-success"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Total Clientes</div>
                    <div class="h4 mb-0"><?= $dados['total_clientes'] ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-dollar-sign fa-2x text-warning"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Receita do Mês</div>
                    <div class="h4 mb-0">R$ <?= number_format($dados['receita_mes']['receita_total'] ?? 0, 2, ',', '.') ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-scissors fa-2x text-info"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Total Serviços</div>
                    <div class="h4 mb-0"><?= $dados['total_servicos'] ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Agendamentos de Hoje -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-day me-2"></i>
                    Agendamentos de Hoje
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($dados['agendamentos_hoje'])): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum agendamento para hoje</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Horário</th>
                                    <th>Cliente</th>
                                    <th>Serviço</th>
                                    <th>Barbeiro</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dados['agendamentos_hoje'] as $agendamento): ?>
                                <tr>
                                    <td>
                                        <strong><?= date('H:i', strtotime($agendamento['hora_agendamento'])) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($agendamento['cliente_nome']) ?></td>
                                    <td><?= htmlspecialchars($agendamento['servico_nome']) ?></td>
                                    <td><?= htmlspecialchars($agendamento['barbeiro_nome']) ?></td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'agendado' => 'primary',
                                            'em_andamento' => 'warning',
                                            'concluido' => 'success',
                                            'cancelado' => 'danger'
                                        ];
                                        $class = $statusClass[$agendamento['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $class ?>"><?= ucfirst(str_replace('_', ' ', $agendamento['status'])) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= SITE_URL ?>/agendamentos/show/<?= $agendamento['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Próximos Agendamentos -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Próximos Agendamentos
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($dados['proximos_agendamentos'])): ?>
                    <div class="text-center py-3">
                        <i class="fas fa-calendar-plus fa-2x text-muted mb-2"></i>
                        <p class="text-muted small">Nenhum agendamento futuro</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($dados['proximos_agendamentos'] as $agendamento): ?>
                    <div class="d-flex align-items-center mb-3 p-2 border-start border-primary border-3">
                        <div class="flex-grow-1">
                            <div class="fw-bold"><?= htmlspecialchars($agendamento['cliente_nome']) ?></div>
                            <div class="small text-muted">
                                <?= date('d/m/Y H:i', strtotime($agendamento['data_agendamento'] . ' ' . $agendamento['hora_agendamento'])) ?> - 
                                <?= htmlspecialchars($agendamento['servico_nome']) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <div class="text-center mt-3">
                    <a href="<?= SITE_URL ?>/agendamentos" class="btn btn-primary btn-sm">
                        Ver Todos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Segunda linha de cards -->
<div class="row">
    <!-- Top Clientes -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2"></i>
                    Top Clientes do Mês
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($dados['top_clientes'])): ?>
                    <p class="text-muted">Nenhum dado disponível</p>
                <?php else: ?>
                    <?php foreach ($dados['top_clientes'] as $index => $cliente): ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="badge bg-primary rounded-pill me-3"><?= $index + 1 ?>º</div>
                        <div class="flex-grow-1">
                            <div class="fw-bold"><?= htmlspecialchars($cliente['cliente']) ?></div>
                            <div class="small text-muted">
                                <?= $cliente['total_agendamentos'] ?> agendamentos - 
                                R$ <?= number_format($cliente['total_gasto'], 2, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Serviços Populares -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-fire me-2"></i>
                    Serviços Mais Populares
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($dados['servicos_populares'])): ?>
                    <p class="text-muted">Nenhum dado disponível</p>
                <?php else: ?>
                    <?php foreach ($dados['servicos_populares'] as $index => $servico): ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="badge bg-success rounded-pill me-3"><?= $index + 1 ?>º</div>
                        <div class="flex-grow-1">
                            <div class="fw-bold"><?= htmlspecialchars($servico['nome']) ?></div>
                            <div class="small text-muted">
                                <?= $servico['total_agendamentos'] ?> agendamentos este mês
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">
                                R$ <?= number_format($servico['preco'], 2, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($dados['usuario_logado']['tipo'] === 'barbeiro' && !empty($dados['meus_agendamentos_semana'])): ?>
<!-- Agenda do Barbeiro -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-week me-2"></i>
                    Minha Agenda da Semana
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Cliente</th>
                                <th>Serviço</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dados['meus_agendamentos_semana'] as $agendamento): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($agendamento['data_hora'])) ?></td>
                                <td><?= date('H:i', strtotime($agendamento['data_hora'])) ?></td>
                                <td><?= htmlspecialchars($agendamento['cliente_nome']) ?></td>
                                <td><?= htmlspecialchars($agendamento['servico_nome']) ?></td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'agendado' => 'primary',
                                        'em_andamento' => 'warning',
                                        'concluido' => 'success',
                                        'cancelado' => 'danger'
                                    ];
                                    $class = $statusClass[$agendamento['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $class ?>"><?= ucfirst(str_replace('_', ' ', $agendamento['status'])) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
