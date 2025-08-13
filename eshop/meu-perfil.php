<?php
require_once '../classes/Login.php';
require_once '../classes/Cliente.php';

// Verifica se o cliente está logado
if (Login::verificarLogin() !== 'cliente') {
    $_SESSION['redirect'] = 'eshop/meu-perfil.php';
    header('Location: ../login.php');
    exit();
}

$usuario = Login::getUsuarioLogado();
$cliente = new Cliente();

// Obter dados atuais do cliente
$dadosCliente = $cliente->getClienteById($usuario['id']);

// Processar atualização
$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar e sanitizar dados
        $dadosAtualizados = [
            'nome_razao_social' => filter_input(INPUT_POST, 'nome_razao_social', FILTER_SANITIZE_STRING),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'cpf_cnpj' => preg_replace('/[^0-9]/', '', $_POST['cpf_cnpj']),
            'rg_ie' => filter_input(INPUT_POST, 'rg_ie', FILTER_SANITIZE_STRING),
            'telefone' => filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING),
            'celular' => filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_STRING),
            'endereco' => filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING),
            'numero' => filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_STRING),
            'complemento' => filter_input(INPUT_POST, 'complemento', FILTER_SANITIZE_STRING),
            'bairro' => filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_STRING),
            'cidade' => filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_STRING),
            'estado' => filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING),
            'cep' => preg_replace('/[^0-9]/', '', $_POST['cep'])
        ];

        // Verificar se email foi alterado e se já existe
        if ($dadosAtualizados['email'] !== $dadosCliente['email'] && $cliente->verificarEmailExistente($dadosAtualizados['email'])) {
            throw new Exception('Este e-mail já está cadastrado por outro cliente.');
        }

        // Atualizar senha se fornecida
        if (!empty($_POST['nova_senha'])) {
            if ($_POST['nova_senha'] !== $_POST['confirmar_senha']) {
                throw new Exception('As senhas não coincidem.');
            }
            $dadosAtualizados['senha'] = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);
        }

        // Construir query de atualização
        $campos = [];
        $valores = [];
        
        foreach ($dadosAtualizados as $campo => $valor) {
            if ($valor !== null && $valor !== '') {
                $campos[] = "$campo = ?";
                $valores[] = $valor;
            }
        }

        // Adicionar data de atualização
        $campos[] = "updated_at = NOW()";
        
        // Adicionar ID para WHERE
        $valores[] = $usuario['id'];

        $sql = "UPDATE clientes SET " . implode(', ', $campos) . " WHERE id = ?";
        $stmt = $cliente->getPdo()->prepare($sql);
        
        if ($stmt->execute($valores)) {
            $mensagem = 'Dados atualizados com sucesso!';
            // Atualizar dados na sessão
            $_SESSION['cliente_nome'] = $dadosAtualizados['nome_razao_social'];
            $_SESSION['cliente_email'] = $dadosAtualizados['email'];
            // Recarregar dados do cliente
            $dadosCliente = $cliente->getClienteById($usuario['id']);
        } else {
            throw new Exception('Erro ao atualizar dados no banco de dados.');
        }
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}
?>
    <?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - E-Shop</title>
    
    <!-- CDNs -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
        }
        
        body {
            background-color: #f5f5f5;
        }
        
        .profile-card {
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            border: none;
        }
        
        .profile-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 1.5rem;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }
        
        .form-label {
            font-weight: 500;
        }
        
        .required-field::after {
            content: " *";
            color: var(--danger-color);
        }
        
        @media (max-width: 767.98px) {
            .profile-avatar {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>

    
    <main class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card profile-card mb-4">
                    <div class="profile-header text-center">
                        <div class="d-flex justify-content-center">
                            <div>
                                <h4><?= htmlspecialchars($dadosCliente['nome_razao_social']) ?></h4>
                                <p class="mb-0">Membro desde <?= date('d/m/Y', strtotime($dadosCliente['data_cadastro'])) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <?php if ($mensagem): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $mensagem ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($erro): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $erro ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="row g-3">
                                <!-- Dados Pessoais -->
                                <div class="col-12">
                                    <h5 class="mb-3 border-bottom pb-2">Dados Pessoais</h5>
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="nome_razao_social" class="form-label required-field">Nome Completo / Razão Social</label>
                                    <input type="text" class="form-control" id="nome_razao_social" name="nome_razao_social" 
                                           value="<?= htmlspecialchars($dadosCliente['nome_razao_social']) ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label required-field">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($dadosCliente['email']) ?>" required>
                                </div>
                                
<div class="col-md-6">
    <label for="cpf_cnpj" class="form-label required-field">
        <?= $dadosCliente['tipo'] === 'PF' ? 'CPF' : 'CNPJ' ?>
    </label>
    <input type="text" class="form-control" id="cpf_cnpj" name="cpf_cnpj" 
           value="<?= htmlspecialchars($dadosCliente['cpf_cnpj']) ?>" 
           required>
</div>
                                
                                <div class="col-md-6">
                                    <label for="rg_ie" class="form-label"><?= $dadosCliente['tipo'] === 'PF' ? 'RG' : 'Inscrição Estadual' ?></label>
                                    <input type="text" class="form-control" id="rg_ie" name="rg_ie" 
                                           value="<?= htmlspecialchars($dadosCliente['rg_ie']) ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" id="telefone" name="telefone" 
                                           value="<?= htmlspecialchars($dadosCliente['telefone']) ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="celular" class="form-label required-field">Celular</label>
                                    <input type="tel" class="form-control" id="celular" name="celular" 
                                           value="<?= htmlspecialchars($dadosCliente['celular']) ?>" required>
                                </div>
                                
                                <!-- Endereço -->
                                <div class="col-12 mt-4">
                                    <h5 class="mb-3 border-bottom pb-2">Endereço</h5>
                                </div>
                                
                                <div class="col-md-8">
                                    <label for="endereco" class="form-label required-field">Endereço</label>
                                    <input type="text" class="form-control" id="endereco" name="endereco" 
                                           value="<?= htmlspecialchars($dadosCliente['endereco']) ?>" required>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="numero" class="form-label required-field">Número</label>
                                    <input type="text" class="form-control" id="numero" name="numero" 
                                           value="<?= htmlspecialchars($dadosCliente['numero']) ?>" required>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="complemento" class="form-label">Complemento</label>
                                    <input type="text" class="form-control" id="complemento" name="complemento" 
                                           value="<?= htmlspecialchars($dadosCliente['complemento']) ?>">
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="bairro" class="form-label required-field">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro" 
                                           value="<?= htmlspecialchars($dadosCliente['bairro']) ?>" required>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="cidade" class="form-label required-field">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" 
                                           value="<?= htmlspecialchars($dadosCliente['cidade']) ?>" required>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="estado" class="form-label required-field">Estado</label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="">UF</option>
                                        <option value="AC" <?= $dadosCliente['estado'] === 'AC' ? 'selected' : '' ?>>Acre</option>
                                        <option value="AL" <?= $dadosCliente['estado'] === 'AL' ? 'selected' : '' ?>>Alagoas</option>
                                        <option value="AP" <?= $dadosCliente['estado'] === 'AP' ? 'selected' : '' ?>>Amapá</option>
                                        <option value="AM" <?= $dadosCliente['estado'] === 'AM' ? 'selected' : '' ?>>Amazonas</option>
                                        <option value="BA" <?= $dadosCliente['estado'] === 'BA' ? 'selected' : '' ?>>Bahia</option>
                                        <option value="CE" <?= $dadosCliente['estado'] === 'CE' ? 'selected' : '' ?>>Ceará</option>
                                        <option value="DF" <?= $dadosCliente['estado'] === 'DF' ? 'selected' : '' ?>>Distrito Federal</option>
                                        <option value="ES" <?= $dadosCliente['estado'] === 'ES' ? 'selected' : '' ?>>Espírito Santo</option>
                                        <option value="GO" <?= $dadosCliente['estado'] === 'GO' ? 'selected' : '' ?>>Goiás</option>
                                        <option value="MA" <?= $dadosCliente['estado'] === 'MA' ? 'selected' : '' ?>>Maranhão</option>
                                        <option value="MT" <?= $dadosCliente['estado'] === 'MT' ? 'selected' : '' ?>>Mato Grosso</option>
                                        <option value="MS" <?= $dadosCliente['estado'] === 'MS' ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                                        <option value="MG" <?= $dadosCliente['estado'] === 'MG' ? 'selected' : '' ?>>Minas Gerais</option>
                                        <option value="PA" <?= $dadosCliente['estado'] === 'PA' ? 'selected' : '' ?>>Pará</option>
                                        <option value="PB" <?= $dadosCliente['estado'] === 'PB' ? 'selected' : '' ?>>Paraíba</option>
                                        <option value="PR" <?= $dadosCliente['estado'] === 'PR' ? 'selected' : '' ?>>Paraná</option>
                                        <option value="PE" <?= $dadosCliente['estado'] === 'PE' ? 'selected' : '' ?>>Pernambuco</option>
                                        <option value="PI" <?= $dadosCliente['estado'] === 'PI' ? 'selected' : '' ?>>Piauí</option>
                                        <option value="RJ" <?= $dadosCliente['estado'] === 'RJ' ? 'selected' : '' ?>>Rio de Janeiro</option>
                                        <option value="RN" <?= $dadosCliente['estado'] === 'RN' ? 'selected' : '' ?>>Rio Grande do Norte</option>
                                        <option value="RS" <?= $dadosCliente['estado'] === 'RS' ? 'selected' : '' ?>>Rio Grande do Sul</option>
                                        <option value="RO" <?= $dadosCliente['estado'] === 'RO' ? 'selected' : '' ?>>Rondônia</option>
                                        <option value="RR" <?= $dadosCliente['estado'] === 'RR' ? 'selected' : '' ?>>Roraima</option>
                                        <option value="SC" <?= $dadosCliente['estado'] === 'SC' ? 'selected' : '' ?>>Santa Catarina</option>
                                        <option value="SP" <?= $dadosCliente['estado'] === 'SP' ? 'selected' : '' ?>>São Paulo</option>
                                        <option value="SE" <?= $dadosCliente['estado'] === 'SE' ? 'selected' : '' ?>>Sergipe</option>
                                        <option value="TO" <?= $dadosCliente['estado'] === 'TO' ? 'selected' : '' ?>>Tocantins</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="cep" class="form-label required-field">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" 
                                           value="<?= htmlspecialchars($dadosCliente['cep']) ?>" required>
                                </div>
                                
                                <!-- Alterar Senha -->
                                <div class="col-12 mt-4">
                                    <h5 class="mb-3 border-bottom pb-2">Alterar Senha</h5>
                                    <p class="text-muted">Deixe em branco se não quiser alterar</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="nova_senha" class="form-label">Nova Senha</label>
                                    <input type="password" class="form-control" id="nova_senha" name="nova_senha">
                                    <div class="form-text">Mínimo de 6 caracteres</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
                                    <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha">
                                </div>
                                
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-circle me-2"></i>Salvar Alterações
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>
    
    <!-- CDNs JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    
    <script>
$(document).ready(function() {
    // Máscara inicial baseada no tipo (PF ou PJ)
    const tipoCliente = '<?= $dadosCliente["tipo"] ?>'; // 'PF' ou 'PJ'
    
    if (tipoCliente === 'PF') {
        $('#cpf_cnpj').mask('000.000.000-00');
    } else {
        $('#cpf_cnpj').mask('00.000.000/0000-00');
    }

    // Alternar entre CPF e CNPJ dinamicamente
    $('#cpf_cnpj').on('input', function() {
        const value = $(this).cleanVal();
        if (value.length <= 11) {
            $(this).mask('000.000.000-00');
            $('label[for="cpf_cnpj"]').text('CPF');
        } else {
            $(this).mask('00.000.000/0000-00');
            $('label[for="cpf_cnpj"]').text('CNPJ');
        }
    });
            // Buscar CEP via API
            $('#cep').on('blur', function() {
                const cep = $(this).cleanVal();
                if (cep.length === 8) {
                    $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                        if (!data.erro) {
                            $('#endereco').val(data.logradouro);
                            $('#bairro').val(data.bairro);
                            $('#cidade').val(data.localidade);
                            $('#estado').val(data.uf);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>