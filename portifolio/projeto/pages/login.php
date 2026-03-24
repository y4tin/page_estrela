<?php
include('../includes/conexao.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = strtolower(trim($conn->real_escape_string($_POST['email'])));
    $senha = $_POST['password'];

    $sql = "SELECT id, email, senha FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        // Verifica a senha com criptografia
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];
            
            // REDIRECIONAMENTO ESTRATÉGICO
            if ($email === 'admin@estrela.com') {
                header("Location: ../admin/gerar_boleto.php");
            } else {
                header("Location: plat.php");
            }
            exit();
        } else {
            echo "<script>alert('Senha incorreta!'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado!'); window.location.href='login.html';</script>";
    }
}
?>