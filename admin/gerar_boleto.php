<?php
// Conecta ao banco (saindo da pasta admin e entrando na includes)
include('../includes/conexao.php'); // Ajuste o caminho se sua conexao estiver em outro lugar

$mensagem = "";

// 2. Se o formulário for enviado, salva o boleto no banco
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST['cliente_id'];
    $referencia = $_POST['referencia'];
    $vencimento = $_POST['vencimento'];
    $valor = $_POST['valor'];

    // status 'Pendente' por padrão
    $sql_insert = "INSERT INTO boletos (usuario_id, referencia, vencimento, valor, status) 
                   VALUES ('$cliente_id', '$referencia', '$vencimento', '$valor', 'Pendente')";
    
    if ($conn->query($sql_insert) === TRUE) {
        $mensagem = "<p style='color: green; font-weight: bold;'>✔ Boleto gerado com sucesso!</p>";
    } else {
        $mensagem = "<p style='color: red;'>Erro ao gerar boleto: " . $conn->error . "</p>";
    }
}

// ira buscar os usuários para colocar na lista (select)
$sql_usuarios = "SELECT id, email FROM usuarios";
$res_usuarios = $conn->query($sql_usuarios);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gerar Boleto | Estrela Rastreamento</title>
    <link rel="stylesheet" href="../css/style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

</head>
<body>

<header style="text-align: center; padding: 20px; background-color: #003366; color: white;">
    <h1>Painel Administrativo</h1>
    <p>Área restrita - Estrela Rastreamento</p>
    <nav style="margin-top: 10px;">
        <a href="gerar_boleto.php" style="color: #32bcad; font-weight: bold; margin-right: 15px; text-decoration: none;">Gerar Boletos</a>
        <a href="cadastrar_cliente.php" style="color: white; text-decoration: none;">Cadastrar Cliente</a>
    </nav>
</header>

<main>
    <section class="container-financeiro">
        <h2 style="text-align: center;">Gerar Novo Boleto</h2>
        
        <div style="text-align: center; margin-bottom: 15px;">
            <?php echo $mensagem; ?>
        </div>

        <form method="POST" action="gerar_boleto.php" class="form-admin">
            <label>Selecione o Cliente:</label>
            <select name="cliente_id" required>
                <option value="">-- Escolha um cliente --</option>
                <?php 
                // Preenche a lista com os e-mails do banco
                if ($res_usuarios->num_rows > 0) {
                    while($user = $res_usuarios->fetch_assoc()) {
                        echo "<option value='" . $user['id'] . "'>" . $user['email'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum cliente cadastrado</option>";
                }
                ?>
            </select>

            <label>Referência (Ex: Mensalidade - Maio/2026):</label>
            <input type="text" name="referencia" placeholder="Digite a referência..." required>

            <label>Data de Vencimento:</label>
            <input type="date" name="vencimento" required>

            <label>Valor (R$):</label>
            <input type="number" step="0.01" name="valor" placeholder="Ex: 59.90" required>

            <button type="submit" class="btn-gerar">Lançar Boleto</button>
        </form>
    </section>
</main>

</body>
</html>