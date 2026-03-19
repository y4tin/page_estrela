// Este script agora apenas avisa que o processamento começou.
// O redirecionamento real será feito pelo PHP.

document.getElementById('loginForm').addEventListener('submit', function() {
    console.log("Enviando dados para o servidor PHP...");
    // Não usamos e.preventDefault() aqui porque queremos que o formulário 
    // SEJA enviado para o login.php
});