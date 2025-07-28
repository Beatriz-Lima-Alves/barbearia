<?php
/**
 * PÁGINA DE ERRO 404 - SISTEMA BARBEARIA
 * Salvar como: C:\xampp\htdocs\barbearia-new\app\views\errors\404.php
 */

// Configurar headers HTTP
http_response_code(404);
header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada - Sistema Barbearia</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
        }
        
        .error-container {
            text-align: center;
            color: white;
            max-width: 600px;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            text-shadow: 0 10px 20px rgba(0,0,0,0.3);
            animation: bounce 2s infinite;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
            font-weight: 500;
        }
        
        .error-description {
            font-size: 1.1rem;
            margin-bottom: 3rem;
            opacity: 0.8;
            line-height: 1.6;
        }
        
        .btn-custom {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            margin: 0.5rem;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: white;
            text-decoration: none;
        }
        
        .btn-secondary-custom {
            background: linear-gradient(45deg, #74b9ff, #0984e3);
        }
        
        .floating-icon {
            position: absolute;
            animation: float 3s ease-in-out infinite;
            opacity: 0.1;
            z-index: 1;
        }
        
        .floating-icon:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-icon:nth-child(2) {
            top: 20%;
            right: 10%;
            animation-delay: 1s;
        }
        
        .floating-icon:nth-child(3) {
            bottom: 10%;
            left: 15%;
            animation-delay: 2s;
        }
        
        .floating-icon:nth-child(4) {
            bottom: 20%;
            right: 20%;
            animation-delay: 0.5s;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        .back-link {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            opacity: 0.8;
            margin: 0.5rem 1rem;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: white;
            opacity: 1;
            transform: translateX(5px);
        }
        
        .countdown {
            font-size: 0.9rem;
            opacity: 0.7;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }
            
            .error-message {
                font-size: 1.5rem;
            }
            
            .error-container {
                padding: 1rem;
            }
            
            .btn-custom {
                display: block;
                margin: 0.5rem auto;
                max-width: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Ícones flutuantes -->
    <div class="floating-icon">
        <i class="fas fa-cut fa-3x"></i>
    </div>
    <div class="floating-icon">
        <i class="fas fa-scissors fa-2x"></i>
    </div>
    <div class="floating-icon">
        <i class="fas fa-user-tie fa-3x"></i>
    </div>
    <div class="floating-icon">
        <i class="fas fa-calendar-alt fa-2x"></i>
    </div>

    <div class="error-container">
        <div class="error-code">404</div>
        
        <h1 class="error-message">Oops! Página não encontrada</h1>
        
        <p class="error-description">
            A página que você está procurando não existe ou foi movida.<br>
            Pode ser um link quebrado ou você digitou o endereço incorretamente.
        </p>
        
        <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-2">
            <a href="/barbearia-new/dashboard" class="btn-custom">
                <i class="fas fa-home me-2"></i>
                Voltar ao Dashboard
            </a>
            
            <a href="javascript:history.back()" class="btn-custom btn-secondary-custom">
                <i class="fas fa-arrow-left me-2"></i>
                Página Anterior
            </a>
        </div>
        
        <div class="mt-4">
            <a href="/barbearia-new/clientes" class="back-link">
                <i class="fas fa-users me-1"></i>
                Ir para Clientes
            </a>
            
            <a href="/barbearia-new/agendamentos" class="back-link">
                <i class="fas fa-calendar-alt me-1"></i>
                Ir para Agendamentos
            </a>
        </div>
        
        <div class="countdown">
            <small class="opacity-75">
                <i class="fas fa-clock me-1"></i>
                Você será redirecionado automaticamente em <span id="countdown">10</span> segundos...
            </small>
        </div>
        
        <div class="mt-3">
            <small class="opacity-75">
                Se você acredita que isso é um erro, entre em contato com o administrador do sistema.
            </small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Countdown e redirecionamento automático
        let timeLeft = 10;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(function() {
            timeLeft--;
            countdownElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                window.location.href = '/barbearia-new/dashboard';
            }
        }, 1000);
        
        // Cancelar timer se usuário interagir
        document.addEventListener('click', function() {
            clearInterval(timer);
            countdownElement.parentElement.style.display = 'none';
        });
        
        document.addEventListener('keypress', function() {
            clearInterval(timer);
            countdownElement.parentElement.style.display = 'none';
        });
        
        // Detectar se veio de tentativa de exclusão
        if (window.location.href.includes('/delete/')) {
            const errorDescription = document.querySelector('.error-description');
            errorDescription.innerHTML = `
                <strong>Erro na exclusão de cliente</strong><br>
                A funcionalidade de exclusão não está disponível ou você não tem permissão para realizar esta ação.
            `;
        }
    </script>
</body>
</html>
