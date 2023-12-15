<?php
$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';

try {
    $dbco = new PDO("mysql:host=$servname;dbname=$dbname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mot = isset($_POST['mot']) ? $_POST['mot'] : 'Aliment';
    $filAriane = array();

    // Ajouter le mot actuel au fil d'Ariane
    $filAriane[] = $mot;

    // Récupérer les catégories précédentes dans le fil d'Ariane
    $query = "SELECT pereAliment FROM Aliment WHERE nomAliment = :mot";
    $stmt = $dbco->prepare($query);
    $stmt->bindParam(':mot', $mot);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $filAriane[] = $row['pereAliment'];
        $mot = $row['pereAliment'];
    }

    // Inverser le tableau pour avoir l'ordre correct dans le fil d'Ariane
    $filAriane = array_reverse($filAriane);

    // Afficher le fil d'Ariane
    echo "<p>Fil d'Ariane : " . implode(" -> ", $filAriane) . "</p>";

    // Récupérer les sous-aliments actuels
    $motSousAliments = end($filAriane);
    $querySousAliments = "SELECT nomAliment FROM Aliment WHERE pereAliment = :motSousAliments";
    $stmtSousAliments = $dbco->prepare($querySousAliments);
    $stmtSousAliments->bindParam(':motSousAliments', $motSousAliments);
    $stmtSousAliments->execute();

    if ($stmtSousAliments->rowCount() > 0) {
        echo "<ul>";
        while ($rowSousAliments = $stmtSousAliments->fetch(PDO::FETCH_ASSOC)) {
            echo "<li class='mot-cliquable'>$rowSousAliments[nomAliment]</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucun sous-aliment trouvé pour $mot.</p>";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
