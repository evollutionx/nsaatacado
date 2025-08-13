<?php
require_once '../classes/Login.php';

// Verifica se o usuário está logado, caso contrário redireciona para o login
if (Login::verificarLogin() !== 'usuario') {
    header('Location: ../login.php');
    exit();
}

// Obtém informações do usuário logado
$usuario = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema</title>
    
    <!-- CDNs para UI/UX profissional -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="../assets/images/empresa/logo.ico" type="image/x-icon">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
            --success-color: #198754;
            --danger-color: #dc3545;
            --transition-speed: 0.3s;
        }
        
        body {
            background-color: #f5f7fa;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 0.5rem 1rem;
        }
        
        .navbar-brand img {
            height: 40px;
            transition: all var(--transition-speed) ease;
        }
        
        .navbar-brand img:hover {
            transform: scale(1.05);
        }
        
        .nav-link {
            color: var(--dark-color);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all var(--transition-speed) ease;
            margin: 0 0.15rem;
        }
        
        .nav-link:hover, .nav-link:focus {
            color: var(--primary-color);
            background-color: rgba(13, 110, 253, 0.1);
        }
        
        .nav-link.active {
            color: var(--primary-color);
            font-weight: 600;
            background-color: rgba(13, 110, 253, 0.1);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-radius: 0.5rem;
            padding: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: all var(--transition-speed) ease;
        }
        
        .dropdown-item:hover, .dropdown-item:focus {
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
        }
        
        .dropdown-divider {
            margin: 0.25rem 0;
            border-color: rgba(0, 0, 0, 0.05);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(13, 110, 253, 0.2);
            transition: all var(--transition-speed) ease;
        }
        
        .user-avatar:hover {
            border-color: var(--primary-color);
        }
        
        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.6rem;
            padding: 0.25rem 0.35rem;
        }
        
        @media (max-width: 991.98px) {
            .navbar-collapse {
                padding: 1rem 0;
            }
            
            .nav-link {
                margin: 0.25rem 0;
                padding: 0.75rem 1rem;
            }
            
            .dropdown-menu {
                box-shadow: none;
                border: 1px solid rgba(0, 0, 0, 0.05);
                margin-left: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="../assets/images/empresa/logo.png" alt="Logo">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Menu Cadastros -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="cadastrosDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-lines-fill me-1"></i>Cadastros
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="cadastrosDropdown">
                            <li><a class="dropdown-item" href="clientes.php"><i class="bi bi-people-fill me-2"></i>Clientes</a></li>
                            <li><a class="dropdown-item" href="produtos.php"><i class="bi bi-box-seam-fill me-2"></i>Produtos</a></li>
                            <li><a class="dropdown-item" href="empresa.php"><i class="bi bi-building-fill me-2"></i>Empresa</a></li>
                        </ul>
                    </li>
                    
                    <!-- Menu Vendas -->
                    <li class="nav-item">
                        <a class="nav-link" href="vendas.php">
                            <i class="bi bi-cart-fill me-1"></i>Vendas
                        </a>
                    </li>
                    
                    <!-- Menu Relatórios -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="relatoriosDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-graph-up me-1"></i>Relatórios
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="relatoriosDropdown">
                            <li><a class="dropdown-item" href="relatorio_vendas.php"><i class="bi bi-receipt me-2"></i>Vendas</a></li>
                            <li><a class="dropdown-item" href="relatorio_produtos.php"><i class="bi bi-box-seam me-2"></i>Produtos</a></li>
                        </ul>
                    </li>
                </ul>
                
                <!-- Área do Usuário -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="../assets/images/usuarios/<?= $usuario['foto'] ?? 'default.png' ?>" alt="Avatar" class="user-avatar me-2">
                            <span class="d-none d-lg-inline"><?= htmlspecialchars($usuario['nome'] ?? 'Usuário') ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="perfil.php"><i class="bi bi-person-fill me-2"></i>Meu Perfil</a></li>
                            <li><a class="dropdown-item" href="configuracoes.php"><i class="bi bi-gear-fill me-2"></i>Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="../logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i>Sair
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CDNs JS para funcionalidades -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Ativa o tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Adiciona classe active conforme a página atual
            const currentPage = location.pathname.split('/').pop();
            $('.nav-link').each(function() {
                const linkPage = $(this).attr('href');
                if (currentPage === linkPage) {
                    $(this).addClass('active');
                    
                    // Se for um item de dropdown, ativa o pai também
                    const dropdown = $(this).closest('.dropdown-menu');
                    if (dropdown.length) {
                        dropdown.prev('.dropdown-toggle').addClass('active');
                    }
                }
            });
        });
    </script>