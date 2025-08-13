<?php
require_once 'classes/Categoria.php';
require_once 'classes/Cliente.php';
require_once 'classes/Empresa.php';

$categorias = Categoria::getTodasCategorias();
$empresa = Empresa::getDadosEmpresa();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($empresa['nome_fantasia'] ?? 'Atacado Nossa Senhora Aparecida') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <link rel="shortcut icon" href="assets/images/empresa/logo.ico" type="image/x-icon">
    
    <style>
        :root {
            --primary-blue: #002366;
            --secondary-blue: #1a4b8c;
            --accent-gold: #d4af37;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        body {
            padding-top: 120px;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
.header-top {
    background-color: var(--light-bg);
    border-bottom: 1px solid rgba(0, 35, 102, 0.1);
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1030;
}

/* Estilo para o botão de categorias mobile */
.navbar-toggler[data-bs-target="#mobileCategoriesMenu"] {
    background-color: var(--primary-blue);
    color: white;
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 4px;
}

.navbar-toggler[data-bs-target="#mobileCategoriesMenu"] i {
    margin-right: 5px;
}

/* Ajustes para mobile */
@media (max-width: 992px) {
    body {
        padding-top: 60px;
    }
    
    .navbar-nav {
        flex-direction: column;
        align-items: flex-start;
    }
    
    /* Garante que o menu principal não mostre as categorias em mobile */
    .nav-item.d-none.d-lg-block {
        display: none !important;
    }
    
    /* Ajusta a posição do menu de categorias mobile */
    #mobileCategoriesMenu {
        position: fixed;
        top: 60px;
        left: 0;
        right: 0;
        z-index: 1028;
        max-height: calc(100vh - 60px);
        overflow-y: auto;
    }
}
.navbar-nav {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
}

.nav-link {
    color: var(--dark-text);
    font-weight: 500;
    padding: 0.5rem 1rem;
    position: relative;
    white-space: nowrap;
}

.nav-link:hover {
    color: var(--primary-blue);
}

.nav-link:after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 0;
    background-color: var(--accent-gold);
    transition: width 0.3s ease;
}

.nav-link:hover:after {
    width: 100%;
}

/* Ajustes para mobile */
@media (max-width: 992px) {
    body {
        padding-top: 60px;
    }
    
    .navbar-nav {
        flex-direction: column;
        align-items: flex-start;
    }
    
    /* Esconde as categorias no mobile (serão mostradas no menu mobile específico) */
    .nav-item:nth-child(n+4):not(:last-child) {
        display: none;
    }
}
        
        .logo-container {
            display: flex;
            align-items: center;
        }
        
        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-left: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-blue);
            border-color: var(--secondary-blue);
            transform: translateY(-1px);
        }
        
        .btn-outline-primary {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-blue);
            color: white;
        }
        
        .nav-link {
            color: var(--dark-text);
            font-weight: 500;
            padding: 0.5rem 1rem;
            position: relative;
        }
        
        .nav-link:hover {
            color: var(--primary-blue);
        }
        
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--accent-gold);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover:after {
            width: 100%;
        }
        
        .cart-badge {
            font-size: 0.6rem;
        }
        
        .dropdown-menu {
            border: 1px solid rgba(0, 35, 102, 0.1);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-item:focus, .dropdown-item:hover {
            background-color: var(--primary-blue);
            color: white;
        }
        
        #mobileCategoriesMenu {
            position: fixed;
            top: 120px;
            left: 0;
            right: 0;
            z-index: 1028;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
        }
        
        @media (max-width: 992px) {
            body {
                padding-top: 60px;
            }
            
            .header-bottom {
                        position: static; /* deixa de ser fixa */
        top: auto;
            }
            
            .mobile-categories-btn {
                display: block !important;
                border-color: var(--primary-blue);
            }
            
            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 35, 102, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            }
        }

              /* Estilos para os modais de login */
        .login-tabs .nav-link {
            color: var(--dark-text);
            font-weight: 500;
        }
        
        .login-tabs .nav-link.active {
            color: var(--primary-blue);
            font-weight: 600;
            border-bottom: 2px solid var(--primary-blue);
        }
        
        .form-floating label {
            color: #6c757d;
        }
        
        .form-floating .form-control:focus ~ label {
            color: var(--primary-blue);
        }
        
        .forgot-password {
            font-size: 0.85rem;
            text-decoration: none;
        }
        
        .social-login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            border-radius: 5px;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .social-login-btn.google {
            background-color: #fff;
            color: #757575;
            border: 1px solid #ddd;
        }
        
        .social-login-btn.facebook {
            background-color: #3b5998;
            color: white;
        }
    </style>
