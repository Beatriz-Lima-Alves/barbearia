<?php
/**
 * VIEW DE VISUALIZAÇÃO DE AGENDAMENTOS
 * Salvar como: app/views/agendamentos/show.php
 */

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$title = 'Detalhes do Agendamento - Sistema de Barbearia';
$currentPage = 'agendamentos';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Estilos customizados -->
    <style>
        :root {
            --primary-color: #1700e6ff;
            --secondary-color: #0c0d4eff;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --info-color: #3498db;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--accent-color);
            color: #fff;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            color: white;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 12px;
        }
        
        .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
        }
        
        .page-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }
        
        .btn-danger {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-agendado {
            background-color: #e3f2fd;
            color: #1976d2;
            border: 2px solid #1976d2;
        }
        
        .status-realizado {
            background-color: #e8f5e8;
            color: #2e7d32;
            border: 2px solid #2e7d32;
        }
        
        .status-cancelado {
            background-color: #ffebee;
            color: #c62828;
            border: 2px solid #c62828;
        }
        
        .info-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }
        
        .info-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        
        .info-value {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .info-value.large {
            font-size: 1.5rem;
        }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 0.25rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
        }
        
        .timeline-content {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .timeline-title {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }
        
        .timeline-text {
            color: #6c757d;
            font-size: 0.875rem;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            
            .main-content {
                padding: 15px;
            }
            
            .page-header {
                margin-bottom: 1rem;
                padding: 1.5rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .info-section {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
        }
        
        .contact-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        
        .contact-info a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="/barbearia-new/dashboard">
                <i class="fas fa-cut me-2"></i>
                Sistema Barbearia
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            <?= $_SESSION['user_nome'] ?? 'Usuário' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/barbearia-new/perfil">
                                <i class="fas fa-user me-2"></i>Meu Perfil
                            </a></li>
                          
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/barbearia-new/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Sair
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>" href="/barbearia-new/dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'agendamentos' ? 'active' : '' ?>" href="/barbearia-new/agendamentos">
                                <i class="fas fa-calendar-alt"></i>
                                Agendamentos
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'clientes' ? 'active' : '' ?>" href="/barbearia-new/clientes">
                                <i class="fas fa-users"></i>
                                Clientes
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'barbeiros' ? 'active' : '' ?>" href="/barbearia-new/usuarios">
                                <i class="fas fa-user-tie"></i>
                                Usuários
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'servicos' ? 'active' : '' ?>" href="/barbearia-new/servicos">
                                <i class="fas fa-scissors"></i>
                                Serviços
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'financeiro' ? 'active' : '' ?>" href="/barbearia-new/financeiro">
                                <i class="fas fa-chart-line"></i>
                                Financeiro
                            </a>
                        </li>
                        
                        <!-- <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'agenda' ? 'active' : '' ?>" href="/barbearia-new/agenda">
                                <i class="fas fa-calendar-week"></i>
                                Minha Agenda
                            </a>
                        </li> -->
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Mensagens -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Page Header -->
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h1><i class="fas fa-eye me-3"></i>Detalhes do Agendamento</h1>
                            <p>Informações completas do agendamento #<?= $agendamento['id'] ?></p>
                        </div>
                        <div class="d-flex gap-2 mt-2 mt-md-0">
                            <span class="status-badge status-<?= $agendamento['status'] ?>">
                                <?= ucfirst($agendamento['status']) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Informações Principais -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i>
                        Informações Principais
                    </div>
                    <div class="card-body">
                        <div class="info-section">
                            <div class="info-item">
                                <div class="info-label">Cliente</div>
                                <div class="info-value large"><?= e($agendamento['cliente_nome']) ?></div>
                                <?php if ($agendamento['cliente_telefone']): ?>
                                <div class="contact-info">
                                    <i class="fas fa-phone me-2"></i>
                                    <a href="tel:<?= $agendamento['cliente_telefone'] ?>">
                                        <?= e($agendamento['cliente_telefone']) ?>
                                    </a>
                                </div>
                                <?php endif; ?>
                                <?php if ($agendamento['cliente_email']): ?>
                                <div class="contact-info">
                                    <i class="fas fa-envelope me-2"></i>
                                    <a href="mailto:<?= $agendamento['cliente_email'] ?>">
                                        <?= e($agendamento['cliente_email']) ?>
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Barbeiro</div>
                                <div class="info-value large"><?= e($agendamento['barbeiro_nome']) ?></div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Serviço</div>
                                <div class="info-value"><?= e($agendamento['servico_nome']) ?></div>
                                <?php if ($agendamento['servico_duracao']): ?>
                                <div class="contact-info">
                                    <i class="fas fa-clock me-2"></i>
                                    Duração: <?= $agendamento['servico_duracao'] ?> minutos
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Data e Horário</div>
                                <div class="info-value">
                                    <?= date('d/m/Y', strtotime($agendamento['data_agendamento'])) ?>
                                </div>
                                <div class="info-value large">
                                    <?= date('H:i', strtotime($agendamento['hora_agendamento'])) ?>
                                </div>
                            </div>
                            
                            <?php if ($agendamento['valor_cobrado'] || $agendamento['servico_preco']): ?>
                            <div class="info-item">
                                <div class="info-label">Valor</div>
                                <?php if ($agendamento['valor_cobrado']): ?>
                                <div class="info-value large">
                                    R$ <?= number_format($agendamento['valor_cobrado'], 2, ',', '.') ?>
                                </div>
                                <?php if ($agendamento['servico_preco'] && $agendamento['valor_cobrado'] != $agendamento['servico_preco']): ?>
                                <div class="contact-info">
                                    Preço original: R$ <?= number_format($agendamento['servico_preco'], 2, ',', '.') ?>
                                </div>
                                <?php endif; ?>
                                <?php else: ?>
                                <div class="info-value">
                                    R$ <?= number_format($agendamento['servico_preco'], 2, ',', '.') ?>
                                </div>
                                <div class="contact-info">Preço do serviço</div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($agendamento['observacoes']): ?>
                <!-- Observações -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-sticky-note me-2"></i>
                        Observações
                    </div>
                    <div class="card-body">
                        <p class="mb-0" style="white-space: pre-wrap;"><?= e($agendamento['observacoes']) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Histórico -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-history me-2"></i>
                        Histórico do Agendamento
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <div class="timeline-title">Agendamento Criado</div>
                                    <div class="timeline-text">
                                        <?= date('d/m/Y H:i', strtotime($agendamento['created_at'] ?? $agendamento['data_agendamento'])) ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($agendamento['status'] == 'realizado'): ?>
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <div class="timeline-title">Atendimento Realizado</div>
                                    <div class="timeline-text">
                                        <?= date('d/m/Y H:i', strtotime($agendamento['updated_at'] ?? 'now')) ?>
                                        <?php if ($agendamento['valor_cobrado']): ?>
                                        <br>Valor cobrado: R$ <?= number_format($agendamento['valor_cobrado'], 2, ',', '.') ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php elseif ($agendamento['status'] == 'cancelado'): ?>
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <div class="timeline-title">Agendamento Cancelado</div>
                                    <div class="timeline-text">
                                        <?= date('d/m/Y H:i', strtotime($agendamento['updated_at'] ?? 'now')) ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="action-buttons">
                    <a href="/barbearia-new/agendamentos" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar para Lista
                    </a>
                    
                    <a href="/barbearia-new/agendamentos/edit/<?= $agendamento['id'] ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Editar Agendamento
                    </a>
                    
                    <?php if ($agendamento['status'] == 'agendado'): ?>
                    <form method="POST" action="/barbearia-new/agendamentos/complete/<?= $agendamento['id'] ?>" class="d-inline">
                        <button type="submit" class="btn btn-success" onclick="return confirm('Marcar este agendamento como realizado?')">
                            <i class="fas fa-check me-2"></i>Marcar como Realizado
                        </button>
                    </form>
                    
                    <form method="POST" action="/barbearia-new/agendamentos/cancel/<?= $agendamento['id'] ?>" class="d-inline">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja cancelar este agendamento?')">
                            <i class="fas fa-times me-2"></i>Cancelar Agendamento
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <?php if ($agendamento['status'] == 'cancelado' && strtotime($agendamento['data_agendamento']) >= strtotime('today')): ?>
                    <form method="POST" action="/agendamentos/reactivate/<?= $agendamento['id'] ?>" class="d-inline">
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Reativar este agendamento?')">
                            <i class="fas fa-undo me-2"></i>Reativar
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <a href="javascript:window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </a>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script customizado -->
    <script>
        // Estilo de impressão
        const printStyles = `
            <style media="print">
                .sidebar, .navbar, .action-buttons { display: none !important; }
                .main-content { padding: 0 !important; }
                .card { break-inside: avoid; }
                @page { margin: 1cm; }
            </style>
        `;
        document.head.insertAdjacentHTML('beforeend', printStyles);
        
        // Confirmação para ações críticas
        document.querySelectorAll('form[action*="/barbearia-new/cancel/"], form[action*="/barbearia-new/complete/"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const action = this.action.includes('/barbearia-new/cancel/') ? 'cancelar' : 'marcar como realizado';
                if (!confirm(`Tem certeza que deseja ${action} este agendamento?`)) {
                    e.preventDefault();
                }
            });
        });
        
        // Auto-refresh para agendamentos do dia
        const agendamentoData = '<?= $agendamento['data_agendamento'] ?>';
        const hoje = new Date().toISOString().split('T')[0];
        
        if (agendamentoData === hoje && '<?= $agendamento['status'] ?>' === 'agendado') {
            // Refresh a cada 5 minutos para agendamentos de hoje
            setTimeout(() => {
                window.location.reload();
            }, 300000); // 5 minutos
        }
    </script>
</body>
</html>