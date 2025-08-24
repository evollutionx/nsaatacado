<?php
require_once 'DB.php';

class Produto {
    private $pdo;
    
    public function __construct() {
        $this->pdo = DB::getInstance();
    }
    
    public function listarProdutos($pagina = 1, $por_pagina = 10, $categoria_id = null, $ordenacao = 'padrao') {
        $offset = ($pagina - 1) * $por_pagina;

        $sql = "SELECT p.*, c.nome as categoria_nome 
                FROM produtos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.status = 'ativo'";

        // Filtro por categoria
        if ($categoria_id) {
            $sql .= " AND p.categoria_id = :categoria_id";
        }

        // Ordenação
        switch ($ordenacao) {
            case 'preco_asc':
                $sql .= " ORDER BY p.vr_unitario ASC";
                break;
            case 'preco_desc':
                $sql .= " ORDER BY p.vr_unitario DESC";
                break;
            default:
                $sql .= " ORDER BY p.id DESC";
                break;
        }

        $sql .= " LIMIT :limite OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        // Bind dos parâmetros
        if ($categoria_id) {
            $stmt->bindValue(':categoria_id', $categoria_id, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTodosProdutos() {
        $sql = "SELECT p.*, c.nome as categoria_nome 
                FROM produtos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                ORDER BY p.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarProdutos() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM produtos WHERE status = 'ativo'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getProdutoById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getPrecoProduto($id, $quantidade) {
        $produto = $this->getProdutoById($id);
        
        if ($quantidade >= $produto['quantidade_caixa'] && $produto['quantidade_caixa'] > 0) {
            return $produto['vr_caixa'];
        } else {
            return $produto['vr_unitario'];
        }
    }

    public function buscarProdutos($termo, $categoria_id = null, $pagina = 1, $por_pagina = 12) {
        $offset = ($pagina - 1) * $por_pagina;
        $termo = '%' . $termo . '%';
        
        $sql = "SELECT p.*, c.nome as categoria_nome 
                FROM produtos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.status = 'ativo' 
                AND (p.nome LIKE :termo OR p.descricao LIKE :termo OR c.nome LIKE :termo)";
        
        if ($categoria_id) {
            $sql .= " AND p.categoria_id = :categoria_id";
        }
        
        $sql .= " LIMIT :offset, :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':termo', $termo);
        
        if ($categoria_id) {
            $stmt->bindValue(':categoria_id', $categoria_id, PDO::PARAM_INT);
        }
        
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $por_pagina, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function contarProdutosBusca($termo, $categoria_id = null) {
        $termo = '%' . $termo . '%';
        
        $sql = "SELECT COUNT(*) 
                FROM produtos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.status = 'ativo' 
                AND (p.nome LIKE :termo OR p.descricao LIKE :termo OR c.nome LIKE :termo)";
        
        if ($categoria_id) {
            $sql .= " AND p.categoria_id = :categoria_id";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':termo', $termo);
        
        if ($categoria_id) {
            $stmt->bindValue(':categoria_id', $categoria_id, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function buscarProdutoPorId($id) {
        $sql = "SELECT p.*, c.nome as categoria_nome 
                FROM produtos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarProduto($id, $dados) {
        $sql = "UPDATE produtos SET 
                nome = :nome, 
                codigo_barras = :codigo_barras, 
                categoria_id = :categoria_id, 
                vr_unitario = :vr_unitario, 
                vr_caixa = :vr_caixa, 
                estoque = :estoque, 
                status = :status, 
                descricao = :descricao";
        
        // Adicionar foto_path se existir
        if (!empty($dados['foto_path'])) {
            $sql .= ", foto_path = :foto_path";
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':codigo_barras', $dados['codigo_barras']);
        $stmt->bindValue(':categoria_id', $dados['categoria_id'], PDO::PARAM_INT);
        $stmt->bindValue(':vr_unitario', $dados['vr_unitario']);
        $stmt->bindValue(':vr_caixa', $dados['vr_caixa']);
        $stmt->bindValue(':estoque', $dados['estoque'], PDO::PARAM_INT);
        $stmt->bindValue(':status', $dados['status']);
        $stmt->bindValue(':descricao', $dados['descricao']);
        
        // Bind da foto se existir
        if (!empty($dados['foto_path'])) {
            $stmt->bindValue(':foto_path', $dados['foto_path']);
        }
        
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // NOVO MÉTODO: Obter todas as categorias para o formulário
    public function listarCategorias() {
        $stmt = $this->pdo->prepare("SELECT id, nome FROM categorias WHERE status = 'ativo' ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // NOVO MÉTODO: Verificar se código de barras já existe (excluindo o produto atual)
    public function verificarCodigoBarras($codigo_barras, $id_excluir = null) {
        $sql = "SELECT COUNT(*) FROM produtos WHERE codigo_barras = :codigo_barras";
        
        if ($id_excluir) {
            $sql .= " AND id != :id_excluir";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':codigo_barras', $codigo_barras);
        
        if ($id_excluir) {
            $stmt->bindValue(':id_excluir', $id_excluir, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>