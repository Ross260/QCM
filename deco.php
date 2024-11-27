<?php
session_start();

// Détruire la session
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session active

header("Location: login.php");
exit;
?>
