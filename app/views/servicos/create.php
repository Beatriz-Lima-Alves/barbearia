<?php
/**
 * VIEW DE CRIAÇÃO DE SERVIÇOS
 * Salvar como: app/views/servicos/create.php
 */

$title = 'Novo Serviço';
$currentPage = 'servicos';

// Função para recuperar dados do formulário
function old($key, $default = '') {
    return $_SESSION['form_data'][$key] ?? $default;
}

ob_start();
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cut me-2"></i>Novo Serviço
        </h1>
        <a href="<?= SITE_URL ?>/servicos" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Voltar
        </a>
    </div>

    <!-- Mensagens de erro -->
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Atenção!</strong> Corrija os erros abaixo:
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
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cut me-2"></i>Dados do Serviço
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= SITE_URL ?>/servicos" id="servicoForm">
                        
                        <!-- Nome e Categoria -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="nome" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Nome do Serviço *
                                </label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       value="<?= htmlspecialchars(old('nome')) ?>"
                                       placeholder="Ex: Corte Masculino, Barba Completa" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="categoria" class="form-label">
                                    <i class="fas fa-layer-group me-1"></i>Categoria
                                </label>
                                <select class="form-select" id="categoria" name="categoria">
                                    <option value="corte" <?= old('categoria') === 'corte' ? 'selected' : '' ?>>Corte</option>
                                    <option value="barba" <?= old('categoria') === 'barba' ? 'selected' : '' ?>>Barba</option>
                                    <option value="combo" <?= old('categoria') === 'combo' ? 'selected' : '' ?>>Combo</option>
                                    <option value="especial" <?= old('categoria') === 'especial' ? 'selected' : '' ?>>Especial</option>
                                    <option value="tratamento" <?= old('categoria') === 'tratamento' ? 'selected' : '' ?>>Tratamento</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Preço e Duração -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="preco" class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>Preço (R$) *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" id="preco" name="preco" 
                                           value="<?= htmlspecialchars(old('preco')) ?>"
                                           placeholder="0,00" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="duracao" class="form-label">
                                    <i class="fas fa-clock me-1"></i>Duração (minutos) *
                                </label>
                                <select class="form-select" id="duracao" name="duracao">
                                    <option value="15" <?= old('duracao') == 15 ? 'selected' : '' ?>>15 minutos</option>
                                    <option value="30" <?= old('duracao') == 30 || !old('duracao') ? 'selected' : '' ?>>30 minutos</option>
                                    <option value="45" <?= old('duracao') == 45 ? 'selected' : '' ?>>45 minutos</option>
                                    <option value="60" <?= old('duracao') == 60 ? 'selected' : '' ?>>1 hora</option>
                                    <option value="90" <?= old('duracao') == 90 ? 'selected' : '' ?>>1h30min</option>
                                    <option value="120" <?= old('duracao') == 120 ? 'selected' : '' ?>>2 horas</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Descrição -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Descrição *
                            </label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4" 
                                      placeholder="Descreva o serviço, o que inclui, técnicas utilizadas..." required><?= htmlspecialchars(old('descricao')) ?></textarea>
                        </div>
                        
                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= SITE_URL ?>/servicos" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Salvar Serviço
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Coluna lateral com dicas -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-lightbulb me-2"></i>Dicas para Cadastro
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-tag text-primary me-2"></i>
                            <strong>Nome do Serviço</strong>
                        </div>
                        <p class="small text-muted mb-0">Use nomes claros e descritivos que os clientes entenderão facilmente.</p>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-dollar-sign text-success me-2"></i>
                            <strong>Preço</strong>
                        </div>
                        <p class="small text-muted mb-0">Considere o custo dos produtos utilizados e o tempo gasto.</p>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-warning me-2"></i>
                            <strong>Duração</strong>
                        </div>
                        <p class="small text-muted mb-0">Seja realista com o tempo. Inclua preparação e finalização.</p>
                    </div>
                    
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-align-left text-info me-2"></i>
                            <strong>Descrição</strong>
                        </div>
                        <p class="small text-muted mb-0">Detalhe o que está incluído no serviço para evitar mal-entendidos.</p>
                    </div>
                </div>
            </div>
            
            <!-- Card com serviços populares -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-star me-2"></i>Exemplos de Serviços
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span class="small"><strong>Corte Social</strong></span>
                                <span class="small text-muted">R$ 25,00 - 30min</span>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span class="small"><strong>Barba Completa</strong></span>
                                <span class="small text-muted">R$ 20,00 - 30min</span>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span class="small"><strong>Corte + Barba</strong></span>
                                <span class="small text-muted">R$ 40,00 - 60min</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para preço
    const precoInput = document.getElementById('preco');
    precoInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = (value / 100).toFixed(2);
        value = value.replace('.', ',');
        e.target.value = value;
    });
    
    // Auto-resize textarea
    const textarea = document.getElementById('descricao');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
    
    // Focus no primeiro campo
    document.getElementById('nome').focus();
    
    // Validação do formulário
    document.getElementById('servicoForm').addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const preco = document.getElementById('preco').value.trim();
        const descricao = document.getElementById('descricao').value.trim();
        
        if (!nome) {
            alert('Por favor, preencha o nome do serviço');
            e.preventDefault();
            return;
        }
        
        if (!preco || parseFloat(preco.replace(',', '.')) <= 0) {
            alert('Por favor, preencha um preço válido');
            e.preventDefault();
            return;
        }
        
        if (!descricao) {
            alert('Por favor, preencha a descrição do serviço');
            e.preventDefault();
            return;
        }
    });
});
</script>

<?php
$content = ob_get_clean();

// Limpar dados do formulário após uso
if (isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}

// Incluir o layout principal se existir
$layoutPath = __DIR__ . '/../layouts/main.php';
if (file_exists($layoutPath)) {
    include $layoutPath;
} else {
    // Layout básico se não existir o main.php
    echo "<!DOCTYPE html>";
    echo "<html><head><title>{$title}</title>";
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">';
    echo "</head><body>";
    echo $content;
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>';
    echo "</body></html>";
}
?>
