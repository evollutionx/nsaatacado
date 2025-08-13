<?php
require_once '../classes/Login.php';
require_once '../classes/Venda.php';
require_once '../classes/Produto.php';

// Verifica se o cliente está logado
if (Login::verificarLogin() !== 'cliente') {
    $_SESSION['redirect'] = 'eshop/meus-pedidos.php';
    header('Location: ../login.php');
    exit();
}

$usuario = Login::getUsuarioLogado();
$venda = new Venda();
$produto = new Produto();

// Obter todas as vendas do cliente que possuem itens
$stmt = $venda->getPdo()->prepare("
    SELECT v.* 
    FROM vendas v
    WHERE v.cliente_id = ? 
    AND EXISTS (SELECT 1 FROM itens_venda iv WHERE iv.venda_id = v.id)
    ORDER BY v.data_venda DESC
");
$stmt->execute([$usuario['id']]);
$vendas = $stmt->fetchAll();

// Verificar se há parâmetro de venda específica
$venda_id = $_GET['venda_id'] ?? null;
$detalhes_venda = null;
$itens_venda = [];

if ($venda_id) {
    // Verificar se a venda pertence ao cliente
    $stmt = $venda->getPdo()->prepare("SELECT * FROM vendas WHERE id = ? AND cliente_id = ?");
    $stmt->execute([$venda_id, $usuario['id']]);
    $detalhes_venda = $stmt->fetch();
    
    if ($detalhes_venda) {
        $itens_venda = $venda->getItensVenda($venda_id);
    }
}
?>
    <?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos - E-Shop</title>
    
    <!-- CDNs -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
            --success-color: #198754;
            --danger-color: #dc3545;
        }
        
        body {
            background-color: #f5f5f5;
        }
        
        .card-pedido {
            transition: all 0.3s ease;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .card-pedido:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-pendente {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-finalizado {
            background-color: #d4edda;
            color: #155724;
        }
        
        .produto-img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 5px;
        }
        
        .accordion-button:not(.collapsed) {
            background-color: rgba(13, 110, 253, 0.05);
            color: var(--dark-color);
        }
        
        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0, 0, 0, 0.125);
        }
        
        @media (max-width: 767.98px) {
            .table-responsive {
                font-size: 0.8rem;
            }
            
            .produto-img {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>

    
    <main class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-0">Meus Pedidos</h2>
                <p class="text-muted">Histórico de todos os seus pedidos</p>
            </div>
        </div>
        
        <?php if (empty($vendas)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-cart-x fs-4"></i>
                <h4 class="mt-3">Nenhum pedido encontrado</h4>
                <p>Você ainda não realizou nenhum pedido em nossa loja.</p>
                <a href="index.php" class="btn btn-primary">Ver produtos</a>
            </div>
        <?php else: ?>
            <div class="accordion" id="pedidosAccordion">
                <?php foreach ($vendas as $pedido): 
                    $itens = $venda->getItensVenda($pedido['id']);
                    $total_pedido = array_sum(array_column($itens, 'vr_total'));
                ?>
                    <div class="card card-pedido mb-3">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <button class="accordion-button collapsed p-0 bg-transparent shadow-none" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#pedido<?= $pedido['id'] ?>" 
                                        aria-expanded="false">
                                    <div class="me-3">
                                        <h6 class="mb-1">Pedido #<?= str_pad($pedido['id'], 6, '0', STR_PAD_LEFT) ?></h6>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar"></i> 
                                            <?= date('d/m/Y H:i', strtotime($pedido['data_venda'])) ?>
                                        </small>
                                    </div>
                                </button>
                                
                                <div class="text-end">
                                    <span class="status-badge status-<?= $pedido['status'] ?>">
                                        <?= ucfirst($pedido['status']) ?>
                                    </span>
                                    <div class="fw-bold mt-1">R$ <?= number_format($total_pedido, 2, ',', '.') ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="pedido<?= $pedido['id'] ?>" class="accordion-collapse collapse" data-bs-parent="#pedidosAccordion">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Produto</th>
                                                <th class="text-center">Qtd</th>
                                                <th class="text-end">Unitário</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($itens as $item): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="../<?= htmlspecialchars($item['foto_path'] ?? 'assets/images/produtos/sem-foto.jpg') ?>" 
                                                                 class="produto-img me-3" 
                                                                 alt="<?= htmlspecialchars($item['nome']) ?>">
                                                            <div>
                                                                <div class="fw-bold"><?= htmlspecialchars($item['nome']) ?></div>
                                                                <small class="text-muted">
                                                                    <?= $item['unidade_medida'] === 'caixa' ? 'Caixa com '.$item['quantidade_caixa'].' un.' : 'Unidade' ?>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center"><?= $item['quantidade'] ?></td>
                                                    <td class="text-end">R$ <?= number_format($item['vr_unitario'], 2, ',', '.') ?></td>
                                                    <td class="text-end fw-bold">R$ <?= number_format($item['vr_total'], 2, ',', '.') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-light">
                                                <td colspan="3" class="text-end fw-bold">Total do Pedido:</td>
                                                <td class="text-end fw-bold">R$ <?= number_format($total_pedido, 2, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>                        
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    
    <?php include 'footer.php'; ?>
    
    <!-- CDNs JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script>
        // Se houver parâmetro de venda_id na URL, expande o accordion correspondente
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const vendaId = urlParams.get('venda_id');
            
            if (vendaId) {
                const accordionItem = $('#pedido' + vendaId);
                accordionItem.collapse('show');
                
                // Rolagem suave até o pedido
                $('html, body').animate({
                    scrollTop: accordionItem.offset().top - 20
                }, 500);
            }
        });
    </script>
</body>
</html>