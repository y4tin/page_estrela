<?php
include('../includes/conexao.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegando e limpando os dados
    $email = strtolower(trim($conn->real_escape_string($_POST['email'])));
    $senha = $_POST['password'];

    // Busca o usuário
    $sql = "SELECT id, email, senha FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        // Verifica a senha
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];
            
            header("Location: plat.php");
            exit();
        } else {
            echo "<script>alert('Senha incorreta!'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado!'); window.location.href='login.html';</script>";
    }
}
?>