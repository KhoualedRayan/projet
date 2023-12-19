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
    <script src="../js/panier.js" defer></script>
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
                <li><a href='../index.php'>Accueil</a></li>
                <br />
                <li><a href='inscription.php'>Inscription</a></li>
                <br>
                    <li><a href='connexion.php'>Connexion</a></li>
                </br>
                <li><a href='recettes.php'>Toute les recettes</a></li>
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
            echo 'L\'utilisateur n\'est pas connecté.';
        }



        ?>
    </main>

    <footer>
        <p> ©Ma boutique à moi</p>
    </footer>
</body>
</html>
<?php
function afficherTab($stmt){
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
?>