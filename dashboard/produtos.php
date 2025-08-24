<?php
require_once 'header.php';
require_once '../classes/Produto.php';

$produto = new Produto();

// Buscar todos os produtos para o DataTables
$produtos = $produto->listarTodosProdutos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Produtos</title>
    
    <!-- CDNs -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #6f42c1;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --light-bg: #f8f9fc;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .page-header {
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
        }
        
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .status-badge {
            padding: 0.35rem 0.5rem;
            border-radius: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.2rem;
        }
        
        .dataTables_filter {
            margin-bottom: 1rem;
        }
        
        .dataTables_filter label {
            display: flex;
            align-items: center;
        }
        
        .dataTables_filter input {
            margin-left: 0.5rem;
            border-radius: 0.35rem;
            border: 1px solid #d1d3e2;
            padding: 0.375rem 0.75rem;
        }
        
        .dataTables_info {
            padding-top: 1rem !important;
        }
        
        .dataTables_paginate {
            padding-top: 1rem !important;
        }
        
        @media (max-width: 768px) {
            .dataTables_wrapper .dataTables_filter {
                float: none;
                text-align: center;
            }
            
            .dataTables_wrapper .dataTables_length {
                text-align: center;
            }
            
            .dataTables_wrapper .dataTables_paginate {
                text-align: center;
            }
            
            .table td {
                font-size: 0.875rem;
            }
            
            .btn-action {
                font-size: 0.75rem;
            }
        }
        
        /* Estilo personalizado para a tabela DataTables */
        table.dataTable thead th {
            border-bottom: 2px solid #e3e6f0;
        }
        
        table.dataTable tbody tr {
            background-color: white;
        }
        
        table.dataTable tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }
        
        table.dataTable tbody tr.even {
            background-color: #f8f9fc;
        }
        
        table.dataTable tbody tr.even:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="page-header d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0 text-gray-800 animate__animated animate__fadeInDown">
                        <i class="fas fa-boxes text-primary me-2"></i> Gestão de Produtos
                    </h1>
                    <a href="cadastrar_produto.php" class="btn btn-primary btn-icon-split animate__animated animate__fadeIn">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Novo Produto</span>
                    </a>
                </div>
                
                <!-- Card de Estatísticas -->
                <div class="row mb-4 animate__animated animate__fadeIn">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total de Produtos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($produtos) ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabela de Produtos -->
                <div class="card animate__animated animate__fadeInUp">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Lista de Produtos</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabela-produtos" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Foto</th>
                                        <th>Código</th>
                                        <th>Nome</th>
                                        <th>Categoria</th>
                                        <th>Preço Unit.</th>
                                        <th>Preço Caixa</th>
                                        <th>Estoque</th>
                                        <th>Status</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($produtos): ?>
                                        <?php foreach ($produtos as $p): ?>
                                            <tr>
                                                <td><?= $p['id'] ?></td>
                                                <td>
                                                    <?php
                                                    $foto = !empty($p['foto_path']) && file_exists("../" . $p['foto_path']) 
                                                        ? "../" . $p['foto_path'] 
                                                        : "https://via.placeholder.com/50x50/e9ecef/868e96?text=Sem+Foto";
                                                    ?>
                                                    <img src="<?= $foto ?>" alt="Foto <?= htmlspecialchars($p['nome']) ?>" class="product-img">
                                                </td>
                                                <td><?= htmlspecialchars($p['codigo_barras']) ?></td>
                                                <td><?= htmlspecialchars($p['nome']) ?></td>
                                                <td><?= htmlspecialchars($p['categoria_nome']) ?></td>
                                                <td>R$ <?= number_format($p['vr_unitario'], 2, ',', '.') ?></td>
                                                <td>R$ <?= number_format($p['vr_caixa'], 2, ',', '.') ?></td>
                                                <td>
                                                    <span class="<?= $p['estoque'] < 10 ? 'text-danger fw-bold' : '' ?>">
                                                        <?= $p['estoque'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if($p['status'] === 'ativo'): ?>
                                                        <span class="status-badge bg-success text-white">
                                                            <i class="fas fa-check-circle me-1"></i> Ativo
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="status-badge bg-secondary text-white">
                                                            <i class="fas fa-ban me-1"></i> Inativo
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="editar_produto.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-action btn-primary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                        <span class="d-none d-md-inline"> Editar</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                                <p class="text-muted">Nenhum produto encontrado.</p>
                                                <a href="cadastrar_produto.php" class="btn btn-primary mt-2">
                                                    <i class="fas fa-plus me-1"></i> Cadastrar Primeiro Produto
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (necessário para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <script>
        // Inicializar DataTables
        $(document).ready(function() {
            $('#tabela-produtos').DataTable({
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                    decimal: ",",
                    thousands: "."
                },
                columnDefs: [
                    { responsivePriority: 1, targets: 3 }, // Nome
                    { responsivePriority: 2, targets: 9 }, // Ações
                    { orderable: false, targets: [1, 9] }, // Colunas não ordenáveis (foto e ações)
                    { searchable: false, targets: [1, 9] } // Colunas não pesquisáveis (foto e ações)
                ],
                order: [[0, 'desc']], // Ordenar por ID decrescente
                pageLength: 10, // Itens por página
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>'
            });
            
            // Adicionar animação ao carregar a página
            const animatedElements = document.querySelectorAll('.animate__animated');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const animation = entry.target.getAttribute('data-animation');
                        entry.target.classList.add(animation || 'animate__fadeIn');
                    }
                });
            }, { threshold: 0.1 });
            
            animatedElements.forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>

<?php require_once 'footer.php'; ?>