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
    $dbco->exec("USE $dbname"); // Selectionner la base de donn�es
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
    <title>Connexion</title>
</head>
<body>
    <header>
        <h1>Connexion</h1>
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
                <?php
                if (isset($_SESSION['utilisateur_connecte'])) {
                    echo "<li><a href='profil.php'>Profil</a></li>";
                    echo '<br/>';
                }
                ?>
                <li><a href='recettes.php'>Toutes les recettes</a></li>
                <br>
                    <li><a href='panier.php'>Panier</a></li>
                </br>
            </ul>
        </div>
    </nav>

    <main>
        <h4>Veuillez remplir le formulaire :</h4>
        <form action="../traitement_php/traitement_connexion.php" method="post">
            <label for="login">Login :</label>
            <input type="text" name="login" required /><br />

            <label for="mdp">Mot de passe :</label>
            <input type="password" name="mdp" required /><br /><br />

            <input type="submit" value="Valider" />
        </form>
    </main>

    <footer>
        <p> Un site développé par Thomas et Rayan.</p>
    </footer>
</body>
</html>
