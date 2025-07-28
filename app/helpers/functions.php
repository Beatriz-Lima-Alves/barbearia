<?php
/**
 * Funções auxiliares do sistema
 */

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
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

/**
 * Formatar telefone brasileiro
 */
function formatPhone($phone) {
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
    return date($format, strtotime($date));
}

/**
 * Formatar data e hora brasileira
 */
function formatDateTime($datetime, $format = 'd/m/Y H:i') {
    return date($format, strtotime($datetime));
}

/**
 * Truncar texto
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

/**
 * Escapar HTML
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
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
    return SITE_URL . '/' . ltrim($path, '/');
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
    return trim(strip_tags($string));
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
    $today = new DateTime();
    $birth = new DateTime($birthDate);
    return $today->diff($birth)->y;
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
    return date('Y-m-d') === date('Y-m-d', strtotime($date));
}

/**
 * Verificar se a data é amanhã
 */
function isTomorrow($date) {
    return date('Y-m-d', strtotime('+1 day')) === date('Y-m-d', strtotime($date));
}

/**
 * Debug - imprimir variável
 */
function dd($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}
?>

