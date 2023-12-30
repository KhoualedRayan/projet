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
    $dbco->exec("USE $dbname");
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$alimentsInclusArray = isset($_POST["alimentsInclus"]) ? $_POST["alimentsInclus"] : array();
$alimentsExclusArray = isset($_POST["alimentsExclus"]) ? $_POST["alimentsExclus"] : array();

$sousAlimentsInclusListe = getSousAlimentsListe($dbco, $alimentsInclusArray);
$sousAlimentsExclusListe = getSousAlimentsListe($dbco, $alimentsExclusArray);


$query = "SELECT
              *,
              c.nomCocktail AS NomRecette,
              COUNT(l.nomAlimentU) AS Score
          FROM
              Cocktail c
          JOIN
              Liaison l ON c.nomCocktail = l.nomCocktailU
          WHERE 1";

if (!empty($sousAlimentsInclusListe)) {
    $query .= " AND l.nomAlimentU IN (";
    $query .= implode(',', array_fill(0, count($sousAlimentsInclusListe), '?'));
    $query .= ")";
}

if (!empty($sousAlimentsExclusListe)) {
    $query .= " AND l.nomAlimentU NOT IN (";
    $query .= implode(',', array_fill(0, count($sousAlimentsExclusListe), '?'));
    $query .= ")";
}

$query .= " GROUP BY c.nomCocktail
            ORDER BY Score DESC";

$stmt = $dbco->prepare($query);

$params = array_merge($sousAlimentsInclusListe, $sousAlimentsExclusListe);
if(!empty($sousAlimentsInclusListe)|| !empty($sousAlimentsExclusListe))
    $stmt->execute($params);




if ($stmt->rowCount() > 0) {
    // Affiche les r�sultats dans un tableau
    echo '<table border="1" class="tab-image">';
    echo '<tr class="tab-image-ligne"><th>Photo</th><th>Nom du Cocktail</th><th>Pr&eacute;paration</th><th>Ingr&eacute;dients</th><th>Panier</th></tr>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        // Colonne de la photo
        $nomCocktail = $row['nomCocktail'];
        $nomCocktail = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $nomCocktail);
        $accents = array('�' => 'a', '�' => 'e', '�' => 'i', '�' => 'o', '�' => 'u', '�' => 'u', '�' => 'i', '�' => 'n', "'" => '', " " => '_');

        $nomCocktail = strtr($nomCocktail, $accents);
        $imagePath = "../../projet/Photos/{$nomCocktail}.jpg";
        if (file_exists($imagePath)) {
            echo '<td><img class="cocktail-image" src="' . $imagePath . '" alt="' . $row['nomCocktail'] . '"></td>';
        } else {
            echo '<td class ="cocktail-image"></td>';
        }
        // Colonne du nom du cocktail
        echo '<td>' . $row['nomCocktail'] . '</td>';
        // Colonne de la pr�paration
        echo '<td>' . $row['preparation'] . '</td>';
        // Colonne des ingr�dients
        echo '<td>' . $row['ingredients'] . '</td>';
        echo '<td>';
        echo '<button class="addToCart" data-cocktail="' . htmlspecialchars($row['nomCocktail']) . '">Ajouter au Panier</button>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    if (!empty($sousAlimentsInclusListe) || !empty($sousAlimentsExclusListe))
        echo 'Aucun cocktail trouv&eacute;.';
}
function getSousAliments($dbco, $aliment)
{
    $sousAliments = array($aliment);

    $querySousAliments = "SELECT nomAliment FROM Aliment WHERE pereAliment = :aliment";
    $stmtSousAliments = $dbco->prepare($querySousAliments);
    $stmtSousAliments->bindParam(':aliment', $aliment, PDO::PARAM_STR);
    $stmtSousAliments->execute();

    while ($rowSousAliments = $stmtSousAliments->fetch(PDO::FETCH_ASSOC)) {
        $sousAliments = array_merge($sousAliments, getSousAliments($dbco, $rowSousAliments['nomAliment']));
    }

    return $sousAliments;
}
function getSousAlimentsListe($dbco, $aliment)
{
    $sousAliments = array();
    foreach ($aliment as $a) {
        $sousAliments = array_merge($sousAliments, getSousAliments($dbco, $a));
    }
    return $sousAliments;
}

?>
