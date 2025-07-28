<?php
/**
 * VIEW DE CADASTRO DE USUÁRIOS
 * Salvar como: C:\xampp\htdocs\barbearia-new\app\views\usuarios\create.php
 */

// Funções auxiliares (caso não existam)
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$title = 'Novo Usuário - Sistema de Barbearia';
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
            --info-color: #3B82F6;
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
        }
        
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .required {
            color: var(--accent-color);
        }
        
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        .password-wrapper {
            position: relative;
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

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3">
                            <i class="fas fa-user-plus me-2"></i>
                            Novo Usuário
                        </h1>
                        <p class="text-muted mb-0">Cadastre um novo barbeiro ou administrador no sistema</p>
                    </div>
                    <a href="/barbearia-new/usuarios" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Voltar
                    </a>
                </div>

                <!-- Formulário -->
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user-cog me-2"></i>
                                    Dados do Usuário
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="/barbearia-new/usuarios" id="formUsuario">
                                    <div class="row">
                                        <!-- Nome -->
                                        <div class="col-md-6 mb-3">
                                            <label for="nome" class="form-label">
                                                Nome Completo <span class="required">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="nome" 
                                                   name="nome" 
                                                   required
                                                   placeholder="Digite o nome completo">
                                        </div>
                                        
                                        <!-- Email -->
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">
                                                E-mail <span class="required">*</span>
                                            </label>
                                            <input type="email" 
                                                   class="form-control" 
                                                   id="email" 
                                                   name="email" 
                                                   required
                                                   placeholder="Digite o e-mail">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <!-- Telefone -->
                                        <div class="col-md-6 mb-3">
                                            <label for="telefone" class="form-label">Telefone</label>
                                            <input type="tel" 
                                                   class="form-control" 
                                                   id="telefone" 
                                                   name="telefone" 
                                                   placeholder="(00) 00000-0000">
                                        </div>
                                        
                                        <!-- Tipo -->
                                        <div class="col-md-6 mb-3">
                                            <label for="tipo" class="form-label">
                                                Tipo de Usuário <span class="required">*</span>
                                            </label>
                                            <select class="form-select" id="tipo" name="tipo" required>
                                                <option value="">Selecione o tipo</option>
                                                <option value="barbeiro">Barbeiro</option>
                                                <option value="administrador">Administrador</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Endereço -->
                                    <div class="mb-3">
                                        <label for="endereco" class="form-label">Endereço</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="endereco" 
                                               name="endereco" 
                                               placeholder="Digite o endereço completo">
                                    </div>
                                    
                                    <div class="row" id="barbeiroFields" style="display: none;">
                                        <!-- Especialidade -->
                                        <div class="col-md-6 mb-3">
                                            <label for="especialidade" class="form-label">Especialidade</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="especialidade" 
                                                   name="especialidade" 
                                                   placeholder="Ex: Corte masculino, Barba, Bigode">
                                        </div>
                                        
                                        <!-- Comissão
                                        <div class="col-md-6 mb-3">
                                            <label for="comissao" class="form-label">Comissão (%)</label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="comissao" 
                                                   name="comissao" 
                                                   min="0" 
                                                   max="100" 
                                                   step="0.1"
                                                   placeholder="0.0">
                                            <div class="form-text">Percentual de comissão por serviço realizado</div>
                                        </div> -->
                                    </div>
                                    
                                    <hr>
                                    
                                    <h6 class="mb-3">
                                        <i class="fas fa-lock me-2"></i>
                                        Configurações de Acesso
                                    </h6>
                                    
                                    <div class="row">
                                        <!-- Senha -->
                                        <div class="col-md-6 mb-3">
                                            <label for="senha" class="form-label">
                                                Senha <span class="required">*</span>
                                            </label>
                                            <div class="password-wrapper">
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="senha" 
                                                       name="senha" 
                                                       required
                                                       minlength="6"
                                                       placeholder="Digite a senha">
                                                <i class="fas fa-eye password-toggle" onclick="togglePassword('senha')"></i>
                                            </div>
                                            <div class="form-text">Mínimo de 6 caracteres</div>
                                        </div>
                                        
                                        <!-- Confirmar Senha -->
                                        <div class="col-md-6 mb-3">
                                            <label for="confirmar_senha" class="form-label">
                                                Confirmar Senha <span class="required">*</span>
                                            </label>
                                            <div class="password-wrapper">
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="confirmar_senha" 
                                                       name="confirmar_senha" 
                                                       required
                                                       placeholder="Confirme a senha">
                                                <i class="fas fa-eye password-toggle" onclick="togglePassword('confirmar_senha')"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Botões -->
                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="/barbearia-new/usuarios" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i>
                                            Cancelar
                                        </a>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            Cadastrar Usuário
                                        </button>
                                    </div>
                                </form>
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
        // Função para mostrar/ocultar senha
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
            }
        }
        
        // Mostrar/ocultar campos específicos do barbeiro
        document.getElementById('tipo').addEventListener('change', function() {
            const barbeiroFields = document.getElementById('barbeiroFields');
            if (this.value === 'barbeiro') {
                barbeiroFields.style.display = 'block';
            } else {
                barbeiroFields.style.display = 'none';
            }
        });
        
        // Máscara para telefone
        document.getElementById('telefone').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            this.value = value;
        });
        
        // Validação de senhas iguais
        document.getElementById('formUsuario').addEventListener('submit', function(e) {
            const senha = document.getElementById('senha').value;
            const confirmarSenha = document.getElementById('confirmar_senha').value;
            
            if (senha !== confirmarSenha) {
                e.preventDefault();
                alert('As senhas não conferem. Por favor, verifique e tente novamente.');
                document.getElementById('confirmar_senha').focus();
                return false;
            }
            
            // Mostrar loading no botão
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Cadastrando...';
            submitBtn.disabled = true;
            
            // Restaurar botão após 5 segundos (fallback)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
        
        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>

</body>
</html>

