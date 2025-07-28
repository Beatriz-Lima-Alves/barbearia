<?php
require_once __DIR__ . '/../../config/database.php';

/**
 * Model para relatórios financeiros
 */
class Financeiro {
    
    /**
     * Obter receita total de um período
     */
    public function getReceitaTotal($dataInicio = null, $dataFim = null) {
        $sql = "SELECT 
                    SUM(COALESCE(valor_cobrado, s.preco)) as receita_total,
                    COUNT(*) as total_agendamentos,
                    AVG(COALESCE(valor_cobrado, s.preco)) as ticket_medio
                FROM agendamentos a
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.status = 'realizado'";
        
        $params = [];
        
        if ($dataInicio) {
            $sql .= " AND a.data_agendamento >= ?";
            $params[] = $dataInicio;
        }
        
        if ($dataFim) {
            $sql .= " AND a.data_agendamento <= ?";
            $params[] = $dataFim;
        }
        
        return DB::selectOne($sql, $params);
    }
    
    /**
     * Obter receita por barbeiro
     */
    public function getReceitaPorBarbeiro($dataInicio = null, $dataFim = null) {
        $sql = "SELECT 
                    u.id,
                    u.nome as barbeiro,
                    SUM(COALESCE(a.valor_cobrado, s.preco)) as receita,
                    COUNT(a.id) as total_agendamentos,
                    AVG(COALESCE(a.valor_cobrado, s.preco)) as ticket_medio
                FROM agendamentos a
                JOIN usuarios u ON a.barbeiro_id = u.id
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.status = 'realizado'";
        
        $params = [];
        
        if ($dataInicio) {
            $sql .= " AND a.data_agendamento >= ?";
            $params[] = $dataInicio;
        }
        
        if ($dataFim) {
            $sql .= " AND a.data_agendamento <= ?";
            $params[] = $dataFim;
        }
        
        $sql .= " GROUP BY u.id, u.nome ORDER BY receita DESC";
        
        return DB::select($sql, $params);
    }
    
    /**
     * Obter receita por serviço
     */
    public function getReceitaPorServico($dataInicio = null, $dataFim = null) {
        $sql = "SELECT 
                    s.id,
                    s.nome as servico,
                    s.preco as preco_padrao,
                    SUM(COALESCE(a.valor_cobrado, s.preco)) as receita,
                    COUNT(a.id) as total_agendamentos,
                    AVG(COALESCE(a.valor_cobrado, s.preco)) as preco_medio
                FROM agendamentos a
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.status = 'realizado'";
        
        $params = [];
        
        if ($dataInicio) {
            $sql .= " AND a.data_agendamento >= ?";
            $params[] = $dataInicio;
        }
        
        if ($dataFim) {
            $sql .= " AND a.data_agendamento <= ?";
            $params[] = $dataFim;
        }
        
        $sql .= " GROUP BY s.id, s.nome, s.preco ORDER BY receita DESC";
        
        return DB::select($sql, $params);
    }
    
    /**
     * Obter receita diária de um período
     */
    public function getReceitaDiaria($dataInicio = null, $dataFim = null) {
        $dataInicio = $dataInicio ?: date('Y-m-01'); // Primeiro dia do mês atual
        $dataFim = $dataFim ?: date('Y-m-t'); // Último dia do mês atual
        
        $sql = "SELECT 
                    a.data_agendamento as data,
                    SUM(COALESCE(a.valor_cobrado, s.preco)) as receita,
                    COUNT(a.id) as total_agendamentos
                FROM agendamentos a
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.status = 'realizado'
                AND a.data_agendamento BETWEEN ? AND ?
                GROUP BY a.data_agendamento
                ORDER BY a.data_agendamento";
        
        return DB::select($sql, [$dataInicio, $dataFim]);
    }
    
    /**
     * Obter receita mensal do ano
     */
    public function getReceitaMensal($ano = null) {
        $ano = $ano ?: date('Y');
        
        $sql = "SELECT 
                    MONTH(a.data_agendamento) as mes,
                    MONTHNAME(a.data_agendamento) as nome_mes,
                    SUM(COALESCE(a.valor_cobrado, s.preco)) as receita,
                    COUNT(a.id) as total_agendamentos
                FROM agendamentos a
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.status = 'realizado'
                AND YEAR(a.data_agendamento) = ?
                GROUP BY MONTH(a.data_agendamento), MONTHNAME(a.data_agendamento)
                ORDER BY mes";
        
        return DB::select($sql, [$ano]);
    }
    
