<?php
// Verifica se está rodando local ou na nuvem
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "estrela-rastreamento"; // Ajustado para o nome com hífen que vi no seu print
} else {
    $host = "sql302.infinityfree.com";
    $user = "if0_41409781";
    $pass = "zAhxRqagZmPz6";
    $dbname = "if0_41409781_estrela";
}

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>