<?php
session_start();
include('menu_admin.php'); 
include('../includes/conexao.php');

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST['cliente_id'];
    $referencia = $conn->real_escape_string($_POST['referencia']);
    $vencimento = $_POST['vencimento'];
    $valor = $_POST['valor'];

    $sql_insert = "INSERT INTO boletos (usuario_id, referencia, vencimento, valor, status) 
                   VALUES ('$cliente_id', '$referencia', '$vencimento', '$valor', 'Pendente')";
    
    if ($conn->query($sql_insert) === TRUE) {
        $mensagem = "<p style='color: green;'>✔ Boleto Gerado!</p>";
    } else {
        $mensagem = "<p style='color: red;'>Erro: " . $conn->error . "</p>";
    }
}

$sql_usuarios = "SELECT id, email, nome FROM usuarios WHERE email != 'admin@estrela.com'";
$res_usuarios = $conn->query($sql_usuarios);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admin - Estrela</title>
    <style>
        body { font-family: sans-serif; background: #f0f0f0; padding: 20px; }
        .caixa { background: white; padding: 20px; max-width: 500px; margin: auto; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input, select, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box; }
        button { background: #003366; color: white; font-weight: bold; cursor: pointer; border: none; }
        button:hover { background: #0055aa; }
    </style>
</head>
<body>

<div class="caixa">
    <h2>⭐ Novo Boleto</h2>
    <?php echo $mensagem; ?>

    <form method="POST">
        <label>Cliente:</label>
        <select name="cliente_id" required>
            <option value="">Selecione...</option>
            <?php 
            if ($res_usuarios->num_rows > 0) {
                while($u = $res_usuarios->fetch_assoc()) {
                    echo "<option value='".$u['id']."'>".$u['email']."</option>";
                }
            }
            ?>
        </select>

        <label>Referência:</label>
        <input type="text" name="referencia" placeholder="Ex: Mensalidade Maio" required>

        <label>Vencimento:</label>
        <input type="date" name="vencimento" required>

        <label>Valor:</label>
        <input type="number" step="0.01" name="valor" placeholder="0.00" required>

        <button type="submit">SALVAR BOLETO</button>
    </form>
    <br>
    <center><a href="../pages/logout.php">Sair do Painel</a></center>
</div>

</body>
</html>