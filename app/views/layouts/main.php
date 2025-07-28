<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistema Barbearia' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
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
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .card-header {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--info-color));
            border: none;
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            font-weight: 600;
        }
        
        .table tbody tr:hover {
            background-color: rgba(52, 73, 94, 0.05);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
        }
        
        .badge {
            border-radius: 6px;
            padding: 6px 12px;
            font-weight: 500;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .stats-card h3 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
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
        
        .loading {
            display: none;
        }
        
        .loading.show {
            display: inline-block;
        }
    </style>

    <style>
:root {
    --primary-color: #1700e6ff;
    --primary-light: #3225e9ff;
    --primary-dark: #0c0d4eff;
    --success-color: #10B981;
    --warning-color: #F59E0B;
    --danger-color: #EF4444;
    --info-color: #3B82F6;
    --dark-bg: #1F2937;
    --card-bg: #FFFFFF;
    --border-radius: 16px;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.page-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border-radius: var(--border-radius);
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    box-shadow: var(--shadow-lg);
}

.modern-card {
    background: var(--card-bg);
    border-radius: var(--border-radius);
    border: none;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
}

.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.form-control, .form-select {
    border-radius: 12px;
    border: 2px solid #E5E7EB;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.btn {
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    box-shadow: var(--shadow);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    box-shadow: var(--shadow-lg);
}

.btn-success {
    background: linear-gradient(135deg, var(--success-color), #34D399);
    box-shadow: var(--shadow);
}

.btn-secondary {
    background: #6B7280;
    box-shadow: var(--shadow);
}

.input-group-text {
    background: #F9FAFB;
    border: 2px solid #E5E7EB;
    border-right: none;
    color: #6B7280;
    font-weight: 600;
}

.input-group .form-control {
    border-left: none;
}

.summary-card {
    background: linear-gradient(135deg, #F3F4F6, #FFFFFF);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    border: none;
}

.agenda-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    border: none;
}

.form-text a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
}

.form-text a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.card-header {
    background: transparent !important;
    border-bottom: 1px solid #E5E7EB;
}

.card-title {
    color: #1F2937;
    font-weight: 700;
}

.text-primary {
    color: var(--primary-color) !important;
}

.text-success {
    color: var(--success-color) !important;
}

.bg-primary {
    background: var(--primary-color) !important;
}

.badge {
    border-radius: 50px;
    padding: 0.5rem 1rem;
    font-weight: 600;
    font-size: 0.75rem;
}
</style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= SITE_URL ?>/dashboard">
                <i class="fas fa-cut me-2"></i>
                <?= SITE_NAME ?>
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
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/perfil">
                                <i class="fas fa-user me-2"></i>Meu Perfil
                            </a></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= SITE_URL ?>/logout">
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
                            <a class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>" href="<?= SITE_URL ?>/dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'agendamentos' ? 'active' : '' ?>" href="<?= SITE_URL ?>/agendamentos">
                                <i class="fas fa-calendar-alt"></i>
                                Agendamentos
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'clientes' ? 'active' : '' ?>" href="<?= SITE_URL ?>/clientes">
                                <i class="fas fa-users"></i>
                                Clientes
                            </a>
                        </li>
                        
                        <?php if ($_SESSION['user_tipo'] == 'administrador'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'barbeiros' ? 'active' : '' ?>" href="<?= SITE_URL ?>/usuarios">
                                <i class="fas fa-user-tie"></i>
                                Usuários
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'servicos' ? 'active' : '' ?>" href="<?= SITE_URL ?>/servicos">
                                <i class="fas fa-scissors"></i>
                                Serviços
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'financeiro' ? 'active' : '' ?>" href="<?= SITE_URL ?>/financeiro">
                                <i class="fas fa-chart-line"></i>
                                Financeiro
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <!-- <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'agenda' ? 'active' : '' ?>" href="<?= SITE_URL ?>/agenda">
                                <i class="fas fa-calendar-week"></i>
                                Minha Agenda
                            </a>
                        </li> -->
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Alerts -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= $_SESSION['success'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= $_SESSION['error'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

                <!-- Page Content -->
                <?= $content ?? '' ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Confirmação para exclusões
        function confirmarExclusao(mensagem = 'Tem certeza que deseja excluir este item?') {
            return confirm(mensagem);
        }
        
        // Loading nos botões
        function showLoading(button) {
            const loading = button.querySelector('.loading');
            if (loading) {
                loading.classList.add('show');
            }
            button.disabled = true;
        }
        
        function hideLoading(button) {
            const loading = button.querySelector('.loading');
            if (loading) {
                loading.classList.remove('show');
            }
            button.disabled = false;
        }
        
        // Auto-hide alerts depois de 5 segundos
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
        
        // Máscaras para telefone
        function mascaraTelefone(input) {
            let valor = input.value.replace(/\D/g, '');
            
            if (valor.length <= 10) {
                valor = valor.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            } else {
                valor = valor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            }
            
            input.value = valor;
        }
        
        // Aplicar máscara em campos de telefone
        document.addEventListener('DOMContentLoaded', function() {
            const telefoneInputs = document.querySelectorAll('input[type="tel"], input[name*="telefone"]');
            telefoneInputs.forEach(input => {
                input.addEventListener('input', function() {
                    mascaraTelefone(this);
                });
            });
        });
    </script>
    
    <?= $scripts ?? '' ?>
</body>
</html>
