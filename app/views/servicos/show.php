<?php
/**
 * VIEW DE DETALHES DO SERVIÇO
 * Salvar como: app/views/servicos/show.php
 */

$title = 'Detalhes do Serviço - ' . ($servico['nome'] ?? 'Serviço');
$currentPage = 'servicos';

ob_start();
?>

<div class="container-fluid px-4">
    <!-- Header com navegação -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item">
                        <a href="<?= SITE_URL ?>/dashboard">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= SITE_URL ?>/servicos">Serviços</a>
                    </li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($servico['nome']) ?></li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-cut me-2"></i>
                <?= htmlspecialchars($servico['nome']) ?>
            </h1>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= SITE_URL ?>/agendamentos/create?servico_id=<?= $servico['id'] ?>" class="btn btn-success">
                <i class="fas fa-calendar-plus me-1"></i>Agendar
            </a>
            <?php if ($_SESSION['user_tipo'] === 'administrador'): ?>
                <a href="<?= SITE_URL ?>/servicos/edit/<?= $servico['id'] ?>" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>Editar
                </a>
            <?php endif; ?>
            <a href="<?= SITE_URL ?>/servicos" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Voltar
            </a>
        </div>
    </div>

    <!-- Mensagens -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row">
        <!-- Informações principais do serviço -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informações do Serviço
                    </h6>
                    <div class="d-flex align-items-center">
                        <?php
                        $categoria_icons = [
                            'corte' => 'fas fa-cut text-primary',
                            'barba' => 'fas fa-user-tie text-success',
                            'combo' => 'fas fa-star text-warning',
                            'especial' => 'fas fa-crown text-danger',
                            'tratamento' => 'fas fa-spa text-info'
                        ];
                        $icon = $categoria_icons[$servico['categoria']] ?? 'fas fa-cut text-primary';
                        ?>
                        <i class="<?= $icon ?> me-2"></i>
                        <span class="badge bg-light text-dark text-capitalize">
                            <?= htmlspecialchars($servico['categoria']) ?>
                        </span>
                        <?php if ($servico['ativo']): ?>
                            <span class="badge bg-success ms-2">Ativo</span>
                        <?php else: ?>
                            <span class="badge bg-danger ms-2">Inativo</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-group mb-3">
                                <label class="text-muted small fw-bold text-uppercase">Nome do Serviço</label>
                                <div class="h5 mb-0"><?= htmlspecialchars($servico['nome']) ?></div>
                            </div>
                            
                            <div class="info-group mb-3">
                                <label class="text-muted small fw-bold text-uppercase">Categoria</label>
                                <div class="text-capitalize"><?= htmlspecialchars($servico['categoria']) ?></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group mb-3">
                                <label class="text-muted small fw-bold text-uppercase">Preço</label>
                                <div class="h4 text-success mb-0">
                                    R$ <?= number_format($servico['preco'], 2, ',', '.') ?>
                                </div>
                            </div>
                            
                            <div class="info-group mb-3">
                                <label class="text-muted small fw-bold text-uppercase">Duração</label>
                                <div class="h5 text-primary mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    <?= $servico['duracao'] ?> minutos
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-group mb-3">
                        <label class="text-muted small fw-bold text-uppercase">Descrição</label>
                        <div class="p-3 bg-light rounded">
                            <?= nl2br(htmlspecialchars($servico['descricao'])) ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="text-muted small fw-bold text-uppercase">Data de Criação</label>
                                <div>
                                    <i class="fas fa-calendar me-1"></i>
                                    <?= date('d/m/Y H:i', strtotime($servico['data_criacao'])) ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($servico['data_atualizacao']): ?>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="text-muted small fw-bold text-uppercase">Última Atualização</label>
                                <div>
                                    <i class="fas fa-clock me-1"></i>
                                    <?= date('d/m/Y H:i', strtotime($servico['data_atualizacao'])) ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Últimos Agendamentos -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Últimos Agendamentos
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($agendamentos_recentes)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Nenhum agendamento encontrado para este serviço</p>
                            <a href="<?= SITE_URL ?>/agendamentos/create?servico_id=<?= $servico['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-1"></i>Fazer Primeiro Agendamento
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Data/Hora</th>
                                        <th>Cliente</th>
                                        <th>Barbeiro</th>
                                        <th>Status</th>
                                        <th>Valor</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($agendamentos_recentes as $agendamento): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold">
                                                <?= date('d/m/Y', strtotime($agendamento['data_agendamento'])) ?>
                                            </div>
                                            <small class="text-muted">
                                                <?= date('H:i', strtotime($agendamento['hora_agendamento'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($agendamento['cliente_nome'] ?? 'N/A') ?></div>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($agendamento['barbeiro_nome'] ?? 'N/A') ?></div>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'agendado' => 'primary',
                                                'confirmado' => 'info',
                                                'em_andamento' => 'warning',
                                                'concluido' => 'success',
                                                'cancelado' => 'danger',
                                                'nao_compareceu' => 'secondary'
                                            ];
                                            $class = $statusClass[$agendamento['status']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $class ?>">
                                                <?= ucfirst(str_replace('_', ' ', $agendamento['status'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (isset($agendamento['preco_final']) && $agendamento['preco_final'] > 0): ?>
                                                <span class="text-success fw-bold">
                                                    R$ <?= number_format($agendamento['preco_final'], 2, ',', '.') ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= SITE_URL ?>/agendamentos/show/<?= $agendamento['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="<?= SITE_URL ?>/agendamentos?servico_id=<?= $servico['id'] ?>" class="btn btn-outline-primary">
                                <i class="fas fa-list me-1"></i>Ver Todos os Agendamentos
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Sidebar com estatísticas -->
        <div class="col-lg-4">
            <!-- Estatísticas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-chart-bar me-2"></i>Estatísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <div class="h3 text-primary mb-1">
                                    <?= $estatisticas['total_agendamentos'] ?? 0 ?>
                                </div>
                                <small class="text-muted">Total de Agendamentos</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h3 text-success mb-1">
                                <?= $estatisticas['agendamentos_concluidos'] ?? 0 ?>
                            </div>
                            <small class="text-muted">Concluídos</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <div class="h4 text-success mb-1">
                                    R$ <?= number_format($estatisticas['receita_total'] ?? 0, 2, ',', '.') ?>
                                </div>
                                <small class="text-muted">Receita Total</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <?php if (isset($estatisticas['avaliacao_media']) && $estatisticas['avaliacao_media'] > 0): ?>
                                <div class="h4 text-warning mb-1">
                                    <i class="fas fa-star"></i>
                                    <?= number_format($estatisticas['avaliacao_media'], 1) ?>
                                </div>
                                <small class="text-muted">Avaliação Média</small>
                            <?php else: ?>
                                <div class="h4 text-muted mb-1">-</div>
                                <small class="text-muted">Sem Avaliações</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($estatisticas['total_agendamentos'] > 0): ?>
                    <hr>
                    <div class="progress mb-2">
                        <?php 
                        $taxa_conclusao = $estatisticas['total_agendamentos'] > 0 
                            ? ($estatisticas['agendamentos_concluidos'] / $estatisticas['total_agendamentos']) * 100 
                            : 0;
                        ?>
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: <?= $taxa_conclusao ?>%" 
                             aria-valuenow="<?= $taxa_conclusao ?>" 
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted">
                        Taxa de Conclusão: <?= number_format($taxa_conclusao, 1) ?>%
                    </small>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Ações rápidas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-bolt me-2"></i>Ações Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= SITE_URL ?>/agendamentos/create?servico_id=<?= $servico['id'] ?>" 
                           class="btn btn-success">
                            <i class="fas fa-calendar-plus me-1"></i>
                            Novo Agendamento
                        </a>
                        
                        <?php if ($_SESSION['user_tipo'] === 'administrador'): ?>
                            <a href="<?= SITE_URL ?>/servicos/edit/<?= $servico['id'] ?>" 
                               class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>
                                Editar Serviço
                            </a>
                            
                            <button type="button" class="btn btn-outline-warning" 
                                    onclick="duplicarServico(<?= $servico['id'] ?>, '<?= addslashes($servico['nome']) ?>')">
                                <i class="fas fa-copy me-1"></i>
                                Duplicar Serviço
                            </button>
                            
                            <?php if ($servico['ativo']): ?>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="confirmarDesativacao(<?= $servico['id'] ?>, '<?= addslashes($servico['nome']) ?>')">
                                    <i class="fas fa-power-off me-1"></i>
                                    Desativar Serviço
                                </button>
                            <?php else: ?>
                                <a href="<?= SITE_URL ?>/servicos/reactive/<?= $servico['id'] ?>" 
                                   class="btn btn-outline-success">
                                    <i class="fas fa-power-off me-1"></i>
                                    Reativar Serviço
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Informações adicionais -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-info-circle me-2"></i>Informações
                    </h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span>ID do Serviço:</span>
                            <span class="fw-bold"><?= $servico['id'] ?></span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Status:</span>
                            <span class="fw-bold <?= $servico['ativo'] ? 'text-success' : 'text-danger' ?>">
                                <?= $servico['ativo'] ? 'Ativo' : 'Inativo' ?>
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Criado em:</span>
                            <span><?= date('d/m/Y', strtotime($servico['data_criacao'])) ?></span>
                        </div>
                        
                        <?php if ($servico['data_atualizacao']): ?>
                        <div class="d-flex justify-content-between">
                            <span>Atualizado em:</span>
                            <span><?= date('d/m/Y', strtotime($servico['data_atualizacao'])) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Função para confirmar desativação
function confirmarDesativacao(id, nome) {
    const confirmacao = confirm(
        `Tem certeza que deseja DESATIVAR o serviço "${nome}"?\n\n` +
        `⚠️ ATENÇÃO:\n` +
        `• Esta ação irá desativar o serviço\n` +
        `• Não será possível fazer novos agendamentos\n` +
        `• Os dados serão mantidos no sistema\n` +
        `• A ação pode ser revertida por um administrador\n\n` +
        `Confirma a desativação?`
    );
    
    if (confirmacao) {
        // Mostrar loading
        const loadingMsg = document.createElement('div');
        loadingMsg.className = 'alert alert-info';
        loadingMsg.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Desativando serviço...';
        document.querySelector('.container-fluid').insertBefore(loadingMsg, document.querySelector('.container-fluid').firstChild);
        
        // Redirecionar para deletar
        window.location.href = `<?= SITE_URL ?>/servicos/reactive/${id}`;
    }
}

// Função para duplicar serviço
function duplicarServico(id, nome) {
    const novoNome = prompt(
        `Duplicar serviço "${nome}"\n\n` +
        `Digite o nome para o novo serviço:`,
        `${nome} - Cópia`
    );
    
    if (novoNome && novoNome.trim()) {
        // Criar formulário para enviar dados
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= SITE_URL ?>/servicos/duplicate/${id}`;
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'novo_nome';
        input.value = novoNome.trim();
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php
$content = ob_get_clean();

// Incluir o layout principal se existir
$layoutPath = __DIR__ . '/../layouts/main.php';
if (file_exists($layoutPath)) {
    include $layoutPath;
} else {
    // Layout básico se não existir o main.php
    echo "<!DOCTYPE html>";
    echo "<html><head><title>{$title}</title>";
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">';
    echo "</head><body>";
    echo $content;
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>';
    echo "</body></html>";
}
?>
