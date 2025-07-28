<?php
/**
 * Funções auxiliares do sistema
 * Salvar como: C:\xampp\htdocs\barbearia-new\config\helpers.php
 */

/**
 * Constantes do sistema
 */
if (!defined('STATUS_AGENDAMENTO')) {
    define('STATUS_AGENDAMENTO', [
        'agendado' => 'Agendado',
        'confirmado' => 'Confirmado',
        'em_andamento' => 'Em Andamento',
        'realizado' => 'Realizado',
        'cancelado' => 'Cancelado',
        'nao_compareceu' => 'Não Compareceu'
    ]);
}

/**
 * Retorna valor antigo do formulário (após erro de validação)
 */
function old($field, $default = '') {
    return $_SESSION['form_data'][$field] ?? $default;
}

/**
 * Formatar moeda brasileira
 */
function formatMoney($valor) {
    if (empty($valor) || $valor == 0) {
        return 'R$ 0,00';
    }
    return 'R$ ' . number_format((float)$valor, 2, ',', '.');
}

/**
 * Formatar telefone brasileiro
 */
function formatPhone($phone) {
    if (empty($phone)) {
        return '';
    }
    
    $phone = preg_replace('/\D/', '', $phone);
    
    if (strlen($phone) === 11) {
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
    } elseif (strlen($phone) === 10) {
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
    }
    
    return $phone;
}

/**
 * Formatar data brasileira
 */
function formatDate($date, $format = 'd/m/Y') {
    if (empty($date) || $date === '0000-00-00') {
        return '';
    }
    
    try {
        return date($format, strtotime($date));
    } catch (Exception $e) {
        return '';
    }
}

/**
 * Formatar data e hora brasileira
 */
function formatDateTime($datetime, $format = 'd/m/Y H:i') {
    if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
        return '';
    }
    
    try {
        return date($format, strtotime($datetime));
    } catch (Exception $e) {
        return '';
    }
}

/**
 * Truncar texto
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (empty($text)) {
        return '';
    }
    
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

/**
 * Escapar HTML (alias para htmlspecialchars)
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Verificar se é o usuário atual
 */
function isCurrentUser($userId) {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId;
}

/**
 * Verificar se é admin
 */
function isAdmin() {
    return isset($_SESSION['user_tipo']) && $_SESSION['user_tipo'] === 'administrador';
}

/**
 * Gerar URL do sistema
 */
function url($path = '') {
    $baseUrl = 'http://localhost/barbearia-new'; // Ajustar conforme necessário
    return $baseUrl . '/' . ltrim($path, '/');
}

/**
 * Redirecionar
 */
function redirect($path = '') {
    header('Location: ' . url($path));
    exit;
}

/**
 * Verificar se a requisição é POST
 */
function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Verificar se a requisição é GET
 */
function isGet() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Obter valor do POST
 */
function post($key, $default = null) {
    return $_POST[$key] ?? $default;
}

/**
 * Obter valor do GET
 */
function get($key, $default = null) {
    return $_GET[$key] ?? $default;
}

/**
 * Definir mensagem flash
 */
function flash($type, $message) {
    $_SESSION[$type] = $message;
}

/**
 * Obter e limpar mensagem flash
 */
function getFlash($type) {
    if (isset($_SESSION[$type])) {
        $message = $_SESSION[$type];
        unset($_SESSION[$type]);
        return $message;
    }
    return null;
}

/**
 * Sanitizar string
 */
function sanitize($string) {
    return trim(strip_tags($string ?? ''));
}

/**
 * Validar email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Gerar senha aleatória
 */
function generatePassword($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    return substr(str_shuffle($chars), 0, $length);
}

/**
 * Calcular idade
 */
function calculateAge($birthDate) {
    if (empty($birthDate) || $birthDate === '0000-00-00') {
        return null;
    }
    
    try {
        $today = new DateTime();
        $birth = new DateTime($birthDate);
        return $today->diff($birth)->y;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Obter nome do mês em português
 */
function getMonthName($month) {
    $months = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
    ];
    
    return $months[(int)$month] ?? '';
}

/**
 * Obter nome do dia da semana em português
 */
function getDayName($day) {
    $days = [
        'Sunday' => 'Domingo', 'Monday' => 'Segunda-feira',
        'Tuesday' => 'Terça-feira', 'Wednesday' => 'Quarta-feira',
        'Thursday' => 'Quinta-feira', 'Friday' => 'Sexta-feira',
        'Saturday' => 'Sábado'
    ];
    
    return $days[$day] ?? $day;
}

/**
 * Verificar se a data é hoje
 */
function isToday($date) {
    if (empty($date)) return false;
    return date('Y-m-d') === date('Y-m-d', strtotime($date));
}

/**
 * Verificar se a data é amanhã
 */
function isTomorrow($date) {
    if (empty($date)) return false;
    return date('Y-m-d', strtotime('+1 day')) === date('Y-m-d', strtotime($date));
}

/**
 * Obter status formatado do agendamento
 */
function getStatusBadge($status) {
    $statusConfig = [
        'agendado' => ['class' => 'bg-secondary', 'text' => 'Agendado'],
        'confirmado' => ['class' => 'bg-info', 'text' => 'Confirmado'],
        'em_andamento' => ['class' => 'bg-warning', 'text' => 'Em Andamento'],
        'realizado' => ['class' => 'bg-success', 'text' => 'Realizado'],
        'cancelado' => ['class' => 'bg-danger', 'text' => 'Cancelado'],
        'nao_compareceu' => ['class' => 'bg-dark', 'text' => 'Não Compareceu']
    ];
    
    $config = $statusConfig[$status] ?? ['class' => 'bg-secondary', 'text' => ucfirst($status)];
    
    return sprintf(
        '<span class="badge %s">%s</span>',
        $config['class'],
        $config['text']
    );
}

/**
 * Debug - imprimir variável (apenas em desenvolvimento)
 */
function dd($var) {
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        die();
    }
}

/**
 * Log de erro personalizado
 */
function logError($message, $context = []) {
    $logMessage = date('Y-m-d H:i:s') . ' - ' . $message;
    if (!empty($context)) {
        $logMessage .= ' - Context: ' . json_encode($context);
    }
    error_log($logMessage);
}

/**
 * Validar CPF brasileiro
 */
function isValidCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);
    
    if (strlen($cpf) !== 11) return false;
    
    if (preg_match('/(\d)\1{10}/', $cpf)) return false;
    
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    
    return true;
}

/**
 * Formatar CPF
 */
function formatCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);
    
    if (strlen($cpf) === 11) {
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }
    
    return $cpf;
}

/**
 * Gerar cor aleatória para avatar
 */
function generateAvatarColor($string) {
    $colors = [
        '#e74c3c', '#3498db', '#2ecc71', '#f39c12', 
        '#9b59b6', '#1abc9c', '#34495e', '#e67e22'
    ];
    
    $hash = crc32($string);
    return $colors[abs($hash) % count($colors)];
}

?>