</head>
<body>
<header class="sticky-top">
    <div class="header-top py-2">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light py-0">
                <div class="logo-container">
                    <a class="navbar-brand p-0" href="index.php">
                        <img src="assets/images/empresa/logo.png" alt="Atacado Nossa Senhora Aparecida" height="45">
                    </a>
                    <span class="logo-text d-none d-lg-block"><?= htmlspecialchars($empresa['nome_fantasia'] ?? 'Atacado Nossa Senhora Aparecida') ?></span>
                </div>
                
                <!-- Botão para menu principal -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Botão adicional para categorias (visível apenas em mobile) -->
                <button class="navbar-toggler d-lg-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileCategoriesMenu">
                    <i class="bi bi-list"></i> Categorias
                </button>
                
                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav mx-auto">
                        <!-- Itens principais -->
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Página Inicial</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="sobre.php">Sobre Nós</a>
                        </li>
                        
                        <!-- Categorias integradas (visíveis apenas em desktop) -->
                        <?php foreach ($categorias as $categoria): ?>
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link" href="categoria.php?id=<?= $categoria['id'] ?>">
                                <?= htmlspecialchars($categoria['nome']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="contato.php">Contato</a>
                        </li>
                    </ul>
                    
                    <!-- Botão de login -->
                    <div class="d-flex">
                        <a href="login.php" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    
    <!-- Menu de categorias mobile -->
    <div class="collapse bg-light d-lg-none" id="mobileCategoriesMenu">
        <div class="container py-2">
            <h6 class="px-3 py-2 fw-bold text-white" style="background-color: var(--primary-blue);">Todas as Categorias</h6>
            <div class="list-group list-group-flush">
                <?php foreach ($categorias as $categoria): ?>
                    <a href="categoria.php?id=<?= $categoria['id'] ?>" class="list-group-item list-group-item-action">
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</header>
    
    <main class="container my-4">

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Fechar menu de categorias quando outro menu é aberto
    const mainMenuToggler = document.querySelector('[data-bs-target="#navbarMain"]');
    const categoriesMenuToggler = document.querySelector('[data-bs-target="#mobileCategoriesMenu"]');
    
    if (mainMenuToggler && categoriesMenuToggler) {
        mainMenuToggler.addEventListener('click', function() {
            const categoriesMenu = document.getElementById('mobileCategoriesMenu');
            if (categoriesMenu.classList.contains('show')) {
                categoriesMenu.classList.remove('show');
            }
        });
        
        categoriesMenuToggler.addEventListener('click', function() {
            const mainMenu = document.getElementById('navbarMain');
            if (mainMenu.classList.contains('show')) {
                mainMenu.classList.remove('show');
            }
        });
    }
        // Fechar menu de categorias ao clicar em um item (mobile)
        document.querySelectorAll('#mobileCategoriesMenu .list-group-item').forEach(item => {
            item.addEventListener('click', function() {
                const categoriesMenu = document.getElementById('mobileCategoriesMenu');
                categoriesMenu.classList.remove('show');
            });
        });
        
        // Scroll suave para links âncora
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Atualizar contador do carrinho (exemplo)
        function updateCartCount() {
            // Aqui você faria uma requisição AJAX para obter o número atual de itens
            // Estou usando um valor fixo como exemplo
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = '0'; // Substituir pelo valor real
            }
        }
        
        // Chamada inicial
        updateCartCount();
    });
    </script>