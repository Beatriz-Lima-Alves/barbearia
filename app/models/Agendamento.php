<?php
require_once __DIR__ . '/../../config/database.php';

/**
 * Model para agendamentos
 */
class Agendamento {
    
    /**
     * Buscar agendamento por ID
     */
    public function getById($id) {
        $sql = "SELECT a.*, c.nome as cliente_nome, c.telefone as cliente_telefone, c.email as cliente_email,
                       u.nome as barbeiro_nome, s.nome as servico_nome, s.preco as servico_preco, s.duracao as servico_duracao
                FROM agendamentos a
                JOIN clientes c ON a.cliente_id = c.id
                JOIN usuarios u ON a.barbeiro_id = u.id
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.id = ?";
        
        return DB::selectOne($sql, [$id]);
    }
    
    /**
     * Listar todos os agendamentos
     */
    public function getAll($filters = []) {
        try {
            $sql = "SELECT a.*, 
                           c.nome as cliente_nome, 
                           c.telefone as cliente_telefone,
                           c.email as cliente_email,
                           u.nome as barbeiro_nome, 
                           s.nome as servico_nome, 
                           s.preco as servico_preco,
                           s.duracao as servico_duracao
                    FROM agendamentos a
                    LEFT JOIN clientes c ON a.cliente_id = c.id
                    LEFT JOIN usuarios u ON a.barbeiro_id = u.id
                    LEFT JOIN servicos s ON a.servico_id = s.id";
            
            $conditions = [];
            $params = [];
            
            // Debug: Log dos filtros recebidos
            error_log("Filtros recebidos no getAll: " . print_r($filters, true));
            
            // Filtros com validação melhorada
            if (isset($filters['data_inicio']) && !empty($filters['data_inicio'])) {
                $conditions[] = "a.data_agendamento >= ?";
                $params[] = $filters['data_inicio'];
                error_log("Filtro data_inicio aplicado: " . $filters['data_inicio']);
            }
            
            if (isset($filters['data_fim']) && !empty($filters['data_fim'])) {
                $conditions[] = "a.data_agendamento <= ?";
                $params[] = $filters['data_fim'];
                error_log("Filtro data_fim aplicado: " . $filters['data_fim']);
            }
            
            // CORREÇÃO: Validação melhorada para barbeiro_id
            if (isset($filters['barbeiro_id'])) {
                // Aceitar tanto string quanto int, mas não aceitar string vazia
                if (is_numeric($filters['barbeiro_id']) || (!empty($filters['barbeiro_id']) && $filters['barbeiro_id'] !== '')) {
                    $conditions[] = "a.barbeiro_id = ?";
                    $params[] = $filters['barbeiro_id'];
                    error_log("Filtro barbeiro_id aplicado: " . $filters['barbeiro_id']);
                }
            }
            
            if (isset($filters['status']) && !empty($filters['status']) && $filters['status'] !== '') {
                $conditions[] = "a.status = ?";
                $params[] = $filters['status'];
                error_log("Filtro status aplicado: " . $filters['status']);
            }
            
            if (isset($filters['cliente_id']) && !empty($filters['cliente_id'])) {
                $conditions[] = "a.cliente_id = ?";
                $params[] = $filters['cliente_id'];
                error_log("Filtro cliente_id aplicado: " . $filters['cliente_id']);
            }
            
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
            
            $sql .= " ORDER BY a.data_agendamento ASC, a.hora_agendamento ASC";
            
            if (isset($filters['limit']) && !empty($filters['limit'])) {
                $sql .= " LIMIT ?";
                $params[] = (int) $filters['limit'];
            }
            
            // Debug: Log da query final
            error_log("SQL Final: " . $sql);
            error_log("Parâmetros: " . print_r($params, true));
            
            $result = DB::select($sql, $params);
            
            // Debug: Log do resultado
            error_log("Total de agendamentos encontrados: " . count($result));
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Erro ao buscar agendamentos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obter agendamentos do dia
     */
    public function getAgendamentosDoDia($data = null, $barbeiroId = null) {
        if (!$data) {
            $data = date('Y-m-d');
        }
        
        $filters = [
            'data_inicio' => $data,
            'data_fim' => $data
        ];
        
        if ($barbeiroId) {
            $filters['barbeiro_id'] = $barbeiroId;
        }
        
        return $this->getAll($filters);
    }
    
    /**
     * Criar novo agendamento
     */
    public function create($dados) {
        $sql = "INSERT INTO agendamentos (cliente_id, barbeiro_id, servico_id, data_agendamento, hora_agendamento, observacoes, valor_cobrado) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $dados['cliente_id'],
            $dados['barbeiro_id'],
            $dados['servico_id'],
            $dados['data_agendamento'],
            $dados['hora_agendamento'],
            $dados['observacoes'] ?? null,
            $dados['valor_cobrado'] ?? null
        ];
        
        return DB::insert($sql, $params);
    }
    
    /**
     * Atualizar agendamento
     */
    public function update($id, $dados) {
        $sql = "UPDATE agendamentos SET 
                cliente_id = ?, 
                barbeiro_id = ?, 
                servico_id = ?, 
                data_agendamento = ?, 
                hora_agendamento = ?, 
                status = ?,
                observacoes = ?,
                valor_cobrado = ?
                WHERE id = ?";
        
        $params = [
            $dados['cliente_id'],
            $dados['barbeiro_id'],
            $dados['servico_id'],
            $dados['data_agendamento'],
            $dados['hora_agendamento'],
            $dados['status'] ?? 'agendado',
            $dados['observacoes'] ?? null,
            $dados['valor_cobrado'] ?? null,
            $id
        ];
        
        return DB::execute($sql, $params);
    }
    
    /**
     * Cancelar agendamento
     */
    public function cancel($id) {
        $sql = "UPDATE agendamentos SET status = 'cancelado' WHERE id = ?";
        return DB::execute($sql, [$id]);
    }
    
    /**
     * Marcar como realizado
     */
    public function complete($id, $valorCobrado = null) {
        $sql = "UPDATE agendamentos SET status = 'realizado'";
        $params = [];
        
        if ($valorCobrado !== null) {
            $sql .= ", valor_cobrado = ?";
            $params[] = $valorCobrado;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        return DB::execute($sql, $params);
    }
    
    /**
     * Verificar se horário está disponível
     */
    public function isHorarioDisponivel($barbeiroId, $data, $hora, $excludeId = null) {
        $sql = "SELECT COUNT(*) as total FROM agendamentos 
                WHERE barbeiro_id = ? AND data_agendamento = ? AND hora_agendamento = ? 
                AND status IN ('agendado', 'realizado')";
        
        $params = [$barbeiroId, $data, $hora];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = DB::selectOne($sql, $params);
        return $result['total'] == 0;
    }
    
    /**
     * Obter horários ocupados de um barbeiro em uma data
     */
    public function getHorariosOcupados($barbeiroId, $data) {
        $sql = "SELECT hora_agendamento FROM agendamentos 
                WHERE barbeiro_id = ? AND data_agendamento = ? 
                AND status IN ('agendado', 'realizado')
                ORDER BY hora_agendamento";
        
        $agendamentos = DB::select($sql, [$barbeiroId, $data]);
        return array_column($agendamentos, 'hora_agendamento');
    }
    
    /**
     * Obter próximos agendamentos
     */
    public function getProximosAgendamentos($limit = 5) {
        $sql = "SELECT a.*, c.nome as cliente_nome, c.telefone as cliente_telefone,
                       u.nome as barbeiro_nome, s.nome as servico_nome
                FROM agendamentos a
                JOIN clientes c ON a.cliente_id = c.id
                JOIN usuarios u ON a.barbeiro_id = u.id
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.data_agendamento >= CURDATE() AND a.status = 'agendado'
                ORDER BY a.data_agendamento ASC, a.hora_agendamento ASC
                LIMIT ?";
        
        return DB::select($sql, [$limit]);
    }
    
    /**
     * Contar agendamentos por status
     */
    public function countByStatus($dataInicio = null, $dataFim = null) {
        $sql = "SELECT status, COUNT(*) as total FROM agendamentos";
        $params = [];
        $conditions = [];
        
        if ($dataInicio) {
            $conditions[] = "data_agendamento >= ?";
            $params[] = $dataInicio;
        }
        
        if ($dataFim) {
            $conditions[] = "data_agendamento <= ?";
            $params[] = $dataFim;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " GROUP BY status";
        
        $results = DB::select($sql, $params);
        $counts = [];
        
        foreach ($results as $result) {
            $counts[$result['status']] = $result['total'];
        }
        
        return $counts;
    }
    
    /**
     * Obter agenda do barbeiro
     */
    public function getAgendaBarbeiro($barbeiroId, $dataInicio, $dataFim) {
        $sql = "SELECT a.*, c.nome as cliente_nome, c.telefone as cliente_telefone,
                       s.nome as servico_nome, s.duracao as servico_duracao
                FROM agendamentos a
                JOIN clientes c ON a.cliente_id = c.id
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.barbeiro_id = ? 
                AND a.data_agendamento BETWEEN ? AND ?
                AND a.status IN ('agendado', 'realizado')
                ORDER BY a.data_agendamento ASC, a.hora_agendamento ASC";
        
        return DB::select($sql, [$barbeiroId, $dataInicio, $dataFim]);
    }
    
    /**
 * Obter próximo agendamento de um barbeiro específico
 */
public function getProximoAgendamentoBarbeiro($barbeiroId) {
    $sql = "SELECT 
                a.id,
                a.data_agendamento,
                a.hora_agendamento as hora_inicio,
                a.hora_fim,
                a.status,
                a.observacoes,
                c.nome as cliente_nome,
                c.telefone as cliente_telefone,
                c.email as cliente_email,
                s.nome as servico_nome,
                s.duracao as servico_duracao,
                s.preco as servico_preco
            FROM agendamentos a
            LEFT JOIN clientes c ON a.cliente_id = c.id
            LEFT JOIN servicos s ON a.servico_id = s.id
            WHERE a.barbeiro_id = ? 
            AND a.data_agendamento >= CURDATE()
            AND a.status IN ('agendado', 'confirmado')
            ORDER BY a.data_agendamento ASC, a.hora_agendamento ASC
            LIMIT 1";
    
    $result = DB::select($sql, [$barbeiroId]);
    return !empty($result) ? $result[0] : null;
}

/**
 * Obter estatísticas de um barbeiro
 */
public function getEstatisticasBarbeiro($barbeiroId) {
    try {
        // Total de atendimentos realizados
        $sql = "SELECT 
                    COUNT(*) as total_atendimentos,
                    SUM(CASE WHEN status = 'realizado' THEN 1 ELSE 0 END) as atendimentos_concluidos,
                    SUM(CASE WHEN status = 'cancelado' THEN 1 ELSE 0 END) as atendimentos_cancelados
                FROM agendamentos 
                WHERE barbeiro_id = ?";
        
        $stats = DB::selectOne($sql, [$barbeiroId]);
        
        // Faturamento do mês atual
        $sql = "SELECT 
                    SUM(s.preco) as faturamento_mes
                FROM agendamentos a
                LEFT JOIN servicos s ON a.servico_id = s.id
                WHERE a.barbeiro_id = ? 
                AND a.status = 'realizado'
                AND MONTH(a.data_agendamento) = MONTH(CURDATE())
                AND YEAR(a.data_agendamento) = YEAR(CURDATE())";
        
        $faturamento = DB::selectOne($sql, [$barbeiroId]);
        $stats['faturamento_mes'] = $faturamento['faturamento_mes'] ?? 0;
        
        return $stats;
        
    } catch (Exception $e) {
        return [
            'total_atendimentos' => 0,
            'atendimentos_concluidos' => 0,
            'atendimentos_cancelados' => 0,
            'faturamento_mes' => 0
        ];
    }
}

/**
 * Obter agenda completa do barbeiro para um período
 */
public function getAgendaCompletaBarbeiro($barbeiroId, $dataInicio = null, $dataFim = null) {
    if (!$dataInicio) {
        $dataInicio = date('Y-m-d');
    }
    
    if (!$dataFim) {
        $dataFim = date('Y-m-d', strtotime('+30 days'));
    }
    
    $sql = "SELECT 
                a.*,
                c.nome as cliente_nome,
                c.telefone as cliente_telefone,
                s.nome as servico_nome,
                s.duracao as servico_duracao,
                s.preco as servico_preco
            FROM agendamentos a
            LEFT JOIN clientes c ON a.cliente_id = c.id
            LEFT JOIN servicos s ON a.servico_id = s.id
            WHERE a.barbeiro_id = ? 
            AND a.data_agendamento BETWEEN ? AND ?
            ORDER BY a.data_agendamento ASC, a.hora_agendamento ASC";
    
    return DB::select($sql, [$barbeiroId, $dataInicio, $dataFim]);
}

/**
 * Verificar se barbeiro tem agendamentos pendentes
 */
public function temAgendamentosPendentes($barbeiroId) {
    $sql = "SELECT COUNT(*) as total 
            FROM agendamentos 
            WHERE barbeiro_id = ? 
            AND data_agendamento >= CURDATE()
            AND status IN ('agendado', 'confirmado')";
    
    $result = DB::selectOne($sql, [$barbeiroId]);
    return ($result['total'] ?? 0) > 0;
}

/**
 * Contar agendamentos do barbeiro por status
 */
public function contarAgendamentosPorStatus($barbeiroId, $dataInicio = null, $dataFim = null) {
    $where = "WHERE barbeiro_id = ?";
    $params = [$barbeiroId];
    
    if ($dataInicio) {
        $where .= " AND data_agendamento >= ?";
        $params[] = $dataInicio;
    }
    
    if ($dataFim) {
        $where .= " AND data_agendamento <= ?";
        $params[] = $dataFim;
    }
    
    $sql = "SELECT 
                status, 
                COUNT(*) as total,
                SUM(CASE WHEN s.preco IS NOT NULL THEN s.preco ELSE 0 END) as valor_total
            FROM agendamentos a
            LEFT JOIN servicos s ON a.servico_id = s.id
            {$where}
            GROUP BY status";
    
    $results = DB::select($sql, $params);
    
    // Formatar resultado
    $counts = [];
    foreach ($results as $result) {
        $counts[$result['status']] = [
            'total' => $result['total'],
            'valor_total' => $result['valor_total']
        ];
    }
    
    return $counts;
}

/**
 * Obter horários mais movimentados do barbeiro
 */
public function getHorariosMaisMovimentados($barbeiroId, $limit = 5) {
    $sql = "SELECT 
                HOUR(hora_agendamento) as hora,
                COUNT(*) as total_agendamentos
            FROM agendamentos 
            WHERE barbeiro_id = ? 
            AND status IN ('realizado', 'agendado', 'confirmado')
            GROUP BY HOUR(hora_agendamento)
            ORDER BY total_agendamentos DESC
            LIMIT ?";
    
    return DB::select($sql, [$barbeiroId, $limit]);
}

/**
 * Obter clientes mais frequentes do barbeiro
 */
public function getClientesMaisFrequentes($barbeiroId, $limit = 10) {
    $sql = "SELECT 
                c.id,
                c.nome,
                c.telefone,
                COUNT(a.id) as total_agendamentos,
                MAX(a.data_agendamento) as ultimo_agendamento
            FROM agendamentos a
            JOIN clientes c ON a.cliente_id = c.id
            WHERE a.barbeiro_id = ?
            GROUP BY c.id, c.nome, c.telefone
            ORDER BY total_agendamentos DESC
            LIMIT ?";
    
    return DB::select($sql, [$barbeiroId, $limit]);
}

    /**
     * Verificar se há conflito de horário
     */
    public function hasConflict($barbeiroId, $data, $hora, $duracao, $excludeId = null) {
        try {
            // Converter hora para timestamp
            $inicioAgendamento = strtotime($data . ' ' . $hora);
            $fimAgendamento = $inicioAgendamento + ($duracao * 60); // duração em minutos
            
            // Buscar agendamentos do barbeiro na mesma data
            $sql = "SELECT a.*, s.duracao as servico_duracao
                    FROM agendamentos a
                    LEFT JOIN servicos s ON a.servico_id = s.id
                    WHERE a.barbeiro_id = ? 
                    AND a.data_agendamento = ? 
                    AND a.status IN ('agendado', 'realizado')";
            
            $params = [$barbeiroId, $data];
            
            if ($excludeId) {
                $sql .= " AND a.id != ?";
                $params[] = $excludeId;
            }
            
            $agendamentos = DB::select($sql, $params);
            
            // Verificar cada agendamento existente
            foreach ($agendamentos as $agendamento) {
                $inicioExistente = strtotime($data . ' ' . $agendamento['hora_agendamento']);
                $duracaoExistente = $agendamento['servico_duracao'] ?? 30; // padrão 30 min
                $fimExistente = $inicioExistente + ($duracaoExistente * 60);
                
                // Verificar se há sobreposição
                if (($inicioAgendamento >= $inicioExistente && $inicioAgendamento < $fimExistente) ||
                    ($fimAgendamento > $inicioExistente && $fimAgendamento <= $fimExistente) ||
                    ($inicioAgendamento <= $inicioExistente && $fimAgendamento >= $fimExistente)) {
                    return true; // Há conflito
                }
            }
            
            return false; // Não há conflito
            
        } catch (Exception $e) {
            error_log("Erro ao verificar conflito de horário: " . $e->getMessage());
            return true; // Em caso de erro, considerar como conflito por segurança
        }
    }

    /**
     * Calcular receita de um período
     */
    public function getReceita($dataInicio = null, $dataFim = null, $barbeiroId = null) {
        $sql = "SELECT SUM(valor_cobrado) as receita_total, COUNT(*) as total_agendamentos
                FROM agendamentos 
                WHERE status = 'realizado' AND valor_cobrado IS NOT NULL";
        
        $params = [];
        
        if ($dataInicio) {
            $sql .= " AND data_agendamento >= ?";
            $params[] = $dataInicio;
        }
        
        if ($dataFim) {
            $sql .= " AND data_agendamento <= ?";
            $params[] = $dataFim;
        }
        
        if ($barbeiroId) {
            $sql .= " AND barbeiro_id = ?";
            $params[] = $barbeiroId;
        }
        
        return DB::selectOne($sql, $params);
    }
}
?>
