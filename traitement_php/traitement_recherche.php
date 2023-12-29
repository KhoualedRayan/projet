<?php

$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';

try {
    $dbco = new PDO("mysql:host=$servname;dbname=$dbname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer le terme de recherche depuis $_POST
    $searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
    echo 'Bonjour';

    // Requête SQL avec LIKE pour rechercher les aliments commençant par le terme
    $query = "SELECT nomAliment FROM Aliment WHERE nomAliment LIKE :searchTerm";
    $stmt = $dbco->prepare($query);
    
    // Ajoutez le % dans la valeur du paramètre avant de le lier
    $searchTermWithPercent = '%' . $searchTerm . '%';
    $stmt->bindParam(':searchTerm', $searchTermWithPercent, PDO::PARAM_STR);
    
    $stmt->execute();

    // Construire le HTML de la liste déroulante
    $options = '';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $options .= "<option value='{$row['nomAliment']}'>{$row['nomAliment']}</option>";
    }

    echo $options;

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
