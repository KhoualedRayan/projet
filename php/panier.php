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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../js/panier.js" defer></script>
    <title>Panier</title>
</head>
<body>
    <header>
        <h1>Panier</h1>
        <div id="compte">
            <?php
            if (isset($_SESSION['utilisateur_connecte'])) {
                // Récupére le nom de l'utilisateur depuis la session
                $nomUtilisateur = $_SESSION['nom_utilisateur'];

                // Affiche le nom de l'utilisateur dans le HEADER
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
                <br />
                <li><a href='connexion.php'>Connexion</a></li>
                <br />
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
        <h2>Panier : </h2>
        <?php
        if (isset($_SESSION['utilisateur_connecte'])) {

            $query = "SELECT * FROM Panier WHERE loginP = :login";
            $stmt = $dbco->prepare($query);
            $stmt->bindParam(':login', $nomUtilisateur, PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                afficherTab($stmt);
            } else {
                echo 'Le panier est vide.';
            }
        } else {
            if (isset($_SESSION['panier_temporaire']) && !empty($_SESSION['panier_temporaire'])) {
                echo '<h3>Cocktails temporaires :</h3>';
                afficherTabTemporaire($_SESSION['panier_temporaire']);
            } else {
                echo 'Panier temporaire vide.';
            }
        }



        ?>
    </main>

    <footer>
        <p> Un site développé par Thomas et Rayan.</p>
    </footer>
</body>
</html>
<?php
function afficherTab($stmt)
{
    echo '<table border="1" class="tab-image">';
    echo '<tr class="tab-image-ligne"><th>Photo</th><th>Nom du Cocktail</th><th>Supprimer du panier</th></tr>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        // Colonne de la photo
        $nomCocktail = $row['nomCocktailP'];
        $accents = array('á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ï' => 'i', 'ñ' => 'n', "'" => '', " " => '_');
        $nomCocktail = strtr($nomCocktail, $accents);
        $imagePath = "../Photos/{$nomCocktail}.jpg";
        if (file_exists($imagePath)) {
            echo '<td><img class="cocktail-image" src="' . $imagePath . '" alt="' . $row['nomCocktailP'] . '"></td>';
        } else {
            echo '<td class ="cocktail-image"></td>';
        }
        // Colonne du nom du cocktail
        echo '<td>' . $row['nomCocktailP'] . '</td>';

        echo '<td>';
        echo '<button class="supprimerDuPanier" data-cocktail="' . $row['id'] . '">Supprimer du Panier</button>';

        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}
function afficherTabTemporaire($panierTemporaire)
{
    echo '<table border="1" class="tab-image">';
    echo '<tr class="tab-image-ligne"><th>Photo</th><th>Nom du Cocktail</th><th>Date d\'ajout temporaire</th><th>Supprimer du panier</th></tr>';
    foreach ($panierTemporaire as $cocktailTemporaire) {
        echo '<tr>';
        // Colonne de la photo
        $nomCocktailTemporaire = $cocktailTemporaire['nomCocktail'];
        $accents = array('á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ï' => 'i', 'ñ' => 'n', "'" => '', " " => '_');
        $nomCocktailTemporaire = strtr($nomCocktailTemporaire, $accents);
        $imagePath = "../Photos/{$nomCocktailTemporaire}.jpg";
        if (file_exists($imagePath)) {
            echo '<td><img class="cocktail-image" src="' . $imagePath . '" alt="' . $cocktailTemporaire['nomCocktail'] . '"></td>';
        } else {
            echo '<td class ="cocktail-image"></td>';
        }
        // Colonne du nom du cocktail temporaire
        echo '<td>' . $cocktailTemporaire['nomCocktail'] . '</td>';
        // Colonne de la date d'ajout temporaire
        echo '<td>' . $cocktailTemporaire['dateAjout'] . '</td>';

        echo '<td>';
        echo '<button class="supprimerDuPanierTemporaire" data-cocktail="' . $cocktailTemporaire['dateAjout'] . '">Supprimer du panier temporaire</button>';

        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

?>