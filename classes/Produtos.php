<?php
require_once 'DB.php';

class Produto {
    private $pdo;

    public function __construct() {
        $this->pdo = DB::getInstance();
    }

    /**
     * Lista produtos com paginação
     */
    public function listarProdutos(int $pagina = 1, int $por_pagina = 10): array {
        $offset = ($pagina - 1) * $por_pagina;

        try {
            $sql = "SELECT p.*, c.nome AS categoria 
                    FROM produtos p
                    LEFT JOIN categorias c ON p.categoria_id = c.id
                    ORDER BY p.nome
                    LIMIT :offset, :por_pagina";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':por_pagina', $por_pagina, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar produtos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Conta total de produtos
     */
    public function contarProdutos(): int {
        try {
            $sql = "SELECT COUNT(*) as total FROM produtos";
            $stmt = $this->pdo->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int) $row['total'];
        } catch (PDOException $e) {
            error_log("Erro ao contar produtos: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Buscar produto por ID
     */
    public function getProdutoPorId(int $id): ?array {
        try {
            $sql = "SELECT * FROM produtos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar produto: " . $e->getMessage());
            return null;
        }
    }

/**
 * Adicionar produto
 */
public function adicionarProduto(array $dados): bool {
    try {
        $sql = "INSERT INTO produtos (
                    categoria_id, codigo_barras, nome, descricao, unidade_medida,
                    vr_unitario, vr_caixa, quantidade_caixa,
                    profundidade, foto_path, status
                ) VALUES (
                    :categoria_id, :codigo_barras, :nome, :descricao, :unidade_medida,
                    :vr_unitario, :vr_caixa, :quantidade_caixa,
                    :profundidade, :foto_path, :status
                )";

        $stmt = $this->pdo->prepare($sql);

        // Definindo o caminho da imagem
        $foto_path = "assets/images/produtos/" . $dados['codigo_barras'] . ".jpg";

        $stmt->bindValue(':categoria_id', $dados['categoria_id'], PDO::PARAM_INT);
        $stmt->bindValue(':codigo_barras', $dados['codigo_barras']);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':descricao', $dados['descricao']);
        $stmt->bindValue(':unidade_medida', $dados['unidade_medida'] ?? 'UN');
        $stmt->bindValue(':vr_unitario', $dados['vr_unitario']);
        $stmt->bindValue(':vr_caixa', $dados['vr_caixa']);
        $stmt->bindValue(':quantidade_caixa', $dados['quantidade_caixa'] ?? 1, PDO::PARAM_INT);
        $stmt->bindValue(':profundidade', $dados['profundidade']);
        $stmt->bindValue(':foto_path', $foto_path);
        $stmt->bindValue(':status', $dados['status'] ?? 'ativo');

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erro ao adicionar produto: " . $e->getMessage());
        return false;
    }
}

/**
 * Atualizar produto
 */
public function atualizarProduto(int $id, array $dados): bool {
    try {
        // pega valores atuais
        $produtoExistente = $this->getProdutoPorId($id);

        $sql = "UPDATE produtos SET 
                    categoria_id = :categoria_id,
                    codigo_barras = :codigo_barras,
                    nome = :nome,
                    descricao = :descricao,
                    unidade_medida = :unidade_medida,
                    vr_unitario = :vr_unitario,
                    vr_caixa = :vr_caixa,
                    quantidade_caixa = :quantidade_caixa,
                    profundidade = :profundidade,
                    foto_path = :foto_path,
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $foto_path = "assets/images/produtos/" . ($dados['codigo_barras'] ?? $produtoExistente['codigo_barras']) . ".jpg";

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':categoria_id', $dados['categoria_id'] ?? $produtoExistente['categoria_id'], PDO::PARAM_INT);
        $stmt->bindValue(':codigo_barras', $dados['codigo_barras'] ?? $produtoExistente['codigo_barras']);
        $stmt->bindValue(':nome', $dados['nome'] ?? $produtoExistente['nome']);
        $stmt->bindValue(':descricao', $dados['descricao'] ?? $produtoExistente['descricao']);
        $stmt->bindValue(':unidade_medida', $dados['unidade_medida'] ?? $produtoExistente['unidade_medida']);
        $stmt->bindValue(':vr_unitario', $dados['vr_unitario'] ?? $produtoExistente['vr_unitario']);
        $stmt->bindValue(':vr_caixa', $dados['vr_caixa'] ?? $produtoExistente['vr_caixa']);
        $stmt->bindValue(':quantidade_caixa', $dados['quantidade_caixa'] ?? $produtoExistente['quantidade_caixa'], PDO::PARAM_INT);
        $stmt->bindValue(':profundidade', $dados['profundidade'] ?? $produtoExistente['profundidade']);
        $stmt->bindValue(':foto_path', $foto_path);
        $stmt->bindValue(':status', $dados['status'] ?? $produtoExistente['status']);

        return $stmt->execute();
    } catch (PDOException $e) {
        die("Erro ao atualizar produto: " . $e->getMessage());
    }
}


}
?>
