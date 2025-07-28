<?php
/**
 * VIEW DE DETALHES DO USUÁRIO - VERSÃO LIMPA
 * Salvar como: C:\xampp\htdocs\barbearia-new\app\views\usuarios\show.php
 */

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$title = 'Detalhes do Usuário - Sistema de Barbearia';
$currentPage = 'barbeiros';
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
        
        .user-header {
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            color: white;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 12px;
        }
        
        .user-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
            margin: 0 auto 1rem;
            border: 4px solid rgba(255,255,255,0.3);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .badge-large {
            font-size: 1rem;
            padding: 0.7rem 1.2rem;
        }
        
        .placeholder-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .placeholder-section i {
            color: #6c757d;
        }
        
        .placeholder-section h5 {
            color: #6c757d;
            margin-bottom: 1rem;
        }
        
        .placeholder-section p {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
        }
        
        .feature-list li {
            padding: 0.5rem 0;
            color: #6c757d;
        }
        
        .feature-list li i {
            width: 20px;
            margin-right: 10px;
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            
            .main-content {
                padding: 15px;
            }
            
            .user-header {
                margin-bottom: 1rem;
            }
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

                <!-- User Header -->
                <div class="user-header">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="user-avatar">
                                    <?= strtoupper(substr($usuario['nome'] ?? 'U', 0, 2)) ?>
                                </div>
                                <h1 class="text-center text-md-start"><?= e($usuario['nome'] ?? 'Nome não informado') ?></h1>
                                <p class="text-center text-md-start mb-0 opacity-75">
                                    <i class="fas fa-envelope me-2"></i><?= e($usuario['email'] ?? 'Email não informado') ?>
                                </p>
                            </div>
                            <div class="col-md-4 text-center text-md-end">
                                <span class="badge badge-large <?= ($usuario['tipo'] ?? 'barbeiro') === 'administrador' ? 'bg-warning' : 'bg-info' ?>">
                                    <i class="fas <?= ($usuario['tipo'] ?? 'barbeiro') === 'administrador' ? 'fa-user-shield' : 'fa-user-tie' ?> me-2"></i>
                                    <?= ($usuario['tipo'] ?? 'barbeiro') === 'administrador' ? 'Administrador' : 'Barbeiro' ?>
                                </span>
                                <div class="mt-3">
                                    <?php if (!empty($usuario['id'])): ?>
                                        <a href="/barbearia-new/usuarios/edit/<?= $usuario['id'] ?>" 
                                           class="btn btn-light me-2" 
                                           title="Editar usuário"
                                           onclick="console.log('Link clicked:', this.href); return true;">
                                            <i class="fas fa-edit me-1"></i>Editar
                                        </a>
                                    <?php else: ?>
                                        <span class="btn btn-light me-2 disabled" title="ID do usuário não encontrado">
                                            <i class="fas fa-edit me-1"></i>Editar (ID não encontrado)
                                        </span>
                                    <?php endif; ?>
                                    <a href="/barbearia-new/usuarios" class="btn btn-outline-light">
                                        <i class="fas fa-arrow-left me-1"></i>Voltar
                                    </a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Container -->
                <div class="container">
                    <div class="row">
                        <!-- Informações Básicas -->
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user-circle me-2"></i>
                                        Informações Pessoais
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <strong>Nome Completo:</strong><br>
                                            <?= e($usuario['nome'] ?? 'Não informado') ?>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <strong>E-mail:</strong><br>
                                            <?= e($usuario['email'] ?? 'Não informado') ?>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <strong>Tipo de Usuário:</strong><br>
                                            <span class="badge <?= ($usuario['tipo'] ?? 'barbeiro') === 'administrador' ? 'bg-warning' : 'bg-info' ?>">
                                                <?= ($usuario['tipo'] ?? 'barbeiro') === 'administrador' ? 'Administrador' : 'Barbeiro' ?>
                                            </span>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <strong>Cadastrado em:</strong><br>
                                            <?= date('d/m/Y H:i', strtotime($usuario['created_at'] ?? 'now')) ?>
                                        </div>
                                        <?php if (!empty($usuario['updated_at']) && $usuario['updated_at'] !== $usuario['created_at']): ?>
                                        <div class="col-md-12 mb-3">
                                            <strong>Última atualização:</strong><br>
                                            <?= date('d/m/Y H:i', strtotime($usuario['updated_at'])) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Próximo Atendimento (apenas para barbeiros) -->
                        <?php if (($usuario['tipo'] ?? 'barbeiro') === 'barbeiro'): ?>
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        Próximo Atendimento
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if ($proximoAgendamento): ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-3">
                                                    <i class="fas fa-user me-2"></i>Cliente
                                                </h6>
                                                <p class="mb-1"><strong><?= e($proximoAgendamento['cliente_nome']) ?></strong></p>
                                                <?php if ($proximoAgendamento['cliente_telefone']): ?>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-phone me-1"></i>
                                                    <?= e($proximoAgendamento['cliente_telefone']) ?>
                                                </p>
                                                <?php endif; ?>
                                                <?php if ($proximoAgendamento['cliente_email']): ?>
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    <?= e($proximoAgendamento['cliente_email']) ?>
                                                </p>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-3">
                                                    <i class="fas fa-calendar me-2"></i>Agendamento
                                                </h6>
                                                <p class="mb-1">
                                                    <strong><?= date('d/m/Y', strtotime($proximoAgendamento['data_agendamento'])) ?></strong>
                                                </p>
                                                <p class="mb-1">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?= date('H:i', strtotime($proximoAgendamento['hora_agendamento'])) ?>
                                                    <?php if (isset($proximoAgendamento['servico_duracao'])): ?>
                                                        - <?= date('H:i', strtotime($proximoAgendamento['hora_agendamento'] . ' +' . $proximoAgendamento['servico_duracao'] . ' minutes')) ?>
                                                    <?php endif; ?>
                                                </p>
                                                <span class="badge bg-<?= $proximoAgendamento['status'] === 'confirmado' ? 'success' : 'warning' ?>">
                                                    <?= ucfirst($proximoAgendamento['status']) ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <?php if ($proximoAgendamento['servico_nome']): ?>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h6 class="text-primary mb-2">
                                                    <i class="fas fa-scissors me-2"></i>Serviço
                                                </h6>
                                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                                    <div>
                                                        <strong><?= e($proximoAgendamento['servico_nome']) ?></strong>
                                                        <?php if ($proximoAgendamento['servico_duracao']): ?>
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-clock me-1"></i>
                                                            <?= $proximoAgendamento['servico_duracao'] ?> min
                                                        </small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if ($proximoAgendamento['servico_preco']): ?>
                                                    <span class="badge bg-success fs-6">
                                                        R$ <?= number_format($proximoAgendamento['servico_preco'], 2, ',', '.') ?>
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($proximoAgendamento['observacoes']): ?>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h6 class="text-primary mb-2">
                                                    <i class="fas fa-sticky-note me-2"></i>Observações
                                                </h6>
                                                <div class="p-3 bg-light rounded">
                                                    <p class="mb-0"><?= e($proximoAgendamento['observacoes']) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="mt-3 d-flex justify-content-between align-items-center">
                                            <div>
                                                <?php
                                                $dataHora = $proximoAgendamento['data_agendamento'] . ' ' . $proximoAgendamento['hora_agendamento'];
                                                $tempoRestante = strtotime($dataHora) - time();
                                                if ($tempoRestante > 0):
                                                    $dias = floor($tempoRestante / 86400);
                                                    $horas = floor(($tempoRestante % 86400) / 3600);
                                                    $minutos = floor(($tempoRestante % 3600) / 60);
                                                ?>
                                                <small class="text-muted">
                                                    <i class="fas fa-hourglass-half me-1"></i>
                                                    <?php if ($dias > 0): ?>
                                                        Em <?= $dias ?> dia(s)
                                                    <?php elseif ($horas > 0): ?>
                                                        Em <?= $horas ?>h <?= $minutos ?>min
                                                    <?php else: ?>
                                                        Em <?= $minutos ?> minutos
                                                    <?php endif; ?>
                                                </small>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <a href="/barbearia-new/agendamentos/show/<?= $proximoAgendamento['id'] ?>" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>Ver Detalhes
                                                </a>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-4">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <h6 class="text-muted">Nenhum atendimento agendado</h6>
                                            <p class="text-muted mb-0">Não há agendamentos confirmados para os próximos dias.</p>
                                            
                                            <div class="mt-3">
                                                <a href="/barbearia-new/agendamentos" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-calendar-plus me-1"></i>Ver Agenda
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Estatísticas (apenas para barbeiros) -->
                        <?php if ($estatisticas): ?>
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-bar me-2"></i>
                                        Estatísticas do Mês
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                                                <h4 class="mb-1"><?= $estatisticas['total_atendimentos'] ?? 0 ?></h4>
                                                <small class="text-muted">Total de Agendamentos</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                                <h4 class="mb-1"><?= $estatisticas['atendimentos_concluidos'] ?? 0 ?></h4>
                                                <small class="text-muted">Concluídos</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                                                <h4 class="mb-1"><?= $estatisticas['atendimentos_cancelados'] ?? 0 ?></h4>
                                                <small class="text-muted">Cancelados</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-dollar-sign fa-2x text-warning mb-2"></i>
                                                <h4 class="mb-1">R$ <?= number_format($estatisticas['faturamento_mes'] ?? 0, 2, ',', '.') ?></h4>
                                                <small class="text-muted">Faturamento do Mês</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
