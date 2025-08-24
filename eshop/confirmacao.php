<?php
require_once '../classes/Login.php';

// Verifica se o usuário está logado e se houve um pedido concluído
if (Login::verificarLogin() !== 'cliente' || !isset($_SESSION['pedido_sucesso'])) {
    header('Location: index.php');
    exit();
}

// Recupera os dados do PIX da sessão
$chave_pix = $_SESSION['chave_pix'] ?? '';
$valor_total = $_SESSION['valor_total'] ?? 0;
$nome_cliente = $_SESSION['nome_cliente'] ?? '';

$whatsappLink = $_SESSION['whatsapp_link'] ?? null;
// Não limpamos a venda_id da sessão aqui, pois queremos manter a nova venda
unset($_SESSION['pedido_sucesso']);
unset($_SESSION['whatsapp_link']);
unset($_SESSION['chave_pix']);
unset($_SESSION['valor_total']);
unset($_SESSION['nome_cliente']);
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Pedido</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        .confirmation-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .confirmation-icon {
            font-size: 5rem;
            color: #28a745;
            margin-bottom: 1.5rem;
            animation: bounceIn 1s;
        }
        
        .btn-whatsapp {
            background-color: #25D366;
            color: white;
            font-weight: 600;
        }
        
        .btn-whatsapp:hover {
            background-color: #128C7E;
            color: white;
        }
        
        .payment-instructions {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border-left: 5px solid #0d6efd;
        }
        
        .pix-container {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .pix-data {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            word-break: break-all;
        }
        
        .copy-btn {
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .copy-btn:hover {
            opacity: 0.8;
        }
        
        .animate-delay-1 {
            animation-delay: 0.3s;
        }
        
        .animate-delay-2 {
            animation-delay: 0.6s;
        }
        
        #pix-key {
            user-select: all;
            -webkit-user-select: all;
            -moz-user-select: all;
            -ms-user-select: all;
        }
    </style>
</head>
<body>
    
    <div class="container py-5">
        <div class="confirmation-container animate__animated animate__fadeIn">
            <div class="card text-center p-4 confirmation-card">
                <div class="confirmation-icon animate__animated animate__bounceIn">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h2 class="mb-3 animate__animated animate__fadeInUp">Pedido Confirmado!</h2>
                <p class="mb-4 animate__animated animate__fadeInUp animate-delay-1">Seu pedido foi recebido e está sendo processado.</p>
                
                <!-- Seção PIX -->
                <div class="pix-container animate__animated animate__fadeInUp animate-delay-1">
                    <h4><i class="bi bi-currency-exchange me-2"></i>Pagamento via PIX</h4>
                    <p>Copie a chave PIX abaixo para realizar o pagamento</p>
                    
                    <div class="row text-start">
                        <div class="col-md-6">
                            <div class="pix-data">
                                <strong>Valor:</strong>
                                <div>R$ <?= number_format($valor_total, 2, ',', '.') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pix-data">
                                <strong>Beneficiário:</strong>
                                <div><?= htmlspecialchars($nome_cliente) ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pix-data">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1 me-3">
                                <strong>Chave PIX:</strong>
                                <div id="pix-key" class="mt-1"><?= htmlspecialchars($chave_pix) ?></div>
                            </div>
                            <div class="copy-btn" onclick="copyPixKey()" title="Copiar chave PIX">
                                <i class="bi bi-clipboard fs-4"></i>
                            </div>
                        </div>
                    </div>
                    
                    <p class="mt-3"><small>O pagamento será confirmado automaticamente em até 24h</small></p>
                </div>
                
                <?php if ($whatsappLink): ?>
                    <div class="alert alert-info mb-4 mt-4 animate__animated animate__fadeInUp animate-delay-2">
                        <a href="<?= $whatsappLink ?>" class="btn btn-whatsapp mb-2" target="_blank">
                            <i class="bi bi-whatsapp me-2"></i>Notifique o vendedor pelo WhatsApp
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="d-grid gap-2 mt-3 animate__animated animate__fadeInUp animate-delay-2">
                    <a href="index.php" class="btn btn-primary">
                        <i class="bi bi-cart-plus me-2"></i>Continuar Comprando
                    </a>
                    <a href="carrinho.php" class="btn btn-outline-secondary">
                        <i class="bi bi-cart-check me-2"></i>Ver Carrinho
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função para copiar a chave PIX
        function copyPixKey() {
            const pixKey = document.getElementById('pix-key');
            
            // Cria um elemento de texto temporário
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = pixKey.textContent;
            document.body.appendChild(tempTextArea);
            
            // Seleciona e copia o texto
            tempTextArea.select();
            tempTextArea.setSelectionRange(0, 99999); // Para dispositivos móveis
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    alert('Chave PIX copiada para a área de transferência!');
                } else {
                    alert('Não foi possível copiar a chave PIX. Tente selecionar e copiar manualmente.');
                }
            } catch (err) {
                console.error('Erro ao copiar texto: ', err);
                alert('Erro ao copiar a chave PIX. Tente selecionar e copiar manualmente.');
            }
            
            // Remove o elemento temporário
            document.body.removeChild(tempTextArea);
        }
        
        // Alternativa usando a API moderna de Clipboard
        async function copyPixKeyModern() {
            try {
                const pixKey = document.getElementById('pix-key').textContent;
                await navigator.clipboard.writeText(pixKey);
                alert('Chave PIX copiada para a área de transferência!');
            } catch (err) {
                console.error('Erro ao copiar texto: ', err);
                // Fallback para o método antigo
                copyPixKey();
            }
        }
        
        // Usa a API moderna se disponível, caso contrário usa o método antigo
        function copyPixKey() {
            if (navigator.clipboard && window.isSecureContext) {
                copyPixKeyModern();
            } else {
                // Método antigo para navegadores mais antigos ou contextos não seguros
                const pixKey = document.getElementById('pix-key');
                const tempTextArea = document.createElement('textarea');
                tempTextArea.value = pixKey.textContent;
                document.body.appendChild(tempTextArea);
                tempTextArea.select();
                tempTextArea.setSelectionRange(0, 99999);
                
                try {
                    const successful = document.execCommand('copy');
                    if (successful) {
                        alert('Chave PIX copiada para a área de transferência!');
                    } else {
                        alert('Não foi possível copiar a chave PIX. Tente selecionar e copiar manualmente.');
                    }
                } catch (err) {
                    console.error('Erro ao copiar texto: ', err);
                    alert('Erro ao copiar a chave PIX. Tente selecionar e copiar manualmente.');
                }
                
                document.body.removeChild(tempTextArea);
            }
        }
    </script>

    <?php if ($whatsappLink): ?>
    <script>
        // Abre o WhatsApp automaticamente em nova aba
        window.onload = function() {
            window.open('<?= $whatsappLink ?>', '_blank');
        };
    </script>
    <?php endif; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php include 'footer.php'; ?>
</body>