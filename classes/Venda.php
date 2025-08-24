<?php
require_once 'DB.php';

class Venda {
    private $pdo;
    
    public function __construct() {
        $this->pdo = DB::getInstance();
    }
    
    public function getPdo() {
        return $this->pdo;
    }
    
    public function getVendaPendente($cliente_id) {
        $stmt = $this->pdo->prepare("SELECT id FROM vendas WHERE cliente_id = ? AND status = 'pendente'  order by id desc LIMIT 1");
        $stmt->execute([$cliente_id]);
        return $stmt->fetchColumn();
    }
    
    public function criarVenda($cliente_id) {
        $stmt = $this->pdo->prepare("INSERT INTO vendas (cliente_id, data_venda, status) VALUES (?, NOW(), 'pendente')");
        $stmt->execute([$cliente_id]);
        return $this->pdo->lastInsertId();
    }
    
    public function adicionarItem($venda_id, $produto_id, $quantidade, $preco_unitario, $preco_total, $unidade_medida = 'unidade') {
        // Sempre cria um novo registro, mesmo para o mesmo produto
        $stmt = $this->pdo->prepare("INSERT INTO itens_venda 
            (venda_id, produto_id, quantidade, vr_unitario, vr_total, vr_final, unidade_medida) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $venda_id, 
            $produto_id, 
            $quantidade, 
            $preco_unitario, 
            $preco_total, 
            $preco_total,
            $unidade_medida
        ]);
        return $this->pdo->lastInsertId();
    }
    
    public function contarItens($venda_id) {
        $stmt = $this->pdo->prepare("SELECT SUM(quantidade) FROM itens_venda WHERE venda_id = ?");
        $stmt->execute([$venda_id]);
        return $stmt->fetchColumn() ?? 0;
    }
    
    public function getItensVenda($venda_id) {
        $stmt = $this->pdo->prepare("SELECT iv.*, p.nome, p.foto_path, p.quantidade_caixa
                                    FROM itens_venda iv
                                    JOIN produtos p ON iv.produto_id = p.id
                                    WHERE iv.venda_id = ?");
        $stmt->execute([$venda_id]);
        return $stmt->fetchAll();
    }
    // classes/Venda.php
public function criarNovaVenda($cliente_id) {
    $stmt = $this->pdo->prepare("INSERT INTO vendas (cliente_id, data_venda, status) VALUES (?, NOW(), 'pendente')");
    $stmt->execute([$cliente_id]);
    return $this->pdo->lastInsertId();
}

// Adicione estes métodos à sua classe Venda

public function getVendasDoDia() {
    $stmt = $this->pdo->prepare("
        SELECT COALESCE(SUM(iv.vr_final), 0) as total 
        FROM vendas v 
        JOIN itens_venda iv ON v.id = iv.venda_id 
        WHERE DATE(v.data_venda) = CURDATE()
    ");
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getVendasSemana() {
    $stmt = $this->pdo->prepare("
        SELECT 
            DAYNAME(v.data_venda) as dia_semana,
            DATE(v.data_venda) as data,
            COALESCE(SUM(iv.vr_final), 0) as total
        FROM vendas v 
        JOIN itens_venda iv ON v.id = iv.venda_id 
        WHERE v.data_venda >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
        GROUP BY DATE(v.data_venda)
        ORDER BY v.data_venda
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}

public function getVendasMes() {
    $stmt = $this->pdo->prepare("
        SELECT 
            DAY(v.data_venda) as dia,
            DATE(v.data_venda) as data,
            COALESCE(SUM(iv.vr_final), 0) as total
        FROM vendas v 
        JOIN itens_venda iv ON v.id = iv.venda_id 
        WHERE MONTH(v.data_venda) = MONTH(CURDATE()) 
        AND YEAR(v.data_venda) = YEAR(CURDATE())
        GROUP BY DATE(v.data_venda)
        ORDER BY v.data_venda
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}

public function getVendasAno() {
    $stmt = $this->pdo->prepare("
        SELECT 
            MONTHNAME(v.data_venda) as mes,
            MONTH(v.data_venda) as mes_num,
            COALESCE(SUM(iv.vr_final), 0) as total
        FROM vendas v 
        JOIN itens_venda iv ON v.id = iv.venda_id 
        WHERE YEAR(v.data_venda) = YEAR(CURDATE())
        GROUP BY MONTH(v.data_venda)
        ORDER BY v.data_venda
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}
}

?>