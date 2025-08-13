<?php
require_once 'header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h5>
                </div>
                <div class="card-body">
                    <!-- Widgets de Resumo -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="card border-0 bg-primary bg-opacity-10">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Vendas Hoje</h6>
                                            <h3 class="mb-0">R$ 5,240</h3>
                                        </div>
                                        <div class="bg-primary bg-opacity-25 p-3 rounded">
                                            <i class="bi bi-cart-check-fill text-primary fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="badge bg-success"><i class="bi bi-arrow-up me-1"></i> 12%</span>
                                        <span class="text-muted ms-2">vs ontem</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="card border-0 bg-success bg-opacity-10">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Total Clientes</h6>
                                            <h3 class="mb-0">1,254</h3>
                                        </div>
                                        <div class="bg-success bg-opacity-25 p-3 rounded">
                                            <i class="bi bi-people-fill text-success fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="badge bg-success"><i class="bi bi-arrow-up me-1"></i> 5%</span>
                                        <span class="text-muted ms-2">vs mês passado</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="card border-0 bg-warning bg-opacity-10">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Produtos</h6>
                                            <h3 class="mb-0">328</h3>
                                        </div>
                                        <div class="bg-warning bg-opacity-25 p-3 rounded">
                                            <i class="bi bi-box-seam-fill text-warning fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="badge bg-danger"><i class="bi bi-arrow-down me-1"></i> 2%</span>
                                        <span class="text-muted ms-2">estoque baixo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-0 bg-info bg-opacity-10">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Pedidos</h6>
                                            <h3 class="mb-0">42</h3>
                                        </div>
                                        <div class="bg-info bg-opacity-25 p-3 rounded">
                                            <i class="bi bi-receipt-cutoff text-info fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="badge bg-success"><i class="bi bi-arrow-up me-1"></i> 8%</span>
                                        <span class="text-muted ms-2">em processamento</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gráficos e Tabelas -->
                    <div class="row">
                        <div class="col-lg-8 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-0">
                                    <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line me-2"></i>Vendas Mensais</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="vendasChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-0">
                                    <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2"></i>Categorias Mais Vendidas</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="categoriasChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold"><i class="bi bi-table me-2"></i>Últimas Vendas</h6>
                                    <a href="vendas.php" class="btn btn-sm btn-outline-primary">Ver Todas</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Cliente</th>
                                                    <th>Data</th>
                                                    <th>Valor</th>
                                                    <th>Status</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>#1254</td>
                                                    <td>João Silva</td>
                                                    <td>10/11/2023</td>
                                                    <td>R$ 259,90</td>
                                                    <td><span class="badge bg-success">Concluído</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>#1253</td>
                                                    <td>Maria Oliveira</td>
                                                    <td>09/11/2023</td>
                                                    <td>R$ 189,50</td>
                                                    <td><span class="badge bg-warning">Processando</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>#1252</td>
                                                    <td>Carlos Souza</td>
                                                    <td>08/11/2023</td>
                                                    <td>R$ 420,00</td>
                                                    <td><span class="badge bg-success">Concluído</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>#1251</td>
                                                    <td>Ana Costa</td>
                                                    <td>07/11/2023</td>
                                                    <td>R$ 150,00</td>
                                                    <td><span class="badge bg-danger">Cancelado</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CDN Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Gráfico de Vendas Mensais
    const vendasCtx = document.getElementById('vendasChart').getContext('2d');
    const vendasChart = new Chart(vendasCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            datasets: [{
                label: 'Vendas 2023',
                data: [12500, 19000, 15000, 18000, 21000, 19500, 23000, 24500, 22000, 25000, 27000, 30000],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
    
    // Gráfico de Categorias
    const categoriasCtx = document.getElementById('categoriasChart').getContext('2d');
    const categoriasChart = new Chart(categoriasCtx, {
        type: 'doughnut',
        data: {
            labels: ['Eletrônicos', 'Roupas', 'Acessórios', 'Livros', 'Outros'],
            datasets: [{
                data: [35, 25, 20, 15, 5],
                backgroundColor: [
                    '#0d6efd',
                    '#20c997',
                    '#fd7e14',
                    '#6f42c1',
                    '#adb5bd'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${percentage}% (${value})`;
                        }
                    }
                }
            }
        }
    });
</script>

<?php
require_once 'footer.php';
?>