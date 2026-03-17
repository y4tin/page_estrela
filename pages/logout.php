<?php
session_start(); // Localiza a sessão atual
session_unset(); // Limpa todas as variáveis da sessão
session_destroy(); // Destrói a sessão completamente

// Redireciona de volta para a página de login
header("Location: login.html"); 
exit();
?>