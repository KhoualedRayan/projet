<?php
include '../Donnees.inc.php';

$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';
session_start();
try {
    $dbco = new PDO("mysql:host=$servname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbco = new PDO("mysql:host=$servname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbco->exec("USE $dbname"); // S�lectionner la base de donn�es
} catch (PDOException $e) {

    echo "Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Recettes</title>
</head>
<body>
    <header>
        <h1>Recettes</h1>
        <div id="compte">
            <?php
            if (isset($_SESSION['utilisateur_connecte'])) {
                // Récupérer le nom de l'utilisateur depuis la session
                $nomUtilisateur = $_SESSION['nom_utilisateur'];

                // Afficher le nom de l'utilisateur dans le HEADER
                echo 'Bienvenue, ' . $nomUtilisateur . '!';
            }
            ?>
        </div>
    </header>

    <nav>
        <div id="content">
            <h2>Rubriques</h2>
            <ul>
                <li><a href='../index.php'>Accueil</a></li>
                <br />
                <li><a href='inscription.php'>Inscription</a></li>
                <br>
                    <li><a href='connexion.php'>Connexion</a></li>
                </br>
                <li><a href='recettes.php'>Toutes les recettes</a></li>
                <br>
                    <li><a href='#'>Mes recettes préférées</a></li>
                </br>
            </ul>
        </div>
    </nav>

    <main>
        <h2>Hiérarchie des aliments</h2>
        <div id="hierarchie-container">
            <!-- Le contenu de hierarchie_aliments.php sera chargé ici -->
        </div>
    </main>

    <script>
        $(document).ready(function () {
            // Fonction pour charger les sous-aliments au chargement de la page
            function chargerSousAliments(mot) {
                $.ajax({
                    type: "POST",
                    url: "hierarchie_aliments.php",
                    data: { mot: mot },
                    success: function (response) {
                        $("#hierarchie-container").html(response);
                    },
                    error: function () {
                        alert("Une erreur s'est produite lors du chargement de la hiérarchie.");
                    }
                });
            }

            // Charger les sous-aliments initiaux pour la catégorie "Aliment"
            chargerSousAliments("Aliment");

            // Fonction de gestion du clic sur un élément
            $(document).on("click", ".mot-cliquable", function () {
                var motClique = $(this).text();
                chargerSousAliments(motClique);
            });
        });
    </script>

    <footer>
        <p> © Ma boutique à moi</p>
    </footer>
</body>
</html>
