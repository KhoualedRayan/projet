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
    $dbco->exec("USE $dbname"); // Sélectionner la base de données
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$alimentsInclusArray = isset($_POST["alimentsInclus"]) ? $_POST["alimentsInclus"] : array();
$alimentsExclusArray = isset($_POST["alimentsExclus"]) ? $_POST["alimentsExclus"] : array();

$alimentsInclusString = implode(',', $alimentsInclusArray);
$alimentsExclusString = implode(',', $alimentsExclusArray);

echo "<br/>alimentsInclusString: ";
echo "<br/>";
echo $alimentsInclusString;
echo "<br/>";
echo "alimentsExclusString: <br/>";
echo $alimentsExclusString;
echo "<br/>";

/*$query = "SELECT * FROM Cocktail WHERE FIND_IN_SET(ingredients, :alimentsInclus) AND NOT FIND_IN_SET(ingredients, :alimentsExclus)";
$stmt = $dbco->prepare($query);
$stmt->bindParam(':alimentsInclus', $alimentsInclusString);
$stmt->bindParam(':alimentsExclus', $alimentsExclusString);
$stmt->execute();
*/
$query = "SELECT * FROM Cocktail WHERE nomCocktail IN (
    SELECT nomCocktailU FROM Liaison WHERE nomAlimentU IN (";

// Ajouter les sous-aliments à la liste
$query .= implode(',', array_fill(0, count($alimentsInclusArray), '?'));
$query .= "))";

$stmt = $dbco->prepare($query);
$stmt->execute($alimentsInclusArray);

// Vérifier s'il y a des résultats
if ($stmt->rowCount() > 0) {
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
