<?php
/**
 * Arquivo de teste para view show.php
 * Salvar como: C:\xampp\htdocs\barbearia-new\test-show-cliente.php
 */

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir arquivo de configuração
require_once __DIR__ . '/config/config.php';

// Dados de teste do cliente
$cliente = [
    'id' => 1,
    'nome' => 'BEATRIZ LIMA ALVES',
    'telefone' => '11999999999',
    'email' => 'beatriz@email.com',
    'data_nascimento' => '1990-05-15',
    'endereco' => 'Rua das Flores, 123 - Centro',
    'observacoes' => 'Cliente preferencial, gosta de cortes modernos.',
    'data_cadastro' => '2024-01-15 10:30:00'
];

// Dados de teste dos agendamentos
$agendamentos = [
    [
        'id' => 1,
        'data_agendamento' => '2024-07-20 14:00:00',
        'servico_nome' => 'Corte + Barba',
        'barbeiro_nome' => 'João Silva',
        'status' => 'realizado',
        'valor' => 45.00
    ],
    [
        'id' => 2,
        'data_agendamento' => '2024-07-25 16:30:00',
        'servico_nome' => 'Corte Simples',
        'barbeiro_nome' => 'Pedro Santos',
        'status' => 'agendado',
        'valor' => 30.00
    ],
    [
        'id' => 3,
        'data_agendamento' => '2024-06-15 15:00:00',
        'servico_nome' => 'Barba',
        'barbeiro_nome' => 'João Silva',
        'status' => 'cancelado',
        'valor' => 20.00
    ]
];

// Definir variáveis para o layout
$title = 'Detalhes do Cliente - Sistema de Barbearia';
$currentPage = 'clientes';

// Capturar conteúdo da view
ob_start();
?>

