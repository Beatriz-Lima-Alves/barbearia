<?php
$title = 'Agendamentos - Sistema de Barbearia';
$currentPage = 'agendamentos';

ob_start();

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="fas fa-calendar-alt me-2"></i>
        Agendamentos
    </h1>
    <div>
        <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#filtroModal">
            <i class="fas fa-filter me-1"></i>
            Filtros
        </button>
        <a href="<?= SITE_URL ?>/agendamentos/create" class="btn btn-success">
            <i class="fas fa-plus me-1"></i>
            Novo Agendamento
        </a>
    </div>
</div>

<!-- Cards de Estatísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Hoje</div>
                    <div class="h4 mb-0"><?= $dados['estatisticas']['hoje'] ?? 0 ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Pendentes</div>
                    <div class="h4 mb-0"><?= $dados['estatisticas']['pendentes'] ?? 0 ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Concluídos</div>
                    <div class="h4 mb-0"><?= $dados['estatisticas']['concluidos'] ?? 0 ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle fa-2x text-danger"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Cancelados</div>
                    <div class="h4 mb-0"><?= $dados['estatisticas']['cancelados'] ?? 0 ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Agendamentos -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>
            Lista de Agendamentos
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($agendamentos)): ?>
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhum agendamento encontrado</h5>
                <p class="text-muted">Crie um novo agendamento para começar</p>
                <a href="<?= SITE_URL ?>/agendamentos/create" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Criar Agendamento
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Cliente</th>
                            <th>Serviço</th>
                            <th>Barbeiro</th>
                            <th>Status</th>
                            <th>Valor</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $agendamento): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= date('d/m/Y', strtotime($agendamento['data_agendamento'])) ?></div>
                                <small class="text-muted"><?= date('H:i', strtotime($agendamento['hora_agendamento'])) ?></small>
                            </td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($agendamento['cliente_nome']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($agendamento['cliente_telefone']) ?></small>
                            </td>
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
                                <span class="fw-bold text-success">
                                    R$ <?= number_format($agendamento['servico_preco'], 2, ',', '.') ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= SITE_URL ?>/agendamentos/show/<?= $agendamento['id'] ?>" 
                                       class="btn btn-outline-primary" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= SITE_URL ?>/agendamentos/edit/<?= $agendamento['id'] ?>" 
                                       class="btn btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-outline-danger" 
                                            onclick="confirmarExclusao(<?= $agendamento['id'] ?>)" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <?php if (isset($paginacao) && $paginacao['total_paginas'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($paginacao['pagina_atual'] > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?= $paginacao['pagina_atual'] - 1 ?>">Anterior</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $paginacao['total_paginas']; $i++): ?>
                        <li class="page-item <?= $i == $paginacao['pagina_atual'] ? 'active' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($paginacao['pagina_atual'] < $paginacao['total_paginas']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?= $paginacao['pagina_atual'] + 1 ?>">Próximo</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Filtros -->
<div class="modal fade" id="filtroModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtrar Agendamentos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Data Início</label>
                            <input type="date" name="data_inicio" class="form-control" 
                                   value="<?= $_GET['data_inicio'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data Fim</label>
                            <input type="date" name="data_fim" class="form-control" 
                                   value="<?= $_GET['data_fim'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="agendado" <?= ($_GET['status'] ?? '') == 'agendado' ? 'selected' : '' ?>>Agendado</option>
                                <option value="em_andamento" <?= ($_GET['status'] ?? '') == 'em_andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                <option value="concluido" <?= ($_GET['status'] ?? '') == 'concluido' ? 'selected' : '' ?>>Concluído</option>
                                <option value="cancelado" <?= ($_GET['status'] ?? '') == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Barbeiro</label>
                            <select name="barbeiro_id" class="form-select">
                                <option value="">Todos</option>
                                <?php if (isset($barbeiros)): ?>
                                    <?php foreach ($barbeiros as $barbeiro): ?>
                                        <option value="<?= $barbeiro['id'] ?>" 
                                                <?= ($_GET['barbeiro_id'] ?? '') == $barbeiro['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($barbeiro['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <a href="<?= SITE_URL ?>/agendamentos" class="btn btn-outline-warning">Limpar</a>
                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir este agendamento?')) {
        fetch(`<?= SITE_URL ?>/agendamentos/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao excluir agendamento: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erro ao excluir agendamento');
            console.error(error);
        });
    }
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
