<?php
session_start();
include('../includes/conexao.php');

if (!isset($_GET['id'])) {
    die("Erro: ID do boleto não informado.");
}

$id_boleto = $_GET['id'];

// Busca os dados do boleto
$sql = "SELECT b.*, u.email FROM boletos b 
        JOIN usuarios u ON b.usuario_id = u.id 
        WHERE b.id = '$id_boleto'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $dados = $result->fetch_assoc();
    
    // =======================================================
    // FUNÇÃO OFICIAL PARA CALCULAR A TRAVA DE SEGURANÇA (CRC16)
    // =======================================================
    function calcula_crc16($payload) {
        $resultado = 0xFFFF;
        if (($length = strlen($payload)) > 0) {
            for ($offset = 0; $offset < $length; $offset++) {
                $resultado ^= (ord($payload[$offset]) << 8);
                for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                    if (($resultado <<= 1) & 0x10000) $resultado ^= 0x1021;
                    $resultado &= 0xFFFF;
                }
            }
        }
        return strtoupper(str_pad(dechex($resultado), 4, '0', STR_PAD_LEFT));
    }

    // =======================================================
    // MONTANDO O CÓDIGO PIX PERFEITO
    // =======================================================
    $valor_pix = number_format($dados['valor'], 2, '.', '');
    
    // Esta é a base exata do código que você me enviou antes que estava funcionando
    $px = "00020101021126580014br.gov.bcb.pix0136524ea84a-b12b-4e24-8759-457eaaf78ea4520400005303986";
    
    // Aqui injetamos o tamanho do valor e o valor real (Ex: 540555.00)
    $px .= "54" . str_pad(strlen($valor_pix), 2, '0', STR_PAD_LEFT) . $valor_pix;
    
    // Restante dos seus dados exatos (Luan Vitor, Brasilia, SXQR)
    $px .= "5802BR5925LUAN VITOR DOMINGOS AFONS6008BRASILIA62080504SXQR";
    
    // Finaliza pedindo o cálculo do CRC
    $px .= "6304";
    
    // Código PIX Final
    $dados_pix = $px . calcula_crc16($px);
    
    // URL do QR Code
    $qr_api_url = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($dados_pix);

} else {
    die("Boleto não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento PIX - Estrela Rastreamento</title>
    <link rel="stylesheet" href="../css/pix.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="checkout-container">
    <div class="pix-card">
        <div class="pix-header">
            <img src="../imagens/logo (1).png" alt="Logo" width="70">
            <h2>Finalizar Pagamento</h2>
        </div>

        <div class="qr-code-section">
            <img src="<?php echo $qr_api_url; ?>" class="qr-code" alt="QR Code PIX">
            <p class="instrucao">Escaneie o código com o app do seu banco</p>
        </div>

        <div class="copy-paste-section">
            <label>Ou copie o código abaixo:</label>
            <div class="copy-box">
                <input type="text" value="<?php echo $dados_pix; ?>" id="pixInput" readonly>
                <button onclick="copiarPix()" id="btnCopiar">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>

        <div class="pix-details">
            <div class="info-linha">
                <span>Cliente:</span>
                <strong><?php echo $dados['email']; ?></strong>
            </div>
            <div class="info-linha">
                <span>Referência:</span>
                <strong><?php echo $dados['referencia']; ?></strong>
            </div>
            <div class="valor-total">
                <p>Valor a pagar:</p>
                <h3>R$ <?php echo number_format($dados['valor'], 2, ',', '.'); ?></h3>
            </div>
        </div>

        <a href="../pages/plat.php" class="btn-voltar">Voltar ao painel</a>
    </div>
</div>

<script>
function copiarPix() {
    var copyText = document.getElementById("pixInput");
    var btn = document.getElementById("btnCopiar");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    btn.innerHTML = '<i class="fas fa-check"></i>';
    btn.style.background = "#32bcad";
    setTimeout(() => {
        btn.innerHTML = '