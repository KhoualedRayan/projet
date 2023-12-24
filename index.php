<?php
include 'Donnees.inc.php';

$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';
session_start();
try {
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
    <script src="js/index.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <title>Acceuil</title>
</head>
<body>
    <header>
        <h1>Acceuil</h1>
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
                <li><a href='index.php'>Accueil</a></li>
                <br />
                <li><a href='php/inscription.php'>Inscription</a></li>
                <br>
                    <li><a href='php/connexion.php'>Connexion</a></li>
                </br>
                <?php
                if (isset($_SESSION['utilisateur_connecte'])) {
                    echo"<li><a href='php/profil.php'>Profil</a></li>";
                    echo'<br/>';
                }
                ?>
                <li><a href='php/recettes.php'>Toutes les recettes</a></li>
                <br>
                    <li><a href='php/panier.php'>Panier</a></li>
                </br>
            </ul>
        </div>
    </nav>

    <main>
        <h2>Tous les Cocktails</h2>

        <?php
        // Sélectionner tous les cocktails de la table
        $query = "SELECT * FROM Cocktail";
        $stmt = $dbco->query($query);

        // Vérifier s'il y a des résultats
        if ($stmt->rowCount() > 0) {
            // Afficher les résultats dans un tableau
            echo '<table border="1" class="tab-image">';
            echo '<tr class="tab-image-ligne"><th>Photo</th><th>Nom du Cocktail</th><th>Préparation</th><th>Ingrédients</th><th>Panier</th></tr>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                // Colonne de la photo
                $nomCocktail = $row['nomCocktail'];
                $accents = array('á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ï' => 'i', 'ñ' => 'n', "'" => '', " " => '_');
                $nomCocktail = strtr($nomCocktail, $accents);
                $imagePath = "Photos/{$nomCocktail}.jpg";
                if (file_exists($imagePath)) {
                    echo '<td><img class="cocktail-image" src="' . $imagePath . '" alt="' . $row['nomCocktail'] . '"></td>';
                } else {
                    echo '<td class ="cocktail-image"></td>';
                }
                // Colonne du nom du cocktail
                echo '<td>' . $row['nomCocktail'] . '</td>';
                // Colonne de la préparation
                echo '<td>' . $row['preparation'] . '</td>';
                // Colonne des ingrédients
                echo '<td>' . $row['ingredients'] . '</td>';
                echo '<td>';
                echo '<button class="addToCart" data-cocktail="' . htmlspecialchars($row['nomCocktail']) . '">Ajouter au Panier</button>';

                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'Aucun cocktail trouvé.';
        }
        ?>
    </main>


    <footer>
        <p> ©Ma boutique à moi</p>
    </footer>
</body>
</html>