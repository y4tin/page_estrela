
<?php
// Verifica se está rodando no computador local ou na InfinityFree
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "if0_41409781_estrela"; 
} else {
    $host = "sql302.infinityfree.com";
    $user = "if0_41409781";
    $pass = "zAhxRqagZmPz6"; // Verifique se esta é a senha do Painel/FTP
    $dbname = "if0_41409781_estrela"; // Substitua o XXX pelo nome exato que você criou
}

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>