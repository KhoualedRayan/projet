<?php
include 'Donnees.inc.php';

$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';
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
    <link rel="stylesheet" href="css/style.css" />
    <title>Index</title>
</head>
<body>
    <header>
        <h1>Boutique de nourriture MIAM</h1>
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
                <li><a href='php/inscription.php'>Inscription</a></li>
                <br>
                    <li><a href='php/connexion.php'>Connexion</a></li>
                </br>
                <li><a href='php/recettes.php'>Toute les recettes</a></li>
                <br>
                    <li><a href='#'>Mes recettes préférées</a></li>
                </br>
            </ul>
        </div>
    </nav>

    <main></main>

    <footer>
        <p> ©Ma boutique à moi</p>
    </footer>
</body>
</html>