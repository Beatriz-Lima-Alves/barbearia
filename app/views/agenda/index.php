
<?php
/**
 * VIEW DA AGENDA CORRIGIDA
 * Substitua o conteúdo da sua view agenda/index.php
 */

$title = 'Agenda';
$currentPage = 'agenda';

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="fas fa-calendar-alt me-2"></i>
        Agenda
    </h1>
    <div>
        <button class="btn btn-outline-primary me-2" data-bs-toggle="collapse" data-bs-target="#filtrosCollapse">
            <i class="fas fa-filter me-1"></i>
            Filtros
        </button>
        <div class="btn-group me-2">
            <a href="?<?= http_build_query(array_merge($filtros, ['view' => 'geral'])) ?>" 
               class="btn btn-<?= $filtros['view'] == 'geral' ? 'primary' : 'outline-primary' ?> btn-sm">
                Geral
            </a>
            <a href="?<?= http_build_query(array_merge($filtros, ['view' => 'semana'])) ?>" 
               class="btn btn-<?= $filtros['view'] == 'semana' ? 'primary' : 'outline-primary' ?> btn-sm">
                Semana
            </a>
            <a href="?<?= http_build_query(array_merge($filtros, ['view' => 'mes'])) ?>" 
               class="btn btn-<?= $filtros['view'] == 'mes' ? 'primary' : 'outline-primary' ?> btn-sm">
                Mês
            </a>
        </div>
        <a href="<?= SITE_URL ?>/agendamentos/novo" class="btn btn-success">
            <i class="fas fa-plus me-1"></i>
            Novo
        </a>
    </div>
</div>

<!-- Filtros Colapsáveis -->
<div class="collapse mb-4" id="filtrosCollapse">
    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="view" value="<?= $filtros['view'] ?>">
                
                <div class="col-md-3">
                    <label class="form-label">Barbeiro</label>
                    <select name="barbeiro_id" class="form-select">
                        <option value="">Todos os barbeiros</option>
                        <?php foreach ($barbeiros as $barbeiro): ?>
                            <option value="<?= $barbeiro['id'] ?>" 
                                    <?= $filtros['barbeiro_id'] == $barbeiro['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($barbeiro['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Data Início</label>
                    <input type="date" name="data_inicio" class="form-control" 
                           value="<?= $filtros['data_inicio'] ?>">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Data Fim</label>
                    <input type="date" name="data_fim" class="form-control" 
                           value="<?= $filtros['data_fim'] ?>">
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>
                        Filtrar
                    </button>
                    <a href="<?= SITE_URL ?>/agenda" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>
                        Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cards de Estatísticas CORRIGIDOS -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card bg-primary text-white">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-day fa-2x"></i>
                </div>
                <div class="flex-grow-1 ms-3 text-end">
                    <div class="small opacity-75">AGENDAMENTOS HOJE</div>
                    <div class="h2 mb-0"><?= $contadores['hoje'] ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card bg-warning text-white">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
                <div class="flex-grow-1 ms-3 text-end">
                    <div class="small opacity-75">AGENDADOS</div>
                    <div class="h2 mb-0"><?= $contadores['pendentes'] ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card bg-success text-white">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <div class="flex-grow-1 ms-3 text-end">
                    <div class="small opacity-75">REALIZADOS</div>
                    <div class="h2 mb-0"><?= $contadores['concluidos'] ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card bg-danger text-white">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle fa-2x"></i>
                </div>
                <div class="flex-grow-1 ms-3 text-end">
                    <div class="small opacity-75">CANCELADOS</div>
                    <div class="h2 mb-0"><?= $contadores['cancelados'] ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Agendamentos por Data -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Agendamentos - <?= $periodo['inicio'] ?> a <?= $periodo['fim'] ?>
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($agendamentosPorData)): ?>
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <p class="text-muted">Nenhum agendamento encontrado para o período selecionado.</p>
                <a href="<?= SITE_URL ?>/agendamentos/novo" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Criar Primeiro Agendamento
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($agendamentosPorData as $data => $agendamentosData): ?>
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-calendar me-2"></i>
                        <?= date('d/m/Y', strtotime($data)) ?> - 
                        <?php
                        $diaSemana = date('l', strtotime($data));
                        $diasSemana = [
                            'Monday' => 'Segunda-feira',
                            'Tuesday' => 'Terça-feira', 
                            'Wednesday' => 'Quarta-feira',
                            'Thursday' => 'Quinta-feira',
                            'Friday' => 'Sexta-feira',
                            'Saturday' => 'Sábado',
                            'Sunday' => 'Domingo'
                        ];
                        echo $diasSemana[$diaSemana] ?? $diaSemana;
                        ?>
                        <span class="badge bg-primary ms-2"><?= count($agendamentosData) ?> agendamento(s)</span>
                    </h5>
                    
                    <div class="row">
                        <?php foreach ($agendamentosData as $agendamento): ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-left-<?= $agendamento['status'] === 'agendado' ? 'info' : ($agendamento['status'] === 'realizado' ? 'success' : 'danger') ?>">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">
                                                <?= date('H:i', strtotime($agendamento['hora_agendamento'])) ?>
                                            </h6>
                                            <span class="badge bg-<?= $agendamento['status'] === 'agendado' ? 'info' : ($agendamento['status'] === 'realizado' ? 'success' : 'danger') ?>">
                                                <?= ucfirst($agendamento['status']) ?>
                                            </span>
                                        </div>
                                        
                                        <p class="card-text mb-1">
                                            <i class="fas fa-user me-1"></i>
                                            <strong><?= htmlspecialchars($agendamento['cliente_nome']) ?></strong>
                                        </p>
                                        
                                        <p class="card-text mb-1">
                                            <i class="fas fa-cut me-1"></i>
                                            <?= htmlspecialchars($agendamento['servico_nome']) ?>
                                        </p>
                                        
                                        <p class="card-text mb-1">
                                            <i class="fas fa-user-tie me-1"></i>
                                            <?= htmlspecialchars($agendamento['barbeiro_nome']) ?>
                                        </p>
                                        
                                        <?php if (!empty($agendamento['valor_cobrado']) || !empty($agendamento['servico_preco'])): ?>
                                            <p class="card-text mb-2">
                                                <i class="fas fa-dollar-sign me-1"></i>
                                                R$ <?= number_format($agendamento['valor_cobrado'] ?? $agendamento['servico_preco'], 2, ',', '.') ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <div class="d-flex gap-1">
                                            <a href="<?= SITE_URL ?>/agendamentos/show/<?= $agendamento['id'] ?>" 
                                               class="btn btn-sm btn-outline-info" title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <?php if ($agendamento['status'] === 'agendado'): ?>
                                                <a href="<?= SITE_URL ?>/agendamentos/edit/<?= $agendamento['id'] ?>" 
                                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
</style>



<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>