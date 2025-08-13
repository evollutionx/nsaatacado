<?php
require_once 'classes/Login.php';

// Verifica se já está logado
if (Login::verificarLogin() === 'cliente') {
    $redirect = $_SESSION['redirect'] ?? 'eshop/index.php';
    unset($_SESSION['redirect']);
    header('Location: ' . $redirect);
    exit();
} elseif (Login::verificarLogin() === 'usuario') {
    header('Location: dashboard/index.php');
    exit();
}

$erro = '';
$tipo_login = $_POST['tipo_login'] ?? 'cliente';

// Processa o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($tipo_login === 'cliente') {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        
        if (Login::loginCliente($email, $senha)) {
            header('Location: eshop/index.php');
            exit();
        } else {
            $erro = 'E-mail ou senha incorretos!';
        }
    } elseif ($tipo_login === 'usuario') {
        $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');
        $senha = $_POST['senha'] ?? '';
        
        if (Login::loginUsuario($cpf, $senha)) {
            header('Location: dashboard/index.php');
            exit();
        } else {
            $erro = 'CPF ou senha incorretos!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema</title>
    
    <!-- CDNs para UI/UX profissional -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="assets/images/empresa/logo.ico" type="image/x-icon">
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
            background-color: var(--light-color);
            display: flex;
            min-height: 100vh;
            align-items: center;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        }
        
        .login-container {
            max-width: 500px;
            width: 100%;
            margin: 2rem auto;
            padding: 2.5rem;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all var(--transition-speed) ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .login-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .login-logo {
            max-width: 180px;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }
        
        .login-logo:hover {
            transform: scale(1.05);
        }
        
        .nav-tabs {
            margin-bottom: 2rem;
            border-bottom: 2px solid #dee2e6;
        }
        
        .nav-link {
            color: var(--secondary-color);
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border: none;
            transition: all var(--transition-speed) ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color);
            background-color: transparent;
        }
        
        .nav-link.active {
            color: var(--primary-color);
            font-weight: 600;
            border-bottom: 3px solid var(--primary-color);
            background-color: transparent;
        }
        
        .form-floating {
            margin-bottom: 1.25rem;
        }
        
        .form-control {
            padding: 1rem 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
            transition: all var(--transition-speed) ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        
        .btn-login {
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all var(--transition-speed) ease;
            letter-spacing: 0.5px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        
        .alert {
            border-radius: 0.5rem;
        }
        
        .link-secondary {
            color: var(--secondary-color);
            text-decoration: none;
            transition: color var(--transition-speed) ease;
        }
        
        .link-secondary:hover {
            color: var(--primary-color);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: var(--secondary-color);
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        
        .divider::before {
            margin-right: 1rem;
        }
        
        .divider::after {
            margin-left: 1rem;
        }
              
        /* Responsividade */
        @media (max-width: 576px) {
            .login-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .nav-link {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container animate__animated animate__fadeIn">
            <div class="login-header animate__animated animate__fadeInDown">
                <img src="assets/images/empresa/logo.png" alt="Logo" class="login-logo">
                <h2 class="fw-bold">Acessar Sistema</h2>
                <p class="text-muted">Entre com suas credenciais para acessar sua conta</p>
            </div>

            <?php if ($erro): ?>
                <div class="alert alert-danger animate__animated animate__shakeX">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $erro ?>
                </div>
            <?php endif; ?>

            <ul class="nav nav-tabs nav-justified animate__animated animate__fadeIn">
                <li class="nav-item">
                    <a class="nav-link <?= $tipo_login === 'cliente' ? 'active' : '' ?>" 
                       data-bs-toggle="tab" href="#cliente">
                       <i class="bi bi-person-fill me-2"></i>Cliente
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $tipo_login === 'usuario' ? 'active' : '' ?>" 
                       data-bs-toggle="tab" href="#usuario">
                       <i class="bi bi-person-badge-fill me-2"></i>Usuário
                    </a>
                </li>
            </ul>

            <div class="tab-content pt-3">
                <!-- Formulário Cliente -->
                <div id="cliente" class="tab-pane fade <?= $tipo_login === 'cliente' ? 'show active' : '' ?>">
                    <form method="POST" action="login.php" class="animate__animated animate__fadeIn">
                        <input type="hidden" name="tipo_login" value="cliente">
                        
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="E-mail" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            <label for="email"><i class="bi bi-envelope-fill me-2"></i>E-mail</label>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="senha" name="senha" 
                                   placeholder="Senha" required>
                            <label for="senha"><i class="bi bi-lock-fill me-2"></i>Senha</label>
                        </div>
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-login">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <a href="cadastroCliente.php" class="link-secondary">
                                <i class="bi bi-person-plus-fill me-1"></i>Cadastre-se
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Formulário Usuário -->
                <div id="usuario" class="tab-pane fade <?= $tipo_login === 'usuario' ? 'show active' : '' ?>">
                    <form method="POST" action="login.php" class="animate__animated animate__fadeIn">
                        <input type="hidden" name="tipo_login" value="usuario">
                        
                        <div class="form-floating">
                            <input type="text" class="form-control" id="cpf" name="cpf" 
                                   placeholder="CPF" required value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>"
                                   data-mask="000.000.000-00">
                            <label for="cpf"><i class="bi bi-person-vcard-fill me-2"></i>CPF</label>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="senha" name="senha" 
                                   placeholder="Senha" required>
                            <label for="senha"><i class="bi bi-lock-fill me-2"></i>Senha</label>
                        </div>
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-login">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- CDNs JS para funcionalidades -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    
    <script>
        $(document).ready(function(){
            // Máscara para CPF
            $('#cpf').mask('000.000.000-00', {reverse: false});
            
            // Efeito de foco nos inputs
            $('.form-control').focus(function() {
                $(this).parent().find('label').addClass('text-primary');
            }).blur(function() {
                $(this).parent().find('label').removeClass('text-primary');
            });
            
            // Inicializa animações
            new WOW().init();
            
            // Validação básica do formulário
            $('form').submit(function() {
                $(this).find('.btn-login').html('<i class="bi bi-arrow-repeat me-2"></i>Processando...').prop('disabled', true);
            });
        });
    </script>
</body>
</html>