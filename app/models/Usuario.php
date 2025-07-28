<?php
require_once __DIR__ . '/../../config/database.php';

/**
 * Model para gerenciamento de usuários (barbeiros e administradores)
 */
class Usuario {
    
    /**
     * Busca usuário por email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = ? AND ativo = 1";
        return DB::selectOne($sql, [$email]);
    }
    
    /**
     * Busca usuário por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM usuarios WHERE id = ? AND ativo = 1";
        return DB::selectOne($sql, [$id]);
    }
    
    /**
     * Lista todos os usuários ativos
     */
    public function getAll($tipo = null) {
        $sql = "SELECT * FROM usuarios WHERE ativo = 1";
        $params = [];
        
        if ($tipo) {
            $sql .= " AND tipo = ?";
            $params[] = $tipo;
        }
        
        $sql .= " ORDER BY nome";
        return DB::select($sql, $params);
    }
    
    /**
     * Lista apenas barbeiros ativos
     */
    public function getBarbeiros() {
        return $this->getAll('barbeiro');
    }
    
    /**
     * Cria novo usuário
     */
    public function create($dados) {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo, especialidades, 
                horario_inicio, horario_fim, dias_trabalho) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $dados['nome'],
            $dados['email'],
            $dados['senha'],
            $dados['tipo'],
            $dados['especialidades'] ?? '',
            $dados['horario_inicio'] ?? '08:00:00',
            $dados['horario_fim'] ?? '18:00:00',
            $dados['dias_trabalho'] ?? 'segunda,terca,quarta,quinta,sexta,sabado'
        ];
        
        return DB::insert($sql, $params);
    }
    
    /**
     * Atualiza dados do usuário
     */
    public function update($id, $dados) {
        $campos = [];
        $params = [];
        
        $camposPermitidos = [
            'nome', 'email', 'tipo', 'especialidades', 
            'horario_inicio', 'horario_fim', 'dias_trabalho', 'senha'
        ];
        
        foreach ($camposPermitidos as $campo) {
            if (isset($dados[$campo])) {
                $campos[] = "$campo = ?";
                $params[] = $dados[$campo];
            }
        }
        
        if (empty($campos)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE id = ?";
        
        return DB::execute($sql, $params);
    }
    
    /**
     * Atualiza senha do usuário
     */
    public function updatePassword($id, $novaSenha) {
        $sql = "UPDATE usuarios SET senha = ? WHERE id = ?";
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        return DB::execute($sql, [$senhaHash, $id]);
    }
    
    /**
     * Desativa usuário (soft delete)
     */
    public function delete($id) {
        $sql = "UPDATE usuarios SET ativo = 0 WHERE id = ?";
        return DB::execute($sql, [$id]);
    }
    
    /**
     * Verifica se barbeiro está disponível em determinado horário
     */
    public function isAvailable($barbeiroId, $data, $hora, $duracao = 30) {
        // Primeiro verifica se o barbeiro trabalha no dia da semana
        $barbeiro = $this->getById($barbeiroId);
        if (!$barbeiro) {
            return false;
        }
        
        $diaSemana = $this->getDiaSemana($data);
        $diasTrabalho = explode(',', $barbeiro['dias_trabalho']);
        
        if (!in_array($diaSemana, $diasTrabalho)) {
            return false;
        }
        
        // Verifica se está dentro do horário de trabalho
        $horaInicio = strtotime($barbeiro['horario_inicio']);
        $horaFim = strtotime($barbeiro['horario_fim']);
        $horaAgendamento = strtotime($hora);
        $horaFimAgendamento = $horaAgendamento + ($duracao * 60);
        
        if ($horaAgendamento < $horaInicio || $horaFimAgendamento > $horaFim) {
            return false;
        }
        
        // Verifica conflitos com outros agendamentos
        $sql = "SELECT COUNT(*) as conflitos FROM agendamentos 
                WHERE barbeiro_id = ? AND data_agendamento = ? 
                AND status IN ('agendado', 'realizado')
                AND (
                    (hora_agendamento <= ? AND ADDTIME(hora_agendamento, SEC_TO_TIME(
                        (SELECT duracao * 60 FROM servicos WHERE id = agendamentos.servico_id)
                    )) > ?) 
                    OR 
                    (hora_agendamento < ADDTIME(?, SEC_TO_TIME(?)) AND hora_agendamento >= ?)
                )";
        
        $params = [
            $barbeiroId,
            $data,
            $hora,
            $hora,
            $hora,
            $duracao * 60,
            $hora
        ];
        
        $resultado = DB::selectOne($sql, $params);
        return $resultado['conflitos'] == 0;
    }
    
    /**
     * Obtém horários disponíveis de um barbeiro em uma data
     */
    public function getHorariosDisponiveis($barbeiroId, $data, $duracaoServico = 30) {
        $barbeiro = $this->getById($barbeiroId);
        if (!$barbeiro) {
            return [];
        }
        
        $diaSemana = $this->getDiaSemana($data);
        $diasTrabalho = explode(',', $barbeiro['dias_trabalho']);
        
        if (!in_array($diaSemana, $diasTrabalho)) {
            return [];
        }
        
        $horariosDisponiveis = [];
        $horaInicio = strtotime($barbeiro['horario_inicio']);
        $horaFim = strtotime($barbeiro['horario_fim']);
        $intervalo = 30 * 60; // 30 minutos em segundos
        
        for ($hora = $horaInicio; $hora < $horaFim; $hora += $intervalo) {
            $horaFormatada = date('H:i:s', $hora);
            
            if ($this->isAvailable($barbeiroId, $data, $horaFormatada, $duracaoServico)) {
                $horariosDisponiveis[] = $horaFormatada;
            }
        }
        
        return $horariosDisponiveis;
    }
    
    /**
     * Converte data para dia da semana em português
     */
    private function getDiaSemana($data) {
        $diasSemana = [
            'Sunday' => 'domingo',
            'Monday' => 'segunda',
            'Tuesday' => 'terca',
            'Wednesday' => 'quarta', 
            'Thursday' => 'quinta',
            'Friday' => 'sexta',
            'Saturday' => 'sabado'
        ];
        
        $diaIngles = date('l', strtotime($data));
        return $diasSemana[$diaIngles];
    }
    
    /**
     * Obtém estatísticas do barbeiro
     */
    public function getEstatisticas($barbeiroId, $dataInicio = null, $dataFim = null) {
        $where = "WHERE a.barbeiro_id = ? AND a.status = 'realizado'";
        $params = [$barbeiroId];
        
        if ($dataInicio) {
            $where .= " AND a.data_agendamento >= ?";
            $params[] = $dataInicio;
        }
        
        if ($dataFim) {
            $where .= " AND a.data_agendamento <= ?";
            $params[] = $dataFim;
        }
        
        $sql = "SELECT 
                    COUNT(*) as total_atendimentos,
                    SUM(COALESCE(a.valor_cobrado, s.preco)) as receita_total,
                    AVG(COALESCE(a.valor_cobrado, s.preco)) as ticket_medio
                FROM agendamentos a
                INNER JOIN servicos s ON a.servico_id = s.id
                $where";
        
        return DB::selectOne($sql, $params);
    }

     /**
     * Validar dados para atualização de perfil
     */
    public function validarDadosAtualizacao($dados, $userId) {
        $erros = [];
        
        // Validar nome
        if (empty($dados['nome'])) {
            $erros[] = 'Nome é obrigatório';
        }
        
        // Validar email
        if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Email válido é obrigatório';
        }
        
        // Verificar se email já existe em outro usuário
        if ($this->emailExiste($dados['email'], $userId)) {
            $erros[] = 'Este email já está sendo usado por outro usuário';
        }
        
        // Validar senha se fornecida
        if (!empty($dados['nova_senha'])) {
            if (empty($dados['senha_atual'])) {
                $erros[] = 'Senha atual é obrigatória para alterar a senha';
            } else {
                // Verificar senha atual
                $usuario = $this->getById($userId);
                if (!$usuario || !password_verify($dados['senha_atual'], $usuario['senha'])) {
                    $erros[] = 'Senha atual incorreta';
                }
            }
            
            if (strlen($dados['nova_senha']) < 6) {
                $erros[] = 'Nova senha deve ter pelo menos 6 caracteres';
            }
            
            if ($dados['nova_senha'] !== $dados['confirmar_senha']) {
                $erros[] = 'Confirmação de senha não confere';
            }
        }
        
        return [
            'valido' => empty($erros),
            'erros' => $erros
        ];
    }

     /**
     * Verificar se email já existe (excluindo um ID específico)
     */
    public function emailExiste($email, $excluirId = null) {
        try {
            if ($excluirId) {
                $sql = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
                $result = DB::select($sql, [$email, $excluirId]);
            } else {
                $sql = "SELECT id FROM usuarios WHERE email = ?";
                $result = DB::select($sql, [$email]);
            }
            return !empty($result);
        } catch (Exception $e) {
            error_log("Erro ao verificar se email existe: " . $e->getMessage());
            return false;
        }
    }
    
}
?>
