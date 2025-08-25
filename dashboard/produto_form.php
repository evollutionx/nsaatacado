<?php
require_once '../classes/Produtos.php';
require_once '../classes/Categoria.php';
require_once 'header.php';

$produto = new Produto();
$categorias = Categoria::getTodasCategorias();

$id = $_GET['id'] ?? null;
$dados = null;

if ($id) {
    $dados = $produto->getProdutoPorId((int)$id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    
    // Processar upload da imagem
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $codigo_barras = $data['codigo_barras'];
        $diretorio = '../assets/images/produtos/';
        
        // Criar diretório se não existir
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0777, true);
        }
        
        $nome_arquivo = $codigo_barras . '.jpg';
        $caminho_completo = $diretorio . $nome_arquivo;
        
        // Mover arquivo uploadado
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_completo)) {
            // Opcional: redimensionar imagem se necessário
        }
    }
    
    if ($id) {
        $sucesso = $produto->atualizarProduto((int)$id, $data);
    } else {
        $sucesso = $produto->adicionarProduto($data);
    }
    
    if ($sucesso) {
        header("Location: produtos.php");
        exit;
    } else {
        $erro = "Erro ao salvar produto!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? "Editar Produto" : "Novo Produto" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .preview-imagem {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2><?= $id ? "Editar Produto" : "Cadastrar Novo Produto" ?></h2>
    
    <?php if (isset($erro)): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>
    
    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Categoria</label>
                    <select name="categoria_id" class="form-select" required>
                        <option value="">Selecione</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($dados['categoria_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Código de Barras</label>
                    <input type="text" name="codigo_barras" class="form-control" required 
                           value="<?= htmlspecialchars($dados['codigo_barras'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" required 
                           value="<?= htmlspecialchars($dados['nome'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($dados['descricao'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Preço Unitário</label>
                    <input type="number" step="0.01" name="vr_unitario" class="form-control" required 
                           value="<?= htmlspecialchars($dados['vr_unitario'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="ativo" <?= ($dados['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                        <option value="inativo" <?= ($dados['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Foto do Produto</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" 
                           onchange="previewImage(this)">
                    
                    <?php if ($id && $dados && file_exists('../' . $dados['foto_path'])): ?>
                        <div class="mt-3">
                            <label class="form-label">Imagem Atual:</label>
                            <img src="../<?= $dados['foto_path'] ?>?t=<?= time() ?>" 
                                 class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>
                    
                    <div id="imagePreview" class="preview-imagem" style="display: none;">
                        <p>Pré-visualização:</p>
                        <img id="preview" src="#" alt="Pré-visualização" class="img-fluid">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Preço por Caixa</label>
                    <input type="number" step="0.01" name="vr_caixa" class="form-control" 
                           value="<?= htmlspecialchars($dados['vr_caixa'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Quantidade por Caixa</label>
                    <input type="number" name="quantidade_caixa" class="form-control" 
                           value="<?= htmlspecialchars($dados['quantidade_caixa'] ?? '1') ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Profundidade (cm)</label>
                    <input type="number" step="0.1" name="profundidade" class="form-control" 
                           value="<?= htmlspecialchars($dados['profundidade'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Unidade de Medida</label>
                    <select name="unidade_medida" class="form-select">
                        <option value="UN" <?= ($dados['unidade_medida'] ?? '') === 'UN' ? 'selected' : '' ?>>Unidade (UN)</option>
                        <option value="KG" <?= ($dados['unidade_medida'] ?? '') === 'KG' ? 'selected' : '' ?>>Quilo (KG)</option>
                        <option value="LT" <?= ($dados['unidade_medida'] ?? '') === 'LT' ? 'selected' : '' ?>>Litro (LT)</option>
                        <option value="M" <?= ($dados['unidade_medida'] ?? '') === 'M' ? 'selected' : '' ?>>Metro (M)</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><?= $id ? "Salvar Alterações" : "Cadastrar" ?></button>
        <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.style.display = 'none';
        preview.src = '';
    }
}
</script>
</body>
</html>
<?php include 'footer.php'; ?>