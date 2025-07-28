<?php
/**
 * VIEW DE LISTAGEM DE CLIENTES - VERSÃO COMPLETA
 * Salvar como: C:\xampp\htdocs\barbearia-new\app\views\clientes\index.php
 */

// Funções auxiliares (caso não existam)
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$title = 'Clientes - Sistema de Barbearia';
$currentPage = 'clientes';

// Garantir que as variáveis existam
$clientes = $clientes ?? [];
$estatisticas = $estatisticas ?? [
    'total' => 0,
    'ativos' => 0,
    'novos_mes' => 0,
    'vip' => 0
];
$paginacao = $paginacao ?? null;

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
            --info-color: #3B82F6;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
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
            background: linear-gradient(135deg, #1700e6, #3225e9);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .modern-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .modern-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn {
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
        }
        
        .btn-success {
            background: linear-gradient(45deg, var(--success-color), #2ecc71);
            border: none;
            border-radius: 8px;
        }
        
        .btn-danger {
            background: linear-gradient(45deg, var(--accent-color), #c0392b);
            border: none;
            border-radius: 8px;
        }
        
        .btn-warning {
            background: linear-gradient(45deg, var(--warning-color), #e67e22);
            border: none;
            border-radius: 8px;
        }
        
        .dropdown-menu {
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            
            .main-content {
                padding: 15px;
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
                        
                        <?php if (($_SESSION['user_tipo'] ?? '') == 'administrador'): ?>
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
                        <?php endif; ?>
                        
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

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3">
                        <i class="fas fa-users me-2"></i>
                        Clientes
                    </h1>
                    <div>
                        <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#pesquisaModal">
                            <i class="fas fa-search me-1"></i>
                            Pesquisar
                        </button>
                        <a href="/barbearia-new/clientes/create" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>
                            Novo Cliente
                        </a>
                    </div>
                </div>

                <!-- Cards de Estatísticas -->
                <!-- <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="small text-muted">Total Clientes</div>
                                    <div class="h4 mb-0"><?= $estatisticas['total'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calendar-check fa-2x text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="small text-muted">Clientes Ativos</div>
                                    <div class="h4 mb-0"><?= $estatisticas['ativos'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-plus fa-2x text-info"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="small text-muted">Novos este Mês</div>
                                    <div class="h4 mb-0"><?= $estatisticas['novos_mes'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-star fa-2x text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="small text-muted">Clientes VIP</div>
                                    <div class="h4 mb-0"><?= $estatisticas['vip'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Tabela de Clientes -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            Lista de Clientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($clientes)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Nenhum cliente encontrado</h5>
                                <p class="text-muted">Cadastre um novo cliente para começar</p>
                                <a href="/barbearia-new/clientes/create" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Cadastrar Cliente
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Contato</th>
                                            <th>Data Nascimento</th>
                                            <th>Cadastro</th>
                                            <th>Agendamentos</th>
                                            <th>Último Corte</th>
                                            <th width="120">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clientes as $cliente): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-3">
                                                        <?= strtoupper(substr($cliente['nome'], 0, 2)) ?>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?= e($cliente['nome']) ?></div>
                                                        <?php if (!empty($cliente['email'])): ?>
                                                            <small class="text-muted"><?= e($cliente['email']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><?= e($cliente['telefone']) ?></div>
                                                <?php if (!empty($cliente['endereco'])): ?>
                                                    <small class="text-muted"><?= e($cliente['endereco']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($cliente['data_nascimento']) && $cliente['data_nascimento'] !== '0000-00-00'): ?>
                                                    <?= date('d/m/Y', strtotime($cliente['data_nascimento'])) ?>
                                                    <br><small class="text-muted">
                                                        <?= floor((time() - strtotime($cliente['data_nascimento'])) / (365*24*60*60)) ?> anos
                                                    </small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div><?= date('d/m/Y', strtotime($cliente['data_criacao'] ?? $cliente['created_at'] ?? 'now')) ?></div>
                                                <small class="text-muted"><?= date('H:i', strtotime($cliente['data_criacao'] ?? $cliente['created_at'] ?? 'now')) ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?= $cliente['total_agendamentos'] ?? 0 ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($cliente['ultimo_agendamento'])): ?>
                                                    <div><?= date('d/m/Y', strtotime($cliente['ultimo_agendamento'])) ?></div>
                                                    <small class="text-muted">
                                                        <?php
                                                        $dias = floor((time() - strtotime($cliente['ultimo_agendamento'])) / (24*60*60));
                                                        echo $dias > 0 ? "há {$dias} dias" : 'hoje';
                                                        ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="text-muted">Nunca</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/barbearia-new/clientes/show/<?= $cliente['id'] ?>" 
                                                       class="btn btn-outline-primary" title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="/barbearia-new/clientes/edit/<?= $cliente['id'] ?>" 
                                                       class="btn btn-outline-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-outline-danger" 
                                                            onclick="confirmarExclusao(<?= $cliente['id'] ?>)" title="Excluir">
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
            </main>
        </div>
    </div>

    <!-- Modal de Pesquisa -->
    <div class="modal fade" id="pesquisaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pesquisar Clientes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Buscar por</label>
                            <input type="text" name="busca" class="form-control" 
                                   placeholder="Nome, telefone ou email..."
                                   value="<?= $_GET['busca'] ?? '' ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Data de Cadastro</label>
                                <input type="date" name="data_inicio" class="form-control" 
                                       value="<?= $_GET['data_inicio'] ?? '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">até</label>
                                <input type="date" name="data_fim" class="form-control" 
                                       value="<?= $_GET['data_fim'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <a href="/barbearia-new/clientes" class="btn btn-outline-warning">Limpar</a>
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Garantir que a função está sempre disponível
    window.confirmarExclusao = function(id) {
        console.log('testeee');
        console.log('Função confirmarExclusao chamada para ID:', id);
        
        if (confirm('Tem certeza que deseja excluir este cliente?\n\nEsta ação não pode ser desfeita.')) {
            console.log('Usuário confirmou a exclusão');
            
            // Mostrar loading no botão
            const button = event.target.closest('button');
            if (button) {
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
                
                // Restaurar botão após 5 segundos (fallback)
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }, 5000);
            }
            
            // Criar formulário para envio
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/barbearia-new/clientes/delete/${id}`;
            form.style.display = 'none';
            
            // Adicionar método DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Adicionar ao DOM e enviar
            document.body.appendChild(form);
            console.log('Enviando formulário de exclusão...');
            form.submit();
        } else {
            console.log('Usuário cancelou a exclusão');
        }
    };
    
    // Função alternativa usando event delegation
    document.addEventListener('click', function(e) {
        if (e.target.closest('button[onclick*="confirmarExclusao"]')) {
            e.preventDefault();
            
            // Extrair ID do onclick
            const onclickAttr = e.target.closest('button').getAttribute('onclick');
            const match = onclickAttr.match(/confirmarExclusao\((\d+)\)/);
            
            if (match && match[1]) {
                const id = match[1];
                console.log('Event delegation - ID extraído:', id);
                window.confirmarExclusao(id);
            }
        }
    });
    
    // Auto-hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM carregado - inicializando scripts');
        
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                try {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                } catch (e) {
                    console.log('Erro ao fechar alert:', e);
                }
            });
        }, 5000);
        
        // Verificar se as funções estão funcionando
        console.log('Função confirmarExclusao disponível:', typeof window.confirmarExclusao);
        
        // Adicionar event listeners extras para debug
        const deleteButtons = document.querySelectorAll('button[onclick*="confirmarExclusao"]');
        console.log('Botões de exclusão encontrados:', deleteButtons.length);
        
        deleteButtons.forEach((button, index) => {
            console.log(`Botão ${index + 1}:`, button.getAttribute('onclick'));
            
            // Adicionar evento de click extra para teste
            button.addEventListener('click', function(e) {
                console.log('CLICK DETECTADO no botão:', index + 1);
                console.log('Event target:', e.target);
            });
        });
    });
    </script>

</body>
</html>