    /**
     * Obter clientes que mais gastaram
     */
    public function getTopClientes($limit = 10, $dataInicio = null, $dataFim = null) {
        $sql = "SELECT 
                    c.id,
                    c.nome as cliente,
                    c.telefone,
                    SUM(COALESCE(a.valor_cobrado, s.preco)) as total_gasto,
                    COUNT(a.id) as total_agendamentos,
                    AVG(COALESCE(a.valor_cobrado, s.preco)) as ticket_medio
                FROM agendamentos a
                JOIN clientes c ON a.cliente_id = c.id
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.status = 'realizado'";
        
        $params = [];
        
        if ($dataInicio) {
            $sql .= " AND a.data_agendamento >= ?";
            $params[] = $dataInicio;
        }
        
        if ($dataFim) {
            $sql .= " AND a.data_agendamento <= ?";
            $params[] = $dataFim;
        }
        
        $sql .= " GROUP BY c.id, c.nome, c.telefone
                  ORDER BY total_gasto DESC
                  LIMIT ?";
        
        $params[] = $limit;
        
        return DB::select($sql, $params);
    }
    
    /**
     * Obter estatísticas de cancelamentos
     */
    public function getEstatisticasCancelamentos($dataInicio = null, $dataFim = null) {
        $sql = "SELECT 
                    COUNT(CASE WHEN status = 'agendado' THEN 1 END) as agendados,
                    COUNT(CASE WHEN status = 'realizado' THEN 1 END) as realizados,
                    COUNT(CASE WHEN status = 'cancelado' THEN 1 END) as cancelados,
                    COUNT(CASE WHEN status = 'nao_compareceu' THEN 1 END) as nao_compareceu,
                    COUNT(*) as total
                FROM agendamentos";
        
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
        
        $result = DB::selectOne($sql, $params);
        
        // Calcular percentuais
        if ($result['total'] > 0) {
            $result['percentual_realizados'] = round(($result['realizados'] / $result['total']) * 100, 2);
            $result['percentual_cancelados'] = round(($result['cancelados'] / $result['total']) * 100, 2);
            $result['percentual_nao_compareceu'] = round(($result['nao_compareceu'] / $result['total']) * 100, 2);
        } else {
            $result['percentual_realizados'] = 0;
            $result['percentual_cancelados'] = 0;
            $result['percentual_nao_compareceu'] = 0;
        }
        
        return $result;
    }
    
    /**
     * Obter horários mais movimentados
     */
    public function getHorariosMaisMovimentados($dataInicio = null, $dataFim = null) {
        $sql = "SELECT 
                    HOUR(hora_agendamento) as hora,
                    COUNT(*) as total_agendamentos,
                    SUM(COALESCE(a.valor_cobrado, s.preco)) as receita
                FROM agendamentos a
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.status = 'realizado'";
        
        $params = [];
        
        if ($dataInicio) {
            $sql .= " AND a.data_agendamento >= ?";
            $params[] = $dataInicio;
        }
        
        if ($dataFim) {
            $sql .= " AND a.data_agendamento <= ?";
            $params[] = $dataFim;
        }
        
        $sql .= " GROUP BY HOUR(hora_agendamento)
                  ORDER BY total_agendamentos DESC";
        
        return DB::select($sql, $params);
    }
    
    /**
     * Obter dias da semana mais movimentados
     */
    public function getDiasSemanaMovimentados($dataInicio = null, $dataFim = null) {
        $sql = "SELECT 
                    DAYOFWEEK(data_agendamento) as dia_semana_num,
                    DAYNAME(data_agendamento) as dia_semana,
                    COUNT(*) as total_agendamentos,
                    SUM(COALESCE(a.valor_cobrado, s.preco)) as receita
                FROM agendamentos a
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.status = 'realizado'";
        
        $params = [];
        
        if ($dataInicio) {
            $sql .= " AND a.data_agendamento >= ?";
            $params[] = $dataInicio;
        }
        
        if ($dataFim) {
            $sql .= " AND a.data_agendamento <= ?";
            $params[] = $dataFim;
        }
        
        $sql .= " GROUP BY DAYOFWEEK(data_agendamento), DAYNAME(data_agendamento)
                  ORDER BY total_agendamentos DESC";
        
        return DB::select($sql, $params);
    }
    
    /**
     * Comparar receita com período anterior
     */
    public function compararComPeriodoAnterior($dataInicio, $dataFim) {
        // Calcular duração do período
        $inicio = new DateTime($dataInicio);
        $fim = new DateTime($dataFim);
        $duracao = $inicio->diff($fim)->days;
        
        // Calcular período anterior
        $inicioAnterior = $inicio->sub(new DateInterval("P{$duracao}D"))->format('Y-m-d');
        $fimAnterior = $dataInicio;
        
        $atual = $this->getReceitaTotal($dataInicio, $dataFim);
        $anterior = $this->getReceitaTotal($inicioAnterior, $fimAnterior);
        
        $crescimento = 0;
        if ($anterior['receita_total'] > 0) {
            $crescimento = (($atual['receita_total'] - $anterior['receita_total']) / $anterior['receita_total']) * 100;
        }
        
        return [
            'atual' => $atual,
            'anterior' => $anterior,
            'crescimento_percentual' => round($crescimento, 2)
        ];
    }
}
?>
