<?php
/**
 * VIEW DE CRIAÇÃO DE AGENDAMENTOS - VERSÃO FUNCIONAL
 * Salvar como: C:\xampp\htdocs\barbearia-new\app\views\agendamentos\create.php
 * 
 * Esta versão funciona tanto na versão simplificada quanto na principal
 * porque define suas próprias funções e carrega os dados necessários
 */

// Funções auxiliares simples (caso não existam)
if (!function_exists('old')) {
    function old($field, $default = '') {
        return $_POST[$field] ?? $_SESSION['form_data'][$field] ?? $default;
    }
}

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$title = 'Novo Agendamento - Sistema de Barbearia';
$currentPage = 'agendamentos';

// Buscar dados necessários (com tratamento de erro)
$clientes = [];
$servicos = [];
$barbeiros = [];

try {
    $clientes = DB::select("SELECT id, nome, telefone FROM clientes ORDER BY nome");
} catch (Exception $e) {
    $clientes = [];
}

try {
    $servicos = DB::select("SELECT id, nome, preco, duracao FROM servicos WHERE ativo = 1 ORDER BY nome");
} catch (Exception $e) {
    $servicos = [];
}

try {
    $barbeiros = DB::select("SELECT id, nome FROM usuarios WHERE tipo = 'barbeiro' AND ativo = 1 ORDER BY nome");
} catch (Exception $e) {
    $barbeiros = [];
}

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
            border-color: #1700e6;
            box-shadow: 0 0 0 0.2rem rgba(23, 0, 230, 0.25);
        }
        
        .btn {
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1700e6, #3225e9);
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
        
        .summary-card {
            background: linear-gradient(135deg, #f3f4f6, #ffffff);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
                                Barbeiros
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
                        
                        <!--<li class="nav-item">
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
    <!-- Cabeçalho -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-calendar-plus me-3"></i>
                    Novo Agendamento
                </h1>
                <p class="mb-0 opacity-90">Crie um novo agendamento para seu cliente</p>
            </div>
            <a href="/barbearia-new/agendamentos" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>
                Voltar aos Agendamentos
            </a>
        </div>
    </div>

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

    <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-calendar-plus me-2 text-primary"></i>
                        Dados do Agendamento
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="/barbearia-new/agendamentos/store" id="formAgendamento">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cliente_id" class="form-label">
                                        <i class="fas fa-user me-1"></i>
                                        Cliente *
                                    </label>
                                    <select name="cliente_id" id="cliente_id" class="form-select" required>
                                        <option value="">Selecione um cliente</option>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <option value="<?= $cliente['id'] ?>" 
                                                    data-telefone="<?= e($cliente['telefone'] ?? '') ?>"
                                                    <?= (old('cliente_id') == $cliente['id']) ? 'selected' : '' ?>>
                                                <?= e($cliente['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text">
                                        <a href="/barbearia-new/clientes/create" target="_blank">
                                            <i class="fas fa-plus me-1"></i>
                                            Cadastrar novo cliente
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="servico_id" class="form-label">
                                        <i class="fas fa-scissors me-1"></i>
                                        Serviço *
                                    </label>
                                    <select name="servico_id" id="servico_id" class="form-select" required>
                                        <option value="">Selecione um serviço</option>
                                        <?php foreach ($servicos as $servico): ?>
                                            <option value="<?= $servico['id'] ?>" 
                                                    data-preco="<?= $servico['preco'] ?? 0 ?>"
                                                    data-duracao="<?= $servico['duracao'] ?? 30 ?>"
                                                    <?= (old('servico_id') == $servico['id']) ? 'selected' : '' ?>>
                                                <?= e($servico['nome']) ?> - 
                                                R$ <?= number_format($servico['preco'] ?? 0, 2, ',', '.') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="barbeiro_id" class="form-label">
                                        <i class="fas fa-user-tie me-1"></i>
                                        Barbeiro *
                                    </label>
                                    <select name="barbeiro_id" id="barbeiro_id" class="form-select" required>
                                        <option value="">Selecione um barbeiro</option>
                                        <?php foreach ($barbeiros as $barbeiro): ?>
                                            <option value="<?= $barbeiro['id'] ?>"
                                                    <?= (old('barbeiro_id') == $barbeiro['id']) ? 'selected' : '' ?>>
                                                <?= e($barbeiro['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="data" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>
                                        Data *
                                    </label>
                                    <input type="date" name="data" id="data" class="form-control" 
                                           value="<?= old('data', date('Y-m-d')) ?>" 
                                           min="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hora" class="form-label">
                                        <i class="fas fa-clock me-1"></i>
                                        Hora *
                                    </label>
                                    <select name="hora" id="hora" class="form-select" required>
                                        <option value="">Selecione uma hora</option>
                                        <option value="08:00" <?= old('hora') == '08:00' ? 'selected' : '' ?>>08:00</option>
                                        <option value="08:30" <?= old('hora') == '08:30' ? 'selected' : '' ?>>08:30</option>
                                        <option value="09:00" <?= old('hora') == '09:00' ? 'selected' : '' ?>>09:00</option>
                                        <option value="09:30" <?= old('hora') == '09:30' ? 'selected' : '' ?>>09:30</option>
                                        <option value="10:00" <?= old('hora') == '10:00' ? 'selected' : '' ?>>10:00</option>
                                        <option value="10:30" <?= old('hora') == '10:30' ? 'selected' : '' ?>>10:30</option>
                                        <option value="11:00" <?= old('hora') == '11:00' ? 'selected' : '' ?>>11:00</option>
                                        <option value="11:30" <?= old('hora') == '11:30' ? 'selected' : '' ?>>11:30</option>
                                        <option value="14:00" <?= old('hora') == '14:00' ? 'selected' : '' ?>>14:00</option>
                                        <option value="14:30" <?= old('hora') == '14:30' ? 'selected' : '' ?>>14:30</option>
                                        <option value="15:00" <?= old('hora') == '15:00' ? 'selected' : '' ?>>15:00</option>
                                        <option value="15:30" <?= old('hora') == '15:30' ? 'selected' : '' ?>>15:30</option>
                                        <option value="16:00" <?= old('hora') == '16:00' ? 'selected' : '' ?>>16:00</option>
                                        <option value="16:30" <?= old('hora') == '16:30' ? 'selected' : '' ?>>16:30</option>
                                        <option value="17:00" <?= old('hora') == '17:00' ? 'selected' : '' ?>>17:00</option>
                                        <option value="17:30" <?= old('hora') == '17:30' ? 'selected' : '' ?>>17:30</option>
                                        <option value="18:00" <?= old('hora') == '18:00' ? 'selected' : '' ?>>18:00</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="preco" class="form-label">
                                        <i class="fas fa-dollar-sign me-1"></i>
                                        Preço
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" name="preco" id="preco" class="form-control" 
                                               step="0.01" min="0" value="<?= old('preco') ?>">
                                    </div>
                                    <div class="form-text">O preço será preenchido automaticamente</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="observacoes" class="form-label">
                                <i class="fas fa-comment me-1"></i>
                                Observações
                            </label>
                            <textarea name="observacoes" id="observacoes" class="form-control" rows="3" 
                                      placeholder="Observações sobre o agendamento..."><?= old('observacoes') ?></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="/barbearia-new/agendamentos" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Salvar Agendamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Resumo do Agendamento -->
            <div class="summary-card mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #3B82F6, #60A5FA); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Resumo do Agendamento</h6>
                        <small class="text-muted">Informações em tempo real</small>
                    </div>
                </div>
                <div id="resumo-agendamento">
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-plus fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                        <p class="text-muted mb-0">Preencha os dados para ver o resumo</p>
                    </div>
                </div>
            </div>
            
            <!-- Info sobre horários -->
            <div class="modern-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #F59E0B, #FBBF24); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Horários de Funcionamento</h6>
                            <small class="text-muted">Segunda a Sábado</small>
                        </div>
                    </div>
                    <div class="small">
                        <p class="mb-2"><strong>Manhã:</strong> 08:00 às 12:00</p>
                        <p class="mb-0"><strong>Tarde:</strong> 14:00 às 18:00</p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </main>
        </div>
    </div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clienteSelect = document.getElementById('cliente_id');
    const servicoSelect = document.getElementById('servico_id');
    const barbeiroSelect = document.getElementById('barbeiro_id');
    const dataInput = document.getElementById('data');
    const horaSelect = document.getElementById('hora');
    const precoInput = document.getElementById('preco');
    
    // Atualizar preço quando selecionar serviço
    servicoSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value) {
            const preco = option.dataset.preco;
            precoInput.value = preco;
        } else {
            precoInput.value = '';
        }
        atualizarResumo();
    });
    
    // Atualizar resumo
    function atualizarResumo() {
        const cliente = clienteSelect.options[clienteSelect.selectedIndex];
        const servico = servicoSelect.options[servicoSelect.selectedIndex];
        const barbeiro = barbeiroSelect.options[barbeiroSelect.selectedIndex];
        const data = dataInput.value;
        const hora = horaSelect.value;
        const preco = precoInput.value;
        
        const resumoDiv = document.getElementById('resumo-agendamento');
        
        if (cliente.value && servico.value && barbeiro.value && data && hora) {
            const dataFormatada = new Date(data + 'T00:00:00').toLocaleDateString('pt-BR');
            const precoFormatado = parseFloat(preco || 0).toFixed(2).replace('.', ',');
            
            resumoDiv.innerHTML = `
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-user text-primary me-2"></i>
                    <strong>Cliente:</strong>
                </div>
                <p class="mb-3">${cliente.textContent}</p>
                
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-scissors text-primary me-2"></i>
                    <strong>Serviço:</strong>
                </div>
                <p class="mb-3">${servico.textContent.split(' - ')[0]}</p>
                
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-user-tie text-primary me-2"></i>
                    <strong>Barbeiro:</strong>
                </div>
                <p class="mb-3">${barbeiro.textContent}</p>
                
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-calendar text-primary me-2"></i>
                    <strong>Data/Hora:</strong>
                </div>
                <p class="mb-3">${dataFormatada} às ${hora}</p>
                
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-dollar-sign text-success me-2"></i>
                    <strong>Valor:</strong>
                </div>
                <p class="h5 text-success mb-0">R$ ${precoFormatado}</p>
            `;
        } else {
            resumoDiv.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-calendar-plus fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                    <p class="text-muted mb-0">Preencha os dados para ver o resumo</p>
                </div>
            `;
        }
    }
    
    // Adicionar listeners para atualizar resumo
    clienteSelect.addEventListener('change', atualizarResumo);
    servicoSelect.addEventListener('change', atualizarResumo);
    barbeiroSelect.addEventListener('change', atualizarResumo);
    dataInput.addEventListener('change', atualizarResumo);
    horaSelect.addEventListener('change', atualizarResumo);
    
    // Auto-hide alerts depois de 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Validação do formulário
    document.getElementById('formAgendamento').addEventListener('submit', function(e) {
        const cliente = clienteSelect.value;
        const servico = servicoSelect.value;
        const barbeiro = barbeiroSelect.value;
        const data = dataInput.value;
        const hora = horaSelect.value;
        
        if (!cliente || !servico || !barbeiro || !data || !hora) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios.');
        }
    });
});
</script>

</body>
</html>
