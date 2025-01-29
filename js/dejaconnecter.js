
        document.addEventListener("DOMContentLoaded", function () {
            fetch("https://4db7e0eb-4ed7-4f36-8568-cbdb4e93af75.mock.pstmn.io/connexion/admin")
                .then(response => response.json())
                .then(data => {
                    if (data.administrateur && data.administrateur.length > 0) {
                        const prenom = data.administrateur[0].prenom || "Utilisateur";
                        const nom = data.administrateur[0].nom || "";
                        document.getElementById("userName").textContent = `${prenom} ${nom}`;
                    } else {
                        document.getElementById("userName").textContent = "Utilisateur inconnu";
                    }
                })
                .catch(error => {
                    console.error("Erreur lors de la récupération des données :", error);
                    document.getElementById("userName").textContent = "Erreur de connexion";
                });
        });