<div class="container-fluid px-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user me-2"></i>
                <?= htmlspecialchars($cliente['nome']) ?>
            </h1>
            <p class="text-muted mb-0">Cliente #<?= $cliente['id'] ?></p>
        </div>
        <div>
            <a href="/clientes" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>
                Voltar
            </a>
            <a href="/clientes/edit/<?= $cliente['id'] ?>" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i>
                Editar
            </a>
            <a href="/agendamentos/create?cliente_id=<?= $cliente['id'] ?>" class="btn btn-success">
                <i class="fas fa-calendar-plus me-1"></i>
                Novo Agendamento
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informações do Cliente -->
        <div class="col-lg-4">
            <!-- Card Principal -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-primary text-white me-3" style="width: 60px; height: 60px; font-size: 24px;">
                            <?= strtoupper(substr($cliente['nome'], 0, 2)) ?>
                        </div>
                        <div>
                            <h5 class="mb-0"><?= htmlspecialchars($cliente['nome']) ?></h5>
                            <small class="text-muted">Cliente desde <?= formatDate($cliente['data_cadastro']) ?></small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Telefone -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <strong>Telefone</strong>
                        </div>
                        <div class="ms-4">
                            <a href="tel:<?= $cliente['telefone'] ?>" class="text-decoration-none">
                                <?= formatPhone($cliente['telefone']) ?>
                            </a>
                            <button class="btn btn-sm btn-outline-success ms-2" onclick="copyToClipboard('<?= $cliente['telefone'] ?>')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Email -->
                    <?php if (!empty($cliente['email'])): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <strong>E-mail</strong>
                        </div>
                        <div class="ms-4">
                            <a href="mailto:<?= $cliente['email'] ?>" class="text-decoration-none">
                                <?= htmlspecialchars($cliente['email']) ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Data de Nascimento -->
                    <?php if (!empty($cliente['data_nascimento'])): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-birthday-cake text-primary me-2"></i>
                            <strong>Nascimento</strong>
                        </div>
                        <div class="ms-4">
                            <?= formatDate($cliente['data_nascimento']) ?>
                            <span class="badge bg-info ms-2">
                                <?= calculateAge($cliente['data_nascimento']) ?> anos
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Endereço -->
                    <?php if (!empty($cliente['endereco'])): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <strong>Endereço</strong>
                        </div>
                        <div class="ms-4">
                            <?= htmlspecialchars($cliente['endereco']) ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Data de Cadastro -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-calendar text-primary me-2"></i>
                            <strong>Cadastrado em</strong>
                        </div>
                        <div class="ms-4">
                            <?= formatDateTime($cliente['data_cadastro']) ?>
                            <br><small class="text-muted">
                                há <?= max(0, floor((time() - strtotime($cliente['data_cadastro'])) / (24*60*60))) ?> dias
                            </small>
                        </div>
                    </div>

                    <!-- Observações -->
                    <?php if (!empty($cliente['observacoes'])): ?>
                    <div class="mb-0">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-comment text-primary me-2"></i>
                            <strong>Observações</strong>
                        </div>
                        <div class="ms-4">
                            <div class="alert alert-light">
                                <?= nl2br(htmlspecialchars($cliente['observacoes'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Estatísticas Rápidas -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Estatísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 text-primary mb-0">
                                    <?= isset($agendamentos) ? count($agendamentos) : 0 ?>
                                </div>
                                <small class="text-muted">Agendamentos</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-success mb-0">
                                <?php
                                $realizados = 0;
                                if (isset($agendamentos)) {
                                    foreach ($agendamentos as $agend) {
                                        if ($agend['status'] === 'realizado') $realizados++;
                                    }
                                }
                                echo $realizados;
                                ?>
                            </div>
                            <small class="text-muted">Realizados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Histórico de Agendamentos -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-calendar-check me-2"></i>
                            Histórico de Agendamentos
                        </h6>
                        <a href="/agendamentos/create?cliente_id=<?= $cliente['id'] ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i>
                            Novo Agendamento
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($agendamentos)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum agendamento encontrado</h5>
                            <p class="text-muted">Este cliente ainda não possui agendamentos.</p>
                            <a href="/agendamentos/create?cliente_id=<?= $cliente['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-1"></i>
                                Criar Primeiro Agendamento
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Data/Hora</th>
                                        <th>Serviço</th>
                                        <th>Barbeiro</th>
                                        <th>Status</th>
                                        <th>Valor</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($agendamentos as $agendamento): ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <strong><?= formatDate($agendamento['data_agendamento']) ?></strong>
                                            </div>
                                            <small class="text-muted">
                                                <?= date('H:i', strtotime($agendamento['data_agendamento'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($agendamento['servico_nome'] ?? 'Não informado') ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($agendamento['barbeiro_nome'] ?? 'Não informado') ?>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'agendado' => 'bg-secondary',
                                                'confirmado' => 'bg-info',
                                                'em_andamento' => 'bg-warning',
                                                'realizado' => 'bg-success',
                                                'cancelado' => 'bg-danger',
                                                'nao_compareceu' => 'bg-dark'
                                            ];
                                            $class = $statusClass[$agendamento['status']] ?? 'bg-secondary';
                                            ?>
                                            <span class="badge <?= $class ?>">
                                                <?= STATUS_AGENDAMENTO[$agendamento['status']] ?? $agendamento['status'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($agendamento['valor'])): ?>
                                                <?= formatMoney($agendamento['valor']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="/agendamentos/show/<?= $agendamento['id'] ?>" 
                                                   class="btn btn-outline-primary" title="Ver detalhes">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($agendamento['status'] === 'agendado' || $agendamento['status'] === 'confirmado'): ?>
                                                <a href="/agendamentos/edit/<?= $agendamento['id'] ?>" 
                                                   class="btn btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
    background-color: #f8f9fc;
}
</style>

<script>
// Função para copiar texto
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Telefone copiado: ' + text);
    }).catch(function(err) {
        console.error('Erro ao copiar: ', err);
    });
}
</script>

<?php
$content = ob_get_clean();

// Incluir layout
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
        body { background-color: #f8f9fa; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(45deg, #2c3e50, #34495e); color: white; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="py-4">
            <?= $content ?>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

