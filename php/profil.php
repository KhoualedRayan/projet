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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../js/profil.js" defer></script>
    <title>Profil</title>
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
                <li><a href='../index.php'>Accueil</a></li>
                <br>
                    <li><a href='inscription.php'>Inscription</a></li>
                </br>
                <li><a href='connexion.php'>Connexion</a></li>
                <br />
                <?php
                if (isset($_SESSION['utilisateur_connecte'])) {
                    echo"<li><a href='profil.php'>Profil</a></li>";
                    echo'<br/>';
                }
                ?>
                    <li><a href='recettes.php'>Toute les recettes</a></li>
                </br>
                <li><a href='panier.php'>Panier</a></li>
                <br />
            </ul>
        </div>
    </nav>

    <main>
        <?php
        $stmt = $dbco->prepare('SELECT * FROM utilisateur WHERE login = :login');
        $stmt->bindValue('login', $nomUtilisateur);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        ?>
        <h4>Modifier votre profil : </h4>
        <form action="../traitement_php/traitement_profil.php" method="post">
            <label for="login">Login :</label>
            <input type="text" class="grise" name="login" value="<?php echo $row['login']; ?>" readonly/ /><br />

            <label for="mdp">Mot de passe :</label>
            <input type="password" name="mdp"  /><br />

            <label for="nom">Nom :</label>
            <input type="text" name="nom" value="<?php echo $row['nom']; ?>" /><br />

            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" value="<?php echo $row['prenom']; ?>" /><br />

            <span class="sexe">
                Sexe :
                <input type="radio" name="sexe" value="f" <?php if ($row['sexe'] == 'f') echo 'checked'; ?> /> Femme
                <input type="radio" name="sexe" value="h" <?php if ($row['sexe'] == 'h') echo 'checked'; ?> /> Homme

            </span>
            <br />

            <label for="mail">Adresse électronique :</label>
            <input type="email" name="mail" value="<?php echo $row['mail']; ?>" /><br />

            <label for="date_naiss">Date de naissance :</label>
            <input type="date" name="date_naiss" value="<?php echo $row['dateNaissance']; ?>" /><br />

            <label for="adresse">Adresse :</label>
            <input type="text" name="adresse" value="<?php echo $row['adresse']; ?>" /><br />

            <label for="code_postal">Code postal :</label>
            <input type="tel" name="code_postal" value="<?php echo $row['codePostal']; ?>" pattern="\d{5}" title="Le code postal doit contenir exactement 5 chiffres" /><br />

            <label for="ville">Ville :</label>
            <input type="text" name="ville" value="<?php echo $row['ville']; ?>" /><br />

            <label for="tel">Numéro de téléphone :</label>
            <input type="tel" id="tel" pattern="^0\d{9}$" title="Le téléphone doit être un téléphone français, 10 chiffres" name="tel" value="<?php echo $row['numTelephone']; ?>" />
            <span id="telError" style="color: red;"></span><br /><br />

            <input type="submit" value="Valider" />

        </form>
        <button class="deco" >Déconnexion</button>
        <button class="supprimerCompte" >Supprimer le compte</button>

    </main>

    <footer>
        <p> © Ma boutique à moi</p>
    </footer>
</body>
</html>