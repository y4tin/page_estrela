<?php
session_start();
// 1. Proteção de Segurança e Conexão
include('menu_admin.php'); // O menu_admin.php já deve ter a trava de segurança que fizemos
include('../includes/conexao.php');

$mensagem = "";

// 2. Processar o envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST['cliente_id'];
    $referencia = $conn->real_escape_string($_POST['referencia']);
    $vencimento = $_POST['vencimento'];
    $valor = $_POST['valor'];

    $sql_insert = "INSERT INTO boletos (usuario_id, referencia, vencimento, valor, status) 
                   VALUES ('$cliente_id', '$referencia', '$vencimento', '$valor', 'Pendente')";
    
    if ($conn->query($sql_insert) === TRUE) {
        $mensagem = "<p style='color: #27ae60; font-weight: bold; background: #d4edda; padding: 10px; border-radius: 5px;'>✔ Boleto gerado com sucesso!</p>";
    } else {
        $mensagem = "<p style='color: #c0392b; background: #f8d7da; padding: 10px; border-radius: 5px;'>Erro: " . $conn->error . "</p>";
    }
}

// 3. Buscar usuários para o Select (excluindo o admin da lista)
$sql_usuarios = "SELECT id, email, nome FROM usuarios WHERE email != 'admin@estrela.com' ORDER BY nome ASC";
$res_usuarios = $conn->query($sql_usuarios);

// 4. Buscar boletos já gerados para mostrar na tabela
$sql_lista_boletos = "SELECT b.*, u.nome FROM boletos b JOIN usuarios u ON b.usuario_id = u.id ORDER BY b.data_criacao DESC LIMIT 10";
$res_boletos = $conn->query($sql_lista_boletos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerar Boleto | Estrela Rastreamento</title>
    <link rel="stylesheet" href="../css/style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; margin: 0; }
        .container-admin { max-width: 900px; margin: 30px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { color: #003366; border-bottom: 2px solid #fbc02d; padding-bottom: 10px; }
        .form-admin label { display: block; margin-top: 15px; font-weight: 600; color: #333; }
        .form-admin input, .form-admin select { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn-gerar { background: #003366; color: white; border: none; padding: 15px; width: 100%; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 20px; transition: 0.3s; }
        .btn-gerar:hover { background: #fbc02d; color: #003366; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 40px; background: white; }
        table th { background: #003366; color: white; padding: 12px; text-align: left; }
        table td { padding: 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        .status-pendente { color: #e67e22; font-weight: bold; }
    </style>
</head>
<body>

<div class="container-admin">
    <h2>⭐ Gerar Novo Boleto</h2>
    
    <div style="text-align: center;">
        <?php echo $mensagem; ?>
    </div>

    <form method="POST" action="gerar_boleto.php" class="form-admin">
        <label>Selecione o Cliente:</label>
        <select name="cliente_id" required>
            <option value="">-- Escolha um cliente --</option>
            <?php 
            if ($res_usuarios && $res_usuarios->num_rows > 0) {
                while($user = $res_usuarios->fetch_assoc()) {
                    $nome_exibicao = $user['nome'] ? $user['nome'] : $user['email'];
                    echo "<option value='" . $user['id'] . "'>" . $nome_exibicao . "</option>";
                }
            }
            ?>
        </select>

        <label>Referência:</label>
        <input type="text" name="referencia" placeholder="Ex: Mensalidade - Junho/2026" required>

        <label>Data de Vencimento:</label>
        <input type="date" name="vencimento" required>

        <label>Valor (R$):</label>
        <input type="number" step="0.01" name="valor" placeholder="Ex: 55.00" required>

        <button type="submit" class="btn-gerar">LANÇAR BOLETO NO SISTEMA</button>
    </form>

    <hr style="margin-top