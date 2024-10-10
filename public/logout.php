<?php
// Start de sessie om de sessievariabelen te bekijken
session_start();

// Verwijder de login-status uit de sessie zodat de gebruiker naar de index wordt gestuurd
unset($_SESSION['loggedin']);

// Voor de zekerheid vernietig de hele sessie zodat alle variabelen worden gewist
session_destroy();

// Stuur de gebruiker door naar de index pagina
header("Location: index.php");
die();
?>
