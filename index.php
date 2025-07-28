<?php
/**
 * Sistema de Barbearia
 * Arquivo principal - Router simples
 */

// Incluir configurações
require_once __DIR__ . '/config/database.php';

// Incluir controllers
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/DashboardController.php';
require_once __DIR__ . '/app/controllers/AgendamentoController.php';
require_once __DIR__ . '/app/controllers/ClienteController.php';
require_once __DIR__ . '/app/controllers/UsuarioController.php';
require_once __DIR__ . '/app/controllers/ServicoController.php';
require_once __DIR__ . '/app/controllers/FinanceiroController.php';
require_once __DIR__ . '/app/controllers/PerfilController.php';

/**
 * Router simples
 */
class Router {
    private $routes = [];
    private $authController;
    
    public function __construct() {
        $this->authController = new AuthController();
        $this->setupRoutes();
    }
    
    private function setupRoutes() {
        // Rotas de autenticação
        $this->addRoute('GET', '/', [$this->authController, 'login']);
        $this->addRoute('GET', '/login', [$this->authController, 'login']);
        $this->addRoute('POST', '/login', [$this->authController, 'processLogin']);
        $this->addRoute('GET', '/logout', [$this->authController, 'logout']);
        $this->addRoute('GET', '/register', [$this->authController, 'register']);
        $this->addRoute('POST', '/register', [$this->authController, 'processRegister']);
        
        // Dashboard
        $this->addRoute('GET', '/dashboard', [new DashboardController(), 'index']);
        
        // Agendamentos
        $agendamentoController = new AgendamentoController();
        $this->addRoute('GET', '/agendamentos', [$agendamentoController, 'index']);
        $this->addRoute('GET', '/agendamentos/create', [$agendamentoController, 'create']);
        $this->addRoute('POST', '/agendamentos/store', [$agendamentoController, 'store']);
        $this->addRoute('GET', '/agendamentos/edit/{id}', [$agendamentoController, 'edit']);
        $this->addRoute('POST', '/agendamentos/update/{id}', [$agendamentoController, 'update']);
        $this->addRoute('GET', '/agendamentos/cancel/{id}', [$agendamentoController, 'cancel']);
        $this->addRoute('POST', '/agendamentos/cancel/{id}', [$agendamentoController, 'cancel']);
        $this->addRoute('GET', '/agendamentos/complete/{id}', [$agendamentoController, 'complete']);
        $this->addRoute('POST', '/agendamentos/complete/{id}', [$agendamentoController, 'complete']);
        $this->addRoute('GET', '/agendamentos/show/{id}', [$agendamentoController, 'show']);
        $this->addRoute('GET', '/api/horarios-disponiveis', [$agendamentoController, 'getHorariosDisponiveis']);
        
        // Clientes
        $clienteController = new ClienteController();
        $this->addRoute('GET', '/clientes', [$clienteController, 'index']);
        $this->addRoute('GET', '/clientes/create', [$clienteController, 'create']);
        $this->addRoute('POST', '/clientes', [$clienteController, 'store']);
        $this->addRoute('GET', '/clientes/edit/{id}', [$clienteController, 'edit']);
        $this->addRoute('POST', '/clientes/update/{id}', [$clienteController, 'update']);
        // CORRIGIDO: Exclusão usando POST e DELETE
        $this->addRoute('POST', '/clientes/delete/{id}', [$clienteController, 'delete']);
        $this->addRoute('DELETE', '/clientes/delete/{id}', [$clienteController, 'delete']);
        $this->addRoute('GET', '/clientes/show/{id}', [$clienteController, 'show']);
        
        // Usuários/Barbeiros
        $usuarioController = new UsuarioController();
        $this->addRoute('GET', '/usuarios', [$usuarioController, 'index']);
        $this->addRoute('GET', '/usuarios/create', [$usuarioController, 'create']);
        $this->addRoute('POST', '/usuarios', [$usuarioController, 'store']);
        $this->addRoute('GET', '/usuarios/edit/{id}', [$usuarioController, 'edit']);
        $this->addRoute('POST', '/usuarios/update/{id}', [$usuarioController, 'update']);
        $this->addRoute('GET', '/usuarios/delete/{id}', [$usuarioController, 'delete']);
        $this->addRoute('DELETE', '/usuarios/delete/{id}', [$usuarioController, 'delete']);
        $this->addRoute('POST', '/usuarios/delete/{id}', [$usuarioController, 'delete']);
        $this->addRoute('GET', '/usuarios/show/{id}', [$usuarioController, 'show']);

        
        // Serviços
        $servicoController = new ServicoController();
        $this->addRoute('GET', '/servicos', [$servicoController, 'index']);
        $this->addRoute('GET', '/servicos/create', [$servicoController, 'create']);
        $this->addRoute('POST', '/servicos', [$servicoController, 'store']);
        $this->addRoute('GET', '/servicos/edit/{id}', [$servicoController, 'edit']);
        $this->addRoute('POST', '/servicos/update/{id}', [$servicoController, 'update']);
        $this->addRoute('GET', '/servicos/delete/{id}', [$servicoController, 'delete']);
        $this->addRoute('GET', '/servicos/reactive/{id}', [$servicoController, 'reactive']);
        $this->addRoute('GET', '/servicos/show/{id}', [$servicoController, 'show']);
        $this->addRoute('POST', '/servicos/duplicate/{id}', [$servicoController, 'duplicate']);
        
        // Financeiro
        $financeiroController = new FinanceiroController();
        $this->addRoute('GET', '/financeiro', [$financeiroController, 'index']);
        $this->addRoute('GET', '/financeiro/relatorio', [$financeiroController, 'relatorio']);
        $this->addRoute('GET', '/financeiro/exportar', [$financeiroController, 'exportar']);
        
        // Agenda
        $this->addRoute('GET', '/agenda', [$agendamentoController, 'agenda']);

        //Perfil
        $perfilController = new PerfilController();
        $this->addRoute('GET', '/perfil', [$perfilController, 'index']);
        $this->addRoute('POST', '/perfil/update', [$perfilController, 'update']);
    }
    
