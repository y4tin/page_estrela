<?php
include('../includes/conexao.php');

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = strtolower(trim($conn->real_escape_string($_POST['email'])));
    $senha_pura = $_POST['password'];
    
    // Criptografa a senha para segurança
    $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

    // Verifica se o e-mail já existe
    $check = "SELECT id FROM usuarios WHERE email = '$email'";
    $res_check = $conn->query($check);

    if ($res_check->num_rows > 0) {
        $mensagem = "<p style='color: orange;'>Este e-mail já está cadastrado!</p>";
    } else {
        $sql = "INSERT INTO usuarios (email, senha) VALUES ('$email', '$senha_hash')";
        
        if ($conn->query($sql) === TRUE) {
            $mensagem = "<p style='color: green; font-weight: bold;'>✔ Cliente cadastrado com sucesso!</p>";
        } else {
            $mensagem = "<p style='color: red;'>Erro ao cadastrar: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admin - Cadastrar Cliente | Estrela Rastreamento</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<header style="text-align: center; padding: 20px; background-color: #003366; color: white;">
    <h1>Painel Administrativo</h1>
    <nav>
        <a href="gerar_boleto.php" style="color: white; margin-right: 15px;">Gerar Boletos</a>
        <a href="cadastrar_cliente.php" style="color: #32bcad; font-weight: bold;">Cadastrar Cliente</a>
    </nav>
</header>

<main>
    <section class="container-financeiro">
        <h2 style="text-align: center;">Novo Cadastro de Cliente</h2>
        
        <div style="text-align: center; margin-bottom: 15px;">
            <?php echo $mensagem; ?>
        </div>

        <form method="POST" action="cadastrar_cliente.php" class="form-admin">
            <label>E-mail do Cliente:</label>
            <input type="email" name="email" placeholder="exemplo@gmail.com" required>

            <label>Senha Provisória:</label>
            <input type="password" name="password" placeholder="Defina uma senha" required>

            <button type="submit" class="btn-gerar">Cadastrar Cliente</button>
        </form>
    </section>
</main>

</body>
</html>