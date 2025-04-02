<?php
session_start();
session_destroy();
header("Location: /workspaces/25-easyportal/html/index.html"); // Redirige vers la page de connexion
exit;
?>
