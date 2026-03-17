<?php
include('../includes/conexao.php');

$email = "teste@gmail.com";
$senha_pura = "1234";

// Isso transforma "1234" em algo como "$2y$10$vI8..."
$senha_criptografada = password_hash($senha_pura, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (email, senha) VALUES ('$email', '$senha_criptografada')";

if ($conn->query($sql) === TRUE) {
    echo "Usuário de teste criado com sucesso!<br>";
    echo "E-mail: " . $email . "<br>";
    echo "Senha: " . $senha_pura;
} else {
    echo "Erro ao criar usuário: " . $conn->error;
}
?>