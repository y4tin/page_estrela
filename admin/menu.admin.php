<?php
// Proteção: Só deixa ver o menu se for o admin logado
if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== 'admin@estrela.com') {
    header("Location: ../pages/login.html");
    exit();
}
?>
<style>
    .nav-admin { background: #1a1a1a; padding: 15px; display: flex; gap: 20px; align-items: center; border-bottom: 3px solid #fbc02d; }
    .nav-admin a { color: white; text-decoration: none; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600; }
    .nav-admin a:hover { color: #fbc02d; }
    .btn-sair { background: #ff4d4d; padding: 5px 10px; border-radius: 4px; margin-left: auto; }
</style>

<nav class="nav-admin">
    <span style="color: #fbc02d;">⭐ PAINEL ADM:</span>
    <a href="gerar_boleto.php">📄 Gerar Boletos</a>
    <a href="cadastrar_cliente.php">👥 Gerenciar Clientes</a>
    <a href="setup.php">⚙️ Configurações</a>
    <a href="../pages/logout.php" class="btn-sair">Sair</a>
</nav>