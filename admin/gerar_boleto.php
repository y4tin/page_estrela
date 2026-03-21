<?php
session_start();
// 1. Proteção: Só entra se for o admin
if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== 'admin@estrela.com') {
    header("Location: ../pages/login.html");
    exit();
}

include('../includes/conexao.php');

$mensagem = "";

// 2. Processar o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST['cliente_id'];
    $referencia = $conn->real_escape_string($_POST['referencia']);
    $vencimento = $_POST['vencimento'];
    $valor = $_POST['valor'];

    $sql = "INSERT INTO boletos (usuario_id, referencia, vencimento, valor, status) 
            VALUES ('$cliente_id', '$referencia', '$vencimento', '$valor', 'Pendente')";
    
    if ($conn->query($sql)) {
        $mensagem = "<p style='color:green; font-weight:bold;'>✔ Boleto Gerado com Sucesso!</p>";
    } else {
        $mensagem = "<p style='color:red;'>Erro: " . $conn->error . "</p>";
    }
}

// 3. Buscar clientes para a lista
$res_usuarios = $conn->query("SELECT id, email FROM usuarios WHERE email != 'admin@estrela.com' ORDER BY email ASC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerar Boleto - Estrela</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .box { background: white; max-width: 500px; margin: auto; padding: 20px; border-radius: 8px; border-top: 5px solid #fbc02d; }
        input, select, button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #003366; color: white; border: none; font-weight: bold; cursor: pointer; }
        .nav { text-align: center; margin-bottom: 20px; }
        .nav a { margin: 0 10px; text-decoration: none; color: #003366; font-weight: bold; }
    </style>
</head>
<body>

<div class="nav">
    <a href="gerar_boleto.php">📄 Gerar Boletos</a> | 
    <a href="cadastrar_cliente.php">👥 Clientes</a> | 
    <a href="../pages/logout.php" style="color:red;">Sair</a>
</div>

<div class="box">
    <h2>⭐ Lançar Novo Boleto</h2>
    <?php echo $mensagem; ?>

    <form method="POST">
        <label>Selecione o Cliente:</label>
        <select name="cliente_id" required>
            <option value="">-- Escolha --</option>
            <?php while($u = $res_usuarios->fetch_assoc()): ?>
                <option value="<?php echo $u['id']; ?>"><?php echo $u['email']; ?></option>
            <?php endwhile; ?>
        </select>

        <label>Referência:</label>
        <input type="text" name="referencia" placeholder="Ex: Mensalidade Junho" required>

        <label>Vencimento:</label>
        <input type="date" name="vencimento" required>

        <label>Valor (R$):</label>
        <input type="number" step="0.01" name="valor" placeholder="0.00" required>

        <button type="submit">CADASTRAR BOLETO</button>
    </form>
</div>

</body>
</html>