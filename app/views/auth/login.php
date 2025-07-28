<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Barbearia</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }
        
        .login-left {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 60px 40px;
            text-align: center;
            position: relative;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.5;
        }
        
        .login-left > * {
            position: relative;
            z-index: 1;
        }
        
        .login-right {
            padding: 60px 40px;
        }
        
        .brand-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }
        
        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .input-group-text {
            border-radius: 12px 0 0 12px;
            border: 2px solid #e9ecef;
            border-right: none;
            background-color: #f8f9fa;
        }
        
        .input-group .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }
        
        .input-group:focus-within .input-group-text {
            border-color: #667eea;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        .welcome-text {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .welcome-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .login-title {
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 30px;
        }
        
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-element:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .floating-element:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @media (max-width: 768px) {
            .login-container {
                margin: 20px;
                border-radius: 15px;
            }
            
            .login-left,
            .login-right {
                padding: 40px 30px;
            }
            
            .welcome-text {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Elementos flutuantes -->
    <div class="floating-elements">
        <i class="fas fa-cut floating-element" style="font-size: 3rem;"></i>
        <i class="fas fa-scissors floating-element" style="font-size: 2.5rem;"></i>
        <i class="fas fa-user-tie floating-element" style="font-size: 3.5rem;"></i>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="login-container row mx-0">
                    <!-- Lado esquerdo - Boas-vindas -->
                    <div class="col-md-6 login-left p-0 d-flex flex-column justify-content-center">
                        <div class="brand-icon">
                            <i class="fas fa-cut"></i>
                        </div>
                        <h1 class="welcome-text">Bem-vindo!</h1>
                        <p class="welcome-subtitle">
                            Sistema completo de gerenciamento para sua barbearia. 
                            Controle agendamentos, clientes e muito mais de forma simples e eficiente.
                        </p>
                        <div class="mt-4">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span>Agendamentos Online</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-users me-2"></i>
                                <span>Gestão de Clientes</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-chart-line me-2"></i>
                                <span>Relatórios Financeiros</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lado direito - Formulário -->
                    <div class="col-md-6 login-right p-0 d-flex flex-column justify-content-center">
                        <h2 class="login-title text-center">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Entrar no Sistema
                        </h2>
                        
                        <!-- Alertas -->
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= $_SESSION['error'] ?>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= $_SESSION['success'] ?>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?= SITE_URL ?>/login" novalidate>
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           placeholder="Digite seu email"
                                           value="<?= $_POST['email'] ?? '' ?>"
                                           required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="senha" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    Senha
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="senha" 
                                           name="senha" 
                                           placeholder="Digite sua senha"
                                           required>
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="lembrar"
                                           name="lembrar">
                                    <label class="form-check-label" for="lembrar">
                                        Lembrar-me neste dispositivo
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Entrar
                                <span class="loading spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Usuário padrão: admin@barbearia.com | Senha: admin123
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        function togglePassword() {
            const senhaInput = document.getElementById('senha');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (senhaInput.type === 'password') {
                senhaInput.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash';
            } else {
                senhaInput.type = 'password';
                toggleIcon.className = 'fas fa-eye';
            }
        }
        
        // Loading no formulário
        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            const loading = submitBtn.querySelector('.loading');
            
            loading.style.display = 'inline-block';
            submitBtn.disabled = true;
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Focus no primeiro campo
        document.getElementById('email').focus();
    </script>
</body>
</html>
