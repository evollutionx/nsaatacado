<footer class="bg-dark text-white pt-5 pb-3">
    <style>
        /* Estilos específicos para o footer */
        .bg-dark {
            background-color: #001a3d !important;
        }
        
        .hover-gold {
            transition: color 0.3s ease, opacity 0.3s ease;
        }
        
        .hover-gold:hover {
            color: var(--accent-gold) !important;
            opacity: 0.9;
        }
        
        .footer-icon {
            transition: transform 0.3s ease;
        }
        
        .footer-icon:hover {
            transform: scale(1.1);
        }
        
        .newsletter-btn {
            background-color: var(--accent-gold) !important;
            border-color: var(--accent-gold) !important;
            color: #001a3d !important;
            transition: all 0.3s ease;
        }
        
        .newsletter-btn:hover {
            background-color: #e8c15d !important;
            transform: translateY(-1px);
        }
        
        .back-to-top {
            width: 50px;
            height: 50px;
            background-color: var(--accent-gold);
            border-color: var(--accent-gold);
            color: #001a3d;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }
        
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background-color: #e8c15d;
            transform: translateY(-3px);
        }
        
        .payment-icon {
            filter: grayscale(30%);
            transition: filter 0.3s ease;
        }
        
        .payment-icon:hover {
            filter: grayscale(0%);
        }
        
        @media (max-width: 768px) {
            .footer-col {
                margin-bottom: 2rem;
            }
            
            .footer-logo {
                justify-content: center;
                text-align: center;
            }
            
            .footer-links, .footer-contact, .footer-social {
                text-align: center;
            }
            
            .footer-social-icons {
                justify-content: center;
            }
            
            .newsletter-form {
                max-width: 300px;
                margin: 0 auto;
            }
        }
    </style>

    <div class="container">
        <div class="row g-4">
            <!-- Coluna Sobre -->
            <div class="col-lg-4 col-md-6 footer-col">
                <div class="d-flex align-items-center mb-4 footer-logo">
                    <img src="assets/images/empresa/logo.png" alt="Logo" height="40" class="me-2">
                    <h5 class="mb-0 text-uppercase" style="color: var(--accent-gold);"><?= htmlspecialchars($empresa['nome_fantasia'] ?? 'Atacado Nossa Senhora Aparecida') ?></h5>
                </div>
                <p><?= htmlspecialchars($empresa['slogan'] ?? 'Preço de atacado, qualidade de varejo - entregamos em todo Brasil!') ?></p>
                
                <div class="mt-4">
                    <h6 class="text-uppercase mb-3" style="color: var(--accent-gold);">Formas de Pagamento</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <i class="bi bi-credit-card fs-4 footer-icon" title="Cartão de Crédito"></i>
                        <i class="bi bi-cash-coin fs-4 footer-icon" title="Dinheiro"></i>
                        <i class="bi bi-upc-scan fs-4 footer-icon" title="PIX"></i>
                        <i class="bi bi-bank fs-4 footer-icon" title="Transferência"></i>
                        <i class="bi bi-receipt fs-4 footer-icon" title="Boleto"></i>
                    </div>
                </div>
            </div>

            <!-- Coluna Links -->
            <div class="col-lg-2 col-md-6 footer-col footer-links">
                <h5 class="text-uppercase mb-4" style="color: var(--accent-gold);">Links Rápidos</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/" class="text-white text-decoration-none hover-gold">Página Inicial</a></li>
                    <li class="mb-2"><a href="/produtos.php" class="text-white text-decoration-none hover-gold">Nossos Produtos</a></li>
                    <li class="mb-2"><a href="/sobre.php" class="text-white text-decoration-none hover-gold">Sobre Nós</a></li>
                    <li class="mb-2"><a href="/contato.php" class="text-white text-decoration-none hover-gold">Contato</a></li>
                    <li class="mb-2"><a href="/politica-privacidade.php" class="text-white text-decoration-none hover-gold">Política de Privacidade</a></li>
                </ul>
            </div>

            <!-- Coluna Contato -->
            <div class="col-lg-3 col-md-6 footer-col footer-contact">
                <h5 class="text-uppercase mb-4" style="color: var(--accent-gold);">Contato</h5>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-geo-alt-fill me-2 mt-1" style="color: var(--accent-gold)"></i>
                        <span>
                            <?= htmlspecialchars($empresa['endereco'] ?? 'Rua Exemplo, 123') ?>, 
                            <?= htmlspecialchars($empresa['numero'] ?? '100') ?><br>
                            <?= htmlspecialchars($empresa['bairro'] ?? 'Centro') ?> - 
                            <?= htmlspecialchars($empresa['cidade'] ?? 'Aparecida') ?>/<?= htmlspecialchars($empresa['estado'] ?? 'SP') ?><br>
                            CEP: <?= htmlspecialchars($empresa['cep'] ?? '00000-000') ?>
                        </span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="bi bi-telephone-fill me-2" style="color: var(--accent-gold);"></i>
                        <?= htmlspecialchars($empresa['telefone'] ?? '(00) 0000-0000') ?>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="bi bi-whatsapp me-2" style="color: var(--accent-gold);"></i>
                        <a href="https://wa.me/55<?= preg_replace('/[^0-9]/', '', $empresa['celular'] ?? '00000000000') ?>" class="text-white text-decoration-none hover-gold">
                            <?= htmlspecialchars($empresa['celular'] ?? '(00) 00000-0000') ?>
                        </a>
                    </li>
                    <li class="d-flex align-items-center">
                        <i class="bi bi-envelope-fill me-2" style="color: var(--accent-gold);"></i>
                        <a href="mailto:<?= htmlspecialchars($empresa['email'] ?? 'contato@exemplo.com') ?>" class="text-white text-decoration-none hover-gold">
                            <?= htmlspecialchars($empresa['email'] ?? 'contato@exemplo.com') ?>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Coluna Redes Sociais -->
            <div class="col-lg-3 col-md-6 footer-col footer-social">
                <h5 class="text-uppercase mb-4" style="color: var(--accent-gold);">Redes Sociais</h5>
                <div class="mb-4 footer-social-icons">
                    <a href="#" class="text-white me-3 fs-4 hover-gold footer-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-3 fs-4 hover-gold footer-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white fs-4 hover-gold footer-icon"><i class="bi bi-youtube"></i></a>
                </div>
                              
                <div class="d-flex align-items-center">
                    <i class="bi bi-shield-lock me-2 fs-4" style="color: var(--accent-gold);"></i>
                    <small>Site 100% seguro</small>
                </div>
            </div>
        </div>
        
        <hr class="my-4" style="border-color: rgba(59, 35, 35, 0.2);">
        
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <small>&copy; <?= date('Y') ?> <?= htmlspecialchars($empresa['nome_fantasia'] ?? 'Atacado Nossa Senhora Aparecida') ?>. Todos os direitos reservados.</small>
            </div>
        </div>
    </div>
