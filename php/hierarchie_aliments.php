<?php
$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';

session_start();

// Initialiser la session 'fil' comme un tableau si ce n'est pas déjà le cas
if (!isset($_SESSION['fil']) || !is_array($_SESSION['fil'])) {
    $_SESSION['fil'] = array();
}

try {
    $dbco = new PDO("mysql:host=$servname;dbname=$dbname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mot = isset($_POST['mot']) ? $_POST['mot'] : 'Aliment';

    // Si un mot spécifique est passé en paramètre, le fil d'Ariane est reconstruit à partir de ce mot
    if ($mot != 'Aliment') {
        $_SESSION['fil'] = array();
        $currentMot = $mot;
        while ($currentMot != 'Aliment') {
            $_SESSION['fil'][] = $currentMot;

            $queryPereAliment = "SELECT pereAliment FROM Aliment WHERE nomAliment = :currentMot";
            $stmtPereAliment = $dbco->prepare($queryPereAliment);
            $stmtPereAliment->bindParam(':currentMot', $currentMot);
            $stmtPereAliment->execute();

            if ($rowPereAliment = $stmtPereAliment->fetch(PDO::FETCH_ASSOC)) {
                $currentMot = $rowPereAliment['pereAliment'];
            } else {
                break; // Sortir de la boucle si le père n'est pas trouvé
            }
        }
        $_SESSION['fil'][] = 'Aliment';
        $_SESSION['fil'] = array_reverse($_SESSION['fil']);

        // Afficher le fil d'Ariane avec des liens cliquables
        echo "<p id='fil_ariane'>Fil d'Ariane : ";
        foreach ($_SESSION['fil'] as $i => $motAriane) {
            if ($i > 0) {
                echo " -> ";
            }
            echo "<a href='#' class='mot-cliquable' data-index='$i'>$motAriane</a>";
        }
        echo "</p>";
    }

            // Récupérer les sous-aliments actuels
    $motSousAliments = $mot;
    $querySousAliments = "SELECT nomAliment FROM Aliment WHERE pereAliment = :motSousAliments";
    $stmtSousAliments = $dbco->prepare($querySousAliments);
    $stmtSousAliments->bindParam(':motSousAliments', $motSousAliments);
    $stmtSousAliments->execute();

    if ($stmtSousAliments->rowCount() > 0) {
        echo "<ul id='zone_sous_aliments'>";
        while ($rowSousAliments = $stmtSousAliments->fetch(PDO::FETCH_ASSOC)) {
            echo "<li class='mot-cliquable' ><span style='color: blue; cursor: pointer; text-decoration: underline;'>$rowSousAliments[nomAliment]</span></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucun sous-aliment trouvé pour $mot.</p>";
    }


    if($mot != 'Aliment'){



    $ss_aliment = $mot; // Valeur par défaut si non définie

    $sousAliments = getSousAliments($dbco, $ss_aliment);

    // Utiliser la liste des sous-aliments pour construire la requête SQL
    $query = "SELECT * FROM Cocktail WHERE nomCocktail IN (
    SELECT nomCocktailU FROM Liaison WHERE nomAlimentU IN (";

    // Ajouter les sous-aliments à la liste
    $query .= implode(',', array_fill(0, count($sousAliments), '?'));
    $query .= "))";

    $stmt = $dbco->prepare($query);
    $stmt->execute($sousAliments);


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
            $imagePath = "../Photos/{$nomCocktail}.jpg";
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
    }else{
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
                $imagePath = "../Photos/{$nomCocktail}.jpg";
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
    }
    



} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
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

?>
