<?php
// Inicia a sessão (caso ainda não tenha sido iniciada)
session_start();

// Destroi todas as variáveis de sessão
session_unset();

// Destrói a sessão
session_destroy();

echo '<script>window.location.replace("/eventos360");</script>';
exit();
?>
