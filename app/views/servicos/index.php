<?php
$title = 'Serviços - Sistema de Barbearia';
$currentPage = 'servicos';

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="fas fa-scissors me-2"></i>
        Serviços
    </h1>
    <a href="<?= SITE_URL ?>/servicos/create" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>
        Novo Serviço
    </a>
</div>

<!-- Cards de Estatísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-scissors fa-2x text-primary"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Total Serviços</div>
                    <div class="h4 mb-0"><?= count($servicos) ?></div>
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
                    <div class="small text-muted">Serviços Ativos</div>
                    <div class="h4 mb-0"><?= count(array_filter($servicos, fn($s) => $s['ativo'])) ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-dollar-sign fa-2x text-warning"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Preço Médio</div>
                    <div class="h4 mb-0">
                        R$ <?= count($servicos) > 0 ? number_format(array_sum(array_column($servicos, 'preco')) / count($servicos), 2, ',', '.') : '0,00' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock fa-2x text-info"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="small text-muted">Duração Média</div>
                    <div class="h4 mb-0">
                        <?= count($servicos) > 0 ? round(array_sum(array_column($servicos, 'duracao')) / count($servicos)) : 0 ?>min
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grid de Serviços -->
<div class="row">
    <?php if (empty($servicos)): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-scissors fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum serviço cadastrado</h5>
                    <p class="text-muted">Comece cadastrando os serviços da sua barbearia</p>
                    <a href="<?= SITE_URL ?>/servicos/create" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Cadastrar Primeiro Serviço
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($servicos as $servico): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 servico-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="servico-icon bg-primary text-white">
                            <i class="fas fa-scissors"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                    data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="<?= SITE_URL ?>/servicos/edit/<?= $servico['id'] ?>">
                                        <i class="fas fa-edit me-2"></i>Editar
                                    </a>
                                </li>
                                <li>
                                    <button class="dropdown-item text-danger" 
                                            onclick="confirmarExclusao(<?= $servico['id'] ?>)">
                                        <i class="fas fa-trash me-2"></i>Excluir
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item" 
                                            onclick="toggleStatus(<?= $servico['id'] ?>, <?= $servico['ativo'] ? 'false' : 'true' ?>)">
                                        <i class="fas fa-<?= $servico['ativo'] ? 'pause' : 'play' ?> me-2"></i>
                                        <?= $servico['ativo'] ? 'Desativar' : 'Ativar' ?>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <h5 class="card-title"><?= htmlspecialchars($servico['nome']) ?></h5>
                    
                    <?php if (!empty($servico['descricao'])): ?>
                        <p class="card-text text-muted">
                            <?= htmlspecialchars(substr($servico['descricao'], 0, 100)) ?>
                            <?= strlen($servico['descricao']) > 100 ? '...' : '' ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="small text-muted">Preço</div>
                            <div class="h5 text-success mb-0">
                                R$ <?= number_format($servico['preco'], 2, ',', '.') ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted">Duração</div>
                            <div class="h5 text-primary mb-0">
                                <?= $servico['duracao'] ?>min
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <?php if ($servico['ativo']): ?>
                            <span class="badge bg-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inativo</span>
                        <?php endif; ?>
                        
                        <div>
                            <span class="badge bg-info">
                                <i class="fas fa-calendar-check me-1"></i>
                                <?= $servico['total_agendamentos'] ?? 0 ?> agendamentos
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="<?= SITE_URL ?>/servicos/show/<?= $servico['id'] ?>" 
                           class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="fas fa-eye me-1"></i>
                            Detalhes
                        </a>
                        <a href="<?= SITE_URL ?>/agendamentos/create?servico_id=<?= $servico['id'] ?>" 
                           class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-calendar-plus me-1"></i>
                            Agendar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.servico-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.servico-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.servico-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
</style>

<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir este serviço? Esta ação não pode ser desfeita.')) {
        fetch(`<?= SITE_URL ?>/servicos/delete/${id}`, {
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
                alert('Erro ao excluir serviço: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erro ao excluir serviço');
            console.error(error);
        });
    }
}

function toggleStatus(id, novoStatus) {
    const acao = novoStatus ? 'ativar' : 'desativar';
    
    if (confirm(`Tem certeza que deseja ${acao} este serviço?`)) {
        fetch(`<?= SITE_URL ?>/servicos/toggle-status/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ativo: novoStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao atualizar status: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erro ao atualizar status');
            console.error(error);
        });
    }
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
