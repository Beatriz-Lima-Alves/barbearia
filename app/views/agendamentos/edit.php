<?php
/**
 * VIEW DE EDIÇÃO DE AGENDAMENTOS
 * Salvar como: app/views/agendamentos/edit.php
 */

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$title = 'Editar Agendamento - Sistema de Barbearia';
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
        
        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(23, 0, 230, 0.25);
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
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .required {
            color: var(--accent-color);
        }
        
        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .status-agendado {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .status-realizado {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
        
        .status-cancelado {
            background-color: #ffebee;
            color: #c62828;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .info-value {
            color: #495057;
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
                <!-- Mensagens de erro -->
                <?php if (isset($_SESSION['errors'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Erro(s) encontrado(s):</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <li><?= e($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1><i class="fas fa-edit me-3"></i>Editar Agendamento</h1>
                            <p>Atualize as informações do agendamento de <?= e($agendamento['cliente_nome']) ?></p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/barbearia-new/agendamentos/show/<?= $agendamento['id'] ?>" class="btn btn-outline-light">
                                <i class="fas fa-eye me-2"></i>Visualizar
                            </a>
                            <a href="/barbearia-new/agendamentos" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i>Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Informações Atuais -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-info-circle me-2"></i>
                                Informações Atuais
                            </div>
                            <div class="card-body">
                                <div class="info-item">
                                    <span class="info-label">Cliente:</span>
                                    <span class="info-value"><?= e($agendamento['cliente_nome']) ?></span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Barbeiro:</span>
                                    <span class="info-value"><?= e($agendamento['barbeiro_nome']) ?></span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Serviço:</span>
                                    <span class="info-value"><?= e($agendamento['servico_nome']) ?></span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Data:</span>
                                    <span class="info-value"><?= date('d/m/Y', strtotime($agendamento['data_agendamento'])) ?></span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Horário:</span>
                                    <span class="info-value"><?= date('H:i', strtotime($agendamento['hora_agendamento'])) ?></span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Status:</span>
                                    <span class="status-badge status-<?= $agendamento['status'] ?>">
                                        <?= ucfirst($agendamento['status']) ?>
                                    </span>
                                </div>
                                
                                <?php if ($agendamento['valor_cobrado']): ?>
                                <div class="info-item">
                                    <span class="info-label">Valor:</span>
                                    <span class="info-value">R$ <?= number_format($agendamento['valor_cobrado'], 2, ',', '.') ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário de Edição -->
                    <div class="col-lg-8">
                        <form method="POST" action="/barbearia-new/agendamentos/update/<?= $agendamento['id'] ?>" id="formEditarAgendamento">
                            <!-- Dados do Agendamento -->
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-calendar me-2"></i>
                                    Dados do Agendamento
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cliente_id" class="form-label">
                                                Cliente <span class="required">*</span>
                                            </label>
                                            <select class="form-select" id="cliente_id" name="cliente_id" required>
                                                <option value="">Selecione o cliente</option>
                                                <?php foreach ($clientes as $cliente): ?>
                                                    <option value="<?= $cliente['id'] ?>" 
                                                            <?= ($cliente['id'] == ($agendamento['cliente_id'] ?? '')) ? 'selected' : '' ?>>
                                                        <?= e($cliente['nome']) ?> - <?= e($cliente['telefone']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="barbeiro_id" class="form-label">
                                                Barbeiro <span class="required">*</span>
                                            </label>
                                            <select class="form-select" id="barbeiro_id" name="barbeiro_id" required>
                                                <option value="">Selecione o barbeiro</option>
                                                <?php foreach ($barbeiros as $barbeiro): ?>
                                                    <option value="<?= $barbeiro['id'] ?>" 
                                                            <?= ($barbeiro['id'] == ($agendamento['barbeiro_id'] ?? '')) ? 'selected' : '' ?>>
                                                        <?= e($barbeiro['nome']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="servico_id" class="form-label">
                                                Serviço <span class="required">*</span>
                                            </label>
                                            <select class="form-select" id="servico_id" name="servico_id" required>
                                                <option value="">Selecione o serviço</option>
                                                <?php foreach ($servicos as $servico): ?>
                                                    <option value="<?= $servico['id'] ?>" 
                                                            data-preco="<?= $servico['preco'] ?>"
                                                            <?= ($servico['id'] == ($agendamento['servico_id'] ?? '')) ? 'selected' : '' ?>>
                                                        <?= e($servico['nome']) ?> - R$ <?= number_format($servico['preco'], 2, ',', '.') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label">
                                                Status <span class="required">*</span>
                                            </label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="agendado" <?= ($agendamento['status'] == 'agendado') ? 'selected' : '' ?>>
                                                    Agendado
                                                </option>
                                                <option value="realizado" <?= ($agendamento['status'] == 'realizado') ? 'selected' : '' ?>>
                                                    Realizado
                                                </option>
                                                <option value="cancelado" <?= ($agendamento['status'] == 'cancelado') ? 'selected' : '' ?>>
                                                    Cancelado
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="data_agendamento" class="form-label">
                                                Data <span class="required">*</span>
                                            </label>
                                            <input type="date" 
                                                   class="form-control" 
                                                   id="data_agendamento" 
                                                   name="data_agendamento" 
                                                   value="<?= $agendamento['data_agendamento'] ?>" 
                                                   required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="hora_agendamento" class="form-label">
                                                Horário <span class="required">*</span>
                                            </label>
                                            <input type="time" 
                                                   class="form-control" 
                                                   id="hora_agendamento" 
                                                   name="hora_agendamento" 
                                                   value="<?= $agendamento['hora_agendamento'] ?>" 
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Valor e Observações -->
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-dollar-sign me-2"></i>
                                    Valor e Observações
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="valor_cobrado" class="form-label">Valor Cobrado</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" 
                                                       class="form-control" 
                                                       id="valor_cobrado" 
                                                       name="valor_cobrado" 
                                                       step="0.01"
                                                       min="0"
                                                       placeholder="0,00"
                                                       value="<?= $agendamento['valor_cobrado'] ?? '' ?>">
                                            </div>
                                            <div class="form-text">Deixe em branco para usar o preço do serviço</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="observacoes" class="form-label">Observações</label>
                                        <textarea class="form-control" 
                                                  id="observacoes" 
                                                  name="observacoes" 
                                                  rows="3"
                                                  placeholder="Observações sobre o agendamento..."><?= e($agendamento['observacoes'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="d-flex justify-content-end gap-3 mb-4">
                                <a href="/barbearia-new/agendamentos" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Atualizar Agendamento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script customizado -->
    <script>
        // Preencher valor automaticamente quando selecionar serviço
        document.getElementById('servico_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const preco = selectedOption.getAttribute('data-preco');
            
            if (preco && !document.getElementById('valor_cobrado').value) {
                document.getElementById('valor_cobrado').value = parseFloat(preco).toFixed(2);
            }
        });
        
        // Validação do formulário
        document.getElementById('formEditarAgendamento').addEventListener('submit', function(e) {
            const cliente = document.getElementById('cliente_id').value;
            const barbeiro = document.getElementById('barbeiro_id').value;
            const servico = document.getElementById('servico_id').value;
            const data = document.getElementById('data_agendamento').value;
            const hora = document.getElementById('hora_agendamento').value;
            const status = document.getElementById('status').value;
            
            let errors = [];
            
            if (!cliente) {
                errors.push('Selecione um cliente');
            }
            
            if (!barbeiro) {
                errors.push('Selecione um barbeiro');
            }
            
            if (!servico) {
                errors.push('Selecione um serviço');
            }
            
            if (!data) {
                errors.push('Informe a data do agendamento');
            }
            
            if (!hora) {
                errors.push('Informe o horário do agendamento');
            }
            
            if (!status) {
                errors.push('Selecione o status');
            }
            
            // Validar se a data não é anterior a hoje (exceto para status realizado ou cancelado)
            if (data && status === 'agendado') {
                const hoje = new Date().toISOString().split('T')[0];
                if (data < hoje) {
                    errors.push('A data não pode ser anterior a hoje para agendamentos ativos');
                }
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Corrija os seguintes erros:\n\n' + errors.join('\n'));
            }
        });
        
        // Confirmação para mudanças de status críticas
        document.getElementById('status').addEventListener('change', function() {
            const novoStatus = this.value;
            const statusAtual = '<?= $agendamento['status'] ?>';
            
            if (statusAtual !== 'cancelado' && novoStatus === 'cancelado') {
                if (!confirm('Tem certeza que deseja cancelar este agendamento?')) {
                    this.value = statusAtual;
                }
            }
        });
        
        // Formatação automática do valor
        document.getElementById('valor_cobrado').addEventListener('blur', function() {
            let value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(2);
            }
        });
    </script>
</body>
</html>