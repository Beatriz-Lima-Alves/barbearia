<?php
/**
 * VIEW DE EDIÇÃO DE SERVIÇOS
 * Salvar como: app/views/servicos/edit.php
 */

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$title = 'Editar Serviço - Sistema de Barbearia';
$currentPage = 'servicos';
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
        
        .is-invalid {
            border-color: var(--accent-color);
        }
        
        .invalid-feedback {
            color: var(--accent-color);
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
            <a class="navbar-brand" href="/dashboard">
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
                            <a class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>" href="/dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'agendamentos' ? 'active' : '' ?>" href="/agendamentos">
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
                            <h1><i class="fas fa-edit me-3"></i>Editar Serviço</h1>
                            <p>Atualize as informações do serviço "<?= e($servico['nome']) ?>"</p>
                        </div>
                        <a href="/barbearia-new/servicos" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                    </div>
                </div>

                <!-- Formulário de Edição -->
                <form method="POST" action="/barbearia-new/servicos/update/<?= $servico['id'] ?>" id="formEditarServico">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <!-- Informações Básicas -->
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informações Básicas
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label for="nome" class="form-label">
                                                Nome do Serviço <span class="required">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="nome" 
                                                   name="nome" 
                                                   placeholder="Ex: Corte Masculino"
                                                   value="<?= e($_SESSION['form_data']['nome'] ?? $servico['nome'] ?? '') ?>" 
                                                   required>
                                            <div class="form-text">Nome identificador do serviço</div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="categoria" class="form-label">
                                                Categoria <span class="required">*</span>
                                            </label>
                                            <select class="form-select" id="categoria" name="categoria" required>
                                                <option value="">Selecione a categoria</option>
                                                <?php 
                                                $categorias = ['Corte', 'Barba', 'Sobrancelha', 'Tratamento', 'Combo'];
                                                $categoriaSelecionada = $_SESSION['form_data']['categoria'] ?? $servico['categoria'] ?? '';
                                                foreach ($categorias as $cat): 
                                                ?>
                                                    <option value="<?= $cat ?>" <?= $categoriaSelecionada === $cat ? 'selected' : '' ?>>
                                                        <?= $cat ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="descricao" class="form-label">Descrição</label>
                                        <textarea class="form-control" 
                                                  id="descricao" 
                                                  name="descricao" 
                                                  rows="3"
                                                  placeholder="Descreva os detalhes do serviço..."><?= e($_SESSION['form_data']['descricao'] ?? $servico['descricao'] ?? '') ?></textarea>
                                        <div class="form-text">Informações adicionais sobre o serviço (opcional)</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Valores e Duração -->
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-dollar-sign me-2"></i>
                                    Valores e Duração
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="preco" class="form-label">
                                                Preço <span class="required">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" 
                                                       class="form-control" 
                                                       id="preco" 
                                                       name="preco" 
                                                       placeholder="0,00"
                                                       step="0.01"
                                                       min="0"
                                                       value="<?= e($_SESSION['form_data']['preco'] ?? $servico['preco'] ?? '') ?>" 
                                                       required>
                                            </div>
                                            <div class="form-text">Valor cobrado pelo serviço</div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="duracao" class="form-label">
                                                Duração (minutos) <span class="required">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="duracao" 
                                                   name="duracao" 
                                                   placeholder="30"
                                                   min="1"
                                                   value="<?= e($_SESSION['form_data']['duracao'] ?? $servico['duracao'] ?? '') ?>" 
                                                   required>
                                            <div class="form-text">Tempo estimado em minutos</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-toggle-on me-2"></i>
                                    Status do Serviço
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="ativo" 
                                                   name="ativo" 
                                                   value="1"
                                                   <?= (($_SESSION['form_data']['ativo'] ?? $servico['ativo'] ?? 1) == 1) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="ativo">
                                                <strong>Serviço Ativo</strong>
                                            </label>
                                        </div>
                                        <div class="form-text">
                                            Serviços inativos não aparecerão para agendamento
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="d-flex justify-content-end gap-3 mb-4">
                                <a href="/barbearia-new/servicos" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Atualizar Serviço
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script customizado -->
    <script>
        // Limpar dados do formulário da sessão após renderizar
        <?php unset($_SESSION['form_data']); ?>
        
        // Formatação automática do preço
        document.getElementById('preco').addEventListener('blur', function() {
            let value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(2);
            }
        });
        
        // Validação do formulário
        document.getElementById('formEditarServico').addEventListener('submit', function(e) {
            const nome = document.getElementById('nome').value.trim();
            const categoria = document.getElementById('categoria').value;
            const preco = parseFloat(document.getElementById('preco').value);
            const duracao = parseInt(document.getElementById('duracao').value);
            
            let errors = [];
            
            if (nome.length < 2) {
                errors.push('O nome deve ter pelo menos 2 caracteres');
            }
            
            if (!categoria) {
                errors.push('Selecione uma categoria');
            }
            
            if (isNaN(preco) || preco <= 0) {
                errors.push('O preço deve ser um valor positivo');
            }
            
            if (isNaN(duracao) || duracao <= 0) {
                errors.push('A duração deve ser um número positivo');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Corrija os seguintes erros:\n\n' + errors.join('\n'));
            }
        });
        
        // Preview das mudanças
        function updatePreview() {
            const nome = document.getElementById('nome').value || 'Nome do Serviço';
            const preco = document.getElementById('preco').value || '0,00';
            const duracao = document.getElementById('duracao').value || '0';
            const ativo = document.getElementById('ativo').checked;
            
            console.log('Preview:', {nome, preco, duracao, ativo});
        }
        
        // Atualizar preview em tempo real
        document.getElementById('nome').addEventListener('input', updatePreview);
        document.getElementById('preco').addEventListener('input', updatePreview);
        document.getElementById('duracao').addEventListener('input', updatePreview);
        document.getElementById('ativo').addEventListener('change', updatePreview);
    </script>
</body>
</html>