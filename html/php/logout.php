<?php
session_start();
session_destroy();
header("Location: index.html"); // Redirige vers la page d'accueil
exit;
?>