</footer>

<!-- Voltar ao Topo -->
<a href="#" class="btn back-to-top position-fixed bottom-0 end-0 m-3 rounded-circle shadow d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up"></i>
</a>

<script>
// JavaScript para o footer
document.addEventListener('DOMContentLoaded', function() {
    // Botão "Voltar ao Topo"
    const backToTopBtn = document.querySelector('.back-to-top');
    
    if (backToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });
        
        backToTopBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Formulário de Newsletter
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            
            // Validação simples
            if (emailInput.value && emailInput.value.includes('@')) {
                // Aqui você faria a requisição AJAX para enviar o e-mail
                alert('Obrigado por assinar nossa newsletter!');
                emailInput.value = '';
            } else {
                alert('Por favor, insira um e-mail válido.');
            }
        });
    }
    
    // Tooltips para ícones de pagamento
    const paymentIcons = document.querySelectorAll('[title]');
    paymentIcons.forEach(icon => {
        new bootstrap.Tooltip(icon, {
            trigger: 'hover'
        });
    });
    
    // Efeito hover nos ícones de contato
    const contactIcons = document.querySelectorAll('.footer-contact i[class^="bi"]');
    contactIcons.forEach(icon => {
        icon.style.transition = 'transform 0.3s ease';
        icon.addEventListener('mouseenter', () => {
            icon.style.transform = 'scale(1.2)';
        });
        icon.addEventListener('mouseleave', () => {
            icon.style.transform = 'scale(1)';
        });
    });
});
</script>