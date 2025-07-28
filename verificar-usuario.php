<?php
require_once 'config/database.php';

echo "<h2>Verificação do Usuário Administrador</h2>";

try {
    // Verificar se a tabela existe
    $sql = "SHOW TABLES LIKE 'usuarios'";
    $result = DB::select($sql);
    
    if (empty($result)) {
        echo "<p style='color: red;'>❌ Tabela 'usuarios' não existe!</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✅ Tabela 'usuarios' existe</p>";
    
    // Verificar se há usuários
    $sql = "SELECT COUNT(*) as total FROM usuarios";
    $count = DB::selectOne($sql);
    echo "<p>Total de usuários: " . $count['total'] . "</p>";
    
    // Buscar o usuário admin
    $sql = "SELECT * FROM usuarios WHERE email = 'admin@barbearia.com'";
    $admin = DB::selectOne($sql);
    
    if ($admin) {
        echo "<p style='color: green;'>✅ Usuário admin encontrado:</p>";
        echo "<ul>";
        echo "<li>ID: " . $admin['id'] . "</li>";
        echo "<li>Nome: " . $admin['nome'] . "</li>";
        echo "<li>Email: " . $admin['email'] . "</li>";
        echo "<li>Tipo: " . $admin['tipo'] . "</li>";
        echo "<li>Ativo: " . ($admin['ativo'] ? 'Sim' : 'Não') . "</li>";
        echo "<li>Senha hash: " . substr($admin['senha'], 0, 20) . "...</li>";
        echo "</ul>";
        
        // Testar a senha
        $senhaCorreta = password_verify('admin123', $admin['senha']);
        if ($senhaCorreta) {
            echo "<p style='color: green;'>✅ Senha 'admin123' está correta!</p>";
        } else {
            echo "<p style='color: red;'>❌ Senha 'admin123' está incorreta!</p>";
            
            // Criar nova senha
            $novaSenha = password_hash('admin123', PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET senha = ? WHERE email = 'admin@barbearia.com'";
            if (DB::execute($sql, [$novaSenha])) {
                echo "<p style='color: blue;'>🔄 Senha foi redefinida para 'admin123'</p>";
            }
        }
        
    } else {
        echo "<p style='color: red;'>❌ Usuário admin não encontrado!</p>";
        
        // Criar o usuário admin
        echo "<p>Criando usuário administrador...</p>";
        
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo, ativo) VALUES (?, ?, ?, ?, ?)";
        $senha = password_hash('admin123', PASSWORD_DEFAULT);
        $params = ['Administrador', 'admin@barbearia.com', $senha, 'administrador', 1];
        
        if (DB::insert($sql, $params)) {
            echo "<p style='color: green;'>✅ Usuário administrador criado com sucesso!</p>";
            echo "<p><strong>Email:</strong> admin@barbearia.com</p>";
            echo "<p><strong>Senha:</strong> admin123</p>";
        } else {
            echo "<p style='color: red;'>❌ Erro ao criar usuário administrador</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erro: " . $e->getMessage() . "</p>";
}
?>

