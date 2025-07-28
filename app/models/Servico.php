<?php
require_once __DIR__ . '/../../config/database.php';

/**
 * Model para serviços da barbearia
 */
class Servico {
    
    /**
     * Buscar serviço por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM servicos WHERE id = ?";
        return DB::selectOne($sql, [$id]);
    }
    
    /**
     * Listar todos os serviços
     */
    public function getAll($ativo = null) {
        $sql = "SELECT * FROM servicos";
        $params = [];
        
        if ($ativo !== null) {
            $sql .= " WHERE ativo = ?";
            $params[] = $ativo;
        }
        
        $sql .= " ORDER BY nome";
        return DB::select($sql, $params);
    }
    
    /**
     * Listar apenas serviços ativos
     */
    public function getAtivos() {
        return $this->getAll(1);
    }
    
    /**
     * Criar novo serviço
     */
    public function create($dados) {
        $sql = "INSERT INTO servicos (nome, descricao, duracao, preco,categoria) 
                VALUES (?, ?, ?, ?, ?)";
        
        $params = [
            $dados['nome'],
            $dados['descricao'] ?? '',
            $dados['duracao'],
            $dados['preco'],
            $dados['categoria']
        ];
        
        return DB::insert($sql, $params);
    }
    
    /**
     * Atualizar serviço
     */
    public function update($id, $dados) {
        $sql = "UPDATE servicos SET 
                nome = ?, 
                descricao = ?, 
                duracao = ?, 
                categoria = ?, 
                preco = ?,
                ativo = ?
                WHERE id = ?";
        
        $params = [
            $dados['nome'],
            $dados['descricao'] ?? '',
            $dados['duracao'],
            $dados['categoria'],
            $dados['preco'],
            $dados['ativo'] ?? 1,
            $id
        ];
        
        return DB::execute($sql, $params);
    }
    
    /**
     * Desativar serviço
     */
    public function deactivate($id) {
        $sql = "UPDATE servicos SET ativo = 0 WHERE id = ?";
        return DB::execute($sql, [$id]);
    }

    
    /**
     * ativar serviço
     */
    public function active($id) {
        $sql = "UPDATE servicos SET ativo = 1 WHERE id = ?";
        return DB::execute($sql, [$id]);
    }

    /**
     * Duplicar serviço
     */
    public function duplicate($originalId, $novoNome) {
        // Buscar dados do serviço original
        $original = $this->getById($originalId);
        if (!$original) {
            return false;
        }
        
        // Criar novo serviço baseado no original
        $data = [
            'nome' => $novoNome,
            'descricao' => $original['descricao'],
            'preco' => $original['preco'],
            'duracao' => $original['duracao'],
            'categoria' => $original['categoria']
        ];
        
        return $this->create($data);
    }
    
    /**
     * Verificar se nome do serviço já existe
     */
    public function nomeExists($nome, $excludeId = null) {
        $sql = "SELECT COUNT(*) as total FROM servicos WHERE nome = ? AND ativo = 1";
        $params = [$nome];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = DB::selectOne($sql, $params);
        return $result['total'] > 0;
    }
    
    /**
     * Obter serviços mais agendados
     */
    public function getMaisAgendados($limit = 5) {
        $sql = "SELECT s.*, COUNT(a.id) as total_agendamentos
                FROM servicos s
                LEFT JOIN agendamentos a ON s.id = a.servico_id
                WHERE s.ativo = 1
                GROUP BY s.id
                ORDER BY total_agendamentos DESC
                LIMIT ?";
        
        return DB::select($sql, [$limit]);
    }
    
    /**
     * Contar total de serviços
     */
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM servicos WHERE ativo = 1";
        $result = DB::selectOne($sql);
        return $result['total'];
    }
    
    /**
     * Obter faixa de preços
     */
    public function getFaixaPrecos() {
        $sql = "SELECT MIN(preco) as preco_minimo, MAX(preco) as preco_maximo 
                FROM servicos WHERE ativo = 1";
        return DB::selectOne($sql);
    }
    
    /**
     * Calcular receita total de um serviço
     */
    public function getReceitaTotal($servicoId, $dataInicio = null, $dataFim = null) {
        $sql = "SELECT SUM(a.valor_cobrado) as receita_total, COUNT(a.id) as total_agendamentos
                FROM agendamentos a
                WHERE a.servico_id = ? AND a.status = 'realizado'";
        
        $params = [$servicoId];
        
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
     * Obter serviços por faixa de preço
     */
    public function getByFaixaPreco($precoMin = null, $precoMax = null) {
        $sql = "SELECT * FROM servicos WHERE ativo = 1";
        $params = [];
        
        if ($precoMin !== null) {
            $sql .= " AND preco >= ?";
            $params[] = $precoMin;
        }
        
        if ($precoMax !== null) {
            $sql .= " AND preco <= ?";
            $params[] = $precoMax;
        }
        
        $sql .= " ORDER BY preco";
        return DB::select($sql, $params);
    }
    
    /**
     * Obter serviços por duração
     */
    public function getByDuracao($duracaoMin = null, $duracaoMax = null) {
        $sql = "SELECT * FROM servicos WHERE ativo = 1";
        $params = [];
        
        if ($duracaoMin !== null) {
            $sql .= " AND duracao >= ?";
            $params[] = $duracaoMin;
        }
        
        if ($duracaoMax !== null) {
            $sql .= " AND duracao <= ?";
            $params[] = $duracaoMax;
        }
        
        $sql .= " ORDER BY duracao";
        return DB::select($sql, $params);
    }
}
?>
