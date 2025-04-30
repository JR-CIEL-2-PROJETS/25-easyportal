<?php
// Inclure le fichier d'authentification pour vérifier l'utilisateur
include('php/auth.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Easy Portal - Portail</title>
</head>
<body>
    <div class="header">
        <div class="logo">✦Easy Portal</div>
        <div class="user-name" id="userName"><?php echo $_SESSION['user_name']; ?></div>
    </div>

    <div class="sidebar">
        <a>Dashboard</a>
        <a href="user.php">Utilisateurs</a>
        <a href="admin.php">Admin</a>
        <a href="plaques.php">Plaques</a>
        <a href="portail.php" class="active">Portail</a>
        <a href="log.php">Logs</a>
    </div>

    <div class="main">
        <div class="title">Flux Caméra Ip</div>

        <div class="portal-container">
            <div class="video-frame" id="cameraFeed">Chargement...</div>
            <button class="btn" id="openPortalBtn">OUVRIR</button>
            <div id="portalAlert" class="alert"></div> <!-- Élément pour afficher le message du portail -->
        </div>
    </div>

    <script src="js/dejaconnecter.js"></script>
    <script>
        async function fetchCameraFeed() {
            try {
                const response = await fetch('https://aa8ef8d1-a278-416a-9cc9-85baa14b5d59.mock.pstmn.io/Dashboard/Camera');

                if (!response.ok) {
                    throw new Error(`Erreur HTTP : ${response.status}`);
                }

                const cameraData = await response.json(); // Utilisez .json() directement
                const cameraStatus = cameraData.map(item => item.Camera).join(', ');

                // Affichez le statut des caméras (pour le débogage)
                console.log("Statut des caméras :", cameraStatus);

                // Changer le texte si la requête réussit
                document.getElementById('cameraFeed').textContent = "Flux Caméra en direct";

            } catch (error) {
                console.error("Erreur :", error);
                document.getElementById('cameraFeed').textContent = "Erreur lors de la récupération de l'état de la caméra.";
            }
        }

        async function openPortal() {
            try {
                const response = await fetch('https://aa8ef8d1-a278-416a-9cc9-85baa14b5d59.mock.pstmn.io/Dashboard/Portail', {
                    method: 'POST',
                });
                
                if (!response.ok) {
                    throw new Error(`Erreur HTTP : ${response.status}`);
                }

                const result = await response.json();
                const alertMessage = document.createElement('div');
                alertMessage.textContent = result.message || "Le portail a été ouvert avec succès.";
                alertMessage.className = 'alert'; // Appliquer la classe d'alerte
                document.getElementById('portalAlert').innerHTML = ''; // Effacer les anciens messages
                document.getElementById('portalAlert').appendChild(alertMessage);
                
                // Faire disparaître le message après 3 secondes
                setTimeout(() => {
                    alertMessage.remove();
                }, 3000);

            } catch (error) {
                console.error("Erreur lors de l'ouverture du portail :", error);
                const alertMessage = document.createElement('div');
                alertMessage.textContent = "Erreur lors de l'ouverture du portail.";
                alertMessage.className = 'alert'; // Appliquer la classe d'alerte
                document.getElementById('portalAlert').innerHTML = ''; // Effacer les anciens messages
                document.getElementById('portalAlert').appendChild(alertMessage);
                
                // Faire disparaître le message après 3 secondes
                setTimeout(() => {
                    alertMessage.remove();
                }, 3000);
            }
        }

        document.getElementById("openPortalBtn").addEventListener("click", openPortal);
        document.addEventListener("DOMContentLoaded", fetchCameraFeed);
    </script>
</body>
</html>
