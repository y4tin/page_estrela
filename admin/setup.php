<?php
include('../includes/conexao.php');

echo "<body style='font-family: sans-serif; background: #f4f4f4; padding: 50px;'>";
echo "<div style='background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 600px; margin: auto;'>";

// 1. Criar Tabela de Usuários
$sql_usuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// 2. Criar Tabela de Boletos (com chave estrangeira)
$sql_boletos = "CREATE TABLE IF NOT EXISTS boletos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    referencia VARCHAR(100) NOT NULL,
    vencimento DATE NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    status ENUM('Pendente', 'Pago') DEFAULT 'Pendente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
)";

// Executando a criação das tabelas
if ($conn->query($sql_usuarios) === TRUE && $conn->query($sql_boletos) === TRUE) {
    echo "<h2 style='color: #32bcad;'>✅ Banco de Dados Configurado!</h2>";
    echo "<p>As tabelas <strong>usuarios</strong> e <strong>boletos</strong> foram criadas com sucesso para a <strong>Estrela Rastreamento</strong>.</p>";
    
    // Opcional: Criar um usuário de teste se a tabela estiver vazia
    $check = $conn->query("SELECT id FROM usuarios LIMIT 1");
    if ($check->num_rows == 0) {
        $senha_teste = password_hash('123456', PASSWORD_DEFAULT);
        $conn->query("INSERT INTO usuarios (nome, email, senha) VALUES ('Cliente Teste', 'teste@estrela.com', '$senha_teste')");
        echo "<p style='background: #e6ffed; padding: 10px; border-radius: 5px;'><strong>Usuário de teste criado:</strong><br>Login: teste@estrela.com<br>Senha: 123456</p>";
    }

    echo "<hr><a href='../pages/plat.php' style='display: inline-block; background: #003366; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Acessar Painel</a>";
} else {
    echo "<h2 style='color: #d73a49;'>❌ Erro ao criar tabelas:</h2> " . $conn->error;
}

echo "</div></body>";
$conn->close();
?>