    private function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }
    
    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        
        // Remover query string
        $requestUri = strtok($requestUri, '?');
        
        // Remover base path se necessário
        $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/') {
            $requestUri = str_replace($basePath, '', $requestUri);
        }
        
        // Garantir que sempre comece com /
        if (empty($requestUri) || $requestUri[0] !== '/') {
            $requestUri = '/' . $requestUri;
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            
            $pattern = $this->convertToRegex($route['path']);
            
            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove full match
                
                // Extrair parâmetros nomeados
                $params = [];
                if (preg_match_all('/\{(\w+)\}/', $route['path'], $paramNames)) {
                    foreach ($paramNames[1] as $index => $name) {
                        if (isset($matches[$index])) {
                            $params[$name] = $matches[$index];
                        }
                    }
                }
                
                // Chamar callback
                return $this->callCallback($route['callback'], $params);
            }
        }
        
        // Rota não encontrada
        $this->show404();
    }
    
    private function convertToRegex($path) {
        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    private function callCallback($callback, $params = []) {
        if (is_array($callback)) {
            $controller = $callback[0];
            $method = $callback[1];
            
            if (empty($params)) {
                return $controller->$method();
            } else {
                return call_user_func_array([$controller, $method], array_values($params));
            }
        }
        
        return call_user_func($callback, $params);
    }
    
    private function show404() {
        http_response_code(404);
        include __DIR__ . '/app/views/errors/404.php';
        exit;
    }
}

// Tratamento de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Headers de segurança básicos
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Inicializar e executar router
try {
    $router = new Router();
    $router->dispatch();
} catch (Exception $e) {
    error_log("Erro no sistema: " . $e->getMessage());
    
    // Em produção, mostrar página de erro genérica
    http_response_code(500);
    include __DIR__ . '/app/views/errors/500.php';
}
?>
