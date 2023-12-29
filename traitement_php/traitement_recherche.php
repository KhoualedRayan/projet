<?php

$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';

try {
    $dbco = new PDO("mysql:host=$servname;dbname=$dbname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
    echo 'Bonjour';

    $query = "SELECT nomAliment FROM Aliment WHERE nomAliment LIKE :searchTerm ORDER BY CASE WHEN nomAliment LIKE :searchTermStart THEN 0 ELSE 1 END, nomAliment";
    $stmt = $dbco->prepare($query);

    $searchTermWithPercent = '%' . $searchTerm . '%';
    $stmt->bindParam(':searchTerm', $searchTermWithPercent, PDO::PARAM_STR);

    $searchTermStart = $searchTerm . '%';
    $stmt->bindParam(':searchTermStart', $searchTermStart, PDO::PARAM_STR);

    $stmt->execute();

    $options = '';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $options .= "<option value='{$row['nomAliment']}'>{$row['nomAliment']}</option>";
    }

    echo $options;

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
