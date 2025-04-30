<?php
// Inclure le fichier d'authentification pour vérifier l'utilisateur
include('php/auth.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/plaques.css">
    <title>Easy Portal - Plaques</title>
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
        <a href="plaques.php" class="active">Plaques</a>
        <a href="portail.php">Portail</a>
        <a href="log.php">Logs</a>
    </div>

    <div class="main">
        <div class="title">Plaques</div>

        <div class="plate-container">
            <img src="image/plaque.png" alt="Plaque d'immatriculation" class="plate-image">

            <div class="file-input">
                <form action="php/import_plaques.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="csvFile" accept=".csv">
                    <button type="submit">Importer des plaques📤</button>
                </form>
            </div>

            <form action="php/export_authorized_plaques.php" method="get">
                <button type="submit" class="btn">📥 Télécharger les plaques autorisées</button>
            </form>

            <form action="php/export_unauthorized_plaques.php" method="get">
                <button type="submit" class="btn">📥 Télécharger les plaques non-autorisées</button>
            </form>
        </div>
    </div>

    <!-- Modal pour la gestion des plaques -->
    <div id="plaquesModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Gérer les plaques de <span id="userFullName"></span></h2>
            <div id="plaquesList"></div>
            <input type="text" id="newPlaque" placeholder="Ajouter une nouvelle plaque">
            <button onclick="addPlaque()">Ajouter</button>
        </div>
    </div>

    <script src="js/dejaconnecter.js"></script>
    <script>
        let currentUserEmail = '';

        function viewPlaques(email, fullName) {
            currentUserEmail = email;
            document.getElementById('userFullName').innerText = fullName;
            const modal = document.getElementById("plaquesModal");
            modal.style.display = "block";

            // Réinitialiser les plaques
            const plaquesList = document.getElementById("plaquesList");
            plaquesList.innerHTML = '';

            // Charger les plaques de l'utilisateur
            fetchPlaques(email);
        }

        async function fetchPlaques(email) {
            try {
                const response = await fetch(`php/get_plaques.php?email=${email}`);
                const data = await response.json();
                const plaquesList = document.getElementById("plaquesList");
                data.plaques.forEach(plaque => {
                    const plaqueElement = document.createElement("div");
                    plaqueElement.innerText = plaque;
                    const deleteButton = document.createElement("button");
                    deleteButton.innerText = "Supprimer";
                    deleteButton.onclick = () => deletePlaque(plaque);
                    plaqueElement.appendChild(deleteButton);
                    plaquesList.appendChild(plaqueElement);
                });
            } catch (error) {
                console.error("Erreur lors de la récupération des plaques", error);
            }
        }

        async function deletePlaque(plaque) {
            try {
                const response = await fetch("php/delete_plaque.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ email: currentUserEmail, plaque })
                });
                const data = await response.json();
                if (data.success) {
                    fetchPlaques(currentUserEmail); // Mettre à jour la liste des plaques
                }
            } catch (error) {
                console.error("Erreur lors de la suppression de la plaque", error);
            }
        }

        async function addPlaque() {
            const newPlaque = document.getElementById('newPlaque').value;
            try {
                const response = await fetch("php/add_plaque.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ email: currentUserEmail, plaque: newPlaque })
                });
                const data = await response.json();
                if (data.success) {
                    fetchPlaques(currentUserEmail); // Mettre à jour la liste des plaques
                }
            } catch (error) {
                console.error("Erreur lors de l'ajout de la plaque", error);
            }
        }

        function closeModal() {
            const modal = document.getElementById("plaquesModal");
            modal.style.display = "none";
        }
    </script>
</body>
</html>
