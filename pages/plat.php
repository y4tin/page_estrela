<?php
session_start();
include('../includes/conexao.php');

// 1. Proteção: Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: pages/login.html");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// 2. Busca os dados no banco de dados
$query = "SELECT * FROM boletos WHERE usuario_id = '$usuario_id' ORDER BY vencimento ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estrela Rastreamento - Painel</title> 
    <link rel="stylesheet" href="../css/plat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header style="text-align: center; padding: 20px;">
    <h1>Estrela Rastreamento</h1>
</header>

<main>
    <section class="container-financeiro">
        <h2><i class="fas fa-file-invoice-dollar"></i> Central de Boletos</h2>
        <p>Consulte aqui suas faturas e mantenha seu rastreamento sempre ativo.</p>

        <div class="tabela-scroll">
            <table class="tabela-boletos">
                <thead>
                    <tr>
                        <th>Referência</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                         $status_class = ($row['status'] == 'Pago') ? 'pago' : 'pendente';

                            echo "<td><span class='status $status_class'>" . $row['status'] . "</span></td>";;
                            echo "<tr>";
                            echo "<td>" . $row['referencia'] . "</td>";
                            echo "<td>" . date('d/m/Y', strtotime($row['vencimento'])) . "</td>";
                            echo "<td>R$ " . number_format($row['valor'], 2, ',', '.') . "</td>";
                            echo "<td><span class='status $status_class'>" . $row['status'] . "</span></td>";
                            echo "<td>";
                            
                            if ($row['status'] == 'Pendente') {
                               echo "<a href='../admin/pagarPix.php?id=" . $row['id'] . "' class='btn-pix'><i class='fa-brands fa-pix'></i> Pagar via PIX</a>";
                            } else {
                                echo "<span style='color: green; font-weight: bold;'>✔ Liquidado</span>";
                            }
                            
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>Nenhum boleto encontrado para sua conta.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<footer style="text-align: center; margin-top: 50px;">
    <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i> Sair do Sistema
    </a>
</footer>
</body>
</html>