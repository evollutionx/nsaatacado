<?php
require_once '../classes/Produtos.php';
require_once 'header.php';
$produto = new Produto();

// Paginação simples
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 1000; // DataTables já cuida da paginação no front

$produtos = $produto->listarProdutos($pagina, $por_pagina);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
            --light-text: #6c757d;
            --border-radius: 12px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Poppins', sans-serif;
            color: var(--dark-text);
            padding-bottom: 2rem;
        }
        
        .page-header {
            background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: var(--border-radius);
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--box-shadow);
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
        }
        
        .btn-primary {
            background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn-sm {
            padding: 0.35rem 0.8rem;
            font-size: 0.875rem;
        }
        
        .btn-action {
            border-radius: 8px;
            margin: 0 2px;
        }
        
        .table-hover tbody tr {
            transition: var(--transition);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .status-badge {
            padding: 0.35rem 0.65rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        .dataTables_wrapper {
            padding: 0;
        }
        
        .dataTables_filter input {
            border-radius: 50px;
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
        }
        
        #tabelaProdutos th {
            font-weight: 600;
            color: var(--dark-text);
        }
        
        .action-buttons {
            white-space: nowrap;
        }
        
        .product-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        /* Custom DataTables pagination */
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
        }
        
        .page-link {
            border-radius: 8px;
            margin: 0 3px;
            color: var(--primary-color);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-header {
                padding: 1rem;
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .page-header > div {
                text-align: center;
            }
            
            .card-header {
                padding: 1rem;
            }
            
            .table-responsive {
                border-radius: var(--border-radius);
            }
            
            .btn-new-product {
                width: 100%;
            }
            
            .dataTables_wrapper .row {
                margin: 0;
            }
            
            .dataTables_length, .dataTables_filter {
                margin-bottom: 1rem;
            }
            
            .dataTables_length select, .dataTables_filter input {
                width: 100% !important;
            }
        }
        
        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .page-header h2 {
                font-size: 1.5rem;
            }
            
            .card-header h5 {
                font-size: 1.2rem;
            }
            
            .btn {
                padding: 0.5rem 1rem;
            }
        }
        
        /* Animation for new product button */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .btn-new-product {
            animation: pulse 2s infinite;
        }
        
        /* Mobile-first adjustments */
        .dtr-details li {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 1px solid #eee;
            padding: 0.5rem 0;
        }
        
        .dtr-details .dtr-title {
            font-weight: 600;
            min-width: 40%;
            margin-right: 1rem;
        }
        
        .dtr-details .dtr-data {
            flex: 1;
        }
        
        /* Hide columns on mobile */
        @media (max-width: 992px) {
            .hide-on-mobile {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 class="mb-1"><i class="fas fa-boxes me-2"></i> Gerenciamento de Produtos</h2>
                    <p class="mb-0 opacity-75">Visualize, edite e gerencie todos os produtos do sistema</p>
                </div>
                <a href="produto_form.php" class="btn btn-light btn-new-product">
                    <i class="fas fa-plus-circle me-2"></i> Novo Produto
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Produtos</h5>
                 </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelaProdutos" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Imagem</th>
                                    <th>Nome</th>
                                    <th class="hide-on-mobile">Código de Barras</th>
                                    <th class="hide-on-mobile">Categoria</th>
                                    <th>Preço</th>
                                    <th>Status</th>
                                    <th width="120px" class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produtos as $p): 
                                    $imagem_path = "../" . $p['foto_path'];
                                    $imagem_existe = file_exists($imagem_path);
                                ?>
                                    <tr>
                                        <td>
                                            <?php if ($imagem_existe): ?>
                                                <img src="<?= $imagem_path ?>?t=<?= time() ?>" class="product-image" alt="<?= htmlspecialchars($p['nome']) ?>">
                                            <?php else: ?>
                                                <div class="product-image bg-light d-flex align-items-center justify-content-center rounded">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-semibold"><?= htmlspecialchars($p['nome']) ?></td>
                                        <td class="hide-on-mobile">
                                            <span class="font-monospace"><?= htmlspecialchars($p['codigo_barras']) ?></span>
                                        </td>
                                        <td class="hide-on-mobile">
                                            <span class="badge bg-light text-dark"><?= htmlspecialchars($p['categoria'] ?? 'Sem categoria') ?></span>
                                        </td>
                                        <td class="fw-bold text-nowrap">R$ <?= number_format($p['vr_unitario'], 2, ',', '.') ?></td>
                                        <td>
                                            <?php if ($p['status'] === 'ativo'): ?>
                                                <span class="status-badge bg-success"><i class="fas fa-check-circle me-1"></i> Ativo</span>
                                            <?php else: ?>
                                                <span class="status-badge bg-secondary"><i class="fas fa-times-circle me-1"></i> Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center action-buttons">
                                            <a href="produto_form.php?id=<?= $p['id'] ?>" 
                                               class="btn btn-sm btn-primary btn-action" 
                                               data-bs-toggle="tooltip" 
                                               title="Editar produto">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS: Bootstrap + DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with responsive features
    $('#tabelaProdutos').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
        },
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50, 100],
        "order": [[1, "asc"]], // Order by name (second column)
        "columnDefs": [
            { "orderable": false, "targets": [0, 5, 6] }, // Disable sorting for image, status and actions columns
            { "responsivePriority": 1, "targets": 1 }, // Name column has highest priority
            { "responsivePriority": 2, "targets": 4 }, // Price column
            { "responsivePriority": 3, "targets": 6 }  // Actions column
        ],
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        "responsive": {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function (row) {
                        var data = row.data();
                        return 'Detalhes do Produto: ' + data[1];
                    }
                }),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                    tableClass: 'table'
                })
            }
        },
        "initComplete": function() {
            // Add custom class to pagination elements
            $('.dataTables_paginate').addClass('mt-3');
            $('.dataTables_info').addClass('mt-3');
        }
    });
    
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Window resize adjustments
    $(window).on('resize', function() {
        $('#tabelaProdutos').DataTable().columns.adjust().responsive.recalc();
    });
});
</script>
</body>
</html>
<?php include 'footer.php'; ?>