<?php
include '../Donnees.inc.php';

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
    <link rel="stylesheet" href="../css/style.css" />
    <title>Index</title>
</head>
<body>
    <header>
        <h1>Boutique de nourriture MIAM</h1>
    </header>

    <nav>
        <div id="content">
            <h2>Rubriques</h2>
            <ul>
                <li><a href='inscription.php'>Inscription</a></li>
                <br>
                    <li><a href='connexion.php'>Connexion</a></li>
                </br>
                <li><a href='recettes.php'>Toute les recettes</a></li>
                <br>
                    <li><a href='#'>Mes recettes préférées</a></li>
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
        <p> ©Ma boutique à moi</p>
    </footer>
</body>
</html>