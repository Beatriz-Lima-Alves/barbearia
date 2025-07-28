<?php
require_once __DIR__ . '/../../config/database.php';

/**
 * Model para clientes
 */
class Cliente {
    
    /**
     * Buscar cliente por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM clientes WHERE id = ?";
        return DB::selectOne($sql, [$id]);
    }
    
    /**
     * Buscar cliente por telefone
     */
    public function getByTelefone($telefone) {
        $sql = "SELECT * FROM clientes WHERE telefone = ? AND ativo = 1";
        return DB::selectOne($sql, [$telefone]);
    }
    
    /**
     * Buscar cliente por email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM clientes WHERE email = ? AND ativo = 1";
        return DB::selectOne($sql, [$email]);
    }
    
    /**
     * Listar todos os clientes
     */
    public function getAll($ativo = null, $limit = null, $search = null) {
        $sql = "SELECT * FROM clientes";
        $params = [];
        $conditions = [];
        
        if ($ativo !== null) {
            $conditions[] = "ativo = ?";
            $params[] = $ativo;
        }
        
        if ($search) {
            $conditions[] = "(nome LIKE ? OR telefone LIKE ? OR email LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY nome";
        
        if ($limit) {
            $sql .= " LIMIT ?";
            $params[] = $limit;
        }
        
        return DB::select($sql, $params);
    }
    
    /**
     * Criar novo cliente
     */
    public function create($dados) {
        $sql = "INSERT INTO clientes (nome, telefone, email, data_nascimento, endereco, observacoes) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $params = [
            $dados['nome'],
            $dados['telefone'],
            $dados['email'] ?? null,
            $dados['data_nascimento'] ?? null,
            $dados['endereco'] ?? null,
            $dados['observacoes'] ?? null
        ];
        
        return DB::insert($sql, $params);
    }
    
    /**
     * Atualizar cliente
     */
    public function update($id, $dados) {
        $sql = "UPDATE clientes SET 
                nome = ?, 
                telefone = ?, 
                email = ?, 
                data_nascimento = ?, 
                endereco = ?, 
                observacoes = ?,
                ativo = ?
                WHERE id = ?";
        
        $params = [
            $dados['nome'],
            $dados['telefone'],
            $dados['email'] ?? null,
            $dados['data_nascimento'] ?? null,
            $dados['endereco'] ?? null,
            $dados['observacoes'] ?? null,
            $dados['ativo'] ?? 1,
            $id
        ];
        
        return DB::execute($sql, $params);
    }
    
    /**
     * Desativar cliente
     */
    public function deactivate($id) {
        $sql = "UPDATE clientes SET ativo = 0 WHERE id = ?";
        return DB::execute($sql, [$id]);
    }
    
    /**
     * Verificar se telefone já existe
     */
    public function telefoneExists($telefone, $excludeId = null) {
        $sql = "SELECT COUNT(*) as total FROM clientes WHERE telefone = ? AND ativo = 1";
        $params = [$telefone];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = DB::selectOne($sql, $params);
        return $result['total'] > 0;
    }
    
    /**
     * Verificar se email já existe
     */
    public function emailExists($email, $excludeId = null) {
        if (empty($email)) return false;
        
        $sql = "SELECT COUNT(*) as total FROM clientes WHERE email = ? AND ativo = 1";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = DB::selectOne($sql, $params);
        return $result['total'] > 0;
    }
    
    /**
     * Obter histórico de agendamentos do cliente
     */
    public function getHistoricoAgendamentos($clienteId, $limit = 10) {
        $sql = "SELECT a.*, s.nome as servico, u.nome as barbeiro 
                FROM agendamentos a
                JOIN servicos s ON a.servico_id = s.id
                JOIN usuarios u ON a.barbeiro_id = u.id
                WHERE a.cliente_id = ?
                ORDER BY a.data_agendamento DESC, a.hora_agendamento DESC
                LIMIT ?";
        
        return DB::select($sql, [$clienteId, $limit]);
    }
    
    /**
     * Contar total de clientes
     */
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM clientes WHERE ativo = 1";
        $result = DB::selectOne($sql);
        return $result['total'];
    }
    
    /**
     * Obter clientes mais frequentes
     */
    public function getMaisFrequentes($limit = 5) {
        $sql = "SELECT c.*, COUNT(a.id) as total_agendamentos
                FROM clientes c
                LEFT JOIN agendamentos a ON c.id = a.cliente_id
                WHERE c.ativo = 1
                GROUP BY c.id
                ORDER BY total_agendamentos DESC
                LIMIT ?";
        
        return DB::select($sql, [$limit]);
    }
    
    /**
     * Obter aniversariantes do mês
     */
    public function getAniversariantesDoMes($mes = null) {
        if (!$mes) {
            $mes = date('m');
        }
        
        $sql = "SELECT * FROM clientes 
                WHERE MONTH(data_nascimento) = ? AND ativo = 1
                ORDER BY DAY(data_nascimento)";
        
        return DB::select($sql, [$mes]);
    }
}
?>
