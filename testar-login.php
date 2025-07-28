<?php
require_once 'config/database.php';
require_once 'app/models/Usuario.php';

echo "<h2>Teste de Login</h2>";

$email = 'admin@barbearia.com';
$senha = 'admin123';

echo "<p>Testando login com:</p>";
echo "<p><strong>Email:</strong> $email</p>";
echo "<p><strong>Senha:</strong> $senha</p>";
echo "<hr>";

try {
    $usuarioModel = new Usuario();
    
    // Buscar usuário
    echo "<p>1. Buscando usuário por email...</p>";
    $usuario = $usuarioModel->getByEmail($email);
    
    if (!$usuario) {
        echo "<p style='color: red;'>❌ Usuário não encontrado com este email</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✅ Usuário encontrado:</p>";
    echo "<ul>";
    echo "<li>Nome: " . $usuario['nome'] . "</li>";
    echo "<li>Email: " . $usuario['email'] . "</li>";
    echo "<li>Tipo: " . $usuario['tipo'] . "</li>";
    echo "<li>Ativo: " . ($usuario['ativo'] ? 'Sim' : 'Não') . "</li>";
    echo "</ul>";
    
    // Verificar senha
    echo "<p>2. Verificando senha...</p>";
    $senhaValida = password_verify($senha, $usuario['senha']);
    
    if ($senhaValida) {
        echo "<p style='color: green;'>✅ Senha está correta!</p>";
        echo "<p style='color: green;'>✅ Login deveria funcionar!</p>";
        
        // Simular o que acontece no AuthController
        session_start();
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_nome'] = $usuario['nome'];
        $_SESSION['user_email'] = $usuario['email'];
        $_SESSION['user_tipo'] = $usuario['tipo'];
        $_SESSION['logged_in'] = true;
        
        echo "<p style='color: blue;'>🔄 Sessão criada com sucesso!</p>";
        echo "<p>Dados da sessão:</p>";
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        
    } else {
        echo "<p style='color: red;'>❌ Senha incorreta!</p>";
        echo "<p>Hash armazenado: " . $usuario['senha'] . "</p>";
        echo "<p>Senha testada: $senha</p>";
        
        // Tentar recriar a senha
        echo "<p>Recriando senha...</p>";
        $novaSenha = password_hash($senha, PASSWORD_DEFAULT);
        echo "<p>Nova hash: $novaSenha</p>";
        
        $sql = "UPDATE usuarios SET senha = ? WHERE id = ?";
        if (DB::execute($sql, [$novaSenha, $usuario['id']])) {
            echo "<p style='color: green;'>✅ Senha atualizada!</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erro: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>

