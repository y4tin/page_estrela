
<?php
// Verifica se está rodando local ou no Azure
if ($_SERVER['SERVER_NAME'] == "localhost") {

    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "estrelarastreamento";

} else {

    // Dados do MySQL do Azure
    $host = "estrelaras-a56792f821-wpdbserver.mysql.database.azure.com";
    $user = "lsojxiyqx@estrelaras-a56792f821-wpdbserver";
    $pass = "@miakalu1";
    $dbname = "estrelarastreamento"; // ou o nome do banco que você criou

}

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>