<?php
/**
 * VIEW DE EDIÇÃO DO PERFIL
 * Salvar como: C:\xampp\htdocs\barbearia-new\app\views\perfil\edit.php
 */

$title = 'Editar Perfil - Sistema de Barbearia';
$currentPage = 'perfil';

// Recuperar dados do formulário se houver erro
$formData = $_SESSION['form_data'] ?? [];
$usuario = array_merge($usuario, $formData);

ob_start();
?>

<div class="container-fluid px-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit me-2"></i>
                Editar Perfil
            </h1>
            <p class="text-muted mb-0">Atualize suas informações pessoais</p>
        </div>
        <a href="<?= SITE_URL ?>/perfil" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Voltar ao Perfil
        </a>
    </div>

    <!-- Mensagens de Erro -->
    <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Corrigir os seguintes erros:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
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

    <div class="row">
        <!-- Avatar e Info Básica -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle me-2"></i>
                        Informações do Perfil
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="profile-avatar bg-primary text-white mx-auto mb-3">
                        <?= strtoupper(substr($usuario['nome'], 0, 2)) ?>
                    </div>
                    <h5 class="mb-1"><?= htmlspecialchars($usuario['nome']) ?></h5>
                    <span class="badge <?= $usuario['tipo'] === 'administrador' ? 'bg-warning' : 'bg-info' ?>">
                        <?= $usuario['tipo'] === 'administrador' ? 'Administrador' : 'Barbeiro' ?>
                    </span>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            Membro desde <?= formatDate($usuario['data_criacao'] ?? date('Y-m-d')) ?>
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Dicas de Segurança -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-shield-alt me-2"></i>
                        Dicas de Segurança
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info security-tips">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Dica:</strong> Use uma senha forte com pelo menos 6 caracteres.
                        </small>
                    </div>
                    <div class="alert alert-warning security-tips">
                        <small>
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Atenção:</strong> Mantenha suas informações sempre atualizadas.
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Formulário de Edição -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>
                        Atualizar Dados
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= SITE_URL ?>/perfil/update">
                        <!-- Informações Pessoais -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>
                                    Informações Pessoais
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nome Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    E-mail <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($usuario['email']) ?>" required>
                            </div>
                            
                            <!-- <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Telefone <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control" id="telefone" name="telefone" 
                                       value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>"
                                       placeholder="(11) 99999-9999" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="data_nascimento" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>
                                    Data de Nascimento
                                </label>
                                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" 
                                       value="<?= htmlspecialchars($usuario['data_nascimento'] ?? '') ?>">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="endereco" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    Endereço
                                </label>
                                <input type="text" class="form-control" id="endereco" name="endereco" 
                                       value="<?= htmlspecialchars($usuario['endereco'] ?? '') ?>"
                                       placeholder="Rua, número, bairro, cidade">
                            </div> -->
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Alterar Senha -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-key me-2"></i>
                                    Alterar Senha
                                    <small class="text-muted">(opcional)</small>
                                </h6>
                                <div class="alert alert-info password-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Importante:</strong> Deixe os campos de senha em branco se não quiser alterá-la.
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="senha_atual" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    Senha Atual
                                </label>
                                <input type="password" class="form-control" id="senha_atual" name="senha_atual"
                                       placeholder="Digite sua senha atual">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="nova_senha" class="form-label">
                                    <i class="fas fa-key me-1"></i>
                                    Nova Senha
                                </label>
                                <input type="password" class="form-control" id="nova_senha" name="nova_senha"
                                       placeholder="Mínimo 6 caracteres">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="confirmar_senha" class="form-label">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Confirmar Nova Senha
                                </label>
                                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha"
                                       placeholder="Digite novamente">
                            </div>
                        </div>
                        
                        <!-- Botões -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="<?= SITE_URL ?>/perfil" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Salvar Alterações
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.form-control:focus {
    border-color: #8B5CF6;
    box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
}

.btn-primary {
    background-color: #8B5CF6;
    border-color: #8B5CF6;
}

.btn-primary:hover {
    background-color: #7C3AED;
    border-color: #7C3AED;
}

/* Forçar visibilidade das dicas de segurança */
.security-tips {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.password-warning {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    background-color: #d1ecf1 !important;
    border: 1px solid #bee5eb !important;
    margin-bottom: 20px !important;
}
</style>

<script>
// Validação do formulário
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const novaSenha = document.getElementById('nova_senha');
    const confirmarSenha = document.getElementById('confirmar_senha');
    const senhaAtual = document.getElementById('senha_atual');
    
    // Validar senhas em tempo real
    function validarSenhas() {
        if (novaSenha.value !== '' || confirmarSenha.value !== '') {
            if (novaSenha.value !== confirmarSenha.value) {
                confirmarSenha.setCustomValidity('As senhas não conferem');
            } else {
                confirmarSenha.setCustomValidity('');
            }
            
            if (novaSenha.value !== '' && senhaAtual.value === '') {
                senhaAtual.setCustomValidity('Senha atual é obrigatória para alterar a senha');
            } else {
                senhaAtual.setCustomValidity('');
            }
        } else {
            confirmarSenha.setCustomValidity('');
            senhaAtual.setCustomValidity('');
        }
    }
    
    novaSenha.addEventListener('input', validarSenhas);
    confirmarSenha.addEventListener('input', validarSenhas);
    senhaAtual.addEventListener('input', validarSenhas);
    
    // Máscara para telefone
    const telefone = document.getElementById('telefone');
    if (telefone) {
        telefone.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length >= 7) {
                value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else if (value.length >= 3) {
                value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            } else if (value.length >= 1) {
                value = value.replace(/(\d{0,2})/, '($1');
            }
            e.target.value = value;
        });
    }
});
</script>

<?php
$content = ob_get_clean();

// Limpar dados do formulário se existirem
unset($_SESSION['form_data']);

include __DIR__ . '/../layouts/main.php';
?>